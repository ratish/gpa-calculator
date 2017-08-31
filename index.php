<?php
	include_once '/usr2/phpincludes/southeastern_tmpl_new/html_open.php';
?>
  <title>GPA Calculator</title>
<?php
	include_once '/usr2/phpincludes/southeastern_tmpl_new/css_contents.php';
?>
	<link rel="stylesheet" type="text/css" href="style.css">
<?php
include_once '/usr2/phpincludes/southeastern_tmpl_new/js_header.php';
include_once '/usr2/phpincludes/southeastern_tmpl_new/header_close.php';
include_once '/usr2/phpincludes/southeastern_tmpl_new/body_open.php';
include_once '/usr2/phpincludes/southeastern_tmpl_new/top_contents.php';
?>
<h2>Calculate Your GPA</h2>
<div id="gpaCalculatorApp">
	<div class="row">
		<div class="col-sm-offset-1 col-sm-10 well well-sm">
			<fieldset>
				<legend>GPA Calculator</legend>
				<form class="form-horizontal" name="gpaCalulatorForm" id="gpaCalulatorForm" method="post" novalidate>
					<div class="form-group hidden-xs hidden-sm">
						<div class="col-sm-4"><strong>Course</strong></div>
						<div class="col-sm-2"><strong>Credit Hours</strong></div>
						<div class="col-sm-3"><strong>Grade</strong></div>
					</div>
					<div v-for="(course, index) in courses" id="courseRow" class="form-group">
						<div class="col-sm-4">
						  	<input v-model.trim="course.title" type="text" class="form-control" name="title" id="title" placeholder="Course Name" />
						</div>
						<div class="col-sm-2">
						  	<input v-model.trim="course.creditHour" type="text" class="form-control" name="creditHour" id="creditHour" placeholder="Credit Hours" />
						</div>
						<div class="col-sm-3">
						  	<select v-model="course.grade" class="form-control" name="grade" id="grade">
								<option value="" selected="selected">Select a grade</option>
								<option value="4.0">A</option>
								<option value="3.0">B</option>
								<option value="2.0">C</option>
								<option value="1.0">D</option>
								<option value="0.0">F</option>
								<option value="-1.0">P</option>
							</select>
						</div>
						<div class="col-sm-2">
							<a v-on:click="removeRow(index);" class="btn btn-link remove-link text-danger"><small>Remove</small></a>
						</div>
					</div>
					<div class="text-right">
						<button  v-on:click="addRow" type="button" class="btn btn-default">+ Add Another Class</button>
					</div>
				</form>
			</fieldset>
		</div>
	</div>
	<div class="row" id="gpaOutput">
		<div class="col-sm-offset-3 col-sm-3 text-center">
			<div class="box-round box-grey">
				<div class="box-title">Total Hours</div>
				<div v-if="show" id="semesterHours" class="box-total">{{ semester.hours }}</div>
			</div>
		</div>
		<div class="col-sm-3 text-center">
			<div class="box-round box-green">
				<div class="box-title">Semester GPA</div>
				<div v-if="show" id="semesterGPA" class="box-total">{{ semester.gpa }}</div>
			</div>
		</div>
    </div>
</div>
<?php
include_once '/usr2/phpincludes/southeastern_tmpl_new/bottom_contents.php';
include_once '/usr2/phpincludes/southeastern_tmpl_new/js_contents.php';
?>
<script src="https://vuejs.org/js/vue.js"></script>
<script src="https://unpkg.com/vee-validate@2.0.0-rc.7"></script>
<script src="app.js"></script>
<?php
include_once '/usr2/phpincludes/southeastern_tmpl_new/html_close.php';