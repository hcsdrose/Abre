<?php

	/*
	* Copyright (C) 2016-2018 Abre.io Inc.
	*
	* This program is free software: you can redistribute it and/or modify
    * it under the terms of the Affero General Public License version 3
    * as published by the Free Software Foundation.
	*
    * This program is distributed in the hope that it will be useful,
    * but WITHOUT ANY WARRANTY; without even the implied warranty of
    * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    * GNU Affero General Public License for more details.
	*
    * You should have received a copy of the Affero General Public License
    * version 3 along with this program.  If not, see https://www.gnu.org/licenses/agpl-3.0.en.html.
    */

  require_once(dirname(__FILE__) . '/abre_session.php');
  if(session_id() == '') {
		session_set_save_handler("sess_open", "sess_close", "sess_read", "sess_write", "sess_destroy", "sess_gc");
    session_start();
  }


  require_once('abre_functions.php');
  require_once('abre_parent_google_authentication.php');
  $portal_root = getConfigPortalRoot();

  try{

    if(isset($_GET['code'])){
      $client->fetchAccessTokenWithAuthCode($_GET['code']);
      $_SESSION['google_parent_access_token'] = $client->getAccessToken();
      $pagelocation = $portal_root;
    }

    if(isset($_SESSION['google_parent_access_token'])){
      $client->setAccessToken($_SESSION['google_parent_access_token']);
    }

    //Get basic user information if they are logged in
    if(isset($_SESSION['google_parent_access_token'])){
      include "abre_dbconnect.php";
      if(!isset($_SESSION['useremail'])){
        $client->setAccessToken($_SESSION['google_parent_access_token']);
        $userData = $Service_Oauth2->userinfo->get();
        $userEmail = strtolower($userData["email"]);
        $_SESSION['auth_service'] = "google";
        $_SESSION['useremail'] = $userEmail;
        $_SESSION['escapedemail'] = mysqli_real_escape_string($db, $userEmail);
        $_SESSION['picture'] = $userData['picture'];
        $_SESSION['usertype'] = 'parent';
        $_SESSION['displayName'] = $userData['name'];
      }
    }

    if(isset($_SESSION['google_parent_access_token'])){
      if($_SESSION['usertype'] != ""){
        if($result = $db->query("SELECT COUNT(*) FROM users_parent WHERE email = '".$_SESSION['escapedemail']."' AND siteID = '".$_SESSION['siteID']."'")){
          $resultrow = $result->fetch_assoc();
          $count = $resultrow["COUNT(*)"];

          if($count == 1){
            //If not already logged in, check and get a refresh token
            if(!isset($_SESSION['loggedin'])){ $_SESSION['loggedin'] = ""; }
            if($_SESSION['loggedin'] != "yes"){
              //Mark that they have logged in
              $_SESSION['loggedin'] = "yes";
            }
          }else{
            $stmt = $db->stmt_init();
            $sql = "INSERT INTO users_parent (email, siteID) VALUES (?, ?)";
            $stmt->prepare($sql);
            $stmt->bind_param("si", $_SESSION['useremail'], $_SESSION['siteID']);
            $stmt->execute();
            $stmt->close();
            $_SESSION['loggedin'] = "yes";
          }
        }
        $db->close();
      }
    }
  }catch(Exception $x){
    //Remove cookies and destroy session
    if(isset($_SERVER['HTTP_COOKIE'])){
        $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
        foreach($cookies as $cookie){
            $parts = explode('=', $cookie);
            $name = trim($parts[0]);
            setcookie($name, '', time()-1000);
            setcookie($name, '', time()-1000, '/');
        }
    }
    session_destroy();
    $client->revokeToken();

    //Redirect user
    header("Location: $portal_root");
    exit();
  }

  header("Location: $portal_root");
  exit();

?>