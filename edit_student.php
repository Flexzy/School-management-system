<?php 
	
	require_once "helpers/init.php";
	
		
	$message = "";
	$clear = "";
	
	$photo = "img/img.svg";
	$section = "";
	$alpha = "";
	if($_SERVER['REQUEST_METHOD'] == 'GET') {
		if(isset($_GET['student']) && escape($_GET['student']) != "") {
			$admNo = escape($_GET['student']);
			if($admNo != "") {
				$check = $db->query("SELECT * FROM students WHERE admission_no='".$admNo."' LIMIT 1");
				if($check->num_rows) {
					$row = $check->fetch_assoc();
					$section = num_only($row['section']);
					$alpha = escape(strtoupper($row['class_name']));
					$photo = (escape($row['photo']) == "" && !file_exists(escape($row['photo'])))? $photo : escape($row['photo']);
				}else{
					header("location: student.php");
				}
			}
		}else{
			header("location: student.php");
		}
	}
	
	$state_arr = states_lgas();
	$mOption = "";
	$x = 1;
	if(escape(ucwords($row['state'])) != "") {
		foreach($state_arr[escape(ucwords($row['state']))] as $value) {
			if($x == 1) {
				$mOption .= "<option value=''>Select</option>";
			}elseif($x > 1) {
				if(ucwords(escape($row['lga'])) == ucwords(escape($value))) {
					$mOption .= "<option value='".ucwords(escape($value))."' selected>".ucwords(escape($value))."</option>";
				}else{
					$mOption .= "<option value='".ucwords(escape($value))."'>".ucwords(escape($value))."</option>";
				}
			}
			$x++;
		}
	}
	
	//////////////////////////////////////////
	///////////Select Section/////////////////
	$sec_option = "<option value=''>Select</option>";
	$get_section = $db->query("SELECT * FROM section");
	if($get_section->num_rows) {
		while($row_sec = $get_section->fetch_assoc()) {
			if(num_only($section) == num_only($row_sec['section_id'])) {
				$sec_option .= "<option value='".num_only($row_sec['section_id'])."' selected>".escape(strtoupper($row_sec['section_name']))."</option>";
			}else{
				$sec_option .= "<option value='".num_only($row_sec['section_id'])."'>".escape(strtoupper($row_sec['section_name']))."</option>";
			}
		}
	}
	
	////////////////////////////////////////////
	/////Select Class level with section////////
	$lev_option = "<option value=''>Select</option>";
	$get_level = $db->query("SELECT * FROM class_level WHERE section_id='".$section."'");
	if($get_level->num_rows) {
		while($row_lev = $get_level->fetch_assoc()) {
			if(num_only($row['class_level']) == num_only($row_lev['level_id'])) {
				$lev_option .= "<option value='".num_only($row_lev['level_id'])."' selected>".escape(strtoupper($row_lev['level']))."</option>";
			}else{
				$lev_option .= "<option value='".num_only($row_lev['level_id'])."'>".escape(strtoupper($row_lev['level']))."</option>";
			}
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
			<a href="#">Home</a> <i class="fa fa-angle-right"></i> <a href="#">Student</a> <i class="fa fa-angle-right"></i> <a href="#">Edit Student</a>
		</div>
		<div class="content-wrapper shrink">
			<h3>Update Student</h3>
			<div class="content-body shrink">
				<div class="row">
					<div class="col-sm-12">
						
						<form class="form-horizontal" method="post" action="<?php escape($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data" id="edit_student_form" onSubmit="edit_student(); return false" >
						
							<ul class="nav nav-tabs nav-justified" id="sub_tabs">
								<li class="active"><a data-toggle="tab" href="#bio">Basic Details</a></li>
								<li><a data-toggle="tab" href="#academic">Academic Details</a></li>
								<li><a data-toggle="tab" href="#guardian">Guardian Information</a></li>
								<li><a data-toggle="tab" href="#contact">Contact Details</a></li>
							</ul>
							<div class="custom bg_all">
								
								<div class="tab-content">
									<div id="bio" class="tab-pane fade in active">
										<div class="row">
											<div class="col-md-9">
												<h4><i class="fa fa-pencil"></i> Update entry</h4><br/>
												<div class="col-sm-offset-3 col-sm-10"><?php echo $message; ?></div>
												
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Last Name<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-user-circle"></i></span>
															<input type="text" value="<?php echo escape(ucwords($row['lname'])); ?>" class="form-control" name="eslname" placeholder="Last Name" />
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Middle Name</label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-user-circle"></i></span>
															<input type="text" value="<?php echo escape(ucwords($row['mname'])); ?>" class="form-control" name="esmname" placeholder="Middle name" />
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">First Name<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-user-circle"></i></span>
															<input type="text" value="<?php echo escape(ucwords($row['fname'])); ?>" class="form-control" name="esfname" placeholder="First name" />
														</div>
													</div>
												</div>
												
												
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Date of Birth<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
															<input type="date" value="<?php echo escape($row['dob']); ?>" class="form-control" name="esdob" placeholder="dd/mm/yyyy" />
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Nationality<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-bookmark"></i></span>
															<select name="esnationality" class="form-control">
																<option value="Nigerian" <?php echo (escape(ucwords($row['nationality'])) == "Nigerian")? 'selected' : ''; ?>>Nigerian</option>
																<option value="Non-nigerian" <?php echo (escape(ucwords($row['nationality'])) == "Non-nigerian")? 'selected' : ''; ?>>Non-nigerian</option>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">State of Origin<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-tags"></i></span>
															<select class="form-control" name="esstate" id="state" onChange="load_lga();">
																<option value="">Select</option>
																<?php my_states(escape(ucfirst($row['state']))); ?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Local Govt.<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-tag"></i></span>
															<select class="form-control" name="eslga" id="lga">
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
															<select name="esgender" class="form-control">
																<option value="">Select</option>
																<option value="M" <?php echo (escape(ucwords($row['gender'])) == "M")? 'selected' : ''; ?>>Male</option>
																<option value="F" <?php echo (escape(ucwords($row['gender'])) == "F")? 'selected' : ''; ?>>Female</option>
															</select>
														</div>
													</div>
												</div>
												
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Religion<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-exchange"></i></span>
															<input type="text" value="<?php echo escape(ucfirst($row['religion'])); ?>" class="form-control" name="esreligion" placeholder="Religion" />
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-3">
												<div class="img-circle passport_holder">
													<img src="<?php echo $photo; ?>" id="imageView" class="img-circle" alt="Passport Photo" />
													<div class="col-sm-12">
														<input type="file" name="espassport" id="passport" placeholder="Passport" onchange="readURL(this);" accept="image/gif, image/jpeg, image/png" style="visibility: hidden; height: 0px;" />
														<label class="btn btn-photo text-center photo_label edit" for="passport"><span><i class="fa fa-cloud-upload"></i> Change Passport</span></label>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div id="academic" class="tab-pane fade">
										<h4><i class="fa fa-book"></i> Academic Information</h4><br/>
										<div class="row">
											<div class="col-md-9">
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Admission No. <span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-barcode"></i></span>
															<input type="text" class="form-control" value="<?php echo strtoupper($admNo); ?>" name="esadmission_no" id="admission_no" placeholder="Admission No." readonly />
															<input type="hidden" class="form-control" value="<?php echo $admNo; ?>" name="esadmin_no" id="admin_no" />
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Section <span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-user-circle"></i></span>
															<select name="essection" id="section" onChange="get_level()" class="form-control">
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
															<select name="esclass_level" id="class_level" class="form-control">
																<option value="" >Select</option>
																<?php echo $lev_option; ?>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Class Alphabet<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-user-circle"></i></span>
															<select name="esalpha" class="form-control">
																<option value="">Select</option>
																<option value="A" <?php echo ($alpha == "A")? 'selected' : ''; ?>>A</option>
																<option value="B" <?php echo ($alpha == "B")? 'selected' : ''; ?>>B</option>
																<option value="C" <?php echo ($alpha == "C")? 'selected' : ''; ?>>C</option>
																<option value="D" <?php echo ($alpha == "D")? 'selected' : ''; ?>>D</option>
																<option value="E" <?php echo ($alpha == "E")? 'selected' : ''; ?>>E</option>
																<option value="F" <?php echo ($alpha == "F")? 'selected' : ''; ?>>F</option>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Admission Date<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
															<input type="date" value="<?php echo escape($row['admission_date']); ?>" class="form-control" name="esadmission_date" placeholder="dd/mm/yyyy" />
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div id="guardian" class="tab-pane fade">
										<h4><i class="fa fa-user-circle"></i> Guardian Information</h4><br/>
										<div class="row">
											<div class="col-md-9">
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-2">Father's name<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-user-circle"></i></span>
															<input type="text" value="<?php echo escape(ucwords($row['father_name'])); ?>" class="form-control" name="esfather" id="input-2" placeholder="Father's name" />
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Father's occupation<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-exchange"></i></span>
															<input type="text" value="<?php echo escape(ucfirst($row['father_occu'])); ?>" class="form-control" name="esfather_occu" placeholder="Father's Occupation" />
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-3">Mother's name</label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-user-circle"></i></span>
															<input type="text" value="<?php echo escape(ucwords($row['mother_name'])); ?>" class="form-control" name="esmother" id="input-3" placeholder="Mother's name" />
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Mother's occupation</label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-exchange"></i></span>
															<input type="text" value="<?php echo escape(ucfirst($row['mother_occu'])); ?>" class="form-control" name="esmother_occu" placeholder="Mother's Occupation" />
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									
									<div id="contact" class="tab-pane fade">
										<h4><i class="fa fa-map-marker"></i> Contact details</h4>
										<div class="row">	
											<div class="col-md-9">
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Mobile<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-phone"></i></span>
															<input type="number" min="0" maxlength="11" value="<?php echo escape(num_only($row['mobile'])); ?>" class="form-control" name="esmobile" placeholder="Mobile Number" />
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Email Address</label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
															<input type="email" value="<?php echo escape(strtolower($row['email'])); ?>" class="form-control" name="esemail" placeholder="staffid@school.edu.ng" />
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Home Address<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon">&nbsp;<i class="fa fa-map-marker"></i>&nbsp;</span>
															<textarea name="esaddress" class="form-control" placeholder="permanent home address"><?php echo escape(ucfirst($row['address'])); ?></textarea>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-9 btn_div">
									<button type="submit" class="btn btn-custom pull-right" name="submit">Update student detail</button>
								</div>
							</div>
						</form>
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

	$("#staff_tb").DataTable();
	function check() {
		window.scrollTo(0,0);
	}
	
</script>

</html>