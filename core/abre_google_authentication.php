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

	//Google API PHP library files
	require_once 'google/vendor/autoload.php';

	//Create Client request to access Google API
	$client = new Google_Client();
	$client->setApplicationName("Abre");
	$client_id = getConfigGoogleClientID();
	$client->setClientId($client_id);
	$client_secret = getConfigGoogleClientSecret();
	$client->setClientSecret($client_secret);
	$redirect_uri = getConfigGoogleRedirect();
	$client->setRedirectUri($redirect_uri);
	$simple_api_key = getConfigGoogleApiKey();
	$client->setDeveloperKey($simple_api_key);
	$client->setAccessType("offline");
	$client->setApprovalPrompt("auto");
	$scopes = getConfigGoogleScopes();
	$client->setScopes($scopes);
	$client->setIncludeGrantedScopes(true);

	//Send client requests
	$Service_Oauth2 = new Google_Service_Oauth2($client);
	$Service_Gmail = new Google_Service_Gmail($client);
	$Service_Drive = new Google_Service_Drive($client);
	$Service_Calendar = new Google_Service_Calendar($client);
	$Service_Classroom = new Google_Service_Classroom($client);

?>