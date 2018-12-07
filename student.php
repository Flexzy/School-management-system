<?php 
	
	require_once "helpers/init.php";
	
	//////////////////////////////////
	/////Gen Student ID///////////////
	$num_student = 0;
	$num_student = $db->query("SELECT stu_id FROM students ORDER BY stu_id DESC LIMIT 1") or die($db->error);
	$row = $num_student->fetch_assoc();
	$num_student = $row['stu_id'] + 1;
	if($num_student < 10) {
		$num_student = "SCH-STU-00".$num_student;
	}elseif($num_student < 100) {
		$num_student = "SCH-STU-0".$num_student;  ////Be sure to later include the year of admission in generating admission no
	}
	
	$result = "";
	$get_student = $db->query("SELECT * FROM students AS stu INNER JOIN class_level AS c ON stu.class_level=c.level_id INNER JOIN section AS s ON c.section_id=s.section_id ORDER BY stu_id DESC") or die($db->error);
	if($get_student->num_rows) {
		$result .= "<table class='table table-bordered' id='student_tb'>
					<thead>
						<tr>
							<th>#</th>
							<th>ADM NO</th>
							<th>Name</th>
							<th>Class</th>
							<th>sex</th>
							<th>State</th>
							<th>Lga</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>";
		$x = 1;
		while($row = $get_student->fetch_assoc()) {
			$result .= " <tr>
							<td>".$x."</td>
							<td>".escape(strtoupper($row['admission_no']))."</td>
							<td>".escape(strtoupper($row['lname'])).", ".escape(ucwords($row['mname']))." ".escape(ucwords($row['fname']))."</td>
							<td>".escape(strtoupper($row['level']))."".escape(strtoupper($row['class_name']))."</td>
							<td>".escape(ucwords($row['gender']))."</td>
							<td>".escape(ucwords($row['state']))."</td>
							<td>".escape(ucwords($row['lga']))."</td>
							<td>
								<a href='edit_student.php?student=".escape($row['admission_no'])."' class='btn btn-custom'><i class='fa fa-pencil'></i></a>
								<a href='#' onclick=\"pay_fee_modal('".escape($row['admission_no'])."', '".num_only($row['section'])."', '".escape($row['class_level'])."'); return false\" class='btn btn-success'><i class='fa fa-paypal'></i></a>
							</td>
						</tr>";
			$x++;
		}
		$result .= "</tbody></table>";
	}else{
		$result = "<br/>".messageFormat("danger customized", "<i class='fa fa-warning'></i> You have not recorded any Students yet. Please click on the 'add student' tab to register a new student.");
	}
	
	$message = "";
	$clear = "";
	
	$sec_option = "<option value=''>Select</option>";
	$get_section = $db->query("SELECT * FROM section");
	if($get_section->num_rows) {
		while($row = $get_section->fetch_assoc()) {
			$sec_option .= "<option value='".num_only($row['section_id'])."'>".escape(strtoupper($row['section_name']))."</option>";
		}
	}
	
?>

<!Doctype html>

<html lang="en">

<head>

<title>School Management Software</title>
<meta charset="utf-8" >
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="School Management Software">
<meta name="keywords" content="">

<!----- Designed By Abdulrahman Adam --->
<!----- E-mail; abdulflezy13@gmail.com --->

<!----------------- CSS Style Sheets ------------------>
<link rel="stylesheet" href="css/bootstrap.min.css" />
<link rel="stylesheet" href="css/font-awesome.min.css" />
<link rel="stylesheet" href="css/dataTables.bootstrap.css">
<link rel="stylesheet" href="css/build.css" />
<link rel="stylesheet" href="css/alertify.min.css" />
<link rel="stylesheet" href="css/themes/default.rtl.css" />
<link rel="stylesheet" href="css/custom.css" />
<!---/-------------- CSS Style Sheets ------------------>

</head>

