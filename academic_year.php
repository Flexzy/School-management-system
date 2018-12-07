<?php 
	
	require_once "helpers/init.php";
	
	$result = "";
	$get_sessions = $db->query("SELECT * FROM academic_year ORDER BY id DESC");
	if($get_sessions->num_rows) {
		$result .= "<table class='table table-bordered' id='session_tb'>
					<thead>
						<tr>
							<th>#</th>
							<th>Session</th>
							<th>Term</th>
							<th>Active</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>";
		$x = 1;
		while($row = $get_sessions->fetch_assoc()) {
			$active = (num_only($row['active']) == 0)? "No" : "Yes";
			$result .= " <tr>
							<td>".$x."</td>
							<td>".escape($row['session_year'])."</td>
							<td>".escape(ucfirst($row['term']))."</td>
							<td>".$active."</td>
							<td>
								<a href='#' onClick=\"acad_modal('".escape($row['session_year'])."', '".escape(strtolower($row['term']))."', '".num_only($row['active'])."', '".num_only($row['id'])."'); return false\" class='btn btn-custom'><i class='fa fa-pencil'></i></a>
								<a href='#' onClick=\"remove_acad_yr('".num_only($row['id'])."'); return false\" class='btn btn-danger'><i class='fa fa-trash-o'></i></a>
							</td>
						</tr>";
			$x++;
		}
		$result .= "</tbody></table>";
	}else{
		$result = "<br/>".messageFormat("danger customized", "<i class='fa fa-warning'></i> You have not recorded any academic year / session. Please set up a new academic year and make it active.");
	}
	
	$message = "";
	$clear = "";
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		if(isset($_POST['submit'])) {
			$session_yr = escape($_POST['session']);
			$term = escape(strtolower($_POST['term']));
			$active = 0;
			$flag = true;
			
			if($session_yr != "" && $term != "") {
				$flag = true;
			}else{
				$flag = false;
				$message = messageFormat("danger customized", "<i class='fa fa-warning'></i> You have not added a session or selected a term. Please try again");
			}
			
			if($flag != false) {
				if(isset($_POST['active']) && num_only($_POST['active']) != "") {
					$active = 1;
				}
				$check = $db->query("SELECT session_year, term FROM academic_year WHERE session_year='".$session_yr."' AND term='".$term."' LIMIT 1");
				if($check->num_rows) {
					$flag = false;
					$message = messageFormat("danger customized", "<i class='fa fa-warning'></i> Duplicate entries not allowed. An academic year already exists with the same details. Please try again");
				}else{
					$flag = true;
				}
			}
			
			if($flag != false) {
				if($active == 1) {
					$rengine = $db->query("UPDATE academic_year SET active=0");
				}
				$save = $db->prepare("INSERT INTO academic_year(session_year, term, active) VALUES(?, ?, ?)");
				$save->bind_param("ssi", $session_yr, $term, $active);
				
				if($save->execute()) {
					$mactive = ($active == 1)? "'Active'" : "'Not Active'";
					$message = messageFormat("success customized", "<i class='fa fa-warning'></i> Academic year ".$session_yr." was successfully added and was set to ".$mactive."");
					$clear = "success";
					header("refresh: 2; url=academic_year.php");
				}else{
					$message = messageFormat("danger customized", "<i class='fa fa-warning'></i> Problem encountered. Please try again");
				}
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
			<h3>Academic Year setup</h3>
			<div class="content-body shrink">
				<div class="row">
					<div class="col-sm-12">
						<ul class="nav nav-tabs">
								<li class="active"><a data-toggle="tab" href="#home">+ Add academic year</a></li>
								<li><a data-toggle="tab" href="#menu1">All academic years</a></li>
							</ul>
						<div class="custom bg_all">
							
							<div class="tab-content">
								<div id="home" class="tab-pane fade in active">
									<div class="row">
										<div class="col-md-6 col-md-offset-3">
											<h4>New Academic Year</h4><br/>
											<?php echo $message; ?>
											<form class="form-horizontal" method="post" action="<?php escape($_SERVER['PHP_SELF']); ?>" >
												<div class="form-group">
													<label class="control-label col-sm-2" for="input-1">Session</label>
													<div class="col-sm-10">
														<input type="text" name="session" class="form-control" value="<?php exxcape('session', $clear); ?>" id="input-1" placeholder="2014-2015" />
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-2" for="input-2">Term</label>
													<div class="col-sm-10">
														<select name="term" class="form-control">
															<option value="first" <?php echo(exxcape('term', $clear) == "first")? 'selected' : ''; ?>>First</option>
															<option value="second" <?php echo(exxcape('term', $clear) == "second")? 'selected' : ''; ?>>Second</option>
															<option value="third" <?php echo(exxcape('term', $clear) == "third")? 'selected' : ''; ?>>Third</option>
														</select>
													</div>
												</div>
												<div class="form-group">
													<div class="col-sm-offset-2 col-sm-10">
														<div class="checkbox checkbox-success checkbox-inline">
															<input type="checkbox" name="active" id="active" value="1" checked>
															<label for="active">
																<strong>Active</strong>
															</label>
														</div>
														<p class="the-font custom"><span class="text-red"><strong>IMPORTANT NOTICE: Before submitting, If the academic year and term you just added is not to be used now, be sure to deselect the option that says "Active"</strong></span>.</p>
													</div>
												</div>
												<div class="form-group">
													<div class="col-sm-offset-2 col-sm-10">
														<button type="submit" class="btn btn-custom" name="submit">Save setup</button>
													</div>
												</div>
											</form>
										</div>
									</div>
								</div>
								<div id="menu1" class="tab-pane fade">
									<h4>Academic session year</h4>
									<div id="acad_div">
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
<div class="modal fade" id="acadModal" role="dialog">
	<div class="modal-dialog modal-sm">
		<!-- Modal content-->
		<div class="modal-content" style="border-radius: 0px;">
			<div class="modal-body" style="padding-left: 30px; padding-right: 30px">
				<form class="modal_style" id="edit_acad_form" method="post" action="parse.php" onSubmit="update_acad_yr(); return false" enctype="multipart/form-data">
					<h5 class="text-center text-blue modal_header"><i class="fa fa-calendar text-gold"></i> Edit Academic year</h5>
					<div class="form-group">
						<label class="text-blue" for="msession">Session</label>
						<div class="form-group">
							<input type="text" name="msession" id="msession" class="form-control" placeholder="2016-2017" />
							<input type="hidden" name="mid" id="mid" />
						</div>
					</div>
					<div class="form-group">
						<label class="text-blue" for="mterm">Term</label>
						<div class="form-group">
							<select class="form-control" name="mterm" id="mterm">
								<option value="first">First</option>
								<option value="second">Second</option>
								<option value="third">Third</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label>Who can see this</label><br/>
						<div class="checkbox checkbox-primary radio-inline">
							<input type="checkbox" name="mactive" id="mactive" value="1" checked>
							<label for="mactive">
								<strong>Active</strong>
							</label>
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
<script src="js/custom.js"></script>
<script src="js/bootbox.min.js"></script>
<!---/--------- JQuery Files ---------------------->

<script>

	$("#session_tb").DataTable();

</script>

</html>