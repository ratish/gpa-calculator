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
<div>
	<h2 class="border-bottom">GPA CALCULATORS</h2>
	<ul class="nav nav-tabs tab-section" role="tablist">
		<li role="presentation" class="active"><a href="#semesterGPATab" aria-controls="semesterGPATab" role="tab" data-toggle="tab">Calculate Semester GPA</a></li>
		<li role="presentation"><a href="#targetGPATab" aria-controls="targetGPATab" role="tab" data-toggle="tab">Calculate Target GPA</a></li>
		<li role="presentation"><a href="#reverseGPATab" aria-controls="reverseGPATab" role="tab" data-toggle="tab">Calculate Reverse GPA</a></li>
	</ul>
	<div class="tab-content">
		<div role="tabpanel" class="tab-pane active" id="semesterGPATab">
			<h3>Calculate Semester GPA</h3>
			<div id="semesterGPAApp" class="gpa-app">
				<div class="row">
					<div class="col-sm-offset-1 col-sm-10 well well-sm">
						<fieldset>
							<legend>Semester GPA Calculator</legend>
							<form class="form-horizontal" name="semesterGPAForm" id="semesterGPAForm" method="post" novalidate>
								<div class="form-group hidden-xs hidden-sm">
									<div class="col-sm-5"><strong class="form-label">Course</strong></div>
									<div class="col-sm-2"><strong class="form-label">Credit Hours</strong></div>
									<div class="col-sm-3"><strong class="form-label">Grade</strong></div>
								</div>
								<div v-for="(course, index) in courses" id="courseRow" class="form-group">
									<div class="col-sm-5">
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
									<div class="col-sm-1">
										<a v-on:click="removeRow(index);" class="btn btn-link remove-link text-danger"><small>Remove</small></a>
									</div>
								</div>
								<div class="text-right">
									<button  v-on:click="addRow" type="button" class="btn btn-default">+ Add Another Course</button>
								</div>
							</form>
						</fieldset>
					</div>
				</div>
				<div class="row" id="semesterGPAOutput">
					<div class="col-sm-offset-3 col-sm-3 text-center">
						<div class="box-round box-grey">
							<div class="box-title">Total Hours</div>
							<div v-if="show" id="semesterHours" class="box-total">{{ semester.hours }}</div>
						</div>
					</div>
					<div class="col-sm-3 text-center">
						<div class="box-round" v-bind:class="semester.gpaClass">
							<div class="box-title">Semester GPA</div>
							<div v-if="show" id="semesterGPA" class="box-total change-transition">{{ semester.gpa }}</div>
						</div>
					</div>
			    </div>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane" id="targetGPATab">
			<h3>Calculate Target GPA</h3>
			<div id="targetGPAApp" class="gpa-app">
				<div class="row">
					<div class="col-sm-offset-1 col-sm-3 well well-sm">
						<fieldset>
							<div class="row">
								<div class="col-sm-12">
									<form name="targetGPAForm" id="targetGPAForm" method="post" novalidate>
										<div class="form-group">
											<label for="totalHours">Total Credit Hours Attempted</label>
		    								<input v-model.trim="totalHours" v-validate="'required|decimal:2|between:1,160'" :class="{'input': true, 'text-danger': errors.has('totalHours') }" data-vv-as="Total Credit Hours" type="text" class="form-control" id="totalHours" name="totalHours">
		    								<span v-show="errors.has('totalHours')" class="help text-danger">{{ errors.first('totalHours') }}</span>
		    							</div>
		    							<div class="form-group">
											<label for="currentGPA">Current GPA</label>
		    								<input v-model.trim="currentGPA" v-validate="'required|decimal:2|between:0,4'" :class="{'input': true, 'text-danger': errors.has('currentGPA') }" data-vv-as="Current GPA" type="text" class="form-control" id="currentGPA" name="currentGPA">
		    								<span v-show="errors.has('currentGPA')" class="help text-danger">{{ errors.first('currentGPA') }}</span>
		    							</div>
		    							<div class="form-group">
											<label for="targetGPA">Target GPA</label>
		    								<input v-model.trim="targetGPA" v-validate="'required|decimal:2|between:0,4'" :class="{'input': true, 'text-danger': errors.has('targetGPA') }" data-vv-as="Target GPA" type="text" class="form-control" id="targetGPA" name="targetGPA">
		    								<span v-show="errors.has('targetGPA')" class="help text-danger">{{ errors.first('targetGPA') }}</span>
		    							</div>
										<div class="text-right">
											<button v-on:click="getTargetGPA();" type="button" class="btn btn-primary btn-lg btn-block">Calculate</button>
										</div>
									</form>
								</div>
							</div>
						</fieldset>
					</div>
					<div class="col-sm-offset-1 col-sm-7 well">
						<h3>Result:</h3>
						<div id="targetGPAOutput" v-html="result" class="text-center"></div>
					</div>
				</div>
			</div>
		</div>
		<div role="tabpanel" class="tab-pane" id="reverseGPATab">
			<h3>Calculate Reverse GPA</h3>
			<div id="reverseGPAApp" class="gpa-app">
				<div class="row">
					<div class="col-sm-offset-1 col-sm-10 well well-sm">
						<fieldset>
							<legend>Reverse GPA Calculator</legend>
							<form class="form-horizontal" name="reverseGPAForm" id="reverseGPAForm" method="post" novalidate>
								<div class="form-group hidden-xs hidden-sm">
									<div class="col-sm-5"><strong class="form-label">Course</strong></div>
									<div class="col-sm-2"><strong class="form-label">Credit Hours</strong></div>
									<div class="col-sm-3"><strong class="form-label">Grade</strong></div>
								</div>
								<div v-for="(course, index) in courses" id="courseRow" class="form-group">
									<div class="col-sm-5">
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
									<div class="col-sm-1">
										<a v-on:click="removeRow(index);" class="btn btn-link remove-link text-danger"><small>Remove</small></a>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-3">
										<strong class="form-label">Semester GPA</strong>
									  	<input v-model.trim="semesterGPA" type="text" class="form-control" name="semesterGPA" id="semesterGPA" placeholder="Semester GPA" />
									</div>
									<div class="col-sm-9 text-right">
										<button  v-on:click="addRow" type="button" class="btn btn-default">+ Add Another Course</button>
									</div>
								</div>
								<div class="text-right">
									<button v-on:click="calculateReverseGPA();" type="button" class="btn btn-primary btn-lg btn-block">Calculate Reverse GPA</button>
								</div>
							  	<h5><span class="form-label">Total Credit Hours: {{ totalSemeterHours }}</span></h5>
							</form>
						</fieldset>
					</div>
				</div>
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