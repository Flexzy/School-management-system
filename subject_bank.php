<?php 
	
	require_once "helpers/init.php";
	
	$result = "";
	$get_subjects = $db->query("SELECT * FROM subject_bank ORDER BY subject_id DESC");
	if($get_subjects->num_rows) {
		$result .= "<table class='table table-bordered' id='subject_tb'>
					<thead>
						<tr>
							<th>#</th>
							<th>Subject Title</th>
							<th>Abbreviation</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>";
		$x = 1;
		while($row = $get_subjects->fetch_assoc()) {
			$result .= " <tr>
							<td>".$x."</td>
							<td>".escape(ucwords($row['subject_name']))."</td>
							<td>".escape(strtoupper($row['subject_abbr']))."</td>
							<td>
								<a href='#' onClick=\"sub_bank_modal('".escape(ucwords($row['subject_name']))."', '".escape(strtoupper($row['subject_abbr']))."', '".num_only($row['subject_id'])."'); return false\" class='btn btn-custom'><i class='fa fa-pencil'></i></a>
								<a href='#' onClick=\"remove_subject('".num_only($row['subject_id'])."'); return false\" class='btn btn-danger'><i class='fa fa-trash-o'></i></a>
							</td>
						</tr>";
			$x++;
		}
		$result .= "</tbody></table>";
	}else{
		$result = "<br/>".messageFormat("danger customized", "<i class='fa fa-warning'></i> You have not recorded any Subjects yet. Please click on the 'add subject' tab to add a new subject.");
	}
	
	$message = "";
	$clear = "";
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		if(isset($_POST['submit'])) {
			$subject = escape(strtolower($_POST['subject']));
			$abbr = escape(strtolower($_POST['abbr']));
			$flag = true;
			
			if($subject != "" && $abbr != "") {
				$flag = true;
			}else{
				$flag = false;
				$message = messageFormat("danger customized", "<i class='fa fa-warning'></i> You have not added a subject or its abbreviation. Please try again");
			}
			
			if($flag != false) {
				$check = $db->query("SELECT subject_name FROM subject_bank WHERE subject_name='".$subject."' LIMIT 1");
				if($check->num_rows) {
					$flag = false;
					$message = messageFormat("danger customized", "<i class='fa fa-warning'></i> Duplicate entries not allowed. A subject already exists with the same title. Please try again");
				}else{
					$flag = true;
				}
			}
			
			if($flag != false) {
				$save = $db->prepare("INSERT INTO subject_bank(subject_name, subject_abbr) VALUES(?,?)");
				$save->bind_param("ss", $subject, $abbr);
				
				if($save->execute()) {
					$message = messageFormat("success customized", "<i class='fa fa-warning'></i> Subject was successfully added");
					$clear = "success";
					header("refresh: 2; url=subject_bank.php");
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
			<a href="#">Home</a> <i class="fa fa-angle-right"></i> <a href="#">Subject Bank</a>
		</div>
		<div class="content-wrapper shrink">
			<h3>Subject Bank</h3>
			<div class="content-body shrink">
				<div class="row">
					<div class="col-sm-12">
						<ul class="nav nav-tabs">
								<li class="active"><a data-toggle="tab" href="#home">+ Add Subject</a></li>
								<li><a data-toggle="tab" href="#menu1">All Subjects</a></li>
							</ul>
						<div class="custom bg_all">
							
							<div class="tab-content">
								<div id="home" class="tab-pane fade in active">
									<div class="row">
										<div class="col-md-offset-2 col-md-6">
											<h4><i class="fa fa-book"></i> New Subject</h4><br/>
											<?php echo $message; ?>
											<form class="form-horizontal" method="post" action="<?php escape($_SERVER['PHP_SELF']); ?>" >
												<div class="form-group">
													<label class="control-label col-sm-3" for="input-1">Subject</label>
													<div class="col-sm-9">
														<input type="text" name="subject" class="form-control" value="<?php exxcape('subject', $clear); ?>" id="input-1" placeholder="Subject Title" />
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-sm-3" for="input-2">Abbreviation</label>
													<div class="col-sm-9">
														<input type="text" name="abbr" class="form-control" value="<?php exxcape('abbr', $clear); ?>" id="input-2" placeholder="Subject Abbreviation" />
													</div>
												</div>
												<div class="form-group">
													<div class="col-sm-offset-3 col-sm-9">
														<button type="submit" class="btn btn-custom" name="submit">Save subject</button>
													</div>
												</div>
											</form>
										</div>
									</div>
								</div>
								<div id="menu1" class="tab-pane fade">
									<h4>Subject Bank records</h4>
									<div id="sub_div">
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

<!--Edit Subject Bank Modal -->
<div class="modal fade" id="subModal" role="dialog">
	<div class="modal-dialog modal-sm">
		<!-- Modal content-->
		<div class="modal-content" style="border-radius: 0px;">
			<div class="modal-body" style="padding-left: 30px; padding-right: 30px">
				<form class="modal_style" id="edit_sub_form" method="post" action="parse.php" onSubmit="update_sub(); return false" enctype="multipart/form-data">
					<h5 class="text-center text-blue modal_header"><i class="fa fa-book text-gold"></i> Edit Subject Record</h5>
					<div class="form-group">
						<label class="text-blue" for="msubject">Subject</label>
						<div class="form-group">
							<input type="text" name="msubject" id="msubject" class="form-control" placeholder="Subject title" />
							<input type="hidden" name="msub_id" id="msub_id" />
						</div>
					</div>
					<div class="form-group">
						<label class="text-blue" for="msubject">Abbreviation</label>
						<div class="form-group">
							<input type="text" name="mabbr" id="mabbr" class="form-control" placeholder="Subject abbreviation" />
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
<script src="js/bootbox.min.js"></script>
<script src="js/custom.js"></script>
<!---/--------- JQuery Files ---------------------->

<script>

	$("#subject_tb").DataTable();

</script>

</html>