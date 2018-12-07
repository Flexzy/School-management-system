<?php 
	
	require_once "helpers/init.php";
	require_once "includes/scores.php";
	
	
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
	$sub_arr = array();
	$get_subs = $db->query("SELECT subject_id, subject_abbr FROM subject_bank ORDER BY subject_name");
	if($get_subs->num_rows) {
		while($row = $get_subs->fetch_assoc()) {
			$sub_arr[num_only($row['subject_id'])] = escape(strtoupper($row['subject_abbr']));
		}
	}
	
	if(count($sub_arr) > 0) {
		
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
				
				//Array to hold score
				$score_arr = array();
				
				//Generate subjects header
				$sub_th = "";
				foreach($sub_arr as $key => $value) {
					$sub_th .= "<th>".strtoupper($value)."</th>";
				}
				
				$cl_students = "<div class='text-right' style='margin-bottom: 7px;'><a href='export_broadsheet?type=excel' class='btn btn-custom'><i class='fa fa-file-excel-o text-custom'></i> Export to Excel</a> <a href='export_broadsheet?type=pdf' target='_blank' class='btn btn-danger'><i class='fa fa-file-pdf-o'></i> Export to PDF</a> <a href='export_broadsheet?type=empty' class='btn btn-default'><b><i class='fa fa-file-excel-o text-success'></i> Download empty broadsheet</b></a></div>
								<table class='table table-bordered' id='student_tb'>
								<thead>
										<tr>
											<th>#</th>
											<th>Name</th>
											".$sub_th."
											<th>Total</th>
											<th>Subjects</th>
											<th>Average</th>
											<th>Pos</th>
										</tr>
									</thead>
									<tbody>";
				$x = 1;
				$pos = "";
				$prev_score = "";
				while($rs = $get_std->fetch_assoc()) {
					$class_init = escape(strtoupper($rs['level']));
					$cl_students .= "<tr>
										<th>".$x."</th>
										<td>".escape(strtoupper($rs['lname'])).", ".escape(ucwords($rs['mname']))." ".escape(ucwords($rs['fname']))."</td>";
						
					$score_arr[escape($rs['admission_no'])] = 0;
					$c = 0;
					foreach($sub_arr as $key => $value) {
						$fetch = $db->query("SELECT * FROM marks WHERE admission_no='".strtolower(escape($rs['admission_no']))."' AND subject_id='".$key."' AND session='".$session."' AND term='".$term."' LIMIT 1");
						if($fetch->num_rows) {
							$c++;
							$rm = $fetch->fetch_assoc();
							$subject_score = (num_only($rm['ca_one']) + num_only($rm['ca_two']) + num_only($rm['exam']));
							$score_arr[escape($rs['admission_no'])] += $subject_score;
							$cl_students .= "<td class='text-center'>".$subject_score."</td>";
						}else{
							$cl_students .= "<td class='text-center'>-</td>";
						}
					}
					
					if($prev_score == "") {
						$prev_score = $score_arr[escape($rs['admission_no'])];
					}
					
					if($pos == "") {
						$pos = (array_search(escape($rs['admission_no']),array_keys($sc_score_arr)) + 1);
					}
					
					if($prev_score != $sc_score_arr[escape($rs['admission_no'])]) {
						$pos = (array_search(escape($rs['admission_no']),array_keys($sc_score_arr)) + 1);
						$prev_score = $score_arr[escape($rs['admission_no'])];
					}
					
					if($score_arr[escape($rs['admission_no'])] != 0 || $c != 0) {
						if(substr($pos, -1) == 1) {
							$pos = $pos."<sup>st</sup>";
						}elseif(substr($pos, -1) == 2) {
							$pos = $pos."<sup>nd</sup>";
						}elseif(substr($pos, -1) == 3) {
							$pos = $pos."<sup>rd</sup>";
						}else{
							$pos = $pos."<sup>th</sup>";
						}
					}
					
					$cl_students .=	"<td class='text-center'>".$score_arr[escape($rs['admission_no'])]."</td>
									 <td class='text-center'>".$c."</td>
									 <td class='text-center'>".$sc_score_arr[escape($rs['admission_no'])]."</td>
									 <td class='text-center'>".$pos."</td>
									</tr>";
					$x++;
				}
				$cl_students .= "</tbody></table>";
				$class_init = $class_init.alpha_only(strtoupper($row['class_level']));
			}else{
				$cl_students = messageFormat("danger customized", "<i class='fa fa-warning'></i> No record found!!!. Please try again");
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
			<a href="#">Home</a> <i class="fa fa-angle-right"></i> <a href="#">Broadsheet</a>
		</div>
		<div class="content-wrapper shrink">
			<h3>Broadsheet</h3>
			<div class="content-body shrink">
				<div class="row">
					<div class="col-sm-12">
						
						<form class="form-horizontal custom" method="post" action="" id="query_broadsheet_form" onSubmit="query_broadsheet(); return false">
							<div class="row">
								<div class="col-sm-5">
									<div class="form-group">
										<label class="control-label col-sm-4 txt-left" for="session_mark">Select session</label>
										<div class="col-sm-8">
											<select name="br_session" id="session_mark" class="form-control" onChange="get_term()">
												<?php echo $se_option; ?>
											</select>
										</div>
									</div>
								</div>
								<div class="col-sm-5">
									<div class="form-group">
										<label class="control-label col-sm-3 txt-left" for="term_mark">Select term</label>
										<div class="col-sm-8">
											<select name="br_term" id="term_mark" class="form-control" required>
												<?php echo $te_option; ?>
											</select>
										</div>
									</div>
								</div>
								<div class="col-sm-2">
									<div class="form-group">
										<button type="submit" name="query_sheet" class="btn btn-custom"><i class="fa fa-file-excel-o"></i> Query broadsheet</button>
									</div>
								</div>
							</div>
							
						</form>
						
						<div class="custom bg_all">
							<h4><i class="fa fa-file-excel-o text-custom"></i> Broadsheet for <?php echo $class_init; ?>: <small>view & download</small></h4>
							<div id="marks_br_div" class="table-responsive">
								<?php echo $cl_students; ?>
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

	//$("#student_tb").DataTable();
	function check() {
		window.scrollTo(0,0);
	}
	
</script>

</html>