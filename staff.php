<?php 
	
	require_once "helpers/init.php";
	
	/////////////////////////////////
	/////Gen Staff ID///////////////
	$num_staff = 0;
	$staff = $db->query("SELECT id FROM staff ORDER BY staff_id DESC LIMIT 1") or die($db->error);
	$row = $staff->fetch_assoc();
	$num_staff = $row['id'] + 1;
	if($num_staff < 10) {
		$num_staff = "SCH-00".$num_staff;
	}elseif($num_staff < 100) {
		$num_staff = "SCH-0".$num_staff;
	}
	
	$result = "";
	$get_staff = $db->query("SELECT * FROM staff ORDER BY id DESC");
	if($get_staff->num_rows) {
		$result .= "<table class='table table-bordered' id='staff_tb'>
					<thead>
						<tr>
							<th>#</th>
							<th>Staff ID</th>
							<th>Type</th>
							<th>Desig</th>
							<th>Name</th>
							<th>sex</th>
							<th>State</th>
							<th>Lga</th>
							<th>Qualification</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>";
		$x = 1;
		while($row = $get_staff->fetch_assoc()) {
			$result .= " <tr>
							<td>".$x."</td>
							<td>".escape(strtoupper($row['staff_id']))."</td>
							<td>".escape(ucwords($row['type']))."</td>
							<td>".escape(ucwords($row['desig']))."</td>
							<td>".escape(strtoupper($row['lname'])).", ".escape(ucwords($row['mname']))." ".escape(ucwords($row['fname']))."</td>
							<td>".escape(ucwords($row['gender']))."</td>
							<td>".escape(ucwords($row['state']))."</td>
							<td>".escape(ucwords($row['lga']))."</td>
							<td>".escape(strtoupper($row['highest_qual']))."</td>
							<td>
								<a href='edit_staff.php?id=".escape($row['staff_id'])."' class='btn btn-custom'><i class='fa fa-pencil'></i></a>
								<a href='#' onClick=\"remove_staff('".escape($row['staff_id'])."', '".num_only($row['id'])."'); return false\" class='btn btn-danger'><i class='fa fa-trash-o'></i></a>
							</td>
						</tr>";
			$x++;
		}
		$result .= "</tbody></table>";
	}else{
		$result = "<br/>".messageFormat("danger customized", "<i class='fa fa-warning'></i> You have not recorded any Staff/Employee yet. Please click on the 'add staff' tab to add a new staff.");
	}
	
	$message = "";
	$clear = "";
	
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
			<a href="#">Home</a> <i class="fa fa-angle-right"></i> <a href="#">Staffs</a>
		</div>
		<div class="content-wrapper shrink">
			<h3>Staff & Employees</h3>
			<div class="content-body shrink">
				<div class="row">
					<div class="col-sm-12">
						<ul class="nav nav-tabs">
								<li class="active"><a data-toggle="tab" href="#menu1">All Staffs</a></li>
								<li><a data-toggle="tab" href="#home">+ Add Staff</a></li>
							</ul>
						<div class="custom bg_all">
							
							<div class="tab-content">
								<div id="home" class="tab-pane fade">
									<div class="row">
										<div class="col-md-9">
											<h4><i class="fa fa-user-plus"></i> New entry</h4><br/>
											<div class="col-sm-offset-3 col-sm-10"><?php echo $message; ?></div>
											<form class="form-horizontal" method="post" action="<?php escape($_SERVER['PHP_SELF']); ?>" enctype="multipart/form-data" id="add_staff_form" onSubmit="sub_staff(); return false" >
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Staff ID<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-barcode"></i></span>
															<input type="text" class="form-control" value="<?php echo $num_staff; ?>" name="staff_id" id="staff_id" placeholder="Staff ID" readonly />
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Type of Staff<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-text-width"></i></span>
															<select name="staff_type" class="form-control">
																<option value="">Select</option>
																<option value="teaching" <?php echo (exxcape('staff_type', $clear) == "teaching")? 'selected' : ''; ?>>Teaching</option>
																<option value="non-teaching" <?php echo (exxcape('staff_type', $clear) == "non-teaching")? 'selected' : ''; ?>>Non-teaching</option>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Designation<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-user-circle"></i></span>
															<select name="desig" class="form-control">
																<option value="">Select</option>
																<option value="teacher" <?php echo (exxcape('desig', $clear) == "teacher")? 'selected' : ''; ?>>Teacher</option>
																<option value="accountant" <?php echo (exxcape('desig', $clear) == "accountant")? 'selected' : ''; ?>>Accountant</option>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Date of Appointment<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
															<input type="date" value="<?php exxcape('ap_date', $clear); ?>" class="form-control" name="ap_date" placeholder="dd/mm/yyyy" />
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Last Name<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-user-circle"></i></span>
															<input type="text" value="<?php exxcape('lname', $clear); ?>" class="form-control" name="lname" placeholder="Last Name" />
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Middle Name</label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-user-circle"></i></span>
															<input type="text" value="<?php exxcape('mname', $clear); ?>" class="form-control" name="mname" placeholder="Middle name" />
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">First Name<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-user-circle"></i></span>
															<input type="text" value="<?php exxcape('fname', $clear); ?>" class="form-control" name="fname" placeholder="First name" />
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Date of Birth<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
															<input type="date" value="<?php exxcape('dob', $clear); ?>" class="form-control" name="dob" placeholder="dd/mm/yyyy" />
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Nationality<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-bookmark"></i></span>
															<select name="nationality" class="form-control">
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
															<select class="form-control" name="state" id="state" onChange="load_lga();">
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
															<select class="form-control" name="lga" id="lga">
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
															<select name="gender" class="form-control">
																<option value="">Select</option>
																<option value="M">Male</option>
																<option value="F">Female</option>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Marital Status<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-link"></i></span>
															<select name="m_status" class="form-control">
																<option value="">Select</option>
																<option value="single">Single</option>
																<option value="married">Married</option>
																<option value="divorced">Divorced</option>
															</select>
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Religion<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-exchange"></i></span>
															<input type="text" value="<?php exxcape('religion', $clear); ?>" class="form-control" name="religion" placeholder="Religion" />
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Mobile<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-phone"></i></span>
															<input type="number" min="0" maxlength="11" value="<?php exxcape('mobile', $clear); ?>" class="form-control" name="mobile" placeholder="Mobile Number" />
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Email Address<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
															<input type="email" value="<?php exxcape('email', $clear); ?>" class="form-control" name="email" placeholder="staffid@school.edu.ng" />
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Home Address<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon">&nbsp;<i class="fa fa-map-marker"></i>&nbsp;</span>
															<textarea name="address" class="form-control" placeholder="permanent home address"><?php exxcape('address', $clear); ?></textarea>
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Highest Qualification<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-book"></i></span>
															<input type="text" value="<?php exxcape('qual', $clear); ?>" class="form-control" name="qual" placeholder="Highest Qualification" />
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Years of Experience (Yrs)<span class='text-red'>*</span></label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-angle-double-right"></i>&nbsp;</span>
															<input type="number" value="<?php exxcape('exp', $clear); ?>" min="0" value="0" maxlength="2" class="form-control" name="exp" placeholder="Years of Experience" />
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4" for="input-1">Previous Organization</label>
													<div class="col-sm-8">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-angle-double-left"></i>&nbsp;</span>
															<input type="text" value="<?php exxcape('prev_org', $clear); ?>" class="form-control" name="prev_org" placeholder="Previous place of work" />
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-4">Profile Photo</label>
													<div class="col-sm-8">
														<input type="file" name="passport" id="passport" placeholder="Passport" onchange="readURL(this);" accept="image/gif, image/jpeg, image/png" style="visibility: hidden; height: 0px;" />
														<label class="btn btn-photo text-center photo_label" for="passport"><span><i class="fa fa-cloud-upload"></i> Upload Photo</span></label>
														<div class="img-circle passport_holder">
															<img src="img/img.svg" id="imageView" class="img-circle" alt="Passport Photo" />
														</div>
													</div>
												</div>
												<div class="form-group">
													<div class="col-sm-offset-4 col-sm-8">
														<button type="submit" class="btn btn-custom" name="submit">Save Staff</button>
													</div>
												</div>
											</form>
										</div>
									</div>
								</div>
								<div id="menu1" class="tab-pane fade  in active">
									<h4>My Employees</h4>
									<div id="staff_div">
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
	
	load_staff();
	
</script>

</html>