<?php 
	
	require_once "helpers/init.php";
	require_once("includes/mpdf/mpdf.php");
	require_once "includes/scores.php";
	
	
	$message = "";
	$clear = "";
	$session = "";
	$term = "";
	$type = "";
	
	if($_SERVER['REQUEST_METHOD'] == 'GET') {
		if(isset($_GET['type']) && escape($_GET['type']) != "") {
			$type = escape(strtolower($_GET['type']));
		}else{
			header("location: broadsheet.php");
		}
	}
	
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
		$cl_empty = "";
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
				
				$cl_students = "<div class='text-right' style='margin-bottom: 7px;'></div>
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
									
				$cl_empty = "<div class='text-right' style='margin-bottom: 7px;'></div>
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
										
					$cl_empty .= "<tr>
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
							$cl_empty .= "<td class='text-center'></td>";
						}else{
							$cl_students .= "<td class='text-center'>-</td>";
							$cl_empty .= "<td class='text-center'></td>";
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
					
					if(substr($pos, -1) == 1) {
						$pos = $pos."<sup>st</sup>";
					}elseif(substr($pos, -1) == 2) {
						$pos = $pos."<sup>nd</sup>";
					}elseif(substr($pos, -1) == 3) {
						$pos = $pos."<sup>rd</sup>";
					}else{
						$pos = $pos."<sup>th</sup>";
					}
					
					$cl_students .=	"<td class='text-center'>".$score_arr[escape($rs['admission_no'])]."</td>
									 <td class='text-center'>".$c."</td>
									 <td class='text-center'>".$sc_score_arr[escape($rs['admission_no'])]."</td>
									 <td class='text-center'>".$pos."</td>
									</tr>";
									
					$cl_empty .=	"<td class='text-center'></td>
									 <td class='text-center'></td>
									 <td class='text-center'></td>
									 <td class='text-center'></td>
									</tr>";
					
					$x++;
				}
				$cl_students .= "</tbody></table>";
				$cl_empty .= "</tbody></table>";
				$class_init = $class_init.alpha_only(strtoupper($row['class_level']));
			}else{
				$cl_students = messageFormat("danger customized", "<i class='fa fa-warning'></i> No record found!!!. Please try again");
			}
		}
	}
	
	//Output Excel
	if($type == "excel") {
		header("Content-Type: application/xls");
		header("Content-Disposition: attachment; filename=broadsheet-".$class_init."-".$session."-".$term."-term.xls");
		echo $cl_students;
		
	}elseif($type == "pdf") {
		$cl_students = "<h3 class='text-center text-blue' style='padding: 0px; margin: 0px;'><i class='fa fa-camera-retro text-gold'></i> Bristol International High School<br/><span style='font-style: italic; font-size: 13px;'>Rijiyar zaki, along gwarzo road kano state</span></h3><h4 class='text-center'>Broadsheet for PRI 4B</h4>".$cl_students;
		
		$mpdf=new mPDF('c','A4-L'); 
		
		$mpdf->SetDisplayMode('fullpage');

		$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list

		// LOAD a stylesheet
		$stylesheet = file_get_contents('css/bootstrap.min.css');
		$stylesheet2 = file_get_contents('css/table.css');
		$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text
		$mpdf->WriteHTML($stylesheet2,1);	// The parameter 1 tells that this is css/style only and no body/html/text
		
		//$mpdf->SetWatermarkImage('img/img.svg', 0.10, 'F');
		//$mpdf->showWatermarkImage = true;
		
		$mpdf->SetWatermarkText('BROADSHEET');
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->showWatermarkText = true;

		$mpdf->WriteHTML($cl_students,2);

		$mpdf->Output('mpdf.pdf','I');
		exit;
		//==============================================================
		//==============================================================
		//==============================================================
	}elseif($type == "empty") {
		header("Content-Type: application/xls");
		header("Content-Disposition: attachment; filename=broadsheet-".$class_init."-".$session."-".$term."-term.xls");
		echo $cl_empty;
	}
	
?>