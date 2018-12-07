<?php 
	
	require_once "helpers/init.php";
	
	$message = "";
	$clear = "";
	$session = "";
	$term = "";
	$teacher_id = "SCH-001";
	
	//Get the number of days for the current month
	$d = cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));
	$mnth_att = "";
	
	$days = "";
	for($i = 1; $i <= $d; $i++) {
		$days .= "<th>".$i."</th>";
	}
	
	//Get current session
	$get_session = $db->query("SELECT * FROM academic_year WHERE active=1 ORDER BY id DESC");
	$rw = $get_session->fetch_assoc();
	$session 	= escape($rw['session_year']);
	$term 		= escape(strtolower($rw['term']));
	
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
			
			$cl_students = "<table class='table'>
							<thead>
									<tr>
										<th>#</th>
										<th>
											ADM NO.
											<input type='hidden' name='at_session' value='".$session."' />
											<input type='hidden' name='at_term' value='".$term."' />
										</th>
										<th>STUDENTS</th>
										<th>STATUS</th>
									</tr>
								</thead>
								<tbody>";
								
			$mnth_att .= "<table class='table table-bordered att_tb' id='att_table'>
						 <thead>
							<tr>
								<th>#</th>
								<th style='width: 30%'>STUDENTS</th>
								".$days."
								<th>Total</th>
							</tr>
						 </thead>
						 <tbody>";
						 
			$x = 1;
			while($rs = $get_std->fetch_assoc()) {
				$class_init = escape(strtoupper($rs['level']));
				$cl_students .= "<tr>
									<td>".$x."</td>
									<td>
										".escape(strtoupper($rs['admission_no']))."
										<input type='hidden' name='admin_no_arr[]' value='".escape(strtoupper($rs['admission_no']))."' />
									</td>
									<td>".escape(strtoupper($rs['lname'])).", ".escape(ucwords($rs['mname']))." ".escape(ucwords($rs['fname']))."</td>
									<td><div class='radio radio-success radio-inline'>
											<input type='radio' name='_".escape(strtoupper($rs['admission_no']))."' id='_".num_only($rs['stu_id'])."' class='' value='1' checked />
											<label for='' class=''>Present </label>
										</div>
										<div class='radio radio-danger radio-inline'>
											<input type='radio' name='_".escape(strtoupper($rs['admission_no']))."' id='_".num_only($rs['stu_id'])."'  class='' value='' />
											<label for='' class=''>Absent </label>
										</div>
									</td>
								</tr>";
								
				
				///////////////////////////////////////////////////
				////Attendance Calendar By current month////////////
				$mnth_att .= "<tr>
								<td>".$x."</td>
								<td class='name_td'><b>".escape(ucfirst($rs['lname']))." ".escape(ucwords($rs['mname']))." ".escape(ucwords($rs['fname']))."</b></td>";
				
				$p = 0;
				for($i = 1; $i <= $d; $i++) {
					$at_date = date('Y')."-".date('m')."-".$i;
					if($i <= date('d')) {
						$find = $db->query("SELECT * FROM stu_attendance WHERE admission_no='".escape(strtolower($rs['admission_no']))."' AND session_year='".$session."' AND term='".$term."' AND att_date='".$at_date."'") or die($db->error);
						if($find->num_rows) {
							$mnth_att .= "<td class='text-center' style='background: linear-gradient(90deg, #5cb85c, green); color: #efefef'><b>P</b></td>";
							$p++;
						}else{
							$mnth_att .= "<td class='text-center' style='color: red'><b>A</b></td>";
						}
					}else{
						$mnth_att .= "<td style='background: #2D3945'></td>";
					}
				}
				$mnth_att .= "<td class='text-center text-primary'><b>".$p."</b></td></tr>";
				
				$x++;
			}
			$cl_students .= "</tbody></table>";
			$mnth_att .= "</tbody></table>";
			$class_init = $class_init.alpha_only(strtoupper($row['class_level']));
		}else{
			$cl_students = "";
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
<link rel="stylesheet" href="css/tableexport.min.css" />
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
			<a href="#">Home</a> <i class="fa fa-angle-right"></i> <a href="#">Student Attendance</a>
		</div>
		<div class="content-wrapper shrink">
			<h3>Student Attendance</h3>
			<div class="content-body shrink">
				<div class="row">
					<div class="col-sm-12">
						<ul class="nav nav-tabs">
							<li class="active"><a data-toggle="tab" href="#menu1"><i class="fa fa-user-circle"></i> Student Attendance</a></li>
						</ul>
						<div class="custom bg_all">
							
							<div class="tab-content">
								<div id="menu1" class="tab-pane fade  in active">
									<h4><i class="fa fa-book text-custom"></i><?php echo ($class_init != "")? " Attendance for ".$class_init."" : "No class found"; ?></h4><br/>
									<div class="row">
										<div class="col-sm-12">
											<?php if($cl_students != "") :?>
											<form class="form-horizontal" id="stu_att_form" method="post" action="" onSubmit="students_att(); return false">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
															<label class="control-label col-sm-3" for="session">Select Date</label>
															<div class="col-sm-9">
																<input type="date" class="form-control" name="at_date" placeholder="mm/dd/yyyy" value="<?php echo date('Y-m-d');?>" />
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div id="atloader"></div>
													</div>
												</div>
												<div>
													<?php echo $cl_students; ?>
												</div>
												<div class="form-group">
													<div class="col-sm-12 text-center">
														<button type="submit" class="btn btn-custom" name="submit_sub"><i class="fa fa-hand-o-right text-custom"></i> Submit attendance</button>
													</div>
												</div>
											</form>
											<?php else: ?>
											<?php echo messageFormat("danger customized", "<i class='fa fa-warning'></i> No record found!!!. Please try again"); ?>
											<?php endif; ?>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<h3 class=""><span class="border_bott">Overall Students </span></h3>
						<form method="post" action="" id="att_form" onSubmit="fetch_att(); return false" >
							<div class="row">
								<div class="col-sm-4">
									<div class="form-group">
										<label class="control-label col-sm-4 pad_label" for="session">Select Date</label>
										<div class="col-sm-8">
											<input type="date" class="form-control" name="fe_date" placeholder="mm/dd/yyyy" value="<?php echo date('Y-m-d');?>" />
											<input type="hidden" name="fe_staff" value="<?php echo $teacher_id; ?>" >
										</div>
									</div>
								</div>
								<div class="col-sm-1">
									<div class="form-group">
										<button type="submit" class="btn btn-custom"><i class="fa fa-hand-o-right text-custom"></i> Fetch</button>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group pad_label"><div id="feloader"></div></div>
								</div>
							</div>
						</form>
						
						<div id="att_div">
							<h5><b>Attendance of <span class='text-success'><?php echo date('M').", ".date('Y');?></span> for <?php echo $class_init; ?></b></h5>
							<div class="bg_all table-responsive">
								<?php echo $mnth_att; ?>
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

<!--Export to Excel javascript files---------------->
<script src="js/xlsx.core.min.js"></script>
<script src="js/Blob.js"></script>
<script src="js/FileSaver.min.js"></script>
<script src="js/tableexport.min.js"></script>

<!---/--------- JQuery Files ---------------------->

<script>

	var BootstrapTable = document.getElementById('att_table');
		new TableExport(BootstrapTable, {
		bootstrap: true
	});
	
	function check() {
		window.scrollTo(0,0);
	}
	
</script>

</html>