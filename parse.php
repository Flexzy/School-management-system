<?php 
	
	require_once "helpers/init.php";
	
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		//////////////////////////////////////////////////////
		/////////////UPDATE Academic Year Details///////////////
		if(isset($_POST['msession']) && isset($_POST['mid']) && escape($_POST['msession']) != "" && num_only($_POST['mid']) != "") {
			$session_yr = escape($_POST['msession']);
			$term 		= escape(strtolower($_POST['mterm']));
			$mid 		= num_only($_POST['mid']);
			$active 	= 0;
			$flag 		= true;
			
			if($session_yr != "" && $term != "" && $mid) {
				$flag = true;
			}else{
				$flag = false;
				echo messageFormat("danger customized", "<i class='fa fa-warning'></i> You have added a session or selected a term. Please try again");
			}
			
			if($flag != false) {
				$check = $db->query("SELECT session_year, term FROM academic_year WHERE session_year='".$session_yr."' AND term='".$term."' AND id<>'".$mid."' LIMIT 1");
				if($check->num_rows) {
					$flag = false;
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Duplicate entries not allowed. An academic year already exists with the same details. Please try again");
				}else{
					$flag = true;
				}
			}
			
			if($flag != false) {
				if(isset($_POST['mactive']) && num_only($_POST['mactive']) != "") {
					$active = 1;
				}
				
				if($active == 1) {
					$rengine = $db->query("UPDATE academic_year SET active=0");
				}
				
				$save = $db->prepare("UPDATE academic_year SET session_year=?, term=?, active=? WHERE id=? LIMIT 1");
				$save->bind_param("ssii", $session_yr, $term, $active, $mid) or die($db->error);
				
				if($save->execute()) {
					$mactive = ($active == 1)? "'Active'" : "'Not Active'";
					echo "success";
				}else{
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Problem encountered. Please try again");
				}
			}
			
		}
		
		/////////////////////////////////////////////////
		///////////Fetch academic_year details////////////
		if(isset($_POST['get_acad']) && escape($_POST['get_acad']) == 'yes') {
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
				echo $result;
			}else{
				$result = "<br/>".messageFormat("danger customized", "<i class='fa fa-warning'></i> You have not recorded any academic year / session. Please set up a new academic year and make it active.");
				echo $result;
			}
		}
		
		///////////////////////////////////////////////
		//////////Delete academic year record//////////
		if(isset($_POST['acad_to_delete']) && num_only($_POST['acad_to_delete']) != "") {
			$id = num_only($_POST['acad_to_delete']);
			
			if($id != "") {
				$delete = $db->query("DELETE FROM academic_year WHERE id='".$id."' LIMIT 1");
				if($delete) {
					echo "success";
				}else{
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Problem encountered. Please try again");
				}
			}
		}
		
		//////////////////////////////////////////////////////
		/////////////UPDATE Subject Details///////////////////
		if(isset($_POST['msubject']) && isset($_POST['msub_id']) && escape($_POST['msubject']) != "" && num_only($_POST['msub_id']) != "") {
			$subject = escape(strtolower($_POST['msubject']));
			$abbr = escape(strtolower($_POST['mabbr']));
			$sid 	= escape(strtolower($_POST['msub_id']));
			$flag = true;
			
			if($subject != "" && $abbr != "" && $sid) {
				$flag = true;
			}else{
				$flag = false;
				echo messageFormat("danger customized", "<i class='fa fa-warning'></i> You have not added a subject. Please try again");
			}
			
			if($flag != false) {
				$check = $db->query("SELECT subject_name FROM subject_bank WHERE subject_name='".$subject."' AND subject_id<>'".$sid."' LIMIT 1");
				if($check->num_rows) {
					$flag = false;
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Duplicate entries not allowed. A subject already exists with the same title. Please try again");
				}else{
					$flag = true;
				}
			}
			
			if($flag != false) {
				$save = $db->prepare("UPDATE subject_bank SET subject_name=?, subject_abbr=? WHERE subject_id=? LIMIT 1") or die($db->error);
				$save->bind_param("ssi", $subject, $abbr, $sid);
				
				if($save->execute()) {
					echo "success";
				}else{
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Problem encountered. Please try again");
				}
			}
		}
		
		/////////////////////////////////////////////////
		///////////Fetch Subject records/////////////////
		if(isset($_POST['get_sub_bank']) && escape($_POST['get_sub_bank']) == 'yes') {
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
				echo $result;
			}else{
				$result = "<br/>".messageFormat("danger customized", "<i class='fa fa-warning'></i> You have not recorded any Subjects yet. Please click on the 'add subject' tab to add a new subject.");
				echo $result;
			}
		}
		
		///////////////////////////////////////////////
		//////////Delete Subject record////////////////
		if(isset($_POST['sub_to_delete']) && num_only($_POST['sub_to_delete']) != "") {
			$id = num_only($_POST['sub_to_delete']);
			
			if($id != "") {
				$delete = $db->query("DELETE FROM subject_bank WHERE subject_id='".$id."' LIMIT 1");
				if($delete) {
					echo "success";
				}else{
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Problem encountered. Please try again");
				}
			}
		}
		
		//////////////////////////////////////////////////////////////////
		//////////////////Dynamic States and LGA ///////////////////////////
		if(isset($_POST['mstate']) && escape($_POST['mstate']) != "") {
			$state = ucfirst(escape($_POST['mstate']));
			
			$state_arr = states_lgas();
			
			if($state_arr != "") {
				$mOption = "";
				$x = 1;
				foreach($state_arr[$state] as $value) {
					if($x == 1) {
						$mOption .= "<option value=''>Select</option>";
					}elseif($x > 1) {
						$mOption .= "<option value='".ucwords(escape($value))."'>".ucwords(escape($value))."</option>";
					}
					$x++;
				}
				echo $mOption;
			}
			
		}
		
		/////////////////////////////////
		/////Gen Staff ID///////////////
		$num_staff = 0;
		$staff = $db->query("SELECT id FROM staff ORDER BY staff_id DESC LIMIT 1") or die($db->error);
		$row = $staff->fetch_assoc();
		$num_staff = $row['id'] + 1;
		if($num_staff < 10) {
			$num_staff = "SCH-00".$num_staff;
		}elseif($num_staff < 100) {
			$num_staff = "SCH-0".$num_staff;
		}
		
		///////////////////////////////////////////////
		//////////////////Add new Staff////////////////
		if(isset($_POST['staff_id']) && escape($_POST['staff_id']) != "") {
			$flag 	= true;
			$staff_id = escape($num_staff);
			$type	= escape($_POST['staff_type']);
			$desig	= escape(strtolower($_POST['desig']));
			$fname	= escape(strtolower($_POST['fname']));
			$mname	= escape(strtolower($_POST['mname']));
			$lname	= escape(strtolower($_POST['lname']));
			$dob	= escape($_POST['dob']);
			$nation	= escape(strtolower($_POST['nationality']));
			$state	= escape(strtolower($_POST['state']));
			$lga	= escape(strtolower($_POST['lga']));
			$gender	= escape(strtolower($_POST['gender']));
			$m_status	= escape(strtolower($_POST['m_status']));
			$religion	= escape(strtolower($_POST['religion']));
			$mobile	= num_only($_POST['mobile']);
			$email	= escape(strtolower($_POST['email']));
			$address	= escape(strtolower($_POST['address']));
			$qual		= escape(strtolower($_POST['qual']));
			$exp		= num_only($_POST['exp']);
			$prev_org	= escape(strtolower($_POST['prev_org']));
			$ap_date	= escape($_POST['ap_date']);
			$allowed_ext = array("JPEG", "jpeg", "jpg", "JPG", "PNG", "png");
			$img_path 	= "";
			
			if($staff_id != "" && $type != "" && $desig != "" && $fname != "" && $lname != "" && $dob != "" && $nation != "" && $gender != "" && $m_status != "" && $religion != "" && $mobile != "" && $email != "" && $address != "" && $qual != "" && $exp != "" && $ap_date != "") {
				if($nation == "nigerian" && $state != "" && $lga != "") {
					$flag = true;
				}elseif($nation == "non-nigerian"){
					$flag = true;
					$state = "";
					$lga = "";
				}else{
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Incomplete form data, Note that only the fields without red asterisk '*' can be omitted. Please try again");
				}
			}else{
				$flag = false;
				echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Incomplete form data, Note that only the fields without red asterisk '*' can be omitted. Please try again");
			}
			
			if($flag != false) {
				if(isset($_FILES['passport']) && escape($_FILES['passport']['name']) != "") {
					$img_ext = explode(".", escape($_FILES['passport']['name']));
					if(!in_array($img_ext[count($img_ext)-1], $allowed_ext)) {
						$flag = false;
						echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Invalid image format uploaded, Only PNG, JPG, JPEG files are allowed. Please try again");
					}else{
						$flag = true;
					}
				}
			}
			
			if($flag != false) {
				if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$flag = true;
				}else{
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Invalid E-mail format. Please try again");
				}
			}
			
			if($flag != false) {
				$check = $db->query("SELECT email FROM staff WHERE email='".$email."' LIMIT 1");
				if($check->num_rows) {
					$flag = false;
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Duplicate entries not allowed. A staff has been registered with the same email. Please check the records and try again");
				}else{
					$flag = true;
				}
			}
			
			if($flag != false) {
				if(isset($_FILES['passport']) && escape($_FILES['passport']['name']) != "") {
					$img_path = "img/staff/".$staff_id.".jpg";
				}
				$reg_date = date('Y-m-d');
				$save = $db->prepare("INSERT INTO staff(staff_id, lname, mname, fname, gender, marital_stat, dob, nationality, state, lga, religion, type, desig, photo, mobile, address, email, highest_qual, years_of_exp, prev_org, ap_date, reg_date) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)") or die($db->error);
				
				$save->bind_param("ssssssssssssssssssisss", $staff_id, $lname, $mname, $fname, $gender, $m_status, $dob, $nation, $state, $lga, $religion, $type, $desig, $img_path, $mobile, $address, $email, $qual, $exp, $prev_org, $ap_date, $reg_date);
				
				if($save->execute()) {
					if($img_path != "") {
						move_uploaded_file($_FILES['passport']['tmp_name'], $img_path);
					}
					echo "Operation successful";
				}else{
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Problem encountered. Please try again");
				}
			}
			
		}
		
		//////////////////////////////////////////////////////////
		/////Gen Staff ID on form submission success///////////////
		if(isset($_POST['make_id']) && alpha_only($_POST['make_id']) == 'yes') {
			$gen_id = 0;
			$staff = $db->query("SELECT id FROM staff ORDER BY staff_id DESC LIMIT 1") or die($db->error);
			$row = $staff->fetch_assoc();
			$gen_id = $row['id'] + 1;
			if($gen_id < 10) {
				$gen_id = "SCH-00".$gen_id;
			}elseif($gen_id < 100) {
				$gen_id = "SCH-0".$gen_id;
			}
			echo $gen_id;
		}
		
		//////////////////////////////////////////////////////////
		/////Delete a Staff Record from the Database///////////////
		if(isset($_POST['staffID_to_delete']) && escape($_POST['staffID_to_delete']) != "" && isset($_POST['ID_to_delete']) && num_only($_POST['ID_to_delete']) != "") {
			$staff_id 	= escape($_POST['staffID_to_delete']);
			$id 		= num_only($_POST['ID_to_delete']);
			
			if($staff_id != "" && $id != "") {
				$delete = $db->query("DELETE FROM staff WHERE id='".$id."' AND staff_id='".$staff_id."' LIMIT 1");
				if($delete) {
					unlink(escape($staff_id).".jpg");
					echo "Operation successful";
				}else{
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Problem encountered. Please try again");
				}
			}
		}
		
		////////////////////////////////////////////////////
		//////////////Load all Staff records////////////////
		if(isset($_POST['get_staff']) && escape($_POST['get_staff']) == 'yes') {
			$result = "";
			$get_staff = $db->query("SELECT * FROM staff ORDER BY id DESC");
			if($get_staff->num_rows) {
				$result .= "<table class='table table-bordered' id='staff_tb'>
							<thead>
								<tr>
									<th>#</th>
									<th>Staff ID</th>
									<th>Type</th>
									<th>Desig</th>
									<th>Name</th>
									<th>sex</th>
									<th>State</th>
									<th>Lga</th>
									<th>Qualification</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>";
				$x = 1;
				while($row = $get_staff->fetch_assoc()) {
					$result .= " <tr>
									<td>".$x."</td>
									<td>".escape(strtoupper($row['staff_id']))."</td>
									<td>".escape(ucwords($row['type']))."</td>
									<td>".escape(ucwords($row['desig']))."</td>
									<td>".escape(strtoupper($row['lname'])).", ".escape(ucwords($row['mname']))." ".escape(ucwords($row['fname']))."</td>
									<td>".escape(ucwords($row['gender']))."</td>
									<td>".escape(ucwords($row['state']))."</td>
									<td>".escape(ucwords($row['lga']))."</td>
									<td>".escape(strtoupper($row['highest_qual']))."</td>
									<td>
										<a href='edit_staff.php?id=".escape($row['staff_id'])."' class='btn btn-custom'><i class='fa fa-pencil'></i></a>
										<a href='#' onClick=\"remove_staff('".escape($row['staff_id'])."', '".num_only($row['id'])."'); return false\" class='btn btn-danger'><i class='fa fa-trash-o'></i></a>
									</td>
								</tr>";
					$x++;
				}
				$result .= "</tbody></table>";
				echo $result;
			}else{
				$result = "<br/>".messageFormat("danger customized", "<i class='fa fa-warning'></i> You have not recorded any Staff/Employee yet. Please click on the 'add staff' tab to add a new staff.");
				echo $result;
			}
		}
		
		///////////////////////////////////////////////////////////
		////////////////Update Staff Details///////////////////////
		if(isset($_POST['edit_staffID']) && escape($_POST['edit_staffID']) != "") {
			$flag 	= true;
			$staff_id = escape($_POST['edit_staffID']);
			$type	= escape($_POST['estaff_type']);
			$desig	= escape(strtolower($_POST['edesig']));
			$fname	= escape(strtolower($_POST['efname']));
			$mname	= escape(strtolower($_POST['emname']));
			$lname	= escape(strtolower($_POST['elname']));
			$dob	= escape($_POST['edob']);
			$nation	= escape(strtolower($_POST['enationality']));
			$state	= escape(strtolower($_POST['estate']));
			$lga	= escape(strtolower($_POST['elga']));
			$gender	= escape(strtolower($_POST['egender']));
			$m_status	= escape(strtolower($_POST['em_status']));
			$religion	= escape(strtolower($_POST['ereligion']));
			$mobile	= num_only($_POST['emobile']);
			$email	= escape(strtolower($_POST['eemail']));
			$address	= escape(strtolower($_POST['eaddress']));
			$qual		= escape(strtolower($_POST['equal']));
			$exp		= num_only($_POST['eexp']);
			$prev_org	= escape(strtolower($_POST['eprev_org']));
			$ap_date	= escape($_POST['eap_date']);
			$allowed_ext = array("JPEG", "jpeg", "jpg", "JPG", "PNG", "png");
			$img_path 	= "";
			
			if($staff_id != "" && $type != "" && $desig != "" && $fname != "" && $lname != "" && $dob != "" && $nation != "" && $gender != "" && $m_status != "" && $religion != "" && $mobile != "" && $email != "" && $address != "" && $qual != "" && $exp != "" && $ap_date != "") {
				if($nation == "nigerian" && $state != "" && $lga != "") {
					$flag = true;
				}elseif($nation == "non-nigerian"){
					$flag = true;
					$state = "";
					$lga = "";
				}else{
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Incomplete form data, Note that only the fields without red asterisk '*' can be omitted. Please try again");
				}
			}else{
				$flag = false;
				echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Incomplete form data, Note that only the fields without red asterisk '*' can be omitted. Please try again");
			}
			
			if($flag != false) {
				if(isset($_FILES['epassport']) && escape($_FILES['epassport']['name']) != "") {
					$img_ext = explode(".", escape($_FILES['epassport']['name']));
					if(!in_array($img_ext[count($img_ext)-1], $allowed_ext)) {
						$flag = false;
						echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Invalid image format uploaded, Only PNG, JPG, JPEG files are allowed. Please try again");
					}else{
						$flag = true;
					}
				}
			}
			
			if($flag != false) {
				if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$flag = true;
				}else{
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Invalid E-mail format. Please try again");
				}
			}
			
			if($flag != false) {
				$check = $db->query("SELECT email FROM staff WHERE email='".$email."' AND staff_id<>'".$staff_id."' LIMIT 1");
				if($check->num_rows) {
					$flag = false;
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Duplicate entries not allowed. A staff has been registered with the same email. Please check the records and try again");
				}else{
					$flag = true;
				}
			}
			
			if($flag != false) {
				if(isset($_FILES['epassport']) && escape($_FILES['epassport']['name']) != "") {
					$img_path = "img/staff/".$staff_id.".jpg";
				}
				$reg_date = date('Y-m-d');
				$save = $db->prepare("UPDATE staff SET lname=?, mname=?, fname=?, gender=?, marital_stat=?, dob=?, nationality=?, state=?, lga=?, religion=?, type=?, desig=?, mobile=?, address=?, email=?, highest_qual=?, years_of_exp=?, prev_org=?, ap_date=? WHERE staff_id=? LIMIT 1") or die($db->error);
				
				$save->bind_param("ssssssssssssisssisss", $lname, $mname, $fname, $gender, $m_status, $dob, $nation, $state, $lga, $religion, $type, $desig, $mobile, $address, $email, $qual, $exp, $prev_org, $ap_date, $staff_id);
				
				if($save->execute()) {
					if($img_path != "") {
						$update_pic = $db->query("UPDATE staff SET photo='".$img_path."' WHERE staff_id='".$staff_id."' LIMIT 1");
						move_uploaded_file($_FILES['epassport']['tmp_name'], $img_path);
					}
					echo "Operation successful";
				}else{
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Problem encountered. Please try again");
				}
			}
			
		}
		
		////////////////////////////////////////////////////////////
		//////////////Fetch level using section_id//////////////////
		if(isset($_POST['_level']) && num_only($_POST['_level']) != "") {
			$level = num_only($_POST['_level']);
			$class_level = "";
			
			if($level != "") {
				$class_level = "<option value=''>Select</option>";
				$get_level = $db->query("SELECT * FROM class_level WHERE section_id='".$level."'");
				if($get_level->num_rows) {
					while($row = $get_level->fetch_assoc()) {
						$class_level .= "<option value='".num_only($row['level_id'])."'>".escape(strtoupper($row['level']))."</option>";
					}
					echo $class_level;
				}
			}else{
				$class_level = "<option value=''>Select</option>";
				echo $class_level;
			}
		}
		
		//////////////////////////////////////////////////////////
		//////////////////Save Fee Head//////////////////////////
		if(isset($_POST['section'])) {
			$flag = true;
			$section 	= num_only($_POST['section']);
			$level 		= num_only($_POST['class_level']);
			$fee_name 	= escape($_POST['fee_name']);
			$fee_amt 	= num_only($_POST['amount']);
			
			if($section != "" && $level != "" && $fee_name != "" && $fee_amt != "") {
				$flag = true;
			}else{
				$flag = false;
				echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Incomplete form data, All fields required. Please try again");
			}
			
			if($flag != false) {
				$check = $db->query("SELECT * FROM fee_head WHERE section='".$section."' AND class_level='".$level."' AND fee_type='".$fee_name."' LIMIT 1") or die($db->error);
				if($check->num_rows) {
					$flag = false;
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Duplicate entries not allowed. A fee entry already exists for '".$section."' section with class level '".$level."'. To update fee head details, go to 'All Fees Configuration Tab' and select the fee head you want to update");
				}else{
					$flag = true;
				}
			}
			
			if($flag != false) {
				$save = $db->prepare("INSERT INTO fee_head(section, class_level, fee_type, amount) VALUES(?,?,?,?)") or die($db->error);
				$save->bind_param("iisi", $section, $level, $fee_name, $fee_amt);
				
				if($save->execute()) {
					echo "Operation successful";
				}else{
					$flag = false;
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Problem encountered. Please try again");
				}
			}
			
		}
		
		////////////////////////////////////////////////////
		///////////Load Fee Configurations//////////////////
		if(isset($_POST['get_fee_head']) && escape($_POST['get_fee_head']) == 'yes') {
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
				echo $result;
			}else{
				$result = "<br/>".messageFormat("danger customized", "<i class='fa fa-warning'></i> You have not recorded any Fee configuration. Please set up a new fee configuration by clicking the 'Add Fee Head Tab'.");
				echo $result;
			}
		}
		
		//////////////////////////////////////////////////////////
		/////Delete a Fee Head Record from the Database///////////
		if(isset($_POST['feeID_to_delete']) && num_only($_POST['feeID_to_delete']) != "") {
			$id = num_only($_POST['feeID_to_delete']);
			
			if($id != "") {
				$delete = $db->query("DELETE FROM fee_head WHERE fee_id='".$id."' LIMIT 1");
				if($delete) {
					echo "Operation successful";
				}else{
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Problem encountered. Please try again");
				}
			}
		}
		
		////////////////////////////////////////////////////////////
		//////////////Fetch level using section_id for edit//////////////////
		if(isset($_POST['_level_edit']) && num_only($_POST['_level_edit']) != "") {
			$level = num_only($_POST['_level_edit']);
			$select = num_only($_POST['_section_edit']);
			$class_level = "";
			
			if($level != "") {
				$class_level = "<option value=''>Select</option>";
				$get_level = $db->query("SELECT * FROM class_level WHERE section_id='".$select."'");
				if($get_level->num_rows) {
					while($row = $get_level->fetch_assoc()) {
						if($level == num_only($row['level_id'])) {
							$class_level .= "<option value='".num_only($row['level_id'])."' selected>".escape(strtoupper($row['level']))."</option>";
						}else{
							$class_level .= "<option value='".num_only($row['level_id'])."'>".escape(strtoupper($row['level']))."</option>";
						}
					}
					echo $class_level;
				}
			}else{
				$class_level = "<option value=''>Select</option>";
				echo $class_level;
			}
		}
		
		////////////////////////////////////////////////////////
		//////////////Update fee configuration//////////////////
		if(isset($_POST['msection']) && isset($_POST['fid']) && num_only($_POST['fid']) != "") {
			$flag = true;
			$section 	= num_only($_POST['msection']);
			$level 		= num_only($_POST['mclass_level']);
			$fee_name 	= escape($_POST['mfee_name']);
			$fee_amt 	= num_only($_POST['mamount']);
			$fid 		= num_only($_POST['fid']);
			
			if($section != "" && $level != "" && $fee_name != "" && $fee_amt != "" && $fid != "") {
				$flag = true;
			}else{
				$flag = false;
				echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Incomplete form data, All fields required. Please try again");
			}
			
			if($flag != false) {
				$check = $db->query("SELECT * FROM fee_head WHERE section='".$section."' AND class_level='".$level."' AND fee_type='".$fee_name."' AND fee_id<>'".$fid."' LIMIT 1") or die($db->error);
				if($check->num_rows) {
					$flag = false;
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Duplicate entries not allowed. A fee entry already exists for '".$section."' section with class level '".$level."'.");
				}else{
					$flag = true;
				}
			}
			
			if($flag != false) {
				$save = $db->prepare("UPDATE fee_head SET section=?, class_level=?, fee_type=?, amount=? WHERE fee_id=?") or die($db->error);
				$save->bind_param("iisii", $section, $level, $fee_name, $fee_amt, $fid);
				
				if($save->execute()) {
					echo "success";
				}else{
					$flag = false;
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Problem encountered. Please try again");
				}
			}
			
		}
		
		/////////////////////////////////////////////////////////////
		////////////////New Student Entry///////////////////////////
		if(isset($_POST['sadmin_no']) && escape($_POST['sadmin_no']) != "") {
			$flag			= false;
			$error 			= array();
			$lname 			= escape(strtolower($_POST['slname']));
			$mname 			= escape(strtolower($_POST['smname']));
			$fname 			= escape(strtolower($_POST['sfname']));
			$dob 			= escape($_POST['sdob']);
			$nation 		= escape(strtolower($_POST['snationality']));
			$state 			= escape(strtolower($_POST['sstate']));
			$lga 			= escape(strtolower($_POST['slga']));
			$gender 		= escape(strtolower($_POST['sgender']));
			$religion 		= escape(strtolower($_POST['sreligion']));
			$admin_no 		= escape(strtolower($_POST['sadmin_no']));
			$section 		= escape(strtolower($_POST['ssection']));
			$class_level 	= escape(strtolower($_POST['sclass_level']));
			$alpha 			= escape(strtolower($_POST['salpha']));
			$admin_date 	= escape(strtolower($_POST['sadmission_date']));
			$father 		= escape(strtolower($_POST['sfather']));
			$fa_occu 		= escape(strtolower($_POST['sfather_occu']));
			$mother 		= escape(strtolower($_POST['smother']));
			$mo_occu 		= escape(strtolower($_POST['smother_occu']));
			$mobile 		= escape(strtolower($_POST['smobile']));
			$email 			= escape(strtolower($_POST['semail']));
			$address 		= escape(strtolower($_POST['saddress']));
			$allowed_ext 	= array("JPEG", "jpeg", "jpg", "JPG", "PNG", "png");
			$img_path 		= "";
			
			if($lname == "" || $fname == "" || $dob == "" || $nation == "" || $gender == "" || $religion == "") {
				$error[] = "Incomplete Basic details. Click on the basic details tab and fill all the required fields marked with asterisk '*'";
			}
			
			if($nation == "nigerian" && ($state == "" || $lga == "")) {
				$error[] = "Your nationality is Nigerian and you have not selected your state or local government area.";
			}elseif($nation == "non-nigerian"){
				$state = "";
				$lga = "";
			}
			
			if($admin_no == "" || $section == "" || $class_level == "" || $alpha == "" || $admin_date == "") {
				$error[] = "Incomplete Academic details. Click on the Academic details tab and fill all the required fields marked with asterisk '*'";
			}
			
			if(($fa_occu == "" && $father == "") && ($mother == "" && $mo_occu == "")) {
				$error[] = "Incomplete Guardian information. Click on the Guardian information tab and provide at least complete information about any of the guardian(parents / next of kin)";
			}
			
			if($address == "" || $mobile == "") {
				$error[] = "Incomplete Contact details. Click on the Contact details tab and fill all the required fields marked with asterisk '*'";
			}
			
			if($email != "" && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$error[] = "You have provided an invalid email address. Click on the Contact details tab and correct the email address provided";
			}
			
			if(count($error) > 0) {
				$flag = false;
				$err_res = "";
				$err_res .= "<ol>";
				foreach($error as $err) {
					$err_res .= "<li style='margin-bottom: 13px;'>".$err."</li>";
				}
				$err_res .= "</ol>";
				echo messageFormat("danger customized", "<div style='text-align: left'>".$err_res."</div>");
				$error = array();
			}else{
				$flag = true;
			}
			
			if($flag != false) {
				if(isset($_FILES['spassport']) && escape($_FILES['spassport']['name']) != "") {
					$img_ext = explode(".", escape($_FILES['spassport']['name']));
					if(!in_array($img_ext[count($img_ext)-1], $allowed_ext)) {
						$flag = false;
						echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Invalid image format uploaded, Only PNG, JPG, JPEG files are allowed. Please try again");
					}else{
						$flag = true;
					}
				}
			}
			
			if($flag != false) {
				
				if(isset($_FILES['spassport']) && escape($_FILES['spassport']['name']) != "") {
					$img_path = "img/student/".$admin_no.".jpg";
				}
				
				$save = $db->prepare("INSERT INTO students(admission_no, admission_date, lname, mname, fname, gender, dob, nationality, state, lga, section, class_level, class_name, religion, photo, father_name, father_occu, mother_name, mother_occu, mobile, address, email) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
				
				$save->bind_param("sssssssssssssssssssiss", $admin_no, $admin_date, $lname, $mname, $fname, $gender, $dob, $nation, $state, $lga, $section, $class_level, $alpha, $religion, $img_path, $father, $fa_occu, $mother, $mo_occu, $mobile, $address, $email);
				
				if($save->execute()) {
					if($img_path != "") {
						move_uploaded_file($_FILES['spassport']['tmp_name'], $img_path);
					}
					echo "Operation successful";
				}else{
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Problem encountered. Please try again");
				}
				
			}
			
		}
		
		//////////////////////////////////////////////////
		///////////Load Students records//////////////////
		if(isset($_POST['get_students']) && escape($_POST['get_students']) == 'yes') {
			$result = "";
			$get_student = $db->query("SELECT * FROM students AS stu INNER JOIN class_level AS c ON stu.class_level=c.level_id INNER JOIN section AS s ON c.section_id=s.section_id ORDER BY stu_id DESC") or die($db->error);
			if($get_student->num_rows) {
				$result .= "<table class='table table-bordered' id='student_tb'>
							<thead>
								<tr>
									<th>#</th>
									<th>ADM NO</th>
									<th>Name</th>
									<th>Class</th>
									<th>sex</th>
									<th>State</th>
									<th>Lga</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>";
				$x = 1;
				while($row = $get_student->fetch_assoc()) {
					$result .= " <tr>
									<td>".$x."</td>
									<td>".escape(strtoupper($row['admission_no']))."</td>
									<td>".escape(strtoupper($row['lname'])).", ".escape(ucwords($row['mname']))." ".escape(ucwords($row['fname']))."</td>
									<td>".escape(strtoupper($row['level']))."".escape(strtoupper($row['class_name']))."</td>
									<td>".escape(ucwords($row['gender']))."</td>
									<td>".escape(ucwords($row['state']))."</td>
									<td>".escape(ucwords($row['lga']))."</td>
									<td>
										<a href='edit_student.php?student=".escape($row['admission_no'])."' class='btn btn-custom'><i class='fa fa-pencil'></i></a>
										<a href='collect_fee.php?student=".escape($row['admission_no'])."' class='btn btn-success'><i class='fa fa-paypal'></i></a>
									</td>
								</tr>";
					$x++;
				}
				$result .= "</tbody></table>";
			}else{
				$result = "<br/>".messageFormat("danger customized", "<i class='fa fa-warning'></i> You have not recorded any Students yet. Please click on the 'add student' tab to register a new student.");
				echo $result;
			}
		}
		
		/////////////////////////////////////////////////////////////
		////////////////New Student Entry///////////////////////////
		if(isset($_POST['esadmin_no']) && escape($_POST['esadmin_no']) != "") {
			$flag			= false;
			$error 			= array();
			$lname 			= escape(strtolower($_POST['eslname']));
			$mname 			= escape(strtolower($_POST['esmname']));
			$fname 			= escape(strtolower($_POST['esfname']));
			$dob 			= escape($_POST['esdob']);
			$nation 		= escape(strtolower($_POST['esnationality']));
			$state 			= escape(strtolower($_POST['esstate']));
			$lga 			= escape(strtolower($_POST['eslga']));
			$gender 		= escape(strtolower($_POST['esgender']));
			$religion 		= escape(strtolower($_POST['esreligion']));
			$admin_no 		= escape(strtolower($_POST['esadmin_no']));
			$section 		= escape(strtolower($_POST['essection']));
			$class_level 	= escape(strtolower($_POST['esclass_level']));
			$alpha 			= escape(strtolower($_POST['esalpha']));
			$admin_date 	= escape(strtolower($_POST['esadmission_date']));
			$father 		= escape(strtolower($_POST['esfather']));
			$fa_occu 		= escape(strtolower($_POST['esfather_occu']));
			$mother 		= escape(strtolower($_POST['esmother']));
			$mo_occu 		= escape(strtolower($_POST['esmother_occu']));
			$mobile 		= escape(strtolower($_POST['esmobile']));
			$email 			= escape(strtolower($_POST['esemail']));
			$address 		= escape(strtolower($_POST['esaddress']));
			$allowed_ext 	= array("JPEG", "jpeg", "jpg", "JPG", "PNG", "png");
			$img_path 		= "";
			
			if($lname == "" || $fname == "" || $dob == "" || $nation == "" || $gender == "" || $religion == "") {
				$error[] = "Incomplete Basic details. Click on the basic details tab and fill all the required fields marked with asterisk '*'";
			}
			
			if($nation == "nigerian" && ($state == "" || $lga == "")) {
				$error[] = "Your nationality is Nigerian and you have not selected your state or local government area.";
			}elseif($nation == "non-nigerian"){
				$state = "";
				$lga = "";
			}
			
			if($admin_no == "" || $section == "" || $class_level == "" || $alpha == "" || $admin_date == "") {
				$error[] = "Incomplete Academic details. Click on the Academic details tab and fill all the required fields marked with asterisk '*'";
			}
			
			if(($fa_occu == "" && $father == "") && ($mother == "" && $mo_occu == "")) {
				$error[] = "Incomplete Guardian information. Click on the Guardian information tab and provide at least complete information about any of the guardian(parents / next of kin)";
			}
			
			if($address == "" || $mobile == "") {
				$error[] = "Incomplete Contact details. Click on the Contact details tab and fill all the required fields marked with asterisk '*'";
			}
			
			if($email != "" && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$error[] = "You have provided an invalid email address. Click on the Contact details tab and correct the email address provided";
			}
			
			if(count($error) > 0) {
				$flag = false;
				$err_res = "";
				$err_res .= "<ol>";
				foreach($error as $err) {
					$err_res .= "<li style='margin-bottom: 13px;'>".$err."</li>";
				}
				$err_res .= "</ol>";
				echo messageFormat("danger customized", "<div style='text-align: left'>".$err_res."</div>");
				$error = array();
			}else{
				$flag = true;
			}
			
			if($flag != false) {
				if(isset($_FILES['espassport']) && escape($_FILES['espassport']['name']) != "") {
					$img_ext = explode(".", escape($_FILES['espassport']['name']));
					if(!in_array($img_ext[count($img_ext)-1], $allowed_ext)) {
						$flag = false;
						echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Invalid image format uploaded, Only PNG, JPG, JPEG files are allowed. Please try again");
					}else{
						$flag = true;
					}
				}
			}
			
			if($flag != false) {
				
				if(isset($_FILES['espassport']) && escape($_FILES['espassport']['name']) != "") {
					$img_path = "img/student/".$admin_no.".jpg";
				}
				
				$save = $db->prepare("UPDATE students SET admission_date=?, lname=?, mname=?, fname=?, gender=?, dob=?, nationality=?, state=?, lga=?, section=?, class_level=?, class_name=?, religion=?, father_name=?, father_occu=?, mother_name=?, mother_occu=?, mobile=?, address=?, email=? WHERE admission_no=?");
				
				$save->bind_param("sssssssssssssssssisss", $admin_date, $lname, $mname, $fname, $gender, $dob, $nation, $state, $lga, $section, $class_level, $alpha, $religion, $father, $fa_occu, $mother, $mo_occu, $mobile, $address, $email, $admin_no);
				
				if($save->execute()) {
					if($img_path != "") {
						$update_pic = $db->query("UPDATE students SET photo='".$img_path."' WHERE admission_no='".$admin_no."' LIMIT 1");
						move_uploaded_file($_FILES['espassport']['tmp_name'], $img_path);
					}
					echo "Operation successful";
				}else{
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Problem encountered. Please try again");
				}
				
			}
			
		}
		
		////////////////////////////////////////////////////////
		/////Check if fee for session and term have been paid///
		if(isset($_POST['fee_adm_no']) && escape($_POST['fee_adm_no']) != "") {
			$admin_no = escape($_POST['fee_adm_no']);
			$section = escape($_POST['fee_section']);
			$level = escape($_POST['fee_level']);
			$result = "";
			$total = 0;
			
			/////////////////////////////////
			/////Gen Receipt No ID///////////////
			$receipt_no = 0;
			$access = $db->query("SELECT access_id FROM student_access ORDER BY access_id DESC LIMIT 1") or die($db->error);
			$row = $staff->fetch_assoc();
			$receipt_no = $row['access_id'] + 1;
			if($receipt_no < 10) {
				$receipt_no = "000".$receipt_no;
			}elseif($receipt_no < 100) {
				$receipt_no = "00".$receipt_no;
			}
			
			if($admin_no != "") {
				$get_active_session = $db->query("SELECT * FROM academic_year WHERE active=1");
				if($get_active_session->num_rows) {
					$row_ac = $get_active_session->fetch_assoc();
					//Get Active session
					$session = escape(strtolower($row_ac['session_year']));
					$term = escape(strtolower($row_ac['term']));
					
					//Get Class fee
					$get_fee = $db->query("SELECT * FROM fee_head WHERE section='".$section."' AND class_level='".$level."'");
					if($get_fee->num_rows) {
						$x = 1;
						while($row_fee = $get_fee->fetch_assoc()) {
							$check_acess = $db->query("SELECT DISTINCT * FROM student_access WHERE admission_no='".$admin_no."' AND session_year='".$session."' AND term='".$term."' AND fee_id='".num_only($row_fee['fee_id'])."'");
							if(!$check_acess->num_rows) {
								$total = $total + num_only($row_fee['amount']);
								$result .= "<tr>
												<td>
													<div class='checkbox checkbox-primary radio-inline'>
														<input type='checkbox' onChange=\"toggle_total('".$admin_no."_".$x."', '".$admin_no."_".$x."_price')\" name='fee_arr[]' id='".$admin_no."_".$x."' value='".num_only($row_fee['fee_id'])."' checked />
														<label for=''>".$x."</label>
													</div>
													<input type='hidden' id='".$admin_no."_".$x."_price' value='".num_only($row_fee['amount'])."' />
												</td>
												<td>".escape($row_fee['fee_type'])."</td>
												<td>".number_format(num_only($row_fee['amount']))."</td>
											</tr>";
							}else{
								
							}
							$x++;
						}
						if($result != "") {
							$get_stu = $db->query("SELECT * FROM students AS stu INNER JOIN class_level AS c ON stu.class_level=c.level_id INNER JOIN section AS s ON c.section_id=s.section_id WHERE stu.admission_no='".$admin_no."' LIMIT 1");
							$row = $get_stu->fetch_assoc();
							$result = "<div class='row'>
											<div class='col-md-6'>
												<p><b>Name:</b> ".escape(strtoupper($row['lname'])).", ".escape(ucwords($row['mname']))." ".escape(ucwords($row['fname']))."</p>
												<p><b>Adm No:</b> ".strtoupper($admin_no)."</p>
												<p><b>Date:</b> ".date('d-m-Y')."</p>
											</div>
											<div class='col-md-6'>
												<p class='text-right'><b>Session:</b> ".$session." </p>
												<p class='text-right'><b>Term:</b> ".ucfirst($term)." </p>
												<p class='text-right'><b>Class:</b> ".escape(strtoupper($row['level']))."".escape(strtoupper($row['class_name']))." </p>
												<input type='hidden' name='recipient' id='recipient' value='".$admin_no."' />
												<input type='hidden' name='psession' id='session' value='".$session."' />
												<input type='hidden' name='pterm' id='term' value='".$term."' />
											</div>
										</div>
										<table class='table'>
											<thead>
												<tr>
													<th>#</th>
													<th>Fee name</th>
													<th>Amount</th>
												</tr>
											</thead>
											<tbody>".$result."
											<tr>
												<th colspan='2' class='text-right'>Total charges</th>
												<td><span id='total_sp'>".number_format(num_only($total))."</span> 
													<input type='hidden' name='total' id='total' value='".num_only($total)."' />
												</td>
											</tr>
											<tr>
												<th colspan='2' class='text-right'>Payment mode</th>
												<td>
													<select name='pay_mode' id='mode' onChange='verify_mode()' class='form-control'>
														<option value='cash'>Cash</option>
														<option value='bank'>Bank</option>
													</select>
												</td>
											</tr>
											<tr>
												<th colspan='2' class='text-right'>Payment Slip No:</th>
												<td>
													<input type='text' size='4' class='form-control' disabled name='slip_no' id='slip_no' value='' placeholder='Slip no' />
												</td>
											</tr>
										</tbody>
									</table>";
							echo $result;
						}else{
							echo "cleared";
						}
					}else{
						echo "no fee head";
					}
				}else{
					echo "academic year not found";
				}
			}
		}
		
		if(isset($_POST['recipient']) && escape($_POST['recipient']) != "" && isset($_POST['fee_arr']) && count($_POST['fee_arr']) > 0) {
			$flag 		= true;
			$admin_no 	= escape(strtolower($_POST['recipient']));
			$total 		= num_only($_POST['total']);
			$pay_mode	= escape(strtolower($_POST['pay_mode']));
			$session	= escape($_POST['psession']);
			$term		= escape(strtolower($_POST['pterm']));
			$slip_no	= "";
			$receipt_no	= "";
			
			if($admin_no != "" && $total != "" && $pay_mode != "" && $session != "" && $term != "") {
				$flag = true;
			}else{
				$flag = false;
				echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Incomplete form data. Please try again");
			}
			
			if($flag != false) {
				foreach($_POST['fee_arr'] as $value) {
					if(num_only($value) == "") {
						$flag = false;
					}else{
						$flag = true;
					}
				}
				if($flag == false) {
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> You have not selected any fee to pay. Please try again");
				}
			}
			
			if($flag != false) {
				if($pay_mode == "bank" && escape($_POST['slip_no']) == "") {
					$flag = false;
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> You have not provided the Bank payment slip no. Please try again");
				}else{
					$flag = true;
				}
			}
			
			if($flag != false) {
				/////////////////////////////////
				/////Gen Receipt No ID///////////////
				$receipt_no = 0;
				$access = $db->query("SELECT access_id FROM student_access ORDER BY access_id DESC LIMIT 1") or die($db->error);
				$row = $access->fetch_assoc();
				$receipt_no = $row['access_id'] + 1;
				if($receipt_no < 10) {
					$receipt_no = "000".$receipt_no;
				}elseif($receipt_no < 100) {
					$receipt_no = "00".$receipt_no;
				}
				
				$slip_no = ($pay_mode == "bank")? escape(strtolower($_POST['slip_no'])) : "";
				
				$values = "VALUES";
				$pdate = date('d-m-Y');
				
				foreach($_POST['fee_arr'] as $fee) {
					$check_acess = $db->query("SELECT DISTINCT * FROM student_access WHERE admission_no='".$admin_no."' AND session_year='".$session."' AND term='".$term."' AND fee_id='".$fee."'");
					if(!$check_acess->num_rows) {
						$values .= "('".$admin_no."', '".$session."', '".$term."', '".$fee."', '".$slip_no."', '".$receipt_no."', '".$pdate."', '".$pay_mode."'),";
					}
				}
				$values = rtrim($values, ',');
				
				if($values != "VALUES") {
					$pay = $db->query("INSERT INTO student_access(admission_no, session_year, term, fee_id, slip_no, receipt_no, pay_date, mode) ".$values."");
					if($pay) {
						echo "receipt.php?student=".$admin_no."&no=".$receipt_no."";
					}else{
						echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Problem encountered. Please try again");
					}
				}else{
					echo messageFormat("success customized", "<i class='fa fa-warning'></i> Student has already been clear for this session and term. To collect fees of previous sessions, go to collect fees page");
				}
				
			}
			
		}
		
		///////////////////////////////////////////////////
		////////////Fetch students for fee collection/////
		if(isset($_POST['csession']) && escape($_POST['csession']) != "" && isset($_POST['cterm']) && escape($_POST['cterm']) != "") {
			$session 	= escape($_POST['csession']);
			$term 		= escape(strtolower($_POST['cterm']));
			$section 	= num_only($_POST['csection']);
			$class_lev 	= num_only($_POST['clevel']);
			$alpha 		= escape($_POST['calpha']);
			$flag 		= true;
			
			if($session != "" && $term != "" && $section != "" && $class_lev != "") {
				$flag = true;
			}else{
				$flag = false;
			}
			
			if($flag != false) {
				$result = "";
				$get_student = $db->query("SELECT * FROM students AS stu INNER JOIN class_level AS c ON stu.class_level=c.level_id INNER JOIN section AS s ON c.section_id=s.section_id WHERE stu.section='".$section."' AND stu.class_level='".$class_lev."' AND stu.class_name LIKE '%".$alpha."%' ORDER BY stu_id DESC") or die($db->error);
				if($get_student->num_rows) {
					$result .= "<table class='table table-bordered' id='student_tb'>
								<thead>
									<tr>
										<th>#</th>
										<th>ADM NO</th>
										<th>Name</th>
										<th>Class</th>
										<th>sex</th>
										<th>State</th>
										<th>Lga</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>";
					$x = 1;
					while($row = $get_student->fetch_assoc()) {
						$result .= " <tr>
										<td>".$x."</td>
										<td>".escape(strtoupper($row['admission_no']))."</td>
										<td>".escape(strtoupper($row['lname'])).", ".escape(ucwords($row['mname']))." ".escape(ucwords($row['fname']))."</td>
										<td>".escape(strtoupper($row['level']))."".escape(strtoupper($row['class_name']))."</td>
										<td>".escape(ucwords($row['gender']))."</td>
										<td>".escape(ucwords($row['state']))."</td>
										<td>".escape(ucwords($row['lga']))."</td>
										<td>
											<a href='#' onclick=\"collect_fee_modal('".escape($row['admission_no'])."', '".$session."', '".$term."', '".$section."', '".$class_lev."'); return false\" class='btn btn-success'><i class='fa fa-paypal'></i></a>
										</td>
									</tr>";
						$x++;
					}
					$result .= "</tbody></table>";
					echo $result;
				}else{
					$result = "<br/>".messageFormat("danger customized", "<i class='fa fa-warning'></i> You have not recorded any Students yet. Please click on the 'add student' tab to register a new student.");
					echo $result;
				}
			}
			
		}
		
		////////////////////////////////////////////////////////////////////////////
		/////Check if fee for session and term have been paid for fee collection///
		if(isset($_POST['f_adm_no']) && escape($_POST['f_adm_no']) != "") {
			$admin_no = escape($_POST['f_adm_no']);
			$section = escape($_POST['f_section']);
			$session = escape($_POST['f_session']);
			$term = escape($_POST['f_term']);
			$level = escape($_POST['f_level']);
			$result = "";
			$total = 0;
			
			if($admin_no != "" && $session != "" && $term != "") {
				$get_active_session = $db->query("SELECT * FROM academic_year WHERE session_year='".$session."' AND term='".$term."' LIMIT 1");
				if($get_active_session->num_rows) {
					$row_ac = $get_active_session->fetch_assoc();
					//Get Active session
					$session = escape(strtolower($row_ac['session_year']));
					$term = escape(strtolower($row_ac['term']));
					
					//Get Class fee
					$get_fee = $db->query("SELECT * FROM fee_head WHERE section='".$section."' AND class_level='".$level."'");
					if($get_fee->num_rows) {
						$x = 1;
						while($row_fee = $get_fee->fetch_assoc()) {
							$check_acess = $db->query("SELECT DISTINCT * FROM student_access WHERE admission_no='".$admin_no."' AND session_year='".$session."' AND term='".$term."' AND fee_id='".num_only($row_fee['fee_id'])."'");
							if(!$check_acess->num_rows) {
								$total = $total + num_only($row_fee['amount']);
								$result .= "<tr>
												<td>
													<div class='checkbox checkbox-primary radio-inline'>
														<input type='checkbox' onChange=\"toggle_total('".$admin_no."_".$x."', '".$admin_no."_".$x."_price')\" name='fee_arr[]' id='".$admin_no."_".$x."' value='".num_only($row_fee['fee_id'])."' checked />
														<label for=''>".$x."</label>
													</div>
													<input type='hidden' id='".$admin_no."_".$x."_price' value='".num_only($row_fee['amount'])."' />
												</td>
												<td>".escape($row_fee['fee_type'])."</td>
												<td>".number_format(num_only($row_fee['amount']))."</td>
											</tr>";
							}else{
								
							}
							$x++;
						}
						if($result != "") {
							$get_stu = $db->query("SELECT * FROM students AS stu INNER JOIN class_level AS c ON stu.class_level=c.level_id INNER JOIN section AS s ON c.section_id=s.section_id WHERE stu.admission_no='".$admin_no."' LIMIT 1");
							$row = $get_stu->fetch_assoc();
							$result = "<div class='row'>
											<div class='col-md-6'>
												<p><b>Name:</b> ".escape(strtoupper($row['lname'])).", ".escape(ucwords($row['mname']))." ".escape(ucwords($row['fname']))."</p>
												<p><b>Adm No:</b> ".strtoupper($admin_no)."</p>
												<p><b>Date:</b> ".date('d-m-Y')."</p>
											</div>
											<div class='col-md-6'>
												<p class='text-right'><b>Session:</b> ".$session." </p>
												<p class='text-right'><b>Term:</b> ".ucfirst($term)." </p>
												<p class='text-right'><b>Class:</b> ".escape(strtoupper($row['level']))."".escape(strtoupper($row['class_name']))." </p>
												<input type='hidden' name='recipient' id='recipient' value='".$admin_no."' />
												<input type='hidden' name='psession' id='session' value='".$session."' />
												<input type='hidden' name='pterm' id='term' value='".$term."' />
											</div>
										</div>
										<table class='table'>
											<thead>
												<tr>
													<th>#</th>
													<th>Fee name</th>
													<th>Amount</th>
												</tr>
											</thead>
											<tbody>".$result."
											<tr>
												<th colspan='2' class='text-right'>Total charges</th>
												<td><span id='total_sp'>".number_format(num_only($total))."</span> 
													<input type='hidden' name='total' id='total' value='".num_only($total)."' />
												</td>
											</tr>
											<tr>
												<th colspan='2' class='text-right'>Payment mode</th>
												<td>
													<select name='pay_mode' id='mode' onChange='verify_mode()' class='form-control'>
														<option value='cash'>Cash</option>
														<option value='bank'>Bank</option>
													</select>
												</td>
											</tr>
											<tr>
												<th colspan='2' class='text-right'>Payment Slip No:</th>
												<td>
													<input type='text' size='4' class='form-control' disabled name='slip_no' id='slip_no' value='' placeholder='Slip no' />
												</td>
											</tr>
										</tbody>
									</table>";
							echo $result;
						}else{
							echo "cleared";
						}
					}else{
						echo "no fee head";
					}
				}else{
					echo "academic year not found";
				}
			}
		}
		
		////////////////////////////////////////////////////////////
		//////////////Fetch classes and level using section_id//////////////////
		if(isset($_POST['ass_level']) && num_only($_POST['ass_level']) != "") {
			$level = num_only($_POST['ass_level']);
			$class_level = "";
			$suffix_arr = array('A', 'B', 'C', 'D', 'E', 'F');
			
			if($level != "") {
				$get_level = $db->query("SELECT * FROM class_level WHERE section_id='".$level."'");
				if($get_level->num_rows) {
					$x = 1;
					while($row = $get_level->fetch_assoc()) {
						
						$class_level .= "<div>";
						for($y = 0; $y < count($suffix_arr); $y++) {
							if($x == 1 && $y == 0) {
								$class_level .= "<div class='checkbox checkbox-warning checkbox-inline'>
													<input type='checkbox' name='suffix[]' id='' value='".num_only($row['level_id'])."".$suffix_arr[$y]."' checked>
													<label for=''>
														<strong>".escape(strtoupper($row['level']))."".$suffix_arr[$y]."</strong>
													</label>
												</div>";
							}else{
								$class_level .= "<div class='checkbox checkbox-warning checkbox-inline'>
													<input type='checkbox' name='suffix[]' id='' value='".num_only($row['level_id'])."".$suffix_arr[$y]."'>
													<label for=''>
														<strong>".escape(strtoupper($row['level']))."".$suffix_arr[$y]."</strong>
													</label>
												</div>";
							}
						}
						$class_level .= "</div><br/>";
						
						$x++;
					}
					echo $class_level;
					
				}else{
					$class_level = messageFormat("danger customized", "<i class='fa fa-warning'></i> Classes for the selected section are yet to be added.");
					echo $class_level;
				}
			}else{
				$class_level = messageFormat("danger customized", "<i class='fa fa-warning'></i> Classes for the selected section are yet to be added.");
				echo $class_level;
			}
		}
		
		/////////////////////////////////////////////
		////////////Assign Subject Teacher///////////
		if(isset($_POST['tch_id']) && escape($_POST['tch_id']) != "" && isset($_POST['suffix']) && count($_POST['suffix']) > 0 && escape($_POST['suffix'][0] != "")) {
			//Array ( [tch_id] => SCH-001 [ass_subject] => 3 [ass_section] => 2 [suffix] => Array ( [0] => 2A ) )
			$staff_id 	= escape($_POST['tch_id']);
			$subject 	= num_only($_POST['ass_subject']);
			$section 	= num_only($_POST['ass_section']);
			$flag 		= true;
			$values		= "VALUES";
			$count		= 0;
			
			if($staff_id != "" && $subject != "" && $section != "") {
				$flag = true;
			}else{
				$flag = false;
				echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Incomplete form data. You have not selected a staff or subject or section. Please try again");
			}
			
			if($flag != false) {
				foreach($_POST['suffix'] as $value) {
					if(escape($value) == "") {
						$flag = false;
					}else{
						$flag = true;
						$count += 1;
					}
				}
				if($flag == false) {
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> You have not selected any class for that the subject will be taught. Please try again");
				}
			}
			
			if($flag != false) {
				$check_cnt = $db->query("SELECT DISTINCT subject FROM assigned_subjects WHERE staff_id='".$staff_id."'");
				if($check_cnt->num_rows > 4) {
					$flag = false;
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> You cannot assign more than four(4) subjects to one teacher. Please try again");
				}else{
					$flag = true;
				}
			}
			
			if($flag != false) {
				foreach($_POST['suffix'] as $val) {
					$check_ass = $db->query("SELECT DISTINCT * FROM assigned_subjects WHERE subject='".$subject."' AND section='".$section."' AND class_level='".$val."' AND staff_id='".$staff_id."'");
					if(!$check_ass->num_rows) {
						$values .= "('".$subject."', '".$section."', '".$val."', '".$staff_id."'),";
					}
				}
				if($values != "VALUES") {
					$flag = true;
					$values = rtrim($values, ',');
				}else{
					$flag = false;
				}
			}
			
			if($flag != false) {
				$assign = $db->query("INSERT INTO assigned_subjects(subject, section, class_level, staff_id) ".$values."");
				if($assign) {
					echo "success";
				}else{
					$flag = false;
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Problem encountered. Please try again");
				}
			}
			
		}
		
		///////////////////////////////////////////////////
		///////Fetch data for assigned subject update///////
		if(isset($_POST['staff_ass']) && escape($_POST['staff_ass']) != "" && isset($_POST['sub_ass']) && escape($_POST['sub_ass']) != "" && isset($_POST['section_ass']) && escape($_POST['section_ass']) != "") {
			$section_id = num_only($_POST['section_ass']);
			$staff_id 	= escape(strtoupper($_POST['staff_ass']));
			$subject 	= num_only($_POST['sub_ass']);
			$class_level = "";
			$suffix_arr = array('A', 'B', 'C', 'D', 'E', 'F');
			$ass_sub_arr = array();
			
			if($section_id != "") {
				
				//Fetch already assigned
				$get_ass = $db->query("SELECT * FROM assigned_subjects WHERE subject='".$subject."' AND section='".$section_id."' AND staff_id='".$staff_id."'") or die($db->error);
				
				if($get_ass->num_rows) {
					while($rs = $get_ass->fetch_assoc()) {
						$ass_sub_arr[] = escape(strtoupper($rs['class_level']));
					}
				}
				
				$get_level = $db->query("SELECT * FROM class_level WHERE section_id='".$section_id."'");
				if($get_level->num_rows) {
					$x = 1;
					while($row = $get_level->fetch_assoc()) {
						
						$class_level .= "<div>";
						for($y = 0; $y < count($suffix_arr); $y++) {
							$check = num_only($row['level_id']).$suffix_arr[$y];
							if(!in_array($check, $ass_sub_arr)) {
								$class_level .= "<div class='checkbox checkbox-warning checkbox-inline'>
													<input type='checkbox' name='esuffix[]' id='' value='".num_only($row['level_id'])."".$suffix_arr[$y]."'>
													<label for=''>
														<strong>".escape(strtoupper($row['level']))."".$suffix_arr[$y]."</strong>
													</label>
												</div>";
							}else{
								$class_level .= "<div class='checkbox checkbox-warning checkbox-inline'>
													<input type='checkbox' name='esuffix[]' id='' value='".num_only($row['level_id'])."".$suffix_arr[$y]."' checked>
													<label for=''>
														<strong>".escape(strtoupper($row['level']))."".$suffix_arr[$y]."</strong>
													</label>
												</div>";
							}
						}
						$class_level .= "</div><br/>";
						
						$x++;
					}
					echo $class_level;
					
				}else{
					$class_level = messageFormat("danger customized", "<i class='fa fa-warning'></i> Classes for the selected section are yet to be added.");
					echo $class_level;
				}
			}else{
				$class_level = messageFormat("danger customized", "<i class='fa fa-warning'></i> Classes for the selected section are yet to be added.");
				echo $class_level;
			}
		}
		
		/////////////////////////////////////////////////////////
		/////////////////Load all Assigned subjects///////////////
		if(Isset($_POST['get_ass_sub']) && escape($_POST['get_ass_sub']) == "yes") {
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
				echo $result_sub;
			}else{
				$result_sub = "<br/>".messageFormat("danger customized", "<i class='fa fa-warning'></i> You have not assigned any subjects yet. Please click on the 'Assign Subject Teacher' tab to assign a subject to a teacher.");
				echo $result_sub;
			}
		}
		
		//////////////////////////////////////////////////////
		////////////Update Assigned Subject Teacher///////////
		if(isset($_POST['etch_id']) && escape($_POST['etch_id']) != "" && isset($_POST['esuffix']) && count($_POST['esuffix']) > 0 && escape($_POST['esuffix'][0] != "")) {
			//Array ( [tch_id] => SCH-001 [ass_subject] => 3 [ass_section] => 2 [suffix] => Array ( [0] => 2A ) )
			$staff_id 	= escape($_POST['etch_id']);
			$subject 	= num_only($_POST['eass_subject']);
			$section 	= num_only($_POST['eass_section']);
			$staff_id_hd = escape($_POST['etch_id_hide']);
			$subject_hd = num_only($_POST['eass_subject_hide']);
			$section_hd = num_only($_POST['eass_section_hide']);
			$flag 		= true;
			$values		= "VALUES";
			$count		= 0;
			
			if($staff_id != "" && $subject != "" && $section != "" && $staff_id_hd != "" && $subject_hd != "" && $section_hd != "") {
				$flag = true;
			}else{
				$flag = false;
				echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Incomplete form data. You have not selected a staff or subject or section. Please try again");
			}
			
			if($flag != false) {
				foreach($_POST['esuffix'] as $value) {
					if(escape($value) == "") {
						$flag = false;
					}else{
						$flag = true;
						$count += 1;
					}
				}
				if($flag == false) {
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> You have not selected any class for that the subject will be taught. Please try again");
				}
			}
			
			if($flag != false) {
				$check_cnt = $db->query("SELECT DISTINCT subject FROM assigned_subjects WHERE staff_id='".$staff_id."'");
				if($check_cnt->num_rows > 4) {
					$flag = false;
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> You cannot assign more than four(4) subjects to one teacher. Please try again");
				}else{
					$flag = true;
				}
			}
			
			if($flag != false) {
				$remove_prev = $db->query("DELETE FROM assigned_subjects WHERE subject='".$subject_hd."' AND section='".$section_hd."' AND staff_id='".$staff_id_hd."'") or die($db->error);
				if($remove_prev) {
					$flag = true;
				}else{
					$flag = false;
				}
			}
			
			if($flag != false) {
				foreach($_POST['esuffix'] as $val) {
					$check_ass = $db->query("SELECT DISTINCT * FROM assigned_subjects WHERE subject='".$subject."' AND section='".$section."' AND class_level='".$val."' AND staff_id='".$staff_id."'");
					if(!$check_ass->num_rows) {
						$values .= "('".$subject."', '".$section."', '".$val."', '".$staff_id."'),";
					}
				}
				if($values != "VALUES") {
					$flag = true;
					$values = rtrim($values, ',');
				}else{
					$flag = false;
				}
			}
			
			if($flag != false) {
				$assign = $db->query("INSERT INTO assigned_subjects(subject, section, class_level, staff_id) ".$values."");
				if($assign) {
					echo "success";
				}else{
					$flag = false;
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Problem encountered. Please try again");
				}
			}
			
		}
		
		////////////////////////////////////////////////////////////////////////////
		////Fetch classes and level using section_id for class teacher assignment////
		if(isset($_POST['cl_level']) && num_only($_POST['cl_level']) != "") {
			$level = num_only($_POST['cl_level']);
			$class_level = "";
			$suffix_arr = array('A', 'B', 'C', 'D', 'E', 'F');
			
			if($level != "") {
				$get_level = $db->query("SELECT * FROM class_level WHERE section_id='".$level."'");
				if($get_level->num_rows) {
					$x = 1;
					while($row = $get_level->fetch_assoc()) {
						
						$class_level .= "<div>";
						for($y = 0; $y < count($suffix_arr); $y++) {
							if($x == 1 && $y == 0) {
								$class_level .= "<div class='radio radio-warning radio-inline'>
													<input type='radio' name='csuffix' id='' value='".num_only($row['level_id'])."".$suffix_arr[$y]."' checked>
													<label for=''>
														<strong>".escape(strtoupper($row['level']))."".$suffix_arr[$y]."</strong>
													</label>
												</div>";
							}else{
								$class_level .= "<div class='radio radio-warning radio-inline'>
													<input type='radio' name='csuffix' id='' value='".num_only($row['level_id'])."".$suffix_arr[$y]."'>
													<label for=''>
														<strong>".escape(strtoupper($row['level']))."".$suffix_arr[$y]."</strong>
													</label>
												</div>";
							}
						}
						$class_level .= "</div><br/>";
						
						$x++;
					}
					echo $class_level;
					
				}else{
					$class_level = messageFormat("danger customized", "<i class='fa fa-warning'></i> Classes for the selected section are yet to be added.");
					echo $class_level;
				}
			}else{
				$class_level = messageFormat("danger customized", "<i class='fa fa-warning'></i> Classes for the selected section are yet to be added.");
				echo $class_level;
			}
		}
		
		/////////////////////////////////////////////
		////////////Assign Class Teacher///////////
		if(isset($_POST['ctch_id']) && escape($_POST['ctch_id']) != "" && isset($_POST['csuffix']) && escape($_POST['csuffix']) != "") {
			$staff_id 	= escape($_POST['ctch_id']);
			$section 	= num_only($_POST['cl_section']);
			$suffix 	= escape(strtoupper($_POST['csuffix']));
			$flag 		= true;
			
			if($staff_id != "" && $suffix != "" && $section != "") {
				$flag = true;
			}else{
				$flag = false;
				echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Incomplete form data. You have not selected a staff or class or section. Please try again");
			}
			
			if($flag != false) {
				$check_cnt = $db->query("SELECT DISTINCT * FROM assigned_classes WHERE staff_id='".$staff_id."'");
				if($check_cnt->num_rows) {
					$flag = false;
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> A class has already been assigned to this techer. You cannot assign more than one class to a teacher. Please try again");
				}else{
					$flag = true;
				}
			}
			
			if($flag != false) {
				$assign = $db->query("INSERT INTO assigned_classes(section, class_level, staff_id) VALUES('".$section."', '".$suffix."', '".$staff_id."')");
				if($assign) {
					echo "success";
				}else{
					$flag = false;
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Problem encountered. Please try again");
				}
			}
			
		}
		
		////////////////////////////////////////////////////////
		////Fetch data for assigned class teachers update///////
		if(isset($_POST['staff_ass_cl']) && escape($_POST['staff_ass_cl']) != "" && isset($_POST['section_ass_cl']) && escape($_POST['section_ass_cl']) != "") {
			$section_id = num_only($_POST['section_ass_cl']);
			$staff_id 	= escape(strtoupper($_POST['staff_ass_cl']));
			$class_level = "";
			$suffix_arr = array('A', 'B', 'C', 'D', 'E', 'F');
			$ass_sub_arr = array();
			
			if($section_id != "") {
				
				//Fetch already assigned
				$get_ass = $db->query("SELECT * FROM assigned_classes WHERE section='".$section_id."' AND staff_id='".$staff_id."'") or die($db->error);
				
				if($get_ass->num_rows) {
					while($rs = $get_ass->fetch_assoc()) {
						$ass_sub_arr[] = escape(strtoupper($rs['class_level']));
					}
				}
				
				$get_level = $db->query("SELECT * FROM class_level WHERE section_id='".$section_id."'");
				if($get_level->num_rows) {
					$x = 1;
					while($row = $get_level->fetch_assoc()) {
						
						$class_level .= "<div>";
						for($y = 0; $y < count($suffix_arr); $y++) {
							$check = num_only($row['level_id']).$suffix_arr[$y];
							if(!in_array($check, $ass_sub_arr)) {
								$class_level .= "<div class='radio radio-warning radio-inline'>
													<input type='radio' name='ecsuffix' id='' value='".num_only($row['level_id'])."".$suffix_arr[$y]."'>
													<label for=''>
														<strong>".escape(strtoupper($row['level']))."".$suffix_arr[$y]."</strong>
													</label>
												</div>";
							}else{
								$class_level .= "<div class='radio radio-warning checkbox-inline'>
													<input type='radio' name='ecsuffix' id='' value='".num_only($row['level_id'])."".$suffix_arr[$y]."' checked>
													<label for=''>
														<strong>".escape(strtoupper($row['level']))."".$suffix_arr[$y]."</strong>
													</label>
												</div>";
							}
						}
						$class_level .= "</div><br/>";
						
						$x++;
					}
					echo $class_level;
					
				}else{
					$class_level = messageFormat("danger customized", "<i class='fa fa-warning'></i> Classes for the selected section are yet to be added.");
					echo $class_level;
				}
			}else{
				$class_level = messageFormat("danger customized", "<i class='fa fa-warning'></i> Classes for the selected section are yet to be added.");
				echo $class_level;
			}
		}
		
		///////////////////////////////////////////////////////////
		//////////////Load all assigned class teachers/////////////
		if(Isset($_POST['get_ass_cl']) && escape($_POST['get_ass_cl']) == "yes") {
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
				echo $result;
			}else{
				$result = "<br/>".messageFormat("danger customized", "<i class='fa fa-warning'></i> You have not assigned any classes yet. Please click on the 'Assign Class Teacher' tab to assign a subject to a teacher.");
				echo $result;
			}
		}
		
		///////////////////////////////////////////////
		///////Update Assigned Class Teacher///////////
		if(isset($_POST['ectch_id']) && escape($_POST['ectch_id']) != "" && isset($_POST['ecsuffix']) && escape($_POST['ecsuffix']) != "") {
			$staff_id 	= escape($_POST['ectch_id']);
			$staff_id_hd = escape($_POST['ectch_id_hide']);
			$section 	= num_only($_POST['ecl_section']);
			$suffix 	= escape(strtoupper($_POST['ecsuffix']));
			$flag 		= true;
			
			if($staff_id != "" && $staff_id_hd != "" && $suffix != "" && $section != "") {
				$flag = true;
			}else{
				$flag = false;
				echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Incomplete form data. You have not selected a staff or class or section. Please try again");
			}
			
			if($flag != false) {
				$assign = $db->query("UPDATE assigned_classes SET section='".$section."', class_level='".$suffix."', staff_id='".$staff_id."' WHERE staff_id='".$staff_id_hd."'") or die($db->error);
				if($assign) {
					echo "success";
				}else{
					$flag = false;
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Problem encountered. Please try again");
				}
			}
			
		}
		
		///////////////////////////////////////////////////////////////
		////////////Remove assigned subjects//////////////////////////
		if(isset($_POST['staffID_ass_to_remove']) && escape($_POST['staffID_ass_to_remove']) != "" && isset($_POST['sub_ass_to_remove']) && num_only($_POST['sub_ass_to_remove']) != "") {
			$staff_id = escape($_POST['staffID_ass_to_remove']);
			$subject = num_only($_POST['sub_ass_to_remove']);
			
			if($staff_id != "" && $subject != "") {
				$delete = $db->query("DELETE FROM assigned_subjects WHERE staff_id='".$staff_id."' AND subject='".$subject."'");
				if($delete) {
					echo "Operation successful";
				}else{
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Problem encountered. Please try again");
				}
			}
		}
		
		///////////////////////////////////////////////////////////////
		////////////Remove assigned class teacher//////////////////
		if(isset($_POST['staffID_ass_to_remove_cl']) && escape($_POST['staffID_ass_to_remove_cl']) != "" && isset($_POST['ID_ass_to_remove_cl']) && num_only($_POST['ID_ass_to_remove_cl']) != "") {
			$staff_id = escape($_POST['staffID_ass_to_remove_cl']);
			$ass_id = num_only($_POST['ID_ass_to_remove_cl']);
			
			if($staff_id != "" && $ass_id != "") {
				$delete = $db->query("DELETE FROM assigned_classes WHERE staff_id='".$staff_id."' AND assign_id='".$ass_id."'") or die($db->error);
				if($delete) {
					echo "Operation successful";
				}else{
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Problem encountered. Please try again");
				}
			}
		}
		
		//////////////////////////////////////////////////////
		///////////Fetch classes offering a subject ////////////
		if(isset($_POST['sub_marks_cl']) && num_only($_POST['sub_marks_cl']) != "" && isset($_POST['staff_marks_cl']) && escape($_POST['staff_marks_cl']) != "") {
			$subject = num_only($_POST['sub_marks_cl']);
			$staffID = escape($_POST['staff_marks_cl']);
			$cl_option = "<option value=''>Select</option>";
			
			if($subject != "" && $staff != "") {
				$get = $db->query("SELECT class_level FROM assigned_subjects WHERE subject='".$subject."' AND staff_id='".$staffID."' ORDER BY section ASC");
				if($get->num_rows) {
					while($row = $get->fetch_assoc()) {
						$qry = $db->query("SELECT level FROM class_level WHERE level_id='".num_only($row['class_level'])."'") or die($db->error);
						$rc = $qry->fetch_assoc();
						
						$cl_option .= "<option value='".num_only($row['class_level'])."".alpha_only(ucfirst($row['class_level']))."'>".escape(strtoupper($rc['level']))."".alpha_only(ucfirst($row['class_level']))."</option>";
						
					}
					echo $cl_option;
				}
			}
		}
		
		////////////////////////////////////////////////////////
		//////////////Fetch term using session_year////////////
		if(isset($_POST['session_mark_cl']) && escape($_POST['session_mark_cl']) != "") {
			$session = escape($_POST['session_mark_cl']);
			
			//Get Term using session
			$te_option = "<option value=''>Select</option>";
			$get_terms = $db->query("SELECT * FROM academic_year WHERE session_year='".$session."'");
			if($get_terms->num_rows) {
				while($row = $get_terms->fetch_assoc()) {
					if($row['active'] == 1) {
						$te_option .= "<option value='".escape($row['term'])."' selected>".escape(strtolower($row['term']))."</option>";
					}else{
						$te_option .= "<option value='".escape($row['term'])."'>".escape(strtolower($row['term']))."</option>";
					}
				}
				echo $te_option;
			}
		}
		
		//////////////////////////////////////////////////////
		///////////Fetch classes offering a subject ////////////
		if(isset($_POST['sub_mark']) && num_only($_POST['sub_mark']) != "" && isset($_POST['staff_mark']) && escape($_POST['staff_mark']) != "" && isset($_POST['cl_mark']) && escape($_POST['cl_mark']) && isset($_POST['session_mark']) && escape($_POST['session_mark']) != "" && isset($_POST['term_mark']) && escape($_POST['term_mark']) != "") {
			$session = escape($_POST['session_mark']);
			$term    = escape(strtolower($_POST['term_mark']));
			$subject = num_only($_POST['sub_mark']);
			$staffID = escape($_POST['staff_mark']);
			$class 	 = escape($_POST['cl_mark']);
			$result  = "";
			
			if($subject != "" && $staffID && $class != "") {
				$flag = true;
			}else{
				$flag = false;
				echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Incomplete form data. You have not selected a subject or class. Please try again");
			}
			
			if($flag != false) {
				$get = $db->query("SELECT * FROM students WHERE class_level='".num_only($class)."' AND class_name='".alpha_only(strtolower($class))."' ORDER BY lname");
				if($get->num_rows) {
					$result = "<table class='table' id='sub_marks_tb'>
								<thead>
									<tr>
										<th>#</th>
										<th>Students</th>
										<th>1st C.A. (20)</th>
										<th>2nd C.A. (20)</th>
										<th>Exams (60)</th>
										<th>Total</th>
										<th>Grade</th>
									</tr>
								</thead>
								<tbody>";
					$x = 1;
					while($row = $get->fetch_assoc()) {
						
						$get_prev_score = $db->query("SELECT * FROM marks WHERE subject_id='".$subject."' AND admission_no='".escape($row['admission_no'])."' AND session='".$session."' AND term='".$term."'") or die($db->error);
						$rw = $get_prev_score->fetch_assoc();
						$ca1 = (num_only($rw['ca_one']) == "")? 0 : num_only($rw['ca_one']);
						$ca2 = (num_only($rw['ca_two']) == "")? 0 : num_only($rw['ca_two']);
						$exam = (num_only($rw['exam']) == "")? 0 : num_only($rw['exam']);
						$total = num_only($ca1 + $ca2 + $exam);
						$grade = (escape($rw['grade']) == "")? "" : strtoupper(escape($rw['grade']));
						$grade_col = "";
						
						if($total < 40 && $total >= 0) {
							$grade = "F";
							$grade_col = "text-danger";
						}else if($total >= 40 && $total < 50) {
							$grade = "D";
							$grade_col = "text-custom";
						}else if($total >= 50 && $total < 60) {
							$grade = "C";
						}else if($total < 70 && $total >= 60) {
							$grade = "B";
							$grade_col = "text-primary";
						}else if($total >= 70 && $total <= 100) {
							$grade = "A";
							$grade_col = "text-primary";
						}
						
						$result .= "<tr>
										<td>".$x."</td>
										<td>".escape(strtoupper($row['lname'])).", ".escape(ucwords($row['mname']))." ".escape(ucwords($row['fname']))."
											<input type='hidden' name='admin_no[]' value='".escape($row['admission_no'])."' />
										</td>
										<td><input type='text' name='".escape($row['admission_no'])."_ca1' id='".escape($row['admission_no'])."_ca1' onChange=\"sum_scores('".escape($row['admission_no'])."_ca1', '".escape($row['admission_no'])."_ca2', '".escape($row['admission_no'])."_exam', '".escape($row['admission_no'])."_total', '".escape($row['admission_no'])."_grade')\" size='3' value='".$ca1."' /></td>
										
										<td><input type='text' name='".escape($row['admission_no'])."_ca2' id='".escape($row['admission_no'])."_ca2' onChange=\"sum_scores('".escape($row['admission_no'])."_ca1', '".escape($row['admission_no'])."_ca2', '".escape($row['admission_no'])."_exam', '".escape($row['admission_no'])."_total', '".escape($row['admission_no'])."_grade')\" size='3' value='".$ca2."' /></td>
										
										<td><input type='text' name='".escape($row['admission_no'])."_exam' id='".escape($row['admission_no'])."_exam' onChange=\"sum_scores('".escape($row['admission_no'])."_ca1', '".escape($row['admission_no'])."_ca2', '".escape($row['admission_no'])."_exam', '".escape($row['admission_no'])."_total', '".escape($row['admission_no'])."_grade')\" size='3' value='".$exam."' /></td>
										
										<td><input type='text' name='".escape($row['admission_no'])."_total' size='3' id='".escape($row['admission_no'])."_total' value='".$total."' readonly /></td>
										
										<td><input type='text' class='".$grade_col."' name='".escape($row['admission_no'])."_grade' id='".escape($row['admission_no'])."_grade' onChange=\"sum_scores('".escape($row['admission_no'])."_ca1', '".escape($row['admission_no'])."_ca2', '".escape($row['admission_no'])."_exam', '".escape($row['admission_no'])."_total', '".escape($row['admission_no'])."_grade')\" size='3' value='".$grade."' /></td>
									</tr>";
						$x++;
					}
					$result .= "</tbody>
								</table>
								<div class='form-group'>
									<input type='hidden' name='ssession' value='".$session."' />
									<input type='hidden' name='sterm' value='".$term."' />
									<input type='hidden' name='ssubject' value='".$subject."' />
									<button type='submit' name='upload' class='btn btn-custom'><i class='fa fa-cloud-upload text-custom'></i> Upload marks</button>
								</div>";
					echo $result;
				}else{
					$result = messageFormat("danger customized", "<i class='fa fa-warning'></i> No records found for the selected class and subject");
					echo $result;
				}
			}
			
		}
		
		/////////////////////////////////////////////////////////
		////////////////Save Students Marks//////////////////////
		if(isset($_POST['admin_no']) && count($_POST['admin_no']) > 0 && escape($_POST['admin_no'][0]) != "") {
			$values = "VALUES";
			$subject = num_only($_POST['ssubject']);
			$session = escape($_POST['ssession']);
			$term    = escape(strtolower($_POST['sterm']));
			$flag	= true;
			
			if($subject != "" && $session != "" && $term != "") {
				$flag = true;
			}else{
				$flag = false;
			}
			
			if($flag != false) {
				foreach($_POST['admin_no'] as $val) {
					$admin_no = "";
					$ca_one = 0;
					$ca_two = 0;
					$exam 	= 0;
					$grade 	= "";
					
					if(escape($val) != "") {
						$admin_no = escape($val);
						if(num_only($val."_ca1") != "") {
							$ca_one = num_only($_POST[$admin_no."_ca1"]);
							if($ca_one > 20) {
								die(messageFormat("danger customized", "<i class='fa fa-warning'></i> First C.A for student with Admission no: ".strtoupper($admin_no)." cannot be more than 20 marks. Please make sure both first and second C.A are not more than 20 marks and exams is not more than 60 marks"));
							}
						}
						
						if(num_only($val."_ca2") != "") {
							$ca_two = num_only($_POST[$admin_no."_ca2"]);
							if($ca_two > 20) {
								die(messageFormat("danger customized", "<i class='fa fa-warning'></i> Second C.A for student with Admission no: ".strtoupper($admin_no)." cannot be more than 20 marks. Please make sure both first and second C.A are not more than 20 marks and exams is not more than 60 marks"));
							}
						}
						
						if(num_only($val."_exam") != "") {
							$exam = num_only($_POST[$admin_no."_exam"]);
							if($exam > 60) {
								die(messageFormat("danger customized", "<i class='fa fa-warning'></i> Exams score for student with Admission no: ".strtoupper($admin_no)." cannot be more than 60 marks. Please make sure both first and second C.A are not more than 20 marks and exams is not more than 60 marks"));
							}
						}
						
					}
					
				}
			}
			
			if($flag != false) {
				foreach($_POST['admin_no'] as $val) {
					$admin_no = "";
					$ca_one = 0;
					$ca_two = 0;
					$exam 	= 0;
					$grade 	= "";
					
					if(escape($val) != "") {
						$admin_no = escape($val);
						$del_prev = $db->query("DELETE FROM marks WHERE admission_no='".$admin_no."' AND subject_id='".$subject."' AND session='".$session."' AND term='".$term."'");
						
						if(num_only($val."_ca1") != "") {
							$ca_one = num_only($_POST[$admin_no."_ca1"]);
						}
						
						if(num_only($val."_ca2") != "") {
							$ca_two = num_only($_POST[$admin_no."_ca2"]);
						}
						
						if(num_only($val."_exam") != "") {
							$exam = num_only($_POST[$admin_no."_exam"]);
						}
						
						if(escape($val."grade") != "") {
							$grade = strtoupper(escape($_POST[$admin_no."_grade"]));
						}
						
						$values .= "('".$subject."', '".$admin_no."', '".$ca_one."', '".$ca_two."', '".$exam."', '".$grade."', '".$session."', '".$term."'), ";
						
					}
					
				}
			}
			
			if($flag != false) {
				if($values != "VALUES") {
					$values = rtrim($values, ', ');
					$save = $db->query("INSERT INTO marks(subject_id, admission_no, ca_one, ca_two, exam, grade, session, term) ".$values."") or die($db->error);
					if($save) {
						$flag = true;
						echo "Marks successfully updated";
					}else{
						$flag = false;
						echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Problem encountered, marks could not be updated. Please try again");
					}
				}
			}
			
		}
		
		////////////////////////////////////////////////////////////
		//////////Get subjects and scores using admission no.///////
		if(isset($_POST['cl_admin_no']) && escape($_POST['cl_admin_no']) != "" && isset($_POST['cl_session']) && isset($_POST['cl_term'])) {
			$admin_no = escape(strtoupper($_POST['cl_admin_no']));
			$session = escape($_POST['cl_session']);
			$term = escape(strtolower($_POST['cl_term']));
			$flag = true;
			
			if($admin_no != "" && $session != "" && $term != "") {
				$flag = true;
			}else{
				$flag = false;
			}
			
			if($flag != false) {
				$get = $db->query("SELECT * FROM subject_bank ORDER BY subject_name");
				if($get->num_rows) {
					$result = "<br/>
								<table class='table' id='sub_marks_tb'>
								<thead>
									<tr>
										<th>#</th>
										<th>Subjects</th>
										<th>1st C.A. (20)</th>
										<th>2nd C.A. (20)</th>
										<th>Exams (60)</th>
										<th>Total</th>
										<th>Grade</th>
									</tr>
								</thead>
								<tbody>";
					$x = 1;
					while($row = $get->fetch_assoc()) {
						
						$get_prev_score = $db->query("SELECT * FROM marks WHERE subject_id='".num_only($row['subject_id'])."' AND admission_no='".$admin_no."' AND session='".$session."' AND term='".$term."'") or die($db->error);
						$rw = $get_prev_score->fetch_assoc();
						$ca1 = (num_only($rw['ca_one']) == "")? 0 : num_only($rw['ca_one']);
						$ca2 = (num_only($rw['ca_two']) == "")? 0 : num_only($rw['ca_two']);
						$exam = (num_only($rw['exam']) == "")? 0 : num_only($rw['exam']);
						$total = num_only($ca1 + $ca2 + $exam);
						$grade = (escape($rw['grade']) == "")? "" : strtoupper(escape($rw['grade']));
						$grade_col = "";
						
						if($total < 40 && $total >= 0) {
							$grade = "F";
							$grade_col = "text-danger";
						}else if($total >= 40 && $total < 50) {
							$grade = "D";
							$grade_col = "text-custom";
						}else if($total >= 50 && $total < 60) {
							$grade = "C";
						}else if($total < 70 && $total >= 60) {
							$grade = "B";
							$grade_col = "text-primary";
						}else if($total >= 70 && $total <= 100) {
							$grade = "A";
							$grade_col = "text-primary";
						}
						
						$result .= "<tr>
										<td>".$x."</td>
										<td>".escape(ucwords($row['subject_name']))."
											<input type='hidden' name='subject_id[]' value='".escape($row['subject_id'])."' />
										</td>
										<td><input type='text' name='".escape($row['subject_id'])."_ca1' id='".escape($row['subject_id'])."_ca1' onChange=\"sum_scores('".escape($row['subject_id'])."_ca1', '".escape($row['subject_id'])."_ca2', '".escape($row['subject_id'])."_exam', '".escape($row['subject_id'])."_total', '".escape($row['subject_id'])."_grade')\" size='3' value='".$ca1."' /></td>
										
										<td><input type='text' name='".escape($row['subject_id'])."_ca2' id='".escape($row['subject_id'])."_ca2' onChange=\"sum_scores('".escape($row['subject_id'])."_ca1', '".escape($row['subject_id'])."_ca2', '".escape($row['subject_id'])."_exam', '".escape($row['subject_id'])."_total', '".escape($row['subject_id'])."_grade')\" size='3' value='".$ca2."' /></td>
										
										<td><input type='text' name='".escape($row['subject_id'])."_exam' id='".escape($row['subject_id'])."_exam' onChange=\"sum_scores('".escape($row['subject_id'])."_ca1', '".escape($row['subject_id'])."_ca2', '".escape($row['subject_id'])."_exam', '".escape($row['subject_id'])."_total', '".escape($row['subject_id'])."_grade')\" size='3' value='".$exam."' /></td>
										
										<td><input type='text' name='".escape($row['subject_id'])."_total' size='3' id='".escape($row['subject_id'])."_total' value='".$total."' readonly /></td>
										
										<td><input type='text' class='".$grade_col."' name='".escape($row['subject_id'])."_grade' id='".escape($row['subject_id'])."_grade' onChange=\"sum_scores('".escape($row['subject_id'])."_ca1', '".escape($row['subject_id'])."_ca2', '".escape($row['subject_id'])."_exam', '".escape($row['subject_id'])."_total', '".escape($row['subject_id'])."_grade')\" size='3' value='".$grade."' /></td>
									</tr>";
						$x++;
					}
					$result .= "</tbody>
								</table>
								<div class='form-group'>
									<input type='hidden' name='sm_admin_no' id='sm_admin_no' value='".$admin_no."' />
									<button type='submit' name='upload_cl_marks' class='btn btn-custom'><i class='fa fa-cloud-upload text-custom'></i> Upload marks</button>
								</div>";
					echo $result;
				}else{
					$result = messageFormat("danger customized", "<i class='fa fa-warning'></i> No marks record found for the selected student");
					echo $result;
				}
			
			}
			
		}
		
		
		/////////////////////////////////////////////////////////////////////////
		////////////////Save Students Marks for class teacher//////////////////////
		if(isset($_POST['subject_id']) && count($_POST['subject_id']) > 0 && escape($_POST['subject_id'][0]) != "") {
			$values = "VALUES";
			$admin_no = escape(strtolower($_POST['sm_admin_no']));
			$session = escape($_POST['sm_session']);
			$term    = escape(strtolower($_POST['sm_term']));
			$flag	= true;
			
			if($admin_no != "" && $session != "" && $term != "") {
				$flag = true;
			}else{
				$flag = false;
			}
			
			if($flag != false) {
				foreach($_POST['subject_id'] as $val) {
					$subject = "";
					$ca_one = 0;
					$ca_two = 0;
					$exam 	= 0;
					$grade 	= "";
					
					if(escape($val) != "") {
						$subject = escape($val);
						if(num_only($val."_ca1") != "") {
							$ca_one = num_only($_POST[$subject."_ca1"]);
							if($ca_one > 20) {
								die(messageFormat("danger customized", "<i class='fa fa-warning'></i> First C.A for student with Admission no: ".strtoupper($admin_no)." cannot be more than 20 marks. Please make sure both first and second C.A are not more than 20 marks and exams is not more than 60 marks"));
							}
						}
						
						if(num_only($val."_ca2") != "") {
							$ca_two = num_only($_POST[$subject."_ca2"]);
							if($ca_two > 20) {
								die(messageFormat("danger customized", "<i class='fa fa-warning'></i> Second C.A for student with Admission no: ".strtoupper($admin_no)." cannot be more than 20 marks. Please make sure both first and second C.A are not more than 20 marks and exams is not more than 60 marks"));
							}
						}
						
						if(num_only($val."_exam") != "") {
							$exam = num_only($_POST[$subject."_exam"]);
							if($exam > 60) {
								die(messageFormat("danger customized", "<i class='fa fa-warning'></i> Exams score for student with Admission no: ".strtoupper($admin_no)." cannot be more than 60 marks. Please make sure both first and second C.A are not more than 20 marks and exams is not more than 60 marks"));
							}
						}
						
					}
					
				}
			}
			
			if($flag != false) {
				foreach($_POST['subject_id'] as $val) {
					$subject = "";
					$ca_one = 0;
					$ca_two = 0;
					$exam 	= 0;
					$grade 	= "";
					
					if(escape($val) != "") {
						$subject = escape($val);
						$del_prev = $db->query("DELETE FROM marks WHERE admission_no='".$admin_no."' AND subject_id='".$subject."' AND session='".$session."' AND term='".$term."'");
						
						if(num_only($val."_ca1") != "") {
							$ca_one = num_only($_POST[$subject."_ca1"]);
						}
						
						if(num_only($val."_ca2") != "") {
							$ca_two = num_only($_POST[$subject."_ca2"]);
						}
						
						if(num_only($val."_exam") != "") {
							$exam = num_only($_POST[$subject."_exam"]);
						}
						
						if(escape($val."grade") != "") {
							$grade = strtoupper(escape($_POST[$subject."_grade"]));
						}
						
						$values .= "('".$subject."', '".$admin_no."', '".$ca_one."', '".$ca_two."', '".$exam."', '".$grade."', '".$session."', '".$term."'), ";
						
					}
					
				}
			}
			
			if($flag != false) {
				if($values != "VALUES") {
					$values = rtrim($values, ', ');
					$save = $db->query("INSERT INTO marks(subject_id, admission_no, ca_one, ca_two, exam, grade, session, term) ".$values."") or die($db->error);
					if($save) {
						$flag = true;
						echo "Marks successfully updated";
					}else{
						$flag = false;
						echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Problem encountered, marks could not be updated. Please try again");
					}
				}
			}
			
		}
		
		
		////////////////////////////////////////////////////////////////
		////////////Fetch all Emoluments and Deductions/////////////////
		if(isset($_POST['get_ed']) && escape(strtolower($_POST['get_ed'])) == 'yes') {
			$emoluments = "";
			$deductions = "";
			$id_arr  = array();
			$id_str  = "";
			
			$get_emol_deduce = $db->query("SELECT * FROM emoluments_deductions");
			if($get_emol_deduce->num_rows) {
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
				
				echo '<div class="bg_all">
						<div class="row" id="ed_div">
							<div class="col-md-6 emolument">
								<div class="emol_deduce" id="emolument">	
									<h4 class="emol_head">Emoluments</h4>
									'.$emoluments.'
								</div>
							</div>
							<div class="col-md-6 deduction">
								<div class="emol_deduce" id="deduction">	
									<h4 class="emol_head">Deductions</h4>
									'.$deductions.'
								</div>
							</div>
						</div>
						
					</div>
					
					<div class="form-group">
						'.$id_str.'
						<br/><button type="submit" class="btn btn-custom"><i class="fa fa-hand-o-right text-custom"></i> Submit details</button>
					</div>';
				
			}
		}
		
		
		///////////////////////////////////////////////////////////////////
		////////////Add Emolument or Deduction////////////////////////////
		if(isset($_POST['ed_name']) && escape($_POST['ed_name']) != "") {
			$name 	= escape(strtolower($_POST['ed_name']));
			$type 	= escape(strtolower($_POST['ed_type']));
			$per_amt = escape(strtoupper($_POST['ed_per_amt']));
			$flag 	= true;
			
			if($name != "" && $type != "" && $per_amt != "") {
				$flag = true;
			}else{
				$flag = false;
				echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Incomplete form data, All fields required. Please make sure you have filled up all provided field");
			}
			
			if($flag != false) {
				$check = $db->query("SELECT * FROM emoluments_deductions WHERE ed_name='".$name."' AND ed_type='".$type."' LIMIT 1");
				if($check->num_rows) {
					$flag = false;
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> You have already entered the name '".$name."' under ".$type."s Please not that duplicate entries are not allowed");
				}else{
					$flag = true;
				}
			}
			
			if($flag != false) {
				$save = $db->prepare("INSERT INTO emoluments_deductions(ed_name, ed_type, ed_per_amt) VALUES(?,?,?)");
				$save->bind_param("sss", $name, $type, $per_amt);
				if($save->execute()) {
					echo "success";
				}else{
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Problem encountered. Please try again");;
				}
			}
			
		}
		
		///////////////////////////////////////////////////////////////////
		//////////////////////Save staff financial details////////////////
		if(isset($_POST['ed_staff']) && isset($_POST['ed_ids']) && count($_POST['ed_ids']) > 0 && num_only($_POST['ed_ids'][0]) != "") {
			$staff_id = escape($_POST['ed_staff']);
			$flag = true;
			$query_str = "VALUES";
			
			if($staff_id != "") {
				$flag = true;
			}else{
				$flag = false;
				echo messageFormat("danger customized", "<i class='fa fa-warning'></i>  You have not selected a staff. Please select a staff to continue");
			}
			
			if($flag != false) {
				foreach($_POST['ed_ids'] as $val) {
					if(num_only($_POST['_'.$val.'']) != "") {
						$query_str .= "('".$staff_id."', '".$val."', '".num_only($_POST['_'.$val.''])."'),";
					}
				}
				$query_str = rtrim($query_str, ',');
			}
			
			if($flag != false) {
				if($query_str == "VALUES") {
					$flag = false;
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> You have not provided any value to be submitted");
				}else{
					$remove_prev = $db->query("DELETE FROM staff_fin_details WHERE staff_id='".$staff_id."'");
					if($remove_prev) {
						$save = $db->query("INSERT INTO staff_fin_details(staff_id, ed_id, ed_amt) ".$query_str."") or die($db->error);
						
						if($save) {
							$flag = true;
							echo "success";
						}else{
							$flag = false;
							echo messageFormat("danger customized", "<i class='fa fa-warning'></i>  Problem encountered. Please try again");
						}
						
					}
				}
			}
			
		}
		
		////////////////////////////////////////////////////////////////////
		/////////////////Fetch staff financial details with staffID/////////
		if(isset($_POST['ed_staff_fin'])) {
			$staff_id = escape(strtolower($_POST['ed_staff_fin']));
			$json_array = array();
			
			if($staff_id != "") {
				$get_emol_deduce = $db->query("SELECT * FROM emoluments_deductions");
				if($get_emol_deduce->num_rows) {
					while($row = $get_emol_deduce->fetch_assoc()) {
						$get_val = $db->query("SELECT * FROM staff_fin_details WHERE staff_id='".$staff_id."' AND ed_id='".$row['ed_id']."' LIMIT 1") or die($db->error);
						if($get_val->num_rows) {
							$rs = $get_val->fetch_assoc();
							$json_array['_'.num_only($rs['ed_id'])] = num_only($rs['ed_amt']);
						}
					}
					if(count($json_array) > 0) {
						echo json_encode($json_array);
					}
				}
			}
		}
		
		////////////////////////////////////////////////////////////////////
		/////////////Fetch staff salary slip details with staffID///////////
		if(isset($_POST['ed_staff_salary'])) {
			$staff_id = escape(strtolower($_POST['ed_staff_salary']));
			$basic_pay = "";
			$salary = "";
			$st_name = "";
			$emoluments = 0;
			$deductions = 0;
			$em_array = array();
			$de_array = array();
			$ed_array = array();
			
			if($staff_id != "") {
				$get_ed = $db->query("SELECT ed.ed_id, ed.ed_type FROM staff_fin_details AS s INNER JOIN emoluments_deductions AS ed ON s.ed_id=ed.ed_id WHERE staff_id='".$staff_id."' ORDER BY ed_precedence DESC") or die($db->error);
				if($get_ed->num_rows) {
					while($row = $get_ed->fetch_assoc()) {
						if(strtolower(escape($row['ed_type'])) == "emolument") {
							$em_array[] = num_only($row['ed_id']);
						}elseif(strtolower(escape($row['ed_type'])) == "deduction") {
							$de_array[] = num_only($row['ed_id']);
						}
					}
					
					if(count($em_array) > count($de_array)) {
						while(count($de_array) < count($em_array)) {
							$de_array[] = '';
						}
					}elseif(count($de_array > count($em_array))){
						while(count($em_array) < count($de_array)) {
							$em_array[] = '';
						}
					}
					
					for($i = 0; $i < count($em_array); $i++) {
						$ed_array[] = array($em_array[$i], $de_array[$i]);
					}
					
					$count = count($em_array) + count($de_array);
					$x = 1;
					foreach($ed_array as $arr) {
						$salary .= "<tr>";
						foreach($arr as $value) {
							$get_data = $db->query("SELECT s.*, ed.*, st.lname, st.fname, st.mname FROM staff_fin_details AS s INNER JOIN emoluments_deductions AS ed ON s.ed_id=ed.ed_id INNER JOIN staff AS st ON s.staff_id=st.staff_id WHERE s.staff_id='".$staff_id."' AND ed.ed_id='".$value."' LIMIT 1") or die($db->error) or die($db->error);
							if($get_data->num_rows) {
								$rs = $get_data->fetch_assoc();
								$amt = "";
								$amt_str = "";
								$input_str = "";
								$readonly = "";
								$bt_bottom = "";
								
								if(num_only($rs['ed_precedence']) == 1) {
									$basic_pay = num_only($rs['ed_amt']);
									$readonly = "onClick='return false;'";
									$st_name = escape(strtoupper($rs['lname'])).", ".escape(ucwords($rs['mname']))." ".escape(ucwords($rs['fname']));
								}
								
								if(strtolower(escape($rs['ed_type'])) == 'emolument') {
									if(strtoupper(escape($rs['ed_per_amt'])) == "%") {
										$amt = ((num_only($rs['ed_amt'])/100) * $basic_pay);
										$amt_str = (num_only($rs['ed_amt']))." %";
										$emoluments += (num_only($rs['ed_amt'])/100) * $basic_pay;
									}elseif(strtoupper(escape($rs['ed_per_amt'])) == "NGN"){
										$amt = num_only($rs['ed_amt']);
										$amt_str = num_only($rs['ed_amt'])." #";
										$emoluments += num_only($rs['ed_amt']);
									}
									
									$input_str = "<input type='checkbox' ".$readonly." onChange=\"toggle_total_emol('emol_".$value."', 'staff_id', '_".$value."_amt', '_".$value."_view', 'emol_total', 'deduce_total', emol_view, deduce_view, total_view)\" name='salary_arr[]' id='emol_".$value."' value='".$value."' checked />";
								}elseif(strtolower(escape($rs['ed_type'])) == 'deduction'){
									if(strtoupper(escape($rs['ed_per_amt'])) == "%") {
										$amt = ((num_only($rs['ed_amt'])/100) * $basic_pay);
										$amt_str = (num_only($rs['ed_amt']))." %";
										$deductions += (num_only($rs['ed_amt'])/100) * $basic_pay;
									}elseif(strtoupper(escape($rs['ed_per_amt'])) == "NGN"){
										$amt = num_only($rs['ed_amt']);
										$amt_str = num_only($rs['ed_amt'])." #";
										$deductions += num_only($rs['ed_amt']);
									}
									
									$input_str = "<input type='checkbox' onChange=\"toggle_total_deduce('deduce_".$value."', 'staff_id', '_".$value."_amt', '_".$value."_view', 'emol_total', 'deduce_total', emol_view, deduce_view, total_view)\" name='salary_arr[]' id='deduce_".$value."' value='".$value."' checked />";
									
									/*if($x == count($ed_array) && (count($ed_array) * count($arr)) == $count) {
										$bt_bottom = "td_bt_bottom'";
									}*/
								}
								
								$salary .= "<td class='td_right ".$bt_bottom."'>
												<div class='checkbox checkbox-primary checkbox-inline custom_ck'>
													".$input_str."
													<label for='' class='custom_label'>".ucwords(escape($rs['ed_name']))." </label>
												</div>
											</td>
											<td class='td_left text-right ".$bt_bottom."'><span class='td_span' id='_".$value."_view'>".$amt_str."</span></td>
											<td><input type='number' id='_".$value."_amt' readonly value='".num_only($amt)."' class='form_control'></td>";
							}else{
								$salary .= "<td class='td_right ".$bt_bottom."'></td>
											<td class='td_left text-right ".$bt_bottom."'></td>
											<td></td>";
							}
						}
						$salary .= "</tr>";
						$x++;
					}
					
					if($salary != "") {
						$salary = "<table class='table'>
										<tr>
											<th>Designation: </th>
											<td>".$st_name."</td>
										</tr>
										<tr>
											<th>School Name: </th>
											<td>Techguru College and Vocational Center</td>
										</tr>
									</table>
									<table class='table table-bordered bg_all' id='custom_tb'>
									<thead>
										<tr>
											<th width='35%' colspan='2'>EMOLUMENTS</th>
											<th width='15%'>AMOUNT (NGN)</th>
											<th width='35%' colspan='2'>DEDUCTIONS</th>
											<th width='15%'>AMOUNT (NGN)</th>
										</tr>
									</thead>
									<tbody>
									".$salary."
									<tr>
										<td class='td_right'></td>
										<td class='td_left'></td>
										<td></td>
										<th class='td_right text-right'>
											Total Deductions
										</th>
										<td class='td_left'></td>
										<td>
											<input type='hidden' name='deduce_total' value='".num_only($deductions)."' id='deduce_total' />
											<span id='deduce_view'>".number_format(num_only($deductions), 2)."</span>
										</td>
									</tr>
									</tbody>
									<tfoot>
										<tr>
											<th class='text-center' colspan='2'>GROSS PAY</th>
											<th>
												<input type='hidden' name='emol_total' value='".num_only($emoluments)."' id='emol_total' />
												<span id='emol_view'>".number_format(num_only($emoluments), 2)."</span>
											</th>
											<th class='text-center' colspan='2'>NET PAY</th>
											<th><span id='total_view'>".number_format(num_only($emoluments) - num_only($deductions), 2)."</span></th>
										</tr>
									</tfoot>
								</table>
								<div class='text-right'>
									<button type='submit' class='btn btn-custom'><i class='fa fa-paypal text-custom'></i> | Issue payment</button>
								</div>";
								
						echo $salary;
					}
					
				}else{
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i>  You have not created financial details for the selected staff. Please try again");
				}
				
			}
		}
		
		
		///////////////////////////////////////////////////////////
		//////////////Fetch Previous Salary Slip////////////////////
		if(isset($_POST['salary_staff']) && isset($_POST['salary_month']) && isset($_POST['salary_year'])) {
			$staff_id = escape(strtolower($_POST['salary_staff']));
			$month = num_only($_POST['salary_month']);
			$year = num_only($_POST['salary_year']);
			$flag = true;
			$basic_pay = "";
			$paid_date = "";
			$salary = "";
			$st_name = "";
			$emoluments = 0;
			$deductions = 0;
			$em_array = array();
			$de_array = array();
			$ed_array = array();
			
			if($staff_id != "" && $month != "" && $year != "") {
				$flag = true;
			}else{
				$flag = true;
			}
			
			if($flag != false) {
				$get_ed = $db->query("SELECT sl.ed_id, sl.paid_date, ed.ed_type FROM salary_slip AS sl INNER JOIN emoluments_deductions AS ed ON sl.ed_id=ed.ed_id WHERE sl.staff_id='".$staff_id."' AND sl.month='".$month."' AND sl.year='".$year."' ORDER BY ed_precedence DESC") or die($db->error);
				
				if($get_ed->num_rows) {
					while($row = $get_ed->fetch_assoc()) {
						$paid_date = escape($row['paid_date']);
						if(strtolower(escape($row['ed_type'])) == "emolument") {
							$em_array[] = num_only($row['ed_id']);
						}elseif(strtolower(escape($row['ed_type'])) == "deduction") {
							$de_array[] = num_only($row['ed_id']);
						}
					}
					
					if(count($em_array) > count($de_array)) {
						while(count($de_array) < count($em_array)) {
							$de_array[] = '';
						}
					}elseif(count($de_array > count($em_array))){
						while(count($em_array) < count($de_array)) {
							$em_array[] = '';
						}
					}
					
					for($i = 0; $i < count($em_array); $i++) {
						$ed_array[] = array($em_array[$i], $de_array[$i]);
					}
					
					$count = count($em_array) + count($de_array);
					$x = 1;
					foreach($ed_array as $arr) {
						$salary .= "<tr>";
						foreach($arr as $value) {
							$get_data = $db->query("SELECT s.*, ed.*, st.lname, st.fname, st.mname FROM staff_fin_details AS s INNER JOIN emoluments_deductions AS ed ON s.ed_id=ed.ed_id INNER JOIN staff AS st ON s.staff_id=st.staff_id WHERE s.staff_id='".$staff_id."' AND ed.ed_id='".$value."' LIMIT 1") or die($db->error) or die($db->error);
							if($get_data->num_rows) {
								$rs = $get_data->fetch_assoc();
								$amt = "";
								$amt_str = "";
								$bt_bottom = "";
								
								if(num_only($rs['ed_precedence']) == 1) {
									$basic_pay = num_only($rs['ed_amt']);
									$readonly = "onClick='return false;'";
									$st_name = escape(strtoupper($rs['lname'])).", ".escape(ucwords($rs['mname']))." ".escape(ucwords($rs['fname']));
								}
								
								if(strtolower(escape($rs['ed_type'])) == 'emolument') {
									if(strtoupper(escape($rs['ed_per_amt'])) == "%") {
										$amt = ((num_only($rs['ed_amt'])/100) * $basic_pay);
										$amt_str = (num_only($rs['ed_amt']))." %";
										$emoluments += (num_only($rs['ed_amt'])/100) * $basic_pay;
									}elseif(strtoupper(escape($rs['ed_per_amt'])) == "NGN"){
										$amt = num_only($rs['ed_amt']);
										$amt_str = num_only($rs['ed_amt'])." #";
										$emoluments += num_only($rs['ed_amt']);
									}
									
								}elseif(strtolower(escape($rs['ed_type'])) == 'deduction'){
									if(strtoupper(escape($rs['ed_per_amt'])) == "%") {
										$amt = ((num_only($rs['ed_amt'])/100) * $basic_pay);
										$amt_str = (num_only($rs['ed_amt']))." %";
										$deductions += (num_only($rs['ed_amt'])/100) * $basic_pay;
									}elseif(strtoupper(escape($rs['ed_per_amt'])) == "NGN"){
										$amt = num_only($rs['ed_amt']);
										$amt_str = num_only($rs['ed_amt'])." #";
										$deductions += num_only($rs['ed_amt']);
									}
									
								}
								
								$salary .= "<td class='td_right ".$bt_bottom."'>
												<label for='' class='custom_label'>".ucwords(escape($rs['ed_name']))." </label>
											</td>
											<td class='td_left text-right ".$bt_bottom."'><span class='td_span' id='_".$value."_view'>".$amt_str."</span></td>
											<td>".num_only($amt)."</td>";
							}else{
								$salary .= "<td class='td_right ".$bt_bottom."'></td>
											<td class='td_left text-right ".$bt_bottom."'></td>
											<td></td>";
							}
						}
						$salary .= "</tr>";
						$x++;
					}
					
					if($salary != "") {
						$salary = "<table class='table'>
										<tr>
											<th>Designation: </th>
											<td>".$st_name."</td>
										</tr>
										<tr>
											<th>School Name: </th>
											<td>Techguru College and Vocational Center</td>
										</tr>
									</table>
									<table class='table table-bordered bg_all' id='custom_tb'>
									<thead>
										<tr>
											<th width='35%' colspan='2'>EMOLUMENTS</th>
											<th width='15%'>AMOUNT (NGN)</th>
											<th width='35%' colspan='2'>DEDUCTIONS</th>
											<th width='15%'>AMOUNT (NGN)</th>
										</tr>
									</thead>
									<tbody>
									".$salary."
									<tr>
										<td class='td_right'></td>
										<td class='td_left'></td>
										<td></td>
										<th class='td_right text-right'>
											Total Deductions
										</th>
										<td class='td_left'></td>
										<td>
											<span id='deduce_view'>".number_format(num_only($deductions), 2)."</span>
										</td>
									</tr>
									</tbody>
									<tfoot>
										<tr>
											<th class='text-center' colspan='2'>GROSS PAY</th>
											<th>
												<span id='emol_view'>".number_format(num_only($emoluments), 2)."</span>
											</th>
											<th class='text-center' colspan='2'>NET PAY</th>
											<th><span id='total_view'>".number_format(num_only($emoluments) - num_only($deductions), 2)."</span></th>
										</tr>
									</tfoot>
								</table>
								<div class='text-right'>
									<button type='button' onClick=\"discard_payment('".$staff_id."', '".$month."', '".$year."')\" name='delete' class='btn btn-danger'><i class='fa fa-trash'></i> | Discard</button>
									<a href='payment_slip?staff_id=".$staff_id."&&month=".$month."&&year=".$year."' target='_blank' class='btn btn-custom'><i class='fa fa-print text-custom'></i> | Print receipt</a>
								</div>";
								
						echo $salary;
					}
				}
			}
			
		}
		
		
		///////////////////////////////////////////////////////////
		//////////////Submit Salary Slip//////////////////////////
		if(isset($_POST['ed_staff_salary_submit']) && count($_POST['salary_arr']) > 0 && escape($_POST['salary_arr'][0]) != "") {
			$staff_id = escape(strtolower($_POST['ed_staff_salary_submit']));
			$month = num_only($_POST['month']);
			$year = num_only($_POST['year']);
			$flag = true;
			$query_str = "VALUES";
			
			if($staff_id != "" && $month != "" && $year != "") {
				$flag = true;
			}else{
				$flag = false;
				echo messageFormat("danger customized", "<i class='fa fa-warning'></i>  Incomplete form data, You have not selected a month or a year for payment. Please try again");
			}
			
			if($flag != false) {
				$check = $db->query("SELECT * FROM salary_slip WHERE staff_id='".$staff_id."' AND month='".$month."' AND year='".$year."' LIMIT 1");
				if($check->num_rows) {
					$flag = false;
					echo "found";
				}else{
					$flag = true;
				}
			}
			
			if($flag != false) {
				$paid_date = date('d-m-Y');
				foreach($_POST['salary_arr'] as $ed_id) {
					if(num_only($ed_id) != "") {
						$query_str .= "('".$ed_id."', '".$staff_id."', '".$month."', '".$year."', '".$paid_date."'),";
					}
				}
				$query_str = rtrim($query_str, ',');
				if($query_str != "VALUES") {
					$flag = true;
				}else{
					$flag = false;
				}
			}
			
			if($flag != false) {
				$save = $db->query("INSERT INTO salary_slip(ed_id, staff_id, month, year, paid_date) ".$query_str."") or die($db->error);
				if($save) {
					$flag = true;
					echo "success";
				}else{
					$flag = false;
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Problem encountered, salary slip could not be generated. Please try again");
				}
			}
			
		}
		
		
		///////////////////////////////////////////////////////////////////
		///////////////Discard previous salary slip details////////////////
		if(isset($_POST['staff_to_discard']) && escape($_POST['staff_to_discard']) != "" && isset($_POST['month_to_discard']) && isset($_POST['year_to_discard'])) {
			$staff_id = escape(strtolower($_POST['staff_to_discard']));
			$month = num_only($_POST['month_to_discard']);
			$year = num_only($_POST['year_to_discard']);
			$flag = false;
			
			if($staff_id != "" && $month != "" && $year != "") {
				$flag = true;
			}else{
				$flag = false;
				echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Problem encountered, invalid entry. Please try again");
			}
			
			if($flag != false) {
				$delete = $db->query("DELETE FROM salary_slip WHERE staff_id='".$staff_id."' AND month='".$month."' AND year='".$year."'") or die($db->error);
				if($delete) {
					echo "success";
				}else{
					echo messageFormat("danger customized", "<i class='fa fa-warning'></i> Problem encountered, salary slip data not found, invalid selection. Please try again");
				}
			}
			
		}
		
		////////////////////////////////////////////////////////////////////
		/////////////////Submit student attendance//////////////////////////
		if(isset($_POST['admin_no_arr']) && count($_POST['admin_no_arr']) > 0 && escape($_POST['admin_no_arr'][0]) != "") {
			$session = escape($_POST['at_session']);
			$term = escape($_POST['at_term']);
			$date = escape($_POST['at_date']);
			$flag = true;
			
			if($session != "" && $term != "" && $date != "") {
				$flag = true;
			}else{
				$flag = false;
				echo "no changes";
			}
			
			if($flag != false) {
				$x = 1;
				foreach($_POST['admin_no_arr'] as $value) {
					if(escape($value) != "") {
						$check = $db->query("SELECT * FROM stu_attendance WHERE admission_no='".escape(strtolower($value))."' AND session_year='".$session."' AND term='".$term."' AND att_date='".$date."' LIMIT 1");
						if($check->num_rows && escape($_POST['_'.escape($value)]) == "") {
							$remove = $db->query("DELETE FROM stu_attendance WHERE admission_no='".escape(strtolower($value))."' AND session_year='".$session."' AND term='".$term."' AND att_date='".$date."' LIMIT 1");
							$x++;
						}elseif(!$check->num_rows && escape($_POST['_'.escape($value)]) == 1) {
							$save = $db->query("INSERT INTO stu_attendance(admission_no, session_year, term, att_date) VALUES('".escape(strtolower($value))."', '".$session."', '".$term."', '".$date."')");
							$x++;
						}
					}
				}
				
				if($x > 1) {
					echo "success";
				}else{
					echo "no changes";
				}
			}
			
		}
		
		
		////////////////////////////////////////////////////////
		////////Fetch attendance calendar using date///////////
		if(isset($_POST['fe_date']) && escape($_POST['fe_date']) != "" && isset($_POST['fe_staff']) && escape($_POST['fe_staff']) != "") {
			
			//Get current session
			$get_session = $db->query("SELECT * FROM academic_year WHERE active=1 ORDER BY id DESC");
			$rw 		= $get_session->fetch_assoc();
			$session 	= escape($rw['session_year']);
			$term 		= escape(strtolower($rw['term']));
			
			$staff_id = escape(strtoupper($_POST['fe_staff']));
			$date = escape($_POST['fe_date']);
			$date_arr = explode("-", $date);
			
			if(count($date_arr) > 2 && $staff_id != "") {
				$month = $date_arr[1];
				$year = $date_arr[0];
				$day = $date_arr[2];
				
				$d = cal_days_in_month(CAL_GREGORIAN, $month, $year);
				$mnth_att = "";
	
				$days = "";
				for($i = 1; $i <= $d; $i++) {
					$days .= "<th>".$i."</th>";
				}
				
				//Get Class Taught
				$class_init = "";
				$class_ass = "";
				$get_cl = $db->query("SELECT * FROM assigned_classes WHERE staff_id='".$staff_id."' LIMIT 1") or die($db->error);
				if($get_cl->num_rows) {
					$row = $get_cl->fetch_assoc();
					$class_ass = escape(strtolower($row['class_level']));
					
					$get_std = $db->query("SELECT * FROM students AS stu INNER JOIN class_level AS c ON stu.class_level=c.level_id INNER JOIN section AS s ON c.section_id=s.section_id WHERE stu.section='".num_only($row['section'])."' AND stu.class_level='".num_only($row['class_level'])."' AND stu.class_name='".alpha_only(strtolower($row['class_level']))."' ORDER BY stu.lname, stu.fname, stu.mname") or die($db->error);
					if($get_std->num_rows) {
						
						$mnth_att .= "<table class='table table-bordered att_tb' id='att_table_async'>
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
							///////////////////////////////////////////////////
							////Attendance Calendar By current month////////////
							$mnth_att .= "<tr>
											<td>".$x."</td>
											<td class='name_td'><b>".escape(ucfirst($rs['lname']))." ".escape(ucwords($rs['mname']))." ".escape(ucwords($rs['fname']))."</b></td>";
							
							$p = 0;
							for($i = 1; $i <= $d; $i++) {
								$at_date = $year."-".$month."-".$i;
								if($year <= date('Y')) {
									if($month  == date('m')) {
										if($i <= $day) {
											$find = $db->query("SELECT * FROM stu_attendance WHERE admission_no='".escape(strtolower($rs['admission_no']))."' AND session_year='".$session."' AND term='".$term."' AND att_date='".$at_date."'") or die($db->error);
											if($find->num_rows) {
												$mnth_att .= "<td class='text-center' style='background: linear-gradient(90deg, #5cb85c, green); color: #efefef'><b>P</b></td>";
												$p++;
											}elseif(!$find->num_rows){
												$mnth_att .= "<td class='text-center' style='color: red'><b>A</b></td>";
											}
										}else{
											$mnth_att .= "<td style='background: #2D3945'></td>";
										}
									}elseif($month > date('m')){
										$mnth_att .= "<td style='background: #2D3945'></td>";
									}else{
										$find = $db->query("SELECT * FROM stu_attendance WHERE admission_no='".escape(strtolower($rs['admission_no']))."' AND session_year='".$session."' AND term='".$term."' AND att_date='".$at_date."'") or die($db->error);
										if($find->num_rows) {
											$mnth_att .= "<td class='text-center' style='background: linear-gradient(90deg, #5cb85c, green); color: #efefef'><b>P</b></td>";
											$p++;
										}elseif(!$find->num_rows){
											$mnth_att .= "<td class='text-center' style='color: red'><b>A</b></td>";
										}
									}
								}else{
									$mnth_att .= "<td style='background: #2D3945'></td>";
								}
							}
							$mnth_att .= "<td class='text-center text-primary'><b>".$p."</b></td></tr>";
							
							$x++;
						}
						$mnth_att .= "</tbody></table>";
						$class_init = $class_init.alpha_only(strtoupper($row['class_level']));
							
							$monthName = date("F", mktime(0, 0, 0, $month, 10));
							$mnth_att = "<h5><b>Attendance of <span class='text-success'>".$monthName.", ".$year."</span> for ".$class_init."</b></h5>
							<div class='bg_all table-responsive'>
								".$mnth_att."
							</div>";
						
						echo $mnth_att;
					}
					
				}
				
			}
		}
		
		////////////////////////////////////////////////////////////
		///////////Fetch Broadsheet using session and term//////////
		if(isset($_POST['br_session']) && escape($_POST['br_session']) != "" && isset($_POST['br_term']) && escape($_POST['br_term']) != "") {
			$session = escape(strtolower($_POST['br_session']));
			$term = escape(strtolower($_POST['br_term']));
			
			if($term != "" && $session != "") {
				
				require_once "includes/score_parse.php";
				
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
							echo $cl_students;
						}else{
							$cl_students = messageFormat("danger customized", "<i class='fa fa-warning'></i> No record found!!!. Please try again");
							echo $cl_students;
						}
					}
				}
				
			}
		}
		
	}

?>