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

	//Include required files
	require_once('abre_functions.php');
	$studentLoginServiceEnabled = false;
	$siteColor = getSiteColor();

	//Display the login
	echo "<main>";

			//Overlay Div
			echo "<div id='loadingcover' style='background-color:".$siteColor."; position:fixed; width:100%; height:100%; z-index:2'></div>";

			//Login Holder
			echo "<div class='mdl-layout mdl-js-layout login-card' style='background-color:".$siteColor."; background-image: url(/core/images/abre/abre_pattern.png); '>";

				echo "<div class='login_wrapper' style='z-index:1;'>";
					echo "<div class='login-card-square mdl-card'>";

						//Site Logo
						echo "<div style='margin:30px 40px 10px 45px; height:240px; width:240px; background:url(".getSiteLogo().") center center no-repeat; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: cover; background-size: cover;'></div>";

						//Site Login Text
						if(!isset($_SESSION['invalid_credentials'])){
							echo "<div class='mdl-card-text mdl-color-text--grey-600' style='text-align:center; margin:0 40px 10px 40px;'>Please sign in to continue</div>";
						}
						else{
							echo "<div class='mdl-card-text mdl-color-text--grey-600' style='text-align:center; margin:-5px 40px 0px 40px;'>";
							echo $_SESSION['invalid_credentials'];
							echo "</div>";
							echo "<div class='mdl-card-text mdl-color-text--red-600' style='text-align:center; margin:-15px 40px 0px 40px;'>Invalid login</div>";
							echo "<div class='mdl-card-text mdl-color-text--grey-600' style='text-align:center; margin:-5px 40px 10px 40px;'>Please sign in to continue</div>";
						}

						//Logins
						echo "<div class='loginholder_large'>";

							//Staff Login
							if(getSiteGoogleSignInGroups('staff') == "checked" || getSiteMicrosoftSignInGroups('staff') == "checked"){
								if(getSiteGoogleSignInGroups('students') == "checked" || getSiteMicrosoftSignInGroups('students') == "checked"){
									echo "<div style='padding-top: 10px'>";
										echo "<a class='waves-effect waves-light btn-large mdl-color-text--white loginbutton' style='background-color:".$siteColor." !important; color:#fff !important; text-align:center;' href='?usertype=Staff'>Sign in as Staff</a>";
									echo "</div>";
									$studentLoginServiceEnabled = true;
								}else{
									echo "<div style='padding-top: 10px'>";
										echo "<a class='waves-effect waves-light btn-large mdl-color-text--white loginbutton' style='background-color:".$siteColor." !important; color:#fff !important; text-align:center;' href='?usertype=Staff'>Sign in</a>";
									echo "</div>";
								}
							}

					  	//Student Login
							if($studentLoginServiceEnabled){
								echo "<div style='padding-top: 10px'>";
									echo "<a class='waves-effect waves-light btn-large mdl-color-text--white loginbutton' style='background-color:".$siteColor." !important; color:#fff !important; text-align:center;' href='?usertype=Student'>Sign in as Student</a>";
								echo "</div>";
							}

							//Parent Login
							if(getSiteGoogleSignInGroups('parents') == "checked" || getSiteMicrosoftSignInGroups('parents') == "checked" || getSiteFacebookSignInGroups('parents') == "checked"){
								echo "<div style='padding-top: 10px'>";
									echo "<a class='waves-effect waves-light btn-large mdl-color-text--white loginbutton' style='background-color:".$siteColor." !important; color:#fff !important; text-align:center;' href='?usertype=Parent'>Sign in as Parent</a>";
								echo "</div>";
							}

						echo "</div>";

					echo "</div>";
				echo "</div>";

				include "abre_copyright.php";

			echo "</div>";

	echo "</main>";

?>

<script>

	$(function(){

		$("#loadingcover").delay(300).fadeOut();

		//Responsive login view
		function loginWidthCheck(){
			if ($(window).width() < 600){
				$(".login-card-square").removeClass("mdl-shadow--2dp");
				$(".login-card").addClass("mdl-color--white");
			}else{
				$(".login-card-square").addClass("mdl-shadow--2dp");
				$(".login-card").removeClass("mdl-color--white");
			}
		}

		loginWidthCheck();
		$(window).resize(loginWidthCheck);

	});

</script>