<body id="fullscreen">
<!--onLoad="window.open('dashboard.html', '', 'fullscreen=yes, scrollbars=auto'); window.opener=null; window.close(); return false;"-->
<div class="container-fluid no_pad_margin">
	<div id="sidebar">
		<div class="logo nav_div text-center">
			<span href="#"><i class="fa fa-lightbulb-o"></i> <span>FLEXISERVE</span> 
			<span class="toggle_small pull-right"><i class="fa fa-th-list" onClick="slide_menu()"></i></span>
			<span class="clearfix"></span></span>
		</div>
		<div class="sidebar_content">
			<div class="nav_img text-center">
				<span><i class="fa fa-circle-o"></i> </span><img src="img/img.png" class="img-circle" width="110px" height="110px;" /> <span><i class="fa fa-weixin"></i> </span>
				<p class="text-center"><strong>Abdulrahman Adamu</strong></p>
				<span class="">Web Developer/Designer</span>
			</div>
			<div class="nav_links">
				<div class="nav_menu_head">
					<span>Navigation</span>
				</div>
				<ul class="links_main">
					<li><a href="#"><i class="fa fa-bullseye"></i> <span class="hide_me">Dashboard</span></a></li>
					<li class="has-children <!--open-->"><a href="#"><i class="fa fa-files-o"></i> <span class="hide_me">Pages</span> <i class="fa fa-angle-down pull-right"></i><span class="clearfix"></span></a>
						<ul class="child-nav">
							<li><a href="#"><i class="fa fa-picture-o"></i> Gallery</a></li>
							<li><a href="#"><i class="fa fa-file-pdf-o"></i> Invoice</a></li>
							<li><a href="#"><i class="fa fa-pencil-square"></i> Edit Profile</a></li>
							<li><a href="#"><i class="fa fa-user"></i> Profile</a></li>
							<li><a href="#"><i class="fa fa-envelope"></i> Mailbox</a></li>
							<li><a href="#"><i class="fa fa-key"></i> Login</a></li>
							<li><a href="#"><i class="fa fa-user-plus"></i> Register</a></li>
							<li><a href="#"><i class="fa fa-lock"></i> Lock Screen</a></li>
							<li><a href="#"><i class="fa fa-undo"></i> Error Pages</a></li>
						</ul>
					</li>
					<li class="has-children"><a href="#"><i class="fa fa-cogs"></i> <span class="hide_me">UI Kits</span> <i class="fa fa-angle-down pull-right"></i><span class="clearfix"></span></a>
						<ul class="child-nav">
							<li><a href="#"><i class="fa fa-picture-o"></i> Gallery</a></li>
						</ul>
					</li>
					<li class="has-children"><a href="#"><i class="fa fa-pencil"></i> <span class="hide_me">Forms</span> <i class="fa fa-angle-down pull-right"></i><span class="clearfix"></span></a>
						<ul class="child-nav">
							<li><a href="#"><i class="fa fa-picture-o"></i> Form Layouts</a></li>
							<li><a href="#"><i class="fa fa-picture-o"></i> Elements</a></li>
							<li><a href="#"><i class="fa fa-picture-o"></i> Validation</a></li>
						</ul>
					</li>
					<li class="has-children"><a href="#"><i class="fa fa-table"></i> <span class="hide_me">Tables</span> <i class="fa fa-angle-down pull-right"></i><span class="clearfix"></span></a>
						<ul class="child-nav">
							<li><a href="#"><i class="fa fa-th"></i> Basic</a></li>
							<li><a href="#"><i class="fa fa-th-list"></i> Datatables</a></li>
							<li><a href="#"><i class="fa fa-cloud-download"></i> Export tables</a></li>
						</ul>
					</li>
					<li class="has-children"><a href="#"><i class="fa fa-line-chart"></i> <span class="hide_me">Charts</span> <i class="fa fa-angle-down pull-right"></i><span class="clearfix"></span></a>
						<ul class="child-nav">
							<li><a href="#"><i class="fa fa-th"></i> Chat Js</a></li>
							<li><a href="#"><i class="fa fa-th-list"></i> Morris Js</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div id="content">
		<div class="container-fluid nav_items nav_div">
			<i class="fa fa-outdent" id="icon_minimize" onClick="minimize();"></i>
			<ul class="pull-right nav_icons">
				<li><a href="#"><i class="fa fa-user"></i> </a></li>
				<li><a href="#"><i class="fa fa-weixin"></i><span class="badge">5</span> </a></li>
				<li><a href="#"><i class="fa fa-power-off"></i> </a></li>
			</ul>
			<span class="clearfix"></span>
		</div>
		<div class="content-directory">
			<a href="#">Home</a> <i class="fa fa-angle-right"></i> <a href="#">Students</a>
		</div>
		<div class="content-wrapper shrink">
			<h3>Students</h3>
			<div class="content-body shrink">
				<div class="row">
					<div class="col-sm-12">
						<ul class="nav nav-tabs">
							<li class="active"><a data-toggle="tab" href="#menu1">All Students</a></li>
							<li><a data-toggle="tab" href="#home">+ Add Student</a></li>
						</ul>
						<div class="custom bg_all">
							
							<div class="tab-content">
								<div id="home" class="tab-pane fade">
									<div class="row">
										<ul class="nav nav-tabs nav-justified" id="sub_tabs">
											<li class="active"><a data-toggle="tab" href="#bio">Basic Details</a></li>
											<li><a data-toggle="tab" href="#academic">Academic Details</a></li>
											<li><a data-toggle="tab" href="#guardian">Guardian Information</a></li>
											<li><a data-toggle="tab" href="#contact">Contact Details</a></li>
										</ul>
										<div class="col-md-11">
											<h4><i class="fa fa-user-plus"></i> New entry</h4><br/>
											<div class="col-sm-offset-3 col-sm-10"><?php echo $message; ?></div>
											<form class="form-horizontal" method="post" action="<?php escape($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data" id="add_student_form" onSubmit="sub_student(); return false" >
											
												<div class="tab-content">
													<div id="bio" class="tab-pane fade in active">
														<div class="row">
															<div class="col-md-9">
																<div class="form-group">
																	<label class="control-label col-sm-4" for="input-1">Last Name<span class='text-red'>*</span></label>
																	<div class="col-sm-8">
																		<div class="input-group">
																			<span class="input-group-addon"><i class="fa fa-user-circle"></i></span>
																			<input type="text" value="<?php exxcape('lname', $clear); ?>" class="form-control" name="slname" placeholder="Last Name" />
																		</div>
																	</div>
																</div>
																<div class="form-group">
																	<label class="control-label col-sm-4" for="input-1">Middle Name</label>
																	<div class="col-sm-8">
																		<div class="input-group">
																			<span class="input-group-addon"><i class="fa fa-user-circle"></i></span>
																			<input type="text" value="<?php exxcape('mname', $clear); ?>" class="form-control" name="smname" placeholder="Middle name" />
																		</div>
																	</div>
																</div>
																<div class="form-group">
																	<label class="control-label col-sm-4" for="input-1">First Name<span class='text-red'>*</span></label>
																	<div class="col-sm-8">
																		<div class="input-group">
																			<span class="input-group-addon"><i class="fa fa-user-circle"></i></span>
																			<input type="text" value="<?php exxcape('fname', $clear); ?>" class="form-control" name="sfname" placeholder="First name" />
																		</div>
																	</div>
																</div>
																<div class="form-group">
																	<label class="control-label col-sm-4" for="input-1">Date of Birth<span class='text-red'>*</span></label>
																	<div class="col-sm-8">
																		<div class="input-group">
																			<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
																			<input type="date" value="<?php exxcape('dob', $clear); ?>" class="form-control" name="sdob" placeholder="dd/mm/yyyy" />
																		</div>
																	</div>
																</div>
																<div class="form-group">
																	<label class="control-label col-sm-4" for="input-1">Nationality<span class='text-red'>*</span></label>
																	<div class="col-sm-8">
																		<div class="input-group">
																			<span class="input-group-addon"><i class="fa fa-bookmark"></i></span>
																			<select name="snationality" class="form-control">
																				<option value="Nigerian">Nigerian</option>
																				<option value="Non-nigerian">Non-nigerian</option>
																			</select>
																		</div>
																	</div>
																</div>
																<div class="form-group">
																	<label class="control-label col-sm-4" for="input-1">State of Origin<span class='text-red'>*</span></label>
																	<div class="col-sm-8">
																		<div class="input-group">
																			<span class="input-group-addon"><i class="fa fa-tags"></i></span>
																			<select class="form-control" name="sstate" id="state" onChange="load_lga();">
																				<option value="">Select</option>
																				<?php my_states(); ?>
																			</select>
																		</div>
																	</div>
																</div>
																<div class="form-group">
																	<label class="control-label col-sm-4" for="input-1">Local Govt.<span class='text-red'>*</span></label>
																	<div class="col-sm-8">
																		<div class="input-group">
																			<span class="input-group-addon"><i class="fa fa-tag"></i></span>
																			<select class="form-control" name="slga" id="lga">
																				<option value="">Select</option>
																				<?php echo $mOption; ?>
																			</select>
																		</div>
																	</div>
																</div>
																<div class="form-group">
																	<label class="control-label col-sm-4" for="input-1">Gender<span class='text-red'>*</span></label>
																	<div class="col-sm-8">
																		<div class="input-group">
																			<span class="input-group-addon"><i class="fa fa-user-circle"></i></span>
																			<select name="sgender" class="form-control">
																				<option value="">Select</option>
																				<option value="M">Male</option>
																				<option value="F">Female</option>
																			</select>
																		</div>
																	</div>
																</div>
																<div class="form-group">
																	<label class="control-label col-sm-4" for="input-1">Religion<span class='text-red'>*</span></label>
																	<div class="col-sm-8">
																		<div class="input-group">
																			<span class="input-group-addon"><i class="fa fa-exchange"></i></span>
																			<input type="text" value="<?php exxcape('religion', $clear); ?>" class="form-control" name="sreligion" placeholder="Religion" />
																		</div>
																	</div>
																</div>
															</div>
															
															<div class="col-md-3">
																<div class="img-circle passport_holder">
																	<img src="img/img.svg" id="imageView" class="img-circle" alt="Passport Photo" />
																	<div class="col-sm-12">
																		<input type="file" name="spassport" id="passport" placeholder="Passport" onchange="readURL(this);" accept="image/gif, image/jpeg, image/png" style="visibility: hidden; height: 0px;" />
																		<label class="btn btn-photo text-center photo_label edit" for="passport"><span><i class="fa fa-cloud-upload"></i> Upload Passport</span></label>
																	</div>
																</div>
															</div>
														</div>
													</div>
													
													<div id="academic" class="tab-pane fade">
														<h4><i class="fa fa-book"></i> Guardian Information</h4><br/>
														<div class="row">
															<div class="col-md-9">
																<div class="form-group">
																	<label class="control-label col-sm-4" for="input-1">Admission No. <span class='text-red'>*</span></label>
																	<div class="col-sm-8">
																		<div class="input-group">
																			<span class="input-group-addon"><i class="fa fa-barcode"></i></span>
																			<input type="text" class="form-control" value="<?php echo $num_student; ?>" name="sadmission_no" id="admission_no" placeholder="Admission No." readonly />
																			<input type="hidden" class="form-control" value="<?php echo $num_student; ?>" name="sadmin_no" id="admin_no" />
																		</div>
																	</div>
																</div>
																<div class="form-group">
																	<label class="control-label col-sm-4" for="input-1">Section <span class='text-red'>*</span></label>
																	<div class="col-sm-8">
																		<div class="input-group">
																			<span class="input-group-addon"><i class="fa fa-user-circle"></i></span>
																			<select name="ssection" id="section" onChange="get_level()" class="form-control">
																				<?php echo $sec_option; ?>
																			</select>
																		</div>
																	</div>
																</div>
																<div class="form-group">
																	<label class="control-label col-sm-4" for="input-1">Class Level <span class='text-red'>*</span></label>
																	<div class="col-sm-8">
																		<div class="input-group">
																			<span class="input-group-addon"><i class="fa fa-user-circle"></i></span>
																			<select name="sclass_level" id="class_level" class="form-control">
																				<option value="" >Select</option>
																			</select>
																		</div>
																	</div>
																</div>
																<div class="form-group">
																	<label class="control-label col-sm-4" for="input-1">Class Alphabet<span class='text-red'>*</span></label>
																	<div class="col-sm-8">
																		<div class="input-group">
																			<span class="input-group-addon"><i class="fa fa-user-circle"></i></span>
																			<select name="salpha" class="form-control">
																				<option value="">Select</option>
																				<option value="A">A</option>
																				<option value="B">B</option>
																				<option value="C">C</option>
																				<option value="D">D</option>
																				<option value="E">E</option>
																				<option value="F">F</option>
																			</select>
																		</div>
																	</div>
																</div>
																<div class="form-group">
																	<label class="control-label col-sm-4" for="input-1">Admission Date<span class='text-red'>*</span></label>
																	<div class="col-sm-8">
																		<div class="input-group">
																			<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
																			<input type="date" value="<?php exxcape('ap_date', $clear); ?>" class="form-control" name="sadmission_date" placeholder="dd/mm/yyyy" />
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
													
													<div id="guardian" class="tab-pane fade">
														<div class="row">
															<div class="col-md-9">
																<div class="form-group">
																	<label class="control-label col-sm-4" for="input-2">Father's name<span class='text-red'>*</span></label>
																	<div class="col-sm-8">
																		<div class="input-group">
																			<span class="input-group-addon"><i class="fa fa-user-circle"></i></span>
																			<input type="text" value="<?php exxcape('sfather', $clear); ?>" class="form-control" name="sfather" id="input-2" placeholder="Father's name" />
																		</div>
																	</div>
																</div>
																<div class="form-group">
																	<label class="control-label col-sm-4" for="input-1">Father's occupation<span class='text-red'>*</span></label>
																	<div class="col-sm-8">
																		<div class="input-group">
																			<span class="input-group-addon"><i class="fa fa-exchange"></i></span>
																			<input type="text" value="<?php exxcape('sfather_occu', $clear); ?>" class="form-control" name="sfather_occu" placeholder="Father's Occupation" />
																		</div>
																	</div>
																</div>
																<div class="form-group">
																	<label class="control-label col-sm-4" for="input-3">Mother's name</label>
																	<div class="col-sm-8">
																		<div class="input-group">
																			<span class="input-group-addon"><i class="fa fa-user-circle"></i></span>
																			<input type="text" value="<?php exxcape('smother', $clear); ?>" class="form-control" name="smother" id="input-3" placeholder="Mother's name" />
																		</div>
																	</div>
																</div>
																<div class="form-group">
																	<label class="control-label col-sm-4" for="input-1">Mother's occupation</label>
																	<div class="col-sm-8">
																		<div class="input-group">
																			<span class="input-group-addon"><i class="fa fa-exchange"></i></span>
																			<input type="text" value="<?php exxcape('smother_occu', $clear); ?>" class="form-control" name="smother_occu" placeholder="Mother's Occupation" />
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
													
													<div id="contact" class="tab-pane fade">
														<div class="row">
															<div class="col-md-9">
																<div class="form-group">
																	<label class="control-label col-sm-4" for="input-1">Mobile<span class='text-red'>*</span></label>
																	<div class="col-sm-8">
																		<div class="input-group">
																			<span class="input-group-addon"><i class="fa fa-phone"></i></span>
																			<input type="number" min="0" maxlength="11" value="<?php exxcape('mobile', $clear); ?>" class="form-control" name="smobile" placeholder="Mobile Number" />
																		</div>
																	</div>
																</div>
																<div class="form-group">
																	<label class="control-label col-sm-4" for="input-1">Email Address</label>
																	<div class="col-sm-8">
																		<div class="input-group">
																			<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
																			<input type="email" value="<?php exxcape('email', $clear); ?>" class="form-control" name="semail" placeholder="staffid@school.edu.ng" />
																		</div>
																	</div>
																</div>
																<div class="form-group">
																	<label class="control-label col-sm-4" for="input-1">Home Address<span class='text-red'>*</span></label>
																	<div class="col-sm-8">
																		<div class="input-group">
																			<span class="input-group-addon">&nbsp;<i class="fa fa-map-marker"></i>&nbsp;</span>
																			<textarea name="saddress" class="form-control" placeholder="permanent home address"><?php exxcape('address', $clear); ?></textarea>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="form-group">
													<div class="col-sm-offset-3 col-sm-9">
														<button type="submit" class="btn btn-custom" name="submit">Save Student</button>
													</div>
												</div>
											</form>
										</div>
									</div>
								</div>
								<div id="menu1" class="tab-pane fade  in active">
									<h4><i class="fa fa-users"></i> Registered Students</h4>
									<div id="student_div">
										<?php echo $result; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
		</div>
		
	</div>
