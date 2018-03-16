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

	$sql = "SELECT SchoolCode, SchoolName FROM Abre_Students ORDER BY SchoolCode";
	$schoolResults = databasequery($sql);
?>

	<!--Work Schedule-->
	<div id="viewschedule" class="modal modal-fixed-footer modal-mobile-full" style='width: 80%'>
	    <div class="modal-content">
				<div class='row'>
					<div class ='col l8 s12'>
					<h4>Set Your Work Schedule</h4>
					</div>
					<div class='col l2 s12' style='float:right;'>
						<select id='calendaryear'>
							<option value='2016' selected>2016-2017 School Year</option>
							<option value='2017'>2017-2018 School Year</option>
							<option value='2018'>2018-2019 School Year</option>
							<option value='2019'>2019-2020 School Year</option>
							<option value='2020'>2020-2021 School Year</option>
						</select>
					</div>
				</div>
				<div class='row'>
					<div class='col m12 s12'>
						<?php echo "<form id='form-calendar' method='post'>"; ?>
							<input id="saveddates" type="hidden"></input>
						</form>
						<div id="workcalendardisplay"></div>
					</div>
				</div>
	    </div>
		<div class="modal-footer">
			<button class="modal-close waves-effect btn-flat white-text" style='margin-left:5px; background-color: <?php echo getSiteColor(); ?>'>Close</button>
			<button class="printbutton waves-effect btn-flat white-text" style='background-color: <?php echo getSiteColor(); ?>'>Print</button>
			<div id="selecteddays" style='margin:12px 0 0 20px; font-weight:500; font-size:16px;'></div>
	  </div>
	</div>

<!--Stream Editor-->
	<?php
	if(superadmin()){
	?>

	<div id='streameditor' class='modal modal-fixed-footer modal-mobile-full'>
		<div class='modal-content' style="padding: 0px !important;">
			<div class="row" style='background-color: <?php echo getSiteColor(); ?>; padding: 24px;'>
				<div class='col s11'><span class="truncate" style="color: #fff; font-weight: 500; font-size: 24px; line-height: 26px;">Stream Editor</span></div>
				<div class='col s1 right-align'><a class="modal-close"><i class='material-icons' style='color: #fff;'>clear</i></a></div>
			</div>
			<div style='padding: 0px 24px 0px 24px;'>
				<div class='row'>
					<div class='col s12'>
						<?php
							include "stream_editor_content.php";
						?>
					</div>
				</div>
			</div>
		</div>
		<div class='modal-footer'>
			<a class='modal-action waves-effect btn-flat white-text modal-addeditstream' href='#addeditstream' data-streamtitle='Add New Stream' style='background-color: <?php echo getSiteColor(); ?>'>Add</a>
		</div>
	</div>

	<div id='addeditstream' class='modal modal-fixed-footer modal-mobile-full' style="width: 90%">
		<form id='addeditstreamform' method="post" action='#'>
		<div class='modal-content' id="viewstreammodal" style="padding: 0px !important;">
			<div class="row" style='background-color: <?php echo getSiteColor(); ?>; padding: 24px;'>
				<div class='col s11'><span class="truncate" id='editstreammodaltitle' style="color: #fff; font-weight: 500; font-size: 24px; line-height: 26px;"></span></div>
				<div class='col s1 right-align'><a class="modal-close"><i class='material-icons' style='color: #fff;'>clear</i></a></div>
			</div>
			<div style='padding: 0px 24px 0px 24px;'>
				<div class='row'>
					<div class='input-field col s12'>
						<input placeholder="Enter Stream Name" id="stream_name" name="stream_name" type="text" autocomplete="off" maxlength="30" required>
						<label class="active" for="stream_name">Name</label>
					</div>
				</div>
				<div class="row">
					<div class='input-field col s12'>
						<input placeholder="Enter RSS Link" id="rss_link" name="rss_link" type="text" autocomplete="off">
						<label class="active" for="rss_link">Link</label>
					</div>
				</div>
				<div class="row">
					<div class='col s12' style="padding-bottom: 13px;">
						<label for="stream_color">Select a Color</label><br>
						<input type='text' id="stream_color"><span class='pointer' id='removeColor' style='padding-left:5px; display:none'><a style='color:<?php echo getSiteColor() ?>;'>remove color</a></span>
					</div>
				</div>
				<div class='row'>
					<div class='col m4 s12'>
							<input type="checkbox" class="filled-in" name="stream_staff" id="stream_staff" value="staff">
							<label for="stream_staff">Staff</label>
							<br><br>
							<div id='streamStaffRestrictionsDiv'>
								<label>Staff Building Restrictions</label>
								<select name="staffRestriction[]" id="streamStaffRestrictions" multiple>
									<option value="No Restrictions">All Buildings</option>
									<?php
									$lastSchoolCode = "";
									foreach($schoolResults as $school){
										if($school['SchoolCode'] == $lastSchoolCode){
											continue;
										}else{
											echo "<option value='".$school['SchoolCode']."'>".ucwords(strtolower($school['SchoolName']))."</option>";
											$lastSchoolCode = $school['SchoolCode'];
										}
									}
									?>
								</select>
							</div>
					</div>
					<div class='col m4 s12'>
						<input type="checkbox" class="filled-in" name="stream_students" id="stream_students" value="student">
						<label for="stream_students">Students</label>
						<br><br>
						<div id='streamStudentRestrictionsDiv'>
							<label>Student Building Restrictions</label>
							<select name="studentRestriction[]" id="streamStudentRestrictions" multiple>
								<option value="No Restrictions">All Buildings</option>
								<?php
								$lastSchoolCode = "";
								foreach($schoolResults as $school){
									if($school['SchoolCode'] == $lastSchoolCode){
										continue;
									}else{
										echo "<option value='".$school['SchoolCode']."'>".ucwords(strtolower($school['SchoolName']))."</option>";
										$lastSchoolCode = $school['SchoolCode'];
									}
								}
								?>
							</select>
						</div>
					</div>
					<div class='col m4 s12'>
						<input type="checkbox" class="filled-in" name="stream_parents" id="stream_parents" value="parent">
						<label for="stream_parents">Parents</label>
					</div>
				</div>
				<div class="row">
					<div class='col m4 s12'>
						<input type="checkbox" class="filled-in" name="streamradio" id="required_stream" value="1">
						<label for="required_stream">Require Stream</label>
					</div>
				</div>
				<input id="stream_id" name="stream_id" type="hidden">
			</div>
		</div>
		<div class='modal-footer'>
			<button type="submit" class='modal-action waves-effect btn-flat white-text' id='saveupdatestream' style='background-color: <?php echo getSiteColor(); ?>;'>Save</button>
			<a class='modal-action modal-close waves-effect btn-flat white-text' style='background-color: <?php echo getSiteColor(); ?>; margin-right:5px;'>Cancel</a>
		</div>
		</form>
	</div>
	<?php
 	}
 	?>

