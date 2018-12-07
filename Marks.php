<?php 
	
	require_once "helpers/init.php";
	
	
	$message = "";
	$clear = "";
	$session = "";
	$term = "";
	
	//Get academic year/session
	$se_option = "<option value=''>Select</option>";
	$get_sessions = $db->query("SELECT DISTINCT session_year, active FROM academic_year ORDER BY id DESC");
	if($get_sessions->num_rows) {
		while($row = $get_sessions->fetch_assoc()) {
			if($row['active'] == 1) {
				$se_option .= "<option value='".escape($row['session_year'])."' selected>".escape(strtoupper($row['session_year']))."</option>";
				$session = escape($row['session_year']);
			}else{
				$se_option .= "<option value='".escape($row['session_year'])."'>".escape(strtoupper($row['session_year']))."</option>";
			}
		}
	}
	
	//Get Term using session
	$te_option = "<option value=''>Select</option>";
	$get_terms = $db->query("SELECT * FROM academic_year WHERE session_year='".$session."'");
	if($get_terms->num_rows) {
		while($row = $get_terms->fetch_assoc()) {
			if($row['active'] == 1) {
				$te_option .= "<option value='".escape($row['term'])."' selected>".escape(strtolower($row['term']))."</option>";
				$term = escape($row['term']);
			}else{
				$te_option .= "<option value='".escape($row['term'])."'>".escape(strtolower($row['term']))."</option>";
			}
		}
	}
	
	//Get subjects
	$sub_option = "<option value=''>Select</option>";
	$get_subs = $db->query("SELECT DISTINCT a.subject, s.subject_name FROM assigned_subjects AS a INNER JOIN subject_bank AS s ON a.subject=s.subject_id  WHERE staff_id='SCH-001'");
	if($get_subs->num_rows) {
		while($row = $get_subs->fetch_assoc()) {
			$sub_option .= "<option value='".num_only($row['subject'])."'>".escape(ucwords($row['subject_name']))."</option>";
		}
	}
	
	//Get Class Taught
	$cl_students = "";
	$class_init = "";
	$class_ass = "";
	$get_cl = $db->query("SELECT * FROM assigned_classes WHERE staff_id='SCH-001' LIMIT 1");
	if($get_cl->num_rows) {
		$row = $get_cl->fetch_assoc();
		$class_ass = escape(strtolower($row['class_level']));
		
		$get_std = $db->query("SELECT * FROM students AS stu INNER JOIN class_level AS c ON stu.class_level=c.level_id INNER JOIN section AS s ON c.section_id=s.section_id WHERE stu.section='".num_only($row['section'])."' AND stu.class_level='".num_only($row['class_level'])."' AND stu.class_name='".alpha_only(strtolower($row['class_level']))."' ORDER BY stu.lname, stu.fname, stu.mname") or die($db->error);
		if($get_std->num_rows) {
			
			$cl_students = "<div class='text-right' style='margin-bottom: 7px;'><button type='button' class='btn btn-custom' onClick=\"export_mark_options('excel_marks')\"><i class='fa fa-file-excel-o text-custom'></i> Export marks Excel</button> <button type='button' class='btn btn-danger' onClick=\"export_mark_options('pdf_marks')\"><i class='fa fa-file-pdf-o'></i> Export marks PDF</button> <button type='button' class='btn btn-primary' onClick=\"export_mark_options('pdf_reports')\"><i class='fa fa-file-pdf-o'></i> Export reports PDF</button></div>
							<table class='table table-bordered' id='student_tb'>
							<thead>
									<tr>
										<th>#</th>
										<th>ADM NO.</th>
										<th>Name</th>
										<th>Gender</th>
										<th>Position</th>
										<th>Marks</th>
									</tr>
								</thead>
								<tbody>";
			$x = 1;
			while($rs = $get_std->fetch_assoc()) {
				$class_init = escape(strtoupper($rs['level']));
				$cl_students .= "<tr>
									<td>".$x."</td>
									<td>".escape(strtoupper($rs['admission_no']))."</td>
									<td>".escape(strtoupper($rs['lname'])).", ".escape(ucwords($rs['mname']))." ".escape(ucwords($rs['fname']))."</td>
									<td>".escape(ucwords($rs['gender']))."</td>
									<td>".num_only("1")."</td>
									<td><a href='#' onClick=\"cl_mark_modal('".escape(strtoupper($rs['admission_no']))."', '".$session."', '".$term."', '".escape(strtoupper($rs['lname'])).", ".escape(ucwords($rs['mname']))." ".escape(ucwords($rs['fname']))."', '".escape(ucwords($rs['gender']))."');\" class='btn btn-success'>M</a></td>
								</tr>";
				$x++;
			}
			$cl_students .= "</tbody></table>";
			$class_init = $class_init.alpha_only(strtoupper($row['class_level']));
		}else{
			$cl_students = messageFormat("danger customized", "<i class='fa fa-warning'></i> No record found!!!. Please try again");
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
			<a href="#">Home</a> <i class="fa fa-angle-right"></i> <a href="#">Marks</a>
		</div>
		<div class="content-wrapper shrink">
			<h3>Marks</h3>
			<div class="content-body shrink">
				<div class="row">
					<div class="col-sm-12">
						<ul class="nav nav-tabs">
							<li class="active"><a data-toggle="tab" href="#menu1">+ Add Subject Marks</a></li>
							<li><a data-toggle="tab" href="#home">+ Add Class Marks</a></li>
						</ul>
						<div class="custom bg_all">
							
							<div class="tab-content">
								<div id="menu1" class="tab-pane fade  in active">
									<h4><i class="fa fa-book text-custom"></i> Subject Marks</h4><br/>
									<div class="row">
										<div class="col-sm-12">
											<form class="form-horizontal" id="sub_mark_query_form" method="post" action="" onSubmit="query_students_sub(); return false">
												<div id="amloader"></div>
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label col-sm-3" for="session">Select Session</label>
															<div class="col-sm-9">
																<select name="session_mark" id="session_mark" class="form-control" onChange="get_term()">
																	<?php echo $se_option; ?>
																</select>
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label col-sm-3" for="term">Select Term</label>
															<div class="col-sm-9">
																<select name="term_mark" id="term_mark" class="form-control">
																	<?php echo $te_option; ?>
																</select>
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-sm-6">
														<div class="form-group">
															<label class="control-label col-sm-3">Select Subject</label>
															<div class="col-sm-9">
																<select name="sub_mark" id="sub_mark" onChange="get_classes_sub()" class="form-control">
																	<?php echo $sub_option; ?>
																</select>
																<input type="hidden" value="SCH-001" name="staff_mark" id="staff_mark" />
															</div>
														</div>
													</div>
													<div class="col-sm-6">
														<div class="form-group">
															<label class="control-label col-sm-3">Select Class</label>
															<div class="col-sm-9">
																<select name="cl_mark" id="cl_mark" class="form-control">
																	<option value="">Select</option>
																</select>
															</div>
														</div>
													</div>
												</div>
												<div class="form-group">
													<div class="col-sm-7 text-right">
														<button type="submit" class="btn btn-custom" name="submit_sub"><i class="fa fa-hand-o-right text-custom"></i> Query Students</button>
													</div>
												</div>
											</form>
											<br/>
											<div id="marks_sub_div">
												<form method="post" action="" id="save_sub_marks_form" onSubmit="save_sub_marks(); return false">
													
												</form>
											</div>
										</div>
									</div>
								</div>
								<div id="home" class="tab-pane fade">
									<div class="row">
										<div class="col-md-11">
											<h4><i class="fa fa-building text-custom"></i> <?php echo $class_init; ?> Class Marks: <small>Allocate and Print</small></h4>
											<div id="marks_cl_div">
												<?php echo $cl_students; ?>
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
</div>

<!--Add student marks for class teacher -->
<div class="modal fade" id="classMarks" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content" style="border-radius: 0px;">
			<div class="modal-body">
				<h3 class="text-center">Enter Marks</h3><br/>
				<form method="post" action="" id="save_cl_marks_form" onSubmit="save_cl_marks(); return false">
					<div id="acloader"></div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label col-sm-4" for="session_mark_two">Select Session</label>
								<div class="col-sm-8">
									<select name="sm_session" id="session_mark_two" class="form-control" onChange="get_term_two()">
										<?php echo $se_option; ?>
									</select>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label class="control-label col-sm-4" for="term">Select Term</label>
								<div class="col-sm-8">
									<select name="sm_term" id="term_mark_two" class="form-control">
										<?php echo $te_option; ?>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group text-right" style='margin-top: 7px; padding-right: 10px;'><button type="button" class="btn btn-custom" onClick="cl_mark_session()"><i class="fa fa-hand-o-right text-custom"></i> Query marks</button></div>
					
					<div id="cl_mark_holder_name"></div>
					<div id="cl_mark_holder">
						
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


<!--Export marks modal for excel or PDF -->
<div class="modal fade" id="exportMarks" role="dialog">
	<div class="modal-dialog modal-sm">
		<!-- Modal content-->
		<div class="modal-content" style="border-radius: 0px;">
			<div class="modal-body">
				<h3 class="text-center">Export Options</h3><br/>
				<form method="post" action="" id="export_cl_marks_form" onSubmit="export_cl_marks(); return false">
					<div id="mcloader"></div>
					<div class="form-group">
						<label for="session_mark_thr">Select Session</label>
						<select id="session_mark_thr" class="form-control" onChange="get_term_thr()">
							<?php echo $se_option; ?>
						</select>
						<input type="hidden" name="doc_type" id="doc_type" />
						<input type="hidden" name="class_ass" value="<?php echo $class_ass; ?>" />
					</div>
					<div class="form-group">
						<select id="term_mark_thr" class="form-control">
							<?php echo $te_option; ?>
						</select>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-custom"><i class="fa fa-cloud-download text-custom"></i> Download marks</button>
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
	
</script>

</html>