</div>

<!--Edit Academic Year Modal -->
<div class="modal fade" id="payfeeModal" role="dialog">
	<div class="modal-dialog modal-md">
		<!-- Modal content-->
		<div class="modal-content" style="border-radius: 0px;">
			<div class="modal-body" style="padding-left: 30px; padding-right: 30px">
				<form class="modal_style" id="pay_fee_form" method="post" action="parse.php" onSubmit="pay_fee(); return false" enctype="multipart/form-data">
					<h3 class="text-center text-blue"><i class="fa fa-camera-retro text-gold"></i> Musa Iliyasu College<br/><span style="font-style: italic; font-size: 13px;">Rijiyar zaki, along gwarzo road kano state</span></h3><hr/>
					<div id="mloader"></div>
					
					<div id="fee_body">
						
					</div>
					<div class="form-group text-right">
						<button type="submit" name="pay" class="btn btn-custom">Process fee payment</button>
					</div>
				</form>
				<div class="row">
					<div class="col-md-12">
						<button type="button" class="btn btn-default pull-right" data-dismiss="modal"><span class="text-danger"><i class="fa fa-undo"></i> Close</span></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

</body>

<!-------------- JQuery Files --------------------->
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.dataTables.js"></script>
<script src="js/dataTables.bootstrap.js"></script>
<script src="js/bootbox.min.js"></script>
<script src="js/alertify.min.js"></script>
<script src="js/custom.js"></script>
<!---/--------- JQuery Files ---------------------->

<script>

	$("#student_tb").DataTable();
	function check() {
		window.scrollTo(0,0);
	}
	
	//load_student();
	
</script>

</html>