<script>

	$(function(){

		$("#stream_staff").change(function(){
			if($(this).is(':checked')){
				$("#streamStaffRestrictionsDiv").show();
			}else{
				$("#streamStaffRestrictionsDiv").hide();
			}
		});

		$("#stream_students").change(function(){
			if($(this).is(':checked')){
				$("#streamStudentRestrictionsDiv").show();
			}else{
				$("#streamStudentRestrictionsDiv").hide();
			}
		});

	  $('select').material_select();

	  <?php
		if(superadmin()){
		?>
			$("#removeColor").off().on('click', function(event){
				event.preventDefault();
				$("#stream_color").spectrum("set", "");
				$("#removeColor").hide();
			})
			//Add/Edit Stream
			$(".modal-addeditstream").unbind().click(function(event){
				event.preventDefault();
				$(".modal-content").scrollTop(0);
				$("#editstreammodaltitle").text('Add New Stream');
				$("#stream_name").val('');
				$("#rss_link").val('');
				$("#stream_id").val('');
				$('#stream_staff').prop('checked', false);
				$('#stream_students').prop('checked', false);
				$('#stream_parents').prop('checked', false);
				$('#required_stream').prop('checked', false);
				$("#removeColor").hide();
				$("#streamStudentRestrictions").val('No Restrictions');
				$("#streamStaffRestrictions").val('No Restrictions');
				$("#streamStudentRestrictionsDiv").hide();
				$("#streamStaffRestrictionsDiv").hide();
				$("#stream_color").spectrum({
					color: "",
					allowEmpty: true,
					showPaletteOnly: true,
					showPalette: true,
					palette: [["#F44336", "#B71C1C", "#9C27B0", "#4A148C"],
										["#2196F3", "#0D47A1", "#4CAF50", "#1B5E20"],
										["#FF9800", "#E65100", "#607D8B", "#263238"]],
					hide: function(color) {
						if(color !== null){
							$("#removeColor").show();
					 }
				 }
				});

				$('.modal-addeditstream').leanModal({
					in_duration: 0,
					out_duration: 0,
					ready: function(){
						$("#stream_color").spectrum("set", "");
					}
				});
			});

			//Save/Update Stream
			$('#addeditstreamform').submit(function(event){
				event.preventDefault();

				var streamtitle = $('#stream_name').val();
				var rsslink = $('#rss_link').val();
				var streamArray = [];
				if($('input[id="stream_staff"]').is(':checked')){
					streamArray.push("staff");
				}
				if($('input[id="stream_students"]').is(':checked')){
					streamArray.push("student");
				}
				if($('input[id="stream_parents"]').is(':checked')){
					streamArray.push("parents");
				}

				var streamgroup = streamArray.join(", ");

				var streamid = $('#stream_id').val();
				if($('#required_stream').is(':checked') == true){ var required = 1; }else{ var required = 0; }

				var streamcolor = $("#stream_color").spectrum("get");
				if(streamcolor === null){
					streamcolor = "";
				}else{
					streamcolor = streamcolor.toHexString();
				}
				var staffRestrictions = $("#streamStaffRestrictions").val();
				var studentRestrictions = $("#streamStudentRestrictions").val();

				//Make the post request
				$.ajax({
					type: 'POST',
					url: 'modules/profile/update_stream.php',
					data: { title: streamtitle, link: rsslink, id: streamid, group: streamgroup, required: required, color: streamcolor, staffRestrictions: staffRestrictions, studentRestrictions: studentRestrictions }
				})
				.done(function(){
					$('#addeditstream').closeModal({ in_duration: 0, out_duration: 0 });
					$('#streamsort').load('modules/profile/stream_editor_content.php');
					$('#content_holder').load( 'modules/profile/profile.php');
				});
			});
		<?php
		}
		?>

		//grabs the selected date from the drop down options in the modal.
		var y = $('#calendaryear').val();
		var defaultDate = '8/1/'+y;
		var currYear = y;
		var email = "<?php echo $_SESSION['useremail'] ?>";

		//makes a post request to get the already chosen dates from the database.
		$.ajax({
			type: 'POST',
			url: '/modules/profile/load_dates.php',
			data: { year : y, email: email},
		})
		.done(function(response) {
			var dateArray = response.addDates;
			var json = response.jsonDates;

			//makes a multidatepicker object with the dates returned from the ajax
			//post and displays them in the calendar modal.
			$('#workcalendardisplay').multiDatesPicker({
				addDates: dateArray,
				numberOfMonths: [6,2],
				defaultDate: defaultDate,
				altField: '#saveddates',
				dayNamesMin: ['S', 'M', 'T', 'W', 'T', 'F', 'S'],
				//saves the date selected to the list of stored dates for the user
				onSelect: function (date) {

					var dates = $('#workcalendardisplay').multiDatesPicker('getDates').length;
					$("#selecteddays").text(dates + " Days Selected");

					var datestosave = $( "#saveddates" ).val();

					$.ajax({
						type: 'POST',
						url: '/modules/profile/calendar_update.php',
						data: { year: y, calendardaystosave : datestosave, jsonDates: json, email: email },
					})
				}
			});

			var dates = $('#workcalendardisplay').multiDatesPicker('getDates').length;
			$("#selecteddays").text(dates + " Days Selected");
		})

		//has similiar logic to the above calls, but handles the case when a user
		//selects a different year from the drop down options.
		$("#calendaryear").change(function(){
			var y = $('#calendaryear').val();
			var defaultDate = '8/1/'+y;
			var email = "<?php echo $_SESSION['useremail'] ?>";

			$.ajax({
				type: 'POST',
				url: '/modules/profile/load_dates.php',
				data: { year : y, email: email },
			})
			.done(function(response) {
				var dateArray = response.addDates;
				var json = response.jsonDates;

				$('#workcalendardisplay').multiDatesPicker('destroy');
				$('#workcalendardisplay').multiDatesPicker({
					addDates: dateArray,
					numberOfMonths: [6,2],
					defaultDate: defaultDate,
					altField: '#saveddates',
					dayNamesMin: ['S', 'M', 'T', 'W', 'T', 'F', 'S'],
					onSelect: function (date) {

						var dates = $('#workcalendardisplay').multiDatesPicker('getDates').length;
						$("#selecteddays").text(dates + " Days Selected");

						var datestosave = $( "#saveddates" ).val();

						$.ajax({
							type: 'POST',
							url: '/modules/profile/calendar_update.php',
							data: { year: y, calendardaystosave : datestosave, jsonDates: json, email: email },
						})
					}
				});
				var dates = $('#workcalendardisplay').multiDatesPicker('getDates').length;
				$("#selecteddays").text(dates + " Days Selected");
			})
			//resets the count of days worked if the current year does not equal the date
			//selected in the drop down menu
			if(currYear != y){
				$('#workcalendardisplay').multiDatesPicker('resetDates', 'picked');
				currYear = y;
			}
		});

	});
</script>