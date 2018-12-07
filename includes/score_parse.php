<?php 
	
	$sc_session = $session;
	$sc_term = $term;
	
	//Get subjects
	$sc_sub_arr = array();
	$get_sc_subs = $db->query("SELECT subject_id, subject_abbr FROM subject_bank ORDER BY subject_name");
	if($get_sc_subs->num_rows) {
		while($row = $get_sc_subs->fetch_assoc()) {
			$sc_sub_arr[num_only($row['subject_id'])] = escape(strtoupper($row['subject_abbr']));
		}
	}
	
	if(count($sc_sub_arr) > 0) {
		
		//Get Class Taught
		$sc_cl_students = "";
		$class_sc_ass = "";
		$get_sc_cl = $db->query("SELECT * FROM assigned_classes WHERE staff_id='SCH-001' LIMIT 1");
		if($get_sc_cl->num_rows) {
			$row = $get_sc_cl->fetch_assoc();
			$class_sc_ass = escape(strtolower($row['class_level']));
			
			$get_sc_std = $db->query("SELECT * FROM students AS stu INNER JOIN class_level AS c ON stu.class_level=c.level_id INNER JOIN section AS s ON c.section_id=s.section_id WHERE stu.section='".num_only($row['section'])."' AND stu.class_level='".num_only($row['class_level'])."' AND stu.class_name='".alpha_only(strtolower($row['class_level']))."' ORDER BY stu.lname, stu.fname, stu.mname") or die($db->error);
			if($get_sc_std->num_rows) {
				
				//Array to hold score
				$sc_score_arr = array();
				
				while($rs = $get_sc_std->fetch_assoc()) {
					$sc_score_arr[escape($rs['admission_no'])] = 0;
					$b = 0;
					foreach($sc_sub_arr as $key => $value) {
						$sc_fetch = $db->query("SELECT * FROM marks WHERE admission_no='".strtolower(escape($rs['admission_no']))."' AND subject_id='".$key."' AND session='".$sc_session."' AND term='".$sc_term."' LIMIT 1");
						if($sc_fetch->num_rows) {
							$b++;
							$rm = $sc_fetch->fetch_assoc();
							$sc_subject_score = (num_only($rm['ca_one']) + num_only($rm['ca_two']) + num_only($rm['exam']));
							$sc_score_arr[escape($rs['admission_no'])] += $sc_subject_score;
							
						}else{
							$sc_score_arr[escape($rs['admission_no'])] += 0;
						}
					}
					
					$sc_score_arr[escape($rs['admission_no'])] = ($sc_score_arr[escape($rs['admission_no'])] != 0)? number_format($sc_score_arr[escape($rs['admission_no'])] / $b, 2) : 0;
					
				}
				
				arsort($sc_score_arr);
			}
		}
	}
	
?>