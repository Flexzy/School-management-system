<?php 
	
	require_once "helpers/init.php";
	
	$emoluments = "";
	$deductions = "";
	$display = "";
	$id_arr  = array();
	$id_str  = "";
	$message = "";
	
	$get_emol_deduce = $db->query("SELECT * FROM emoluments_deductions");
	if($get_emol_deduce->num_rows) {
		$display = "style='display: block'";
		while($row = $get_emol_deduce->fetch_assoc()) {
			$id_arr[] = num_only($row['ed_id']);
			if(escape(strtolower($row['ed_type'])) == "emolument") {
				$emoluments .= '<div class="form-group">
									<label class="control-label col-sm-4" for="_'.num_only($row['ed_id']).'">'.ucfirst(escape($row['ed_name'])).' ('.strtoupper(escape($row['ed_per_amt'])).')</label>
									<div class="col-sm-8">
										<input type="number" min="0" name="_'.num_only($row['ed_id']).'" id="_'.num_only($row['ed_id']).'" class="form-control">
									</div>
								</div>';
								
			}elseif(escape(strtolower($row['ed_type'])) == "deduction"){
				$deductions .= '<div class="form-group">
									<label class="control-label col-sm-4" for="_'.num_only($row['ed_id']).'">'.ucfirst(escape($row['ed_name'])).' ('.strtoupper(escape($row['ed_per_amt'])).')</label>
									<div class="col-sm-8">
										<input type="number" min="0" name="_'.num_only($row['ed_id']).'" id="_'.num_only($row['ed_id']).'" class="form-control">
									</div>
								</div>';
			}
		}
		
		foreach($id_arr as $value) {
			$id_str .= "<input type='hidden' name='ed_ids[]' value='".$value."' />";
		}
		
	}else{
		$display = "style='display: none'";
		$message = messageFormat("danger customized", "<i class='fa fa-warning'></i> You have not created any Emolument / Deduction records. Click on the '+Add new Emolument or Deduction' link on the top right corner to add a new emolument or deduction record");
	}
	
	////////////////////////////////////////////////////////////////////
	/////////////////Fetch all Staff Records////////////////////////////
	$staff = "<option value=''>Select</option>";
	$get_staff = $db->query("SELECT staff_id, lname, mname, fname FROM staff ORDER BY lname, fname, mname") or die($db->error);
	if($get_staff->num_rows) {
		while($rs = $get_staff->fetch_assoc()) {
			$staff .= "<option value='".escape(strtolower($rs['staff_id']))."'>".escape(strtoupper($rs['lname'])).", ".escape(ucwords($rs['mname']))." ".escape(ucwords($rs['fname']))."</option>";
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
			<a href="#">Home</a> <i class="fa fa-angle-right"></i> <a href="#">Staff Financial Details</a>
		</div>
		<div class="content-wrapper shrink">
			<h3 class="text-center">Staff Financial Details</h3>
			<div class="content-body shrink">
				<div class="row">
					<div class="col-sm-12">
						
						<div class="row">
							<div class="col-sm-6">
								<h4><i class="fa fa-user-circle"></i> Add Staff financial details</h4>
							</div>
						</div>
						
						<form class="form-horizontal custom" method="post" action="" id="staff_finance_form" onSubmit="add_staff_fin_details(); return false">
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label col-sm-3 txt-left" for="ed_staff">Select Staff</label>
										<div class="col-sm-9">
											<select name="ed_staff" id="ed_staff" class="form-control" onChange="fetch_staff_finance()">
												<?php echo $staff; ?>
											</select>
										</div>
									</div>
								</div>
								<div class="col-sm-6">
									<h5 class='text-right text-primary add_emol' onClick="add_emol_modal()"><i class="fa fa-plus"></i> <b>Add new Emolument or Deduction</b></h5>
								</div>
							</div>
							
							<div id="message"><?php echo $message; ?></div>
							<div id="fin_holder" <?php echo $display; ?> >
								<div class="bg_all">
									<div class="row">
										<div class="col-md-6 emolument">
											<div class="emol_deduce" id="emolument">	
												<h4 class="emol_head">Emoluments</h4>
												<?php echo $emoluments; ?>
											</div>
										</div>
										<div class="col-md-6 deduction">
											<div class="emol_deduce" id="deduction">	
												<h4 class="emol_head">Deductions</h4>
												<?php echo $deductions; ?>
											</div>
										</div>
									</div>
									
								</div>
								
								<div class="form-group">
									<?php echo $id_str; ?>
									<br/><button type="submit" class="btn btn-custom"><i class="fa fa-hand-o-right text-custom"></i> Submit details</button>
								</div>
							
							</div>
							
						</form>
					</div>
				</div>
			</div>
			
		</div>
		
	</div>
</div>

<!--Add Emolument or Deduction Modal -->
<div class="modal fade" id="addEmolument" role="dialog">
	<div class="modal-dialog modal-sm">
		<!-- Modal content-->
		<div class="modal-content" style="border-radius: 0px;">
			<div class="modal-body">
				<h4 class="text-center">Emolument / Deduction details</h4><hr/>
				<form method="post" action="" id="ed_form" onSubmit="add_emolument(); return false">
					<div id="meloader"></div>
					<div class="form-group">
						<label for="ed_name">Name</label>
						<input type="text" class="form-control" name="ed_name" value="" placeholder=" Emolument or Deduction name" />
					</div>
					<div class="form-group">
						<label for="ed_type">Type</label>
						<select id="ed_type" name="ed_type" class="form-control">
							<option value="emolument">Emolument</option>
							<option value="deduction">Deduction</option>
						</select>
					</div>
					<div class="form-group">
						<label for="ed_per_amt">Percent or Amount</label>
						<select id="ed_per_amt" name="ed_per_amt" class="form-control">
							<option value="NGN">Amount (NGN)</option>
							<option value="%">Percentage (%)</option>
						</select>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-custom"><i class="fa fa-save text-custom"></i> Submit</button>
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

	function check() {
		window.scrollTo(0,0);
	}
	
	$(document).ready(function() {
		check_ed_container();
	});
	
</script>

</html>