<?php 
	
	require_once "helpers/init.php";
	
	///////////////////////////////////////////////
	////////Fetch subject teachers/////////////////
	$result = "";
	$get_ass_cl = $db->query("SELECT a.staff_id, a.section, a.class_level, a.assign_id, s.fname, s.mname, s.lname FROM assigned_classes AS a INNER JOIN staff AS s ON a.staff_id=s.staff_id") or die($db->error);
	if($get_ass_cl->num_rows) {
		$result = "<table class='table table-bordered table-striped' id='ass_sub_tb'>
					<thead>
						<tr>
							<th>#</th>
							<th>Staff ID</th>
							<th>Name</th>
							<th>Class assigned</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>";
		$x = 1;
		while($row = $get_ass_cl->fetch_assoc()) {
			$qry = $db->query("SELECT level FROM class_level WHERE level_id='".num_only($row['class_level'])."'") or die($db->error);
			$rc = $qry->fetch_assoc();
			
			$result .= "<tr>
							<td>".$x."</td>
							<td>".escape(strtoupper($row['staff_id']))."</td>
							<td>".escape(strtoupper($row['lname'])).", ".escape(ucwords($row['mname']))." ".escape(ucwords($row['fname']))."</td>
							<td>".escape(strtoupper($rc['level']))."".alpha_only(ucfirst($row['class_level']))."</td>
							<td>
								<a href='#' onclick=\"edit_cl_ass_modal('".escape(strtoupper($row['staff_id']))."', '".num_only($row['section'])."'); return false\" class='btn btn-custom'><i class='fa fa-pencil'></i></a>
								<a href='#' onclick=\"remove_cl_assigned('".escape(strtoupper($row['staff_id']))."', '".num_only($row['assign_id'])."'); return false\" class='btn btn-danger'><i class='fa fa-trash'></i></a>
							</td>
						</tr>";
			
			$x++;
		}
		$result .= "</tbody></table>";
	}else{
		$result = "<br/>".messageFormat("danger customized", "<i class='fa fa-warning'></i> You have not assigned any classes yet. Please click on the 'Assign Class Teacher' tab to assign a subject to a teacher.");
	}
	
	$message = "";
	$clear = "";
	
	///////////////////////////////////////////////
	////////Fetch subject teachers/////////////////
	$result_sub = "";
	$get_ass_sub = $db->query("SELECT DISTINCT a.staff_id, s.fname, s.mname, s.lname FROM assigned_subjects AS a INNER JOIN staff AS s ON a.staff_id=s.staff_id") or die($db->error);
	if($get_ass_sub->num_rows) {
		$result_sub = "<table class='table table-bordered table-striped' id='ass_sub_tb'>
						<thead>
							<tr>
								<th>#</th>
								<th>Staff ID</th>
								<th>Name</th>
								<th>Subjects</th>
								<th>Classes taken</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>";
		$x = 1;
		while($row = $get_ass_sub->fetch_assoc()) {
			$select = $db->query("SELECT DISTINCT subject, subject_name FROM assigned_subjects AS a INNER JOIN subject_bank AS s ON a.subject=s.subject_id WHERE staff_id='".escape($row['staff_id'])."'");
			$span = $select->num_rows;
			
			$result_sub .= "<tr>
							<td rowspan='".$span."'>".$x."</td>
							<td rowspan='".$span."'>".escape(strtoupper($row['staff_id']))."</td>
							<td rowspan='".$span."'>".escape(strtoupper($row['lname'])).", ".escape(ucwords($row['mname']))." ".escape(ucwords($row['fname']))."</td>";
			
			$y = 1;
			while($rs = $select->fetch_assoc()) {
				$get = $db->query("SELECT * FROM assigned_subjects WHERE staff_id='".escape($row['staff_id'])."' AND subject='".num_only($rs['subject'])."'") or die($db->error);
				
				$classes = "";
				$section = "";
				while($rw = $get->fetch_assoc()) {
					$qry = $db->query("SELECT level FROM class_level WHERE level_id='".num_only($rw['class_level'])."'") or die($db->error);
					$rc = $qry->fetch_assoc();
					$classes .= escape(strtoupper($rc['level']))."".alpha_only(ucfirst($rw['class_level'])).", ";
					$section = num_only($rw['section']);
				}
				
				if($y == 1) {
					$result_sub .= "<td>".escape(ucwords($rs['subject_name']))."</td>
									<td>".rtrim($classes, ', ')."</td>
									<td>
										<a href='#' onclick=\"edit_sub_ass_modal('".escape(strtoupper($row['staff_id']))."', '".num_only($rs['subject'])."', '".$section."'); return false\" class='btn btn-custom'><i class='fa fa-pencil'></i></a>
										<a href='#' onclick=\"remove_sub_assigned('".escape(strtoupper($row['staff_id']))."', '".num_only($rs['subject'])."'); return false\" class='btn btn-danger'><i class='fa fa-trash'></i></a>
									</td>
									</tr>";
				}else{
					$result_sub .= "<tr>
									<td>".escape(ucwords($rs['subject_name']))."</td>
									<td>".rtrim($classes, ', ')."</td>
									<td>
										<a href='#' onclick=\"edit_sub_ass_modal('".escape(strtoupper($row['staff_id']))."', '".num_only($rs['subject'])."', '".$section."'); return false\"  class='btn btn-custom'><i class='fa fa-pencil'></i></a>
										<a href='#' onclick=\"remove_sub_assigned('".escape(strtoupper($row['staff_id']))."', '".num_only($rs['subject'])."'); return false\" class='btn btn-danger'><i class='fa fa-trash'></i></a>
									</td>
									</tr>";
				}
				$y++;
			}
			$x++;
		}
		$result_sub .= "</tbody></table>";
	}else{
		$result_sub = "<br/>".messageFormat("danger customized", "<i class='fa fa-warning'></i> You have not assigned any subjects yet. Please click on the 'Assign Subject Teacher' tab to assign a subject to a teacher.");
	}
	
	
	/////////////////////////////////////////////////
	///////////Fetch School Sections/////////////////
	$sec_option = "<option value=''>Select</option>";
	$get_section = $db->query("SELECT * FROM section");
	if($get_section->num_rows) {
		while($row = $get_section->fetch_assoc()) {
			$sec_option .= "<option value='".num_only($row['section_id'])."'>".escape(strtoupper($row['section_name']))."</option>";
		}
	}
	
	/////////////////////////////////////////////////
	///////////Fetch School Teachers/////////////////
	$tch_option = "<option value=''>Select</option>";
	$get_tch = $db->query("SELECT * FROM staff WHERE desig='teacher'");
	if($get_tch->num_rows) {
		while($row = $get_tch->fetch_assoc()) {
			$tch_option .= "<option value='".escape($row['staff_id'])."'>".escape(strtoupper($row['lname'])).", ".escape(ucwords($row['mname']))." ".escape(ucwords($row['fname']))."</option>";
		}
	}
	
	////////////////////////////////////////////////
	/////////////Fetch Subject Options//////////////
	$sub_option = "<option value=''>Select</option>";
	$get_subjects = $db->query("SELECT * FROM subject_bank ORDER BY subject_name");
	if($get_subjects->num_rows) {
		while($row = $get_subjects->fetch_assoc()) {
			$sub_option .= "<option value='".num_only($row['subject_id'])."'>".escape(ucwords($row['subject_name']))."</option>";
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
			<a href="#">Home</a> <i class="fa fa-angle-right"></i> <a href="#">Assigned teachers</a>
		</div>
		<div class="content-wrapper shrink">
			<h3>Subject & Class Teacher Assignment</h3>
			<div class="content-body shrink">
				<div class="row">
					<div class="col-sm-12">
						<ul class="nav nav-tabs">
							<li class="active"><a data-toggle="tab" href="#menu1">Class Teachers</a></li>
							<li><a data-toggle="tab" href="#menu2">Subject Teachers</a></li>
							<li><a data-toggle="tab" href="#home">+ Assign Subject Teacher</a></li>
							<li><a data-toggle="tab" href="#out">+ Assign Class Teacher</a></li>
						</ul>
						<div class="custom bg_all">
							
							<div class="tab-content">
								<div id="menu1" class="tab-pane fade  in active">
									<h4><i class="fa fa-user-circle"></i> Class Teachers</h4>
									<div id="ass_cl_div">
										<?php echo $result; ?>
									</div>
								</div>
								
								<div id="menu2" class="tab-pane fade">
									<h4><i class="fa fa-user-circle"></i> Subject Teachers</h4>
									<div id="ass_sub_div">
										<?php echo $result_sub; ?>
									</div>
								</div>
								
								<div id="home" class="tab-pane fade">
									<div class="row">
										
										<div class="col-md-9">
											<h4><i class="fa fa-user-circle"></i> Assign subject teacher</h4><br/>
											<?php echo $message; ?>
											<form class="form-horizontal" method="post" action="<?php escape($_SERVER['PHP_SELF']); ?>" onSubmit="assign_subject(); return false" id="ass_sub_form" >
												<div id="aloader"></div>
												<div class="form-group">
													<label class="control-label col-sm-3" for="tch_id">Teacher</label>
													<div class="col-sm-6">
														<select name="tch_id" class="form-control" id="tch_id">
															<?php echo $tch_option; ?>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3" for="subject">Subject</label>
													<div class="col-sm-6">
														<select name="ass_subject" id="subject" class="form-control">
															<?php echo $sub_option; ?>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3" for="section">Section</label>
													<div class="col-sm-6">
														<select name="ass_section" id="section_ass" onChange="get_level_two()" class="form-control">
															<?php echo $sec_option; ?>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3" for="input-1">Classes</label>
													<div class="col-sm-9" id="class_level_div">
														<b><div class='alert alert-success customized'><i class='fa fa-warning'></i> Classes will display here for selection when you have selected a section</div></b>
													</div>
												</div>
												<div class="form-group">
													<div class="col-sm-offset-3 col-sm-10">
														<button type="submit" class="btn btn-custom" name="submit">Approve assignment</button>
													</div>
												</div>
											</form>
										</div>
										
									</div>
								</div>
								
								<div id="out" class="tab-pane fade">
									<div class="row">
										
										<div class="col-md-9">
											<h4><i class="fa fa-user-circle"></i> Assign class teacher</h4><br/>
											<form class="form-horizontal" method="post" action="<?php escape($_SERVER['PHP_SELF']); ?>" onSubmit="assign_class(); return false" id="ass_class_form" >
												<div id="cloader"></div>
												<div class="form-group">
													<label class="control-label col-sm-3" for="ctch_id">Teacher</label>
													<div class="col-sm-6">
														<select name="ctch_id" class="form-control" id="ctch_id">
															<?php echo $tch_option; ?>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3" for="section_cl">Section</label>
													<div class="col-sm-6">
														<select name="cl_section" id="section_cl" onChange="get_level_two_cl()" class="form-control">
															<?php echo $sec_option; ?>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3" for="input-1">Classes</label>
													<div class="col-sm-9" id="class_level_div_cl">
														<b><div class='alert alert-success customized'><i class='fa fa-warning'></i> Classes will display here for selection when you have selected a section</div></b>
													</div>
												</div>
												<div class="form-group">
													<div class="col-sm-offset-3 col-sm-10">
														<button type="submit" class="btn btn-custom" name="submit">Approve assignment</button>
													</div>
												</div>
											</form>
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
</div>

<!--Edit Subject Assigned Modal -->
<div class="modal fade" id="subAssModal" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content" style="border-radius: 0px;">
			<div class="modal-body" style="padding-left: 30px; padding-right: 30px">
				<form class="modal_style form-horizontal" id="edit_sub_ass_form" method="post" action="parse.php" onSubmit="edit_sub_ass(); return false" enctype="multipart/form-data">
					<h4 class="text-center text-blue"><i class="fa fa-edit text-gold"></i> Edit Assigned Subjects</h4><hr/>
					<div id="esloader"></div>
					
					<div class="form-group">
						<label class="control-label col-sm-2" for="tch_id">Teacher</label>
						<div class="col-sm-7">
							<select name="etch_id" class="form-control" id="etch_id">
								<?php echo $tch_option; ?>
							</select>
							<input type="hidden" name="etch_id_hide" id="etch_id_hide">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="subject">Subject</label>
						<div class="col-sm-7">
							<select name="eass_subject" id="esubject" class="form-control">
								<?php echo $sub_option; ?>
							</select>
							<input type="hidden" name="eass_subject_hide" id="eass_subject_hide">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="section">Section</label>
						<div class="col-sm-7">
							<select name="eass_section" id="esection_ass" onChange="get_level_two_edit()" class="form-control">
								<?php echo $sec_option; ?>
							</select>
							<input type="hidden" name="eass_section_hide" id="eass_section_hide">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="input-1">Classes</label>
						<div class="col-sm-9" id="class_level_div_edit">
							<b><div class='alert alert-success customized text-center'><i class='fa fa-warning'></i> Classes will display here for selection when you have selected a section</div></b>
						</div>
					</div>
					<div class="form-group text-right">
						<div class="col-sm-offset-2 col-sm-7">
							<button type="submit" name="edit_ass_btn" class="btn btn-custom">Update assignment</button>
						</div>
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

<!--Edit Class Assigned Modal -->
<div class="modal fade" id="classAssModal" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content" style="border-radius: 0px;">
			<div class="modal-body" style="padding-left: 30px; padding-right: 30px">
				<form class="modal_style form-horizontal" id="edit_cl_ass_form" method="post" action="parse.php" onSubmit="edit_cl_ass(); return false" enctype="multipart/form-data">
					<h4 class="text-center text-blue"><i class="fa fa-edit text-gold"></i> Edit Assigned Class</h4><hr/>
					<div id="ecloader"></div>
					
					<div class="form-group">
						<label class="control-label col-sm-2" for="tch_id">Teacher</label>
						<div class="col-sm-7">
							<select name="ectch_id" class="form-control" id="ectch_id">
								<?php echo $tch_option; ?>
							</select>
							<input type="hidden" name="ectch_id_hide" id="ectch_id_hide">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="section">Section</label>
						<div class="col-sm-7">
							<select name="ecl_section" id="esection_cl" onChange="get_level_two_edit_cl()" class="form-control">
								<?php echo $sec_option; ?>
							</select>
							<input type="hidden" name="ecl_section_hide" id="ecl_section_hide">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2" for="input-1">Classes</label>
						<div class="col-sm-9" id="class_level_div_edit_cl">
							<b><div class='alert alert-success customized text-center'><i class='fa fa-warning'></i> Classes will display here for selection when you have selected a section</div></b>
						</div>
					</div>
					<div class="form-group text-right">
						<div class="col-sm-offset-2 col-sm-7">
							<button type="submit" name="edit_ass_btn_cl" class="btn btn-custom">Update assignment</button>
						</div>
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

	$("#ass_sub_table").DataTable();
	function check() {
		window.scrollTo(0,0);
	}
	
</script>

</html>