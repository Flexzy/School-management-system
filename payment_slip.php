<?php 
	
	require_once "helpers/init.php";
	require_once("includes/mpdf/mpdf.php");
	
	if($_SERVER['REQUEST_METHOD'] == 'GET') {
		if(isset($_GET['staff_id']) && escape($_GET['staff_id']) != "" && isset($_GET['month']) && num_only($_GET['month']) != "" && isset($_GET['year']) && num_only($_GET['year']) != "") {
			$mnth_arr = array('january', 'february', 'march', 'april', 'may', 'june', 'july', 'august', 'september', 'october', 'november', 'december');
			$staff_id = escape(strtolower($_GET['staff_id']));
			$month = num_only($_GET['month']);
			$year = num_only($_GET['year']);
			$flag = true;
			$salary = "";
			$basic_pay = "";
			$paid_date = "";
			$st_name = "";
			$emoluments = 0;
			$deductions = 0;
			$em_array = array();
			$de_array = array();
			$ed_array = array();
			$html	= "";
			
			if($staff_id != "" && $month != "" && $year != "") {
				$flag = true;
			}else{
				$flag = false;
				//header("location: salary_slip");
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
											<td class='td_left text-right ".$bt_bottom."'><span class='td_span' id='_".$value."_view'>&nbsp;".$amt_str."&nbsp;</span></td>
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
						$salary = "<h3 class='text-center text-blue' style='padding: 0px; margin: 0px;'><i class='fa fa-camera-retro text-gold'></i> Bristol International High School<br/><span style='font-style: italic; font-size: 13px;'>Rijiyar zaki, along gwarzo road kano state</span></h3><br/><p class='text-center' style='background: #2D3945; color: #fff; padding: 5px; font-weight: bolder'><b>SALARY PAYMENT SLIP</b></p>
									<table class='table table-striped'>
										<tr>
											<th>Designation: </th>
											<td>".$st_name."</td>
										</tr>
										<tr>
											<th>School Name: </th>
											<td>Techguru College and Vocational Center</td>
										</tr>
										<tr>
											<th>Payment For: </th>
											<td>".ucwords($mnth_arr[$month - 1]).", ".$year."</td>
										</tr>
										<tr>
											<th>Payment Date: </th>
											<td>".$paid_date."</td>
										</tr>
									</table>
									<table class='table_sl table-striped bg_all' id='custom_tb'>
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
										<th colspan='2' class='td_right text-center'>
											Total Deductions
										</th>
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
								<br/><br/><br/>
								<p class='text-right'><strong>Accountant</strong></p>
								<p class='text-right'><strong>__________</strong></p>";
								
					}
					
				}else{
					header("location: salary_slip");
				}
			}
		}else{
			header("location: salary_slip");
		}
	}else{
		header("location: salary_slip");
	}
	
	$mpdf=new mPDF('c','A4'); 

    $mpdf->SetDisplayMode('fullpage');

    $mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list

    // LOAD a stylesheet
    $stylesheet = file_get_contents('css/bootstrap.min.css');
    $stylesheet2 = file_get_contents('css/table.css');
    $mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text
    $mpdf->WriteHTML($stylesheet2,1);	// The parameter 1 tells that this is css/style only and no body/html/text
    
    //$mpdf->SetWatermarkImage('img/img.svg', 0.10, 'F');
    //$mpdf->showWatermarkImage = true;
    
    $mpdf->SetWatermarkText('MY COLLEGE NAME');
    $mpdf->watermark_font = 'DejaVuSansCondensed';
    $mpdf->showWatermarkText = true;

    $mpdf->WriteHTML($salary,2);

    $mpdf->Output('mpdf.pdf','I');
    exit;
    //==============================================================
    //==============================================================
    //==============================================================
	
?>