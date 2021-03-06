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

    //Required configuration files
	require_once(dirname(__FILE__) . '/../../core/abre_verification.php');
	require(dirname(__FILE__) . '/../../core/abre_dbconnect.php');
	require_once(dirname(__FILE__) . '/../../core/abre_functions.php');

	$siteColor = getSiteColor();

	//Check for variable in url
	if(isset($_GET['discussion'])){
		$discussionid = $_GET['discussion'];
		$discussionid = preg_replace("/[^0-9]/","",$discussionid);
	}

	if($db->query("SELECT * FROM Abre_Students LIMIT 1")){
		$sql = "SELECT SchoolCode, SchoolName FROM Abre_Students WHERE siteID = '".$_SESSION['siteID']."' ORDER BY SchoolCode";
		$schoolResults = databasequery($sql);
	}
	if(!isset($schoolResults)){
		$schoolResults = array();
	}

?>

	<?php
	if($_SESSION['usertype'] == 'staff'){
	?>

		<!-- Social Sharing -->
		<div id="sharecard" class="modal modal-fixed-footer modal-mobile-full" style="max-width: 600px;">
			<form>
				<div class="modal-content" style="padding: 0px !important;">
					<div class="row" style='background-color: <?php echo $siteColor; ?>; padding: 24px;'>
						<div class='col s11'><span class="truncate" style="color: #fff; font-weight: 500; font-size: 24px; line-height: 26px;">Share</span></div>
						<div class='col s1 right-align'><a class="modal-close"><i class='material-icons' style='color: #fff;'>clear</i></a></div>
					</div>
					<div style='padding: 0px 24px 0px 24px;'>
						<div class="socialshare pointer" style="width:100%; background-color:#0084b4; padding:15px; text-align: center; color:#fff; font-size:16px;" data-link="http://twitter.com/share?url=">Twitter</div><br>
						<div class="socialshare pointer" style="width:100%; background-color:#3B5998; padding:15px; text-align: center; color:#fff; font-size:16px;" data-link="http://www.facebook.com/sharer.php?u=">Facebook</div><br>
						<div class="socialshare pointer" style="width:100%; background-color:#d34836; padding:15px; text-align: center; color:#fff; font-size:16px;" data-link="https://plus.google.com/share?url=">Google</div>
						<input type="hidden" name="share_url" id="share_url">
					</div>
				</div>
	   		</form>
	 	</div>

	 	<!-- Stream Announcement -->
		<div id="streampost" class="fullmodal modal modal-fixed-footer modal-mobile-full" style='max-width:800px;'>
			<form id="form-streampost" method="post" enctype='multipart/form-data' action="modules/stream/action_save_announcement.php">
				<div class="modal-content" style="padding: 0px !important;">
					<div class="row" style='background-color: <?php echo $siteColor; ?>; padding: 24px;'>
						<div class='col s11'><span id="post_header" class="truncate" style="color: #fff; font-weight: 500; font-size: 24px; line-height: 26px;"></span></div>
						<div class='col s1 right-align'><a class="modal-close"><i class='material-icons' style='color: #fff;'>clear</i></a></div>
					</div>
					<div style='padding: 0px 24px 0px 24px;'>

						<div class="row">
							<div class="input-field col s12">
								<label for="post_stream" class="active">Stream Category</label>
								<select id="post_stream" name="post_stream" required>
									<option value="" disabled selected>Choose a Stream</option>
									<?php
										$schoolCodeArray = getRestrictions();

										$sql = "SELECT title, staff_building_restrictions FROM streams WHERE siteID = '".$_SESSION['siteID']."'";
										$result = $db->query($sql);
										while($value = $result->fetch_assoc()){
											$streamTitle = $value['title'];
											$streamTitleEncoded = htmlspecialchars($streamTitle, ENT_QUOTES);
											$staffBuildingRestrictions = $value['staff_building_restrictions'];
											$staffBuildingRestrictionsArray = explode(",", $staffBuildingRestrictions);
											if(admin() || $staffBuildingRestrictions == "No Restrictions" || $staffBuildingRestrictions == NULL){
												echo "<option value='$streamTitleEncoded'>$streamTitle</option>";
											}else{
												if(!empty($schoolCodeArray)){
													foreach($schoolCodeArray as $code){
														if(in_array($code, $staffBuildingRestrictionsArray)){
															echo "<option value='$streamTitleEncoded'>$streamTitle</option>";
														}
													}
												}
											}
										}
									?>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="input-field col s12">
								<input type="text" name="post_title" id="post_title" autocomplete="off" placeholder="Enter Announcement Title" required>
								<label for="post_title" class="active">Title</label>
							</div>
						</div>
						<div class="row">
							<div class="input-field col s12">
								<p class='black-text' style="font-weight: 500;">Content</p>
								<textarea placeholder="Enter your announcement content" class="announcement_wysiwyg" id="post_content" name="post_content"></textarea>
							</div>
						</div>

						<div class='row'>
							<div class='col s12'>
								<img id='post_image' style='max-width: 100%; display:none;' alt='Post Image' src=''>
								<div class='custompostimage pointer' style='width:100%; background-color:#E0E0E0; text-align:center; padding:50px;'>
									<i class="material-icons">crop_original</i><br><b>Click to choose a Featured Image</b></div>
								<input type='file' name='customimage' id='customimage' style='display:none;'>
							</div>
						</div>

						<input type='hidden' id='post_id' name='post_id'>
					</div>

			</div>
			<div class="modal-footer">
				<button class="btn waves-effect btn-flat white-text" id='custompostbutton' type="submit" name="action" style='background-color:<?php echo $siteColor; ?>'>Post</button>
				<button class="btn waves-effect btn-flat white-text" id='custompostsavebutton' style='background-color:<?php echo $siteColor; ?>; display:none;'>Save</button>
				<button class="btn waves-effect btn-flat white-text" id='custompostdeletebutton' style='background-color:<?php echo $siteColor; ?>; display:none; margin-right:5px;'>Delete</button>
				<p id="errorMessage" style="display:none; float:right; color:red; margin:6px 0; padding-right:10px;"></p>
			</div>
			</form>
		</div>

	<?php
	}
	?>

	<!-- Read and Comment Modal -->
	<div id="addstreamcomment" class="fullmodal modal modal-fixed-footer modal-mobile-full" style='max-width:700px;'>
		<div id="commentloader" class="mdl-progress mdl-js-progress mdl-progress__indeterminate" style="width:100%"></div>
		<form id="form-addstreamcomment" method="post" action="modules/stream/action_comment_add.php">
			<div class="modal-content" id="modal-content-section" style="padding: 0px !important;">
				<div class="row" style='background-color: <?php echo $siteColor; ?>; padding: 24px;'>
					<div class='col s11'><span class="truncate" id='readStreamTitle' style="color: #fff; font-weight: 500; font-size: 24px; line-height: 26px;"></span></div>
					<div class='col s1 right-align'><a class="modal-close"><i class='material-icons' style='color: #fff;'>clear</i></a></div>
				</div>
				<div style='padding: 0px 24px 0px 24px;'>

					<div class="row" style='margin-bottom:0;'>
						<div class='wrap-links input-field col s12' id="streamTitle" style="font-weight:700; font-size:24px; line-height:32px;"></div>
					</div>
					<div class="row" id='streamPhotoHolder'>
						<div class="input-field col s12">
							<div id="streamPhoto" class="center-align"></div>
						</div>
					</div>
					<div class="row">
						<div class='input-field col s12'>
							<p class='wrap-links excerpt' id="streamExcerptDisplay" name="streamExcerptDisplay" style="font-size:16px; line-height:1.8em"></p>
						</div>
						<div class='input-field col s12'>
							<a id="streamLink" href="" style="text-decoration: underline; color: <?php echo $siteColor; ?>;" target="_blank">View full article</a>
						</div>
					</div>
					<?php
					if($_SESSION['usertype'] == 'staff'){
					?>
						<div class="row">
							<div class="input-field col s12" style="padding-bottom: 5px;">
								<textarea placeholder="Add a comment..." id="streamComment" name="streamComment" class="materialize-textarea" required></textarea>
							</div>
						</div>

						<div class="row">
							<div class="input-field col s12" style="padding-bottom: 5px;">
								<button class="btn waves-effect btn-flat white-text" type="submit" name="action" style='margin-top:-20px; background-color:<?php echo $siteColor; ?>'>Post</button><br><br>
							</div>
						</div>

						<div name="streamComments" id="streamComments"></div>
					<?php
					}
					?>

					<input type="hidden" name="streamUrl" id="streamUrl">
					<input type="hidden" name="streamTitleValue" id="streamTitleValue">
					<input type="hidden" name="commentID" id="commentID">
					<input type="hidden" name="streamImage" id="streamImage">
					<input type="hidden" name="redirect" id="redirect">
					<input id="streamExcerpt" name="streamExcerpt" type="hidden">
				</div>
			</div>
			<div class="modal-footer">
				<button class="modal-action modal-close waves-effect btn-flat white-text" type="button" style='background-color: <?php echo $siteColor; ?>'>Close</button>
			</div>
		</form>
	</div>

 	<!-- Edit Widgets -->
	<div id="editwidgets" class="modal modal-mobile-full" style="max-width: 600px;">
		<form>
			<div class="modal-content" style="padding: 0px !important;">
				<div class="row" style='background-color: <?php echo $siteColor; ?>; padding: 24px;'>
					<div class='col s11'><span class="truncate" style="color: #fff; font-weight: 500; font-size: 24px; line-height: 26px;">Edit Widgets</span></div>
					<div class='col s1 right-align'><a class="modal-close"><i class='material-icons' style='color: #fff;'>clear</i></a></div>
				</div>
				<div style='padding: 0px 24px 0px 24px;'>
					<div class="row">
						<div class="col s12">
							<?php

								//Check to see if there is hidden widgets
								$sql = "SELECT widgets_hidden FROM profiles WHERE email = '".$_SESSION['escapedemail']."' AND siteID = '".$_SESSION['siteID']."'";
								$result = $db->query($sql);
								$widgets_order = NULL;
								$widgets_hidden = NULL;
								while($row = $result->fetch_assoc()) {
									$widgets_hidden = htmlspecialchars($row["widgets_hidden"], ENT_QUOTES);
								}

								//Show All Widgets
								echo "<table class='bordered'>";
								$widgetsdirectory = dirname(__FILE__) . '/../';
								$widgetsfolders = scandir($widgetsdirectory);
								$widgetcounter=0;

								foreach($widgetsfolders as $result){

									$pagetitle = NULL;
									$restrictions = NULL;

									if(file_exists(dirname(__FILE__) . '/../'.$result.'/widget.php')){

										include(dirname(__FILE__) . '/../'.$result.'/widget_config.php');

										if(strpos($restrictions,$_SESSION['usertype']) === false && strpos($services, $_SESSION['auth_service']) !== false && isAppActive($result)){

											$widgetcounter++;

											echo "<tr>";
												echo "<td><b>$pagetitle</b><td>";
												echo "<td style='width:30px; text-align:right;'>";

													echo "<div class='switch'><label><input type='checkbox' class='widgetclick' name='$result' id='$result' value='1' ";

													//Check if a widget should be hidden
													if($widgets_hidden==NULL){

														echo "checked";

													}
													else
													{

														//Check to see if widget was hidden by use selection
														$HiddenWidgets = explode(',',$widgets_hidden);
														if(!in_array($result, $HiddenWidgets)){

															echo "checked";

														}

													}

													echo "/><span class='lever'></span></label></div>";

												echo "</td>";
											echo "</tr>";

										}

									}

								}

								if($widgetcounter==0){ echo "<tr><td colspan='2'>No widgets are currently available.</td></tr>"; }
								echo "</table>";

							?>
						</div>
					</div>
				</div>
			</div>
   		</form>
 	</div>

	<script src="/core/js/drive-picker.php"></script>

