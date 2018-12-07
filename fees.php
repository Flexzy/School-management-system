<?php 
	
	require_once "helpers/init.php";
	
	$result = "";
	$get_fee_head = $db->query("SELECT * FROM fee_head AS f INNER JOIN class_level AS c ON f.class_level=c.level_id INNER JOIN section AS s ON c.section_id=s.section_id ORDER BY fee_id DESC");
	if($get_fee_head->num_rows) {
		$result .= "<table class='table table-bordered' id='fee_tb'>
					<thead>
						<tr>
							<th>#</th>
							<th>Section</th>
							<th>Class Level</th>
							<th>Fee Name</th>
							<th>Amount</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>";
		$x = 1;
		
		while($row = $get_fee_head->fetch_assoc()) {
			
			$result .= " <tr>
							<td>".$x."</td>
							<td>".escape($row['section_name'])."</td>
							<td>".escape($row['level'])."</td>
							<td>".escape(ucwords($row['fee_type']))."</td>
							<td>".number_format(num_only($row['amount']))."</td>
							<td>
								<a href='#' onClick=\"fee_modal('".escape($row['section'])."', '".escape($row['class_level'])."', '".escape($row['fee_type'])."', '".num_only($row['amount'])."', '".num_only($row['fee_id'])."'); return false\" class='btn btn-custom'><i class='fa fa-pencil'></i></a>
								<a href='#' onClick=\"remove_fee_head('".num_only($row['fee_id'])."'); return false\" class='btn btn-danger'><i class='fa fa-trash-o'></i></a>
							</td>
						</tr>";
			$x++;
		}
		$result .= "</tbody></table>";
	}else{
		$result = "<br/>".messageFormat("danger customized", "<i class='fa fa-warning'></i> You have not recorded any Fee configuration. Please set up a new fee configuration by clicking the 'Add Fee Head Tab'.");
	}
	
	$sec_option = "<option value=''>Select</option>";
	$get_section = $db->query("SELECT * FROM section");
	if($get_section->num_rows) {
		while($row = $get_section->fetch_assoc()) {
			$sec_option .= "<option value='".num_only($row['section_id'])."'>".escape(strtoupper($row['section_name']))."</option>";
		}
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
			<a href="#">Home</a> <i class="fa fa-angle-right"></i> <a href="#">Form</a> <i class="fa fa-angle-right"></i> <a href="#">GeneralElements</a>
		</div>
		<div class="content-wrapper shrink">
			<h3>Fee Configuration</h3>
			<div class="content-body shrink">
				<div class="row">
					<div class="col-sm-12">
						<ul class="nav nav-tabs">
								<li class="active"><a data-toggle="tab" href="#home">All Fees Configuration</a></li>
								<li><a data-toggle="tab" href="#menu1">+ Add Fee head</a></li>
							</ul>
						<div class="custom bg_all">
							
							<div class="tab-content">
								<div id="home" class="tab-pane fade in active">
									<h4>Fees</h4>
									<div id="fee_div">
										<?php echo $result; ?>
									</div>
								</div>
								<div id="menu1" class="tab-pane fade">
									<div class="row">
										<div class="col-md-8 col-md-offset-2">
											<h4>+ New fee head</h4><br/>
											<?php echo $message; ?>
											<div id="mloader"></div>
											<form class="form-horizontal" method="post" action="<?php escape($_SERVER['PHP_SELF']); ?>" onSubmit="sub_fee_head(); return false" id="fee_head_form">
												<div class="form-group">
													<label class="control-label col-sm-2" for="input-1">Section</label>
													<div class="col-sm-9">
														<select name="section" id="section" onChange="get_level()" class="form-control">
															<?php echo $sec_option; ?>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-2" for="input-1">Class Level</label>
													<div class="col-sm-9">
														<select name="class_level" id="class_level" class="form-control">
															<option value="" >Select</option>
														</select>
													</div>
												</div>
												<!--<div class="form-group">
													<label class="control-label col-sm-2" for="input-2">Term</label>
													<div class="col-sm-9">
														<select name="term" class="form-control">
															<option value="first" <?php //echo(exxcape('term', $clear) == "first")? 'selected' : ''; ?>>First</option>
															<option value="second" <?php //echo(exxcape('term', $clear) == "second")? 'selected' : ''; ?>>Second</option>
															<option value="third" <?php //echo(exxcape('term', $clear) == "third")? 'selected' : ''; ?>>Third</option>
														</select>
													</div>
												</div>-->
												<div class="form-group">
													<label class="control-label col-sm-2" for="input-2">Fee name</label>
													<div class="col-sm-9">
														<input type="text" name="fee_name" class="form-control" value="<?php exxcape('fee_name', $clear); ?>" id="input-1" placeholder="e.g Tuition fee" />
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-2" for="input-2">Amount</label>
													<div class="col-sm-9">
														<input type="number" min="0" name="amount" class="form-control" value="<?php exxcape('amount', $clear); ?>" id="input-1" placeholder="10000" />
													</div>
												</div>
												
												<div class="form-group">
													<div class="col-sm-offset-2 col-sm-10">
														<button type="submit" class="btn btn-custom" name="submit">Save configuration</button>
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

<!--Edit Academic Year Modal -->
<div class="modal fade" id="feeModal" role="dialog">
	<div class="modal-dialog modal-sm">
		<!-- Modal content-->
		<div class="modal-content" style="border-radius: 0px;">
			<div class="modal-body" style="padding-left: 30px; padding-right: 30px">
				<form class="modal_style" id="edit_fee_form" method="post" action="parse.php" onSubmit="update_fee(); return false" enctype="multipart/form-data">
					<h5 class="text-center text-blue modal_header"><i class="fa fa-calendar text-gold"></i> Edit Fee Configuration</h5>
					<div id="mloader"></div>
					<div class="form-group">
						<label class="text-blue" for="msection">Section</label>
						<div class="form-group">
							<select name="msection" id="msection" onChange="get_level_edit()" class="form-control">
								<?php echo $sec_option; ?>
							</select>
							<input type="hidden" name="fid" id="fid" />
						</div>
					</div>
					<div class="form-group">
						<label class="text-blue" for="mclass_level">Class Level</label>
						<div class="form-group">
							<select name="mclass_level" id="mclass_level" class="form-control">
								<option value="" >Select</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="text-blue" for="mterm">Fee Name</label>
						<div class="form-group">
							<input type="text" name="mfee_name" class="form-control" id="mfee_name" placeholder="e.g Computer Lab Fee" />
						</div>
					</div>
					<div class="form-group">
						<label class="text-blue" for="mamount">Amount</label>
						<div class="form-group">
							<input type="number" name="mamount" min="0" class="form-control" id="mamount" placeholder="e.g 30000" />
						</div>
					</div>
					<div class="form-group">
						<button type="update" name="update" class="btn btn-custom">Update entry</button>
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
<script src="js/alertify.min.js"></script>
<script src="js/custom.js"></script>
<script src="js/bootbox.min.js"></script>
<!---/--------- JQuery Files ---------------------->

<script>

	$("#fee_tb").DataTable();

</script>

</html>