<?php 
	
	require_once "helpers/init.php";
	
		
	$message = "";
	$clear = "";
	
	$photo = "img/img.svg";
	
	if($_SERVER['REQUEST_METHOD'] == 'GET') {
		if(isset($_GET['id']) && escape($_GET['id']) != "") {
			$staffID = escape($_GET['id']);
			if($staffID != "") {
				$check = $db->query("SELECT * FROM staff WHERE staff_id='".$staffID."' LIMIT 1");
				if($check->num_rows) {
					$row = $check->fetch_assoc();
					$photo = (escape($row['photo']) == "" && !file_exists(escape($row['photo'])))? $photo : escape($row['photo']);
				}else{
					header("location: staff.php");
				}
			}
		}else{
			header("location: staff.php");
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
			<a href="#">Home</a> <i class="fa fa-angle-right"></i> <a href="#">Staff</a> <i class="fa fa-angle-right"></i> <a href="#">Edit Staff</a>
		</div>
		<div class="content-wrapper shrink">
			<h3>Update Staff</h3>
			<div class="content-body shrink">
				<div class="row">
					<div class="col-sm-12">
						
						<form class="form-horizontal" method="post" action="<?php escape($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data" id="edit_staff_form" onSubmit="edit_staff(); return false" >
						
							<ul class="nav nav-tabs">
								<li class="active"><a data-toggle="tab" href="#home">Biodata</a></li>
								<li><a data-toggle="tab" href="#menu1">Employment Info</a></li>
								<li><a data-toggle="tab" href="#menu2">Contact Details</a></li>
								<!--<li><a data-toggle="tab" href="#menu3">Other Details</a></li>-->
							</ul>
							<div class="custom bg_all">
								
								<div class="tab-content">
									<div id="home" class="tab-pane fade in active">
										<div class="row">
											<div class="col-md-9">
												<h4><i class="fa fa-pencil"></i> Update entry</h4><br/>
												<div class="col-sm-offset-3 col-sm-10"><?php echo $message; ?></div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Staff ID<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-barcode"></i></span>
															<input type="text" class="form-control" value="<?php echo $staffID; ?>" name="estaff_id" id="staff_id" placeholder="Staff ID" readonly />
															<input type="hidden" value="<?php echo $staffID; ?>" name="edit_staffID" id="staffID" />
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Last Name<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-user-circle"></i></span>
															<input type="text" value="<?php echo escape(ucwords($row['lname'])); ?>" class="form-control" name="elname" placeholder="Last Name" />
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Middle Name</label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-user-circle"></i></span>
															<input type="text" value="<?php echo escape(ucwords($row['mname'])); ?>" class="form-control" name="emname" placeholder="Middle name" />
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">First Name<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-user-circle"></i></span>
															<input type="text" value="<?php echo escape(ucwords($row['fname'])); ?>" class="form-control" name="efname" placeholder="First name" />
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Date of Birth<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
															<input type="date" value="<?php echo escape($row['dob']); ?>" class="form-control" name="edob" placeholder="dd/mm/yyyy" />
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Nationality<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-bookmark"></i></span>
															<select name="enationality" class="form-control">
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
															<select class="form-control" name="estate" id="state" onChange="load_lga();">
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
															<select class="form-control" name="elga" id="lga">
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
															<select name="egender" class="form-control">
																<option value="">Select</option>
																<option value="M" <?php echo (escape(ucwords($row['gender'])) == "M")? 'selected' : ''; ?>>Male</option>
																<option value="F" <?php echo (escape(ucwords($row['gender'])) == "F")? 'selected' : ''; ?>>Female</option>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Marital Status<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-link"></i></span>
															<select name="em_status" class="form-control">
																<option value="">Select</option>
																<option value="single" <?php echo (escape($row['marital_stat']) == "single")? 'selected' : ''; ?>>Single</option>
																<option value="married" <?php echo (escape($row['marital_stat']) == "married")? 'selected' : ''; ?>>Married</option>
																<option value="divorced" <?php echo (escape($row['marital_stat']) == "divorced")? 'selected' : ''; ?>>Divorced</option>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Religion<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-exchange"></i></span>
															<input type="text" value="<?php echo escape(ucfirst($row['religion'])); ?>" class="form-control" name="ereligion" placeholder="Religion" />
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-3">
												<div class="img-circle passport_holder">
													<img src="<?php echo $photo; ?>" id="imageView" class="img-circle" alt="Passport Photo" />
													<div class="col-sm-12">
														<input type="file" name="epassport" id="passport" placeholder="Passport" onchange="readURL(this);" accept="image/gif, image/jpeg, image/png" style="visibility: hidden; height: 0px;" />
														<label class="btn btn-photo text-center photo_label edit" for="passport"><span><i class="fa fa-cloud-upload"></i> Change Passport</span></label>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div id="menu1" class="tab-pane fade">
										<h4><i class="fa fa-user-circle"></i> Employment Information</h4><br/>
										<div class="row">	
											<div class="col-md-9">
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Type of Staff<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-text-width"></i></span>
															<select name="estaff_type" class="form-control">
																<option value="">Select</option>
																<option value="teaching" <?php echo (escape($row['type']) == "teaching")? 'selected' : ''; ?>>Teaching</option>
																<option value="non-teaching" <?php echo (escape($row['type']) == "non-teaching")? 'selected' : ''; ?>>Non-teaching</option>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Designation<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-user-circle"></i></span>
															<select name="edesig" class="form-control">
																<option value="">Select</option>
																<option value="teacher" <?php echo (escape($row['desig']) == "teacher")? 'selected' : ''; ?>>Teacher</option>
																<option value="accountant" <?php echo (escape($row['desig']) == "accountant")? 'selected' : ''; ?>>Accountant</option>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Date of Appointment<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
															<input type="date" value="<?php echo escape(ucwords($row['ap_date'])); ?>" class="form-control" name="eap_date" placeholder="dd/mm/yyyy" />
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Highest Qualification<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-book"></i></span>
															<input type="text" value="<?php echo escape(ucwords($row['highest_qual'])); ?>" class="form-control" name="equal" placeholder="Highest Qualification" />
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Years of Experience (Yrs)<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-angle-double-right"></i>&nbsp;</span>
															<input type="number" value="<?php echo escape(strtoupper($row['years_of_exp'])); ?>" min="0" value="0" maxlength="2" class="form-control" name="eexp" placeholder="Years of Experience" />
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Previous Organization</label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-angle-double-left"></i>&nbsp;</span>
															<input type="text" value="<?php echo escape(ucwords($row['prev_org'])); ?>" class="form-control" name="eprev_org" placeholder="Previous place of work" />
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									
									<div id="menu2" class="tab-pane fade">
										<h4><i class="fa fa-map-marker"></i> Contact Information</h4>
										<div class="row">	
											<div class="col-md-9">
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Mobile<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-phone"></i></span>
															<input type="number" min="0" maxlength="11" value="<?php echo num_only($row['mobile']); ?>" class="form-control" name="emobile" placeholder="Mobile Number" />
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Email Address<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
															<input type="email" value="<?php echo escape($row['email']); ?>" class="form-control" name="eemail" placeholder="staffid@school.edu.ng" />
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Home Address<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon">&nbsp;<i class="fa fa-map-marker"></i>&nbsp;</span>
															<textarea name="eaddress" class="form-control" placeholder="permanent home address"><?php echo escape($row['address']); ?></textarea>
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
									<button type="submit" class="btn btn-custom pull-right" name="submit">Update staff detail</button>
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