<script>
	$(function(){
		//Material Design Dropdown Selects
		$('select').material_select();

		//Start TinyMCE
		tinymce.remove('.announcement_wysiwyg');
		tinymce.init({
			selector: '.announcement_wysiwyg',
			branding: false,
			height: 200,
			menubar: false,
			resize: false,
			statusbar: false,
			autoresize_min_height: 200,
			autoresize_max_height: 400,
			code_dialog_height: 200,
			content_css : "/core/css/tinymce.0.0.11.css",
			oninit : "setPlainText",
			plugins: 'paste print autolink fullscreen image link media lists imagetools autoresize textcolor code table colorpicker',
			toolbar: [
				'bold italic underline | fontsizeselect forecolor backcolor | alignleft aligncenter alignright | numlist bullist | indent outdent',
				'table | link drive image media | removeformat | code'
			],
			image_advtab: true,
			file_picker_callback: filePickerCallback,
			file_picker_types: "image",
			images_upload_url: '/core/abre_tiny_upload.php',
			automatic_uploads: true,
			fontsize_formats: "8pt 10pt 11pt 12pt 14pt 18pt 24pt 36pt",
			<?php
			if($_SESSION['auth_service'] == "google"){
			?>
				setup: editor => {
					loadGoogleDrivePickerForTiny(editor);
				},
			<?php
			}
			?>
			default_link_target: "_blank"
		});

		//Provide image upload on icon click
		$(".custompostimage").unbind().click(function(event){
			event.preventDefault();
			$("#customimage").click();
	  });

		//Submit form if image if changed
		$("#customimage").change(function (){
			if (this.files && this.files[0]){
				var reader = new FileReader();
				reader.onload = function (e) {
					$('#post_image').show();
					$('#post_image').attr('src', e.target.result);
				}
				reader.readAsDataURL(this.files[0]);
			}
	  	});

		//Social Share
		$(".socialshare").unbind().click(function(e){
			e.preventDefault();
			$('#sharecard').closeModal({ in_duration: 0, out_duration: 0 });
			var network = $(this).data('link');
			var link = $('#share_url').val();
			var finallink = network+link;
		    objWindow = window.open(finallink, 'Social Share', 'width=500,height=500,resizable=no').focus();
		});

		//Update Widget Setting
		$(".widgetclick").unbind().click(function(e){
			var widget = $(this).attr('id');
			var selectedWidgets = "";

			$("input:checkbox[class=widgetclick]:not(:checked)").each(function () {
           		selectedWidgets = $(this).attr("id") + "," + selectedWidgets;
        	});

        	selectedWidgets = selectedWidgets.replace(/^,|,$/g,'');

			$.post("modules/stream/action_save_widget_visibility.php", {widgets: selectedWidgets}, function() {
				$("#streamwidgets").load("modules/<?php echo basename(__DIR__); ?>/view_widgets.php");
			});

		});

		//Save comment
		var form = $('#form-addstreamcomment');
		$(form).submit(function(event) {
			event.preventDefault();
			$("#commentstatustext").text("Posting comment...");
			var url = $("#streamUrl").val();
			var id = $("#commentID").val();
			var redirect = $("#redirect").val();
			var formData = $(form).serialize();
			$.ajax({
				type: 'POST',
				url: $(form).attr('action'),
				data: formData
			})
			//Show the notification
			.done(function(response) {
				$( "#streamComments" ).load( "modules/stream/view_comment_list.php?url="+response, function() {
					$("textarea").val('');
					$("#commentstatustext").text("Write a comment");
					$(".modal-content #streamUrl").val(response);
					var element = document.getElementById("commentthreadbox");
					element.scrollTop = element.scrollHeight;
					$.post( "modules/<?php echo basename(__DIR__); ?>/action_update_card.php", {url: url, redirect: redirect, type: "comment"})
					.done(function(data) {
						$("#"+id).prev().removeClass("mdl-color-text--grey-600");
						$("#"+id).prev().css("color", "<?php echo $siteColor; ?>");
						$("#"+id).css("color", "<?php echo $siteColor; ?>");
						$("#"+id).html(data.count);
						if(redirect == "comments"){
							if(data.currentusercount > 0){
								$("#"+id).closest(".card_stream").show();
							}
							if(data.streamcardsleft > 0){
								$("#noCommentsMessage").hide();
							}
						}
					});
				});
			})
		});

		//Submit the Custom Post
		$("#form-streampost").submit(function(event) {
			event.preventDefault();
			$("errorMessage").hide();
			$('#custompostsavebutton').html("Saving...");
			$('#custompostbutton').html("Posting...");
			var data = new FormData($(this)[0]);

			$.ajax({ type: 'POST', url: $(this).attr('action'), data: data, contentType: false, processData: false })
			.done(function(response){
				$('#custompostsavebutton').html("Save");
				$('#custompostbutton').html("Post");
				if(response.status == "Success"){
					$('#streampost').closeModal({ in_duration: 0, out_duration: 0, });
					$('#announcements').trigger('click');
					var notification = document.querySelector('.mdl-js-snackbar');
					var data = { message: response.message };
					notification.MaterialSnackbar.showSnackbar(data);
				}
				if(response.status == "Error"){
					$("#errorMessage").html(response.message);
					$("#errorMessage").show();
				}
			});
		});


	});

</script>
