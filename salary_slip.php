<?php 
	
	require_once "helpers/init.php";
	
	
	////////////////////////////////////////////////////////////////////
	/////////////////Fetch all Staff Records////////////////////////////
	$staff = "<option value=''>Select</option>";
	$get_staff = $db->query("SELECT staff_id, lname, mname, fname FROM staff ORDER BY lname, fname, mname") or die($db->error);
	if($get_staff->num_rows) {
		while($rs = $get_staff->fetch_assoc()) {
			$staff .= "<option value='".escape(strtolower($rs['staff_id']))."'>".escape(strtoupper($rs['lname'])).", ".escape(ucwords($rs['mname']))." ".escape(ucwords($rs['fname']))."</option>";
		}
	}
	
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		print_r($_POST);
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
			<a href="#">Home</a> <i class="fa fa-angle-right"></i> <a href="#">Salary Slip</a>
		</div>
		<div class="content-wrapper shrink">
			<h3 class="text-center"><b>Salary Slip</b></h3>
			<div class="content-body shrink">
				<div class="row">
					<div class="col-sm-12">
						
						<div class="row">
							<div class="col-sm-6">
								<h4><i class="fa fa-tags"></i> <b>Generate salary slip</b></h4>
							</div>
						</div>
						
						<form class="form-horizontal custom" method="post" action="" id="salary_slip_form" onSubmit="gen_salary_slip(); return false">
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label class="control-label col-sm-3 txt-left" for="ed_staff">Select Staff</label>
										<div class="col-sm-9">
											<select name="ed_staff_salary_submit" id="ed_staff_salary" class="form-control" onChange="fetch_staff_salary()">
												<?php echo $staff; ?>
											</select>
										</div>
									</div>
								</div>
								<div class="col-sm-offset-1 col-sm-3">
									<div class="form-group">
										<label class="control-label col-sm-3 txt-left" for="ed_staff">Month</label>
										<div class="col-sm-9">
											<select name="month" id="salary_month" class="form-control" required>
												<?php echo gen_month(date('m')); ?>
											</select>
										</div>
									</div>
								</div>
								<div class="col-sm-2">
									<div class="form-group">
										<label class="control-label col-sm-3 txt-left" for="ed_staff">Year</label>
										<div class="col-sm-9">
											<select name="year" class="form-control" id="salary_year" required>
												<?php echo gen_date(date('Y')); ?>
											</select>
										</div>
									</div>
								</div>
							</div>
							
							<div id="fin_holder">
								<div class="alert alert-success customized">
									<b><i class="fa fa-angle-double-right"></i> Select a staff, payment month and payment year to generate his/her Salary payment slip</b>
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

	function check() {
		window.scrollTo(0,0);
	}
	
	$(document).ready(function() {
		check_ed_container();
	});
	
</script>

</html>