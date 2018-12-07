<?php 
	
	require_once "helpers/init.php";
	
	$result = "";
	
	$get_receipt = $db->query("SELECT DISTINCT receipt_no FROM student_access");
	if($get_receipt->num_rows) {
		$result .= "<table class='table table-bordered' id='student_tb'>
			<thead>
				<tr>
					<th>#</th>
					<th>Receipt No.</th>
					<th>Name</th>
					<th>Class</th>
					<th>Payment Date</th>
					<th>Amount</th>
					<th>Payment Mode</th>
					<th>Print</th>
				</tr>
			</thead>
			<tbody>";
		
		$x = 1;
		while($row = $get_receipt->fetch_assoc()) {
			
			$get_fees = $db->query("SELECT * FROM student_access AS a INNER JOIN students AS s ON a.admission_no=s.admission_no INNER JOIN fee_head AS f ON a.fee_id=f.fee_id INNER JOIN class_level AS c ON s.class_level=c.level_id INNER JOIN section AS sec ON c.section_id=sec.section_id WHERE a.receipt_no='".$row['receipt_no']."' ORDER BY access_id DESC") or die($db->error);
			$row_fe = $get_fees->fetch_all();
			//echo "<pre>";
			//die(print_r($row_fe));
			
			$result .= "<tr>
							<td>".$x."</td>
							<td>".num_only($row['receipt_no'])."</td>
							<td>".escape(strtoupper($row_fe[0][14])).", ".escape(ucwords($row_fe[0][15]))." ".escape(ucwords($row_fe[0][16]))."</td>
							<td>".escape(strtoupper($row_fe[0][42]))."".escape(strtoupper($row_fe[0][24]))."</td>
							<td>".escape($row_fe[0][9])."</td>";
			
			$total = 0;
			$x = 0;
			while($x < count($row_fe)) {
				$total += $row_fe[$x][39];
				$x++;
			}
			
			$result .= "<td>".number_format(num_only($total))."</td>
						<td>".escape($row_fe[0][6])."</td>
						<td><a href='receipt.php?student=".escape($row_fe[0][1])."&no=".num_only($row['receipt_no'])."' class='btn btn-custom' target='_blank'><i class='fa fa-print'></i></a></td>";
			$x++;
		}
		$result .= "</tbody></table>";
		
	}else{
		$result = "<br/>".messageFormat("danger customized", "<i class='fa fa-warning'></i> You have not any fees yet. Please click on the 'collect fees' tab to collect fees.");
	}
	
	$query = $db->query("SELECT * FROM student_access AS a INNER JOIN students AS s ON a.admission_no=s.admission_no INNER JOIN fee_head AS f ON a.fee_id=f.fee_id INNER JOIN class_level AS c ON s.class_level=c.level_id INNER JOIN section AS sec ON c.section_id=sec.section_id WHERE a.receipt_no='0028'");
	$mr = $query->fetch_assoc();
	
	$message = "";
	$clear = "";
	
	
	//Get academic year/session
	$se_option = "<option value=''>Select</option>";
	$get_sessions = $db->query("SELECT * FROM academic_year ORDER BY id DESC");
	if($get_sessions->num_rows) {
		while($row = $get_sessions->fetch_assoc()) {
			$se_option .= "<option value='".escape($row['session_year'])."'>".escape(strtoupper($row['session_year']))."</option>";
		}
	}
	
	//Get section
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
			<a href="#">Home</a> <i class="fa fa-angle-right"></i> <a href="#">Collect Fees</a>
		</div>
		<div class="content-wrapper shrink">
			<h3>Collect Fees</h3>
			<div class="content-body shrink">
				<div class="row">
					<div class="col-sm-12">
						<ul class="nav nav-tabs">
							<li class="active"><a data-toggle="tab" href="#menu1">Collected fees</a></li>
							<li><a data-toggle="tab" href="#home">Collect Fee</a></li>
						</ul>
						<div class="custom bg_all">
							
							<div class="tab-content">
								<div id="home" class="tab-pane fade">
									<h4><i class="fa fa-paypal"></i> Collect Fees</h4>
									<form class="form-horizontal">
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label col-sm-2" for="session">Session</label>
													<div class="col-sm-10">
														<select name="session" id="session" class="form-control" onChange="fetch_students()">
															<?php echo $se_option; ?>
														</select>
													</div>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label col-sm-2" for="term">Term</label>
													<div class="col-sm-10">
														<select name="term" id="term" class="form-control" onChange="fetch_students()">
															<option value="first" <?php echo(exxcape('term', $clear) == "first")? 'selected' : ''; ?>>First</option>
															<option value="second" <?php echo(exxcape('term', $clear) == "second")? 'selected' : ''; ?>>Second</option>
															<option value="third" <?php echo(exxcape('term', $clear) == "third")? 'selected' : ''; ?>>Third</option>
														</select>
													</div>
												</div>
											</div>
										</div>
										
										<div class="row">
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label col-sm-2" for="section">Section</label>
													<div class="col-sm-10">
														<select name="section" id="section" onChange="get_level(); fetch_students()" class="form-control">
															<?php echo $sec_option; ?>
														</select>
													</div>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label col-sm-2" for="class">Class</label>
													<div class="col-sm-10">
														<select name="class" id="class_level" class="form-control" onChange="fetch_students()">
															<option value="">Select</option>
														</select>
													</div>
												</div>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label class="control-label col-sm-2" for="alpha">Suffix</label>
													<div class="col-sm-5">
														<select name="alpha" id="alpha" class="form-control" onChange="fetch_students()">
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
										</div>
										<br/><div id="mloader"></div>
									</form>
									
									<div id="fetched_stud"></div>

								</div>
								<div id="menu1" class="tab-pane fade  in active">
									<h4><i class="fa fa-calculator"></i> Collected Fees</h4>
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

<!--Collect Fee Modal -->
<div class="modal fade" id="collectFeeModal" role="dialog">
	<div class="modal-dialog modal-md">
		<!-- Modal content-->
		<div class="modal-content" style="border-radius: 0px;">
			<div class="modal-body" style="padding-left: 30px; padding-right: 30px">
				<form class="modal_style" id="collect_fee_form" method="post" action="parse.php" onSubmit="collect_fee(); return false" enctype="multipart/form-data">
					<h3 class="text-center text-blue"><i class="fa fa-camera-retro text-gold"></i> Musa Iliyasu College<br/><span style="font-style: italic; font-size: 13px;">Rijiyar zaki, along gwarzo road kano state</span></h3><hr/>
					<div id="ploader"></div>
					
					<div id="collect_body">
						
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
	
</script>

</html>