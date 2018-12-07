<?php 
	
	require_once "helpers/init.php";
	require_once("includes/mpdf/mpdf.php");
	
	if($_SERVER['REQUEST_METHOD'] == 'GET') {
		if(isset($_GET['student']) && escape($_GET['student']) != "" && isset($_GET['no']) && num_only($_GET['no']) != "") {
			$admin_no 	= escape($_GET['student']);
			$receipt_no = num_only($_GET['no']);
			$total 		= 0;
			$html		= "";
			$session	= "";
			$term		= "";
			$pdate		= "";
			$mode		= "";
			$slip		= "";
			
			if($admin_no != "" && $receipt_no != "") {
				
				$check_acess = $db->query("SELECT DISTINCT * FROM student_access AS s INNER JOIN fee_head AS f ON s.fee_id=f.fee_id WHERE s.admission_no='".$admin_no."' AND s.receipt_no='".$receipt_no."'");
				if($check_acess->num_rows) {
					$x = 1;
					while($row_ac = $check_acess->fetch_assoc()) {
						$session = escape($row_ac['session_year']);
						$term = escape(ucfirst($row_ac['term']));
						$pdate = escape(ucfirst($row_ac['pay_date']));
						$mode = escape(ucfirst($row_ac['mode']));
						$slip = escape(ucfirst($row_ac['slip_no']));
						$total = $total + num_only($row_ac['amount']);
						$html .= "<tr>
									<td>
										".$x."
									</td>
									<td>".escape($row_ac['fee_type'])."</td>
									<td>".number_format(num_only($row_ac['amount']))."</td>
								</tr>";
						$x++;
					}
					
					if($html != "") {
						$get_stu = $db->query("SELECT * FROM students AS stu INNER JOIN class_level AS c ON stu.class_level=c.level_id INNER JOIN section AS s ON c.section_id=s.section_id WHERE stu.admission_no='".$admin_no."' LIMIT 1");
						$row = $get_stu->fetch_assoc();
						
						$html = "<h3 class='text-center text-blue' style='padding: 0px; margin: 0px;'><i class='fa fa-camera-retro text-gold'></i> Musa Iliyasu College<br/><span style='font-style: italic; font-size: 13px;'>Rijiyar zaki, along gwarzo road kano state</span></h3><hr/>
									<div class='row row-flex'>
										<div class='col-md-6 col'>
											<p><b>Receipt No:</b> ".escape(strtoupper($receipt_no))."</p>
											<p><b>Name:</b> ".escape(strtoupper($row['lname'])).", ".escape(ucwords($row['mname']))." ".escape(ucwords($row['fname']))."</p>
											<p><b>Adm No:</b> ".strtoupper($admin_no)."</p>
											<p><b>Date:</b> ".$pdate."</p>
										</div>
										<div class='col-md-6 col'>
											<p class='text-right'><b>Session:</b> ".$session." </p>
											<p class='text-right'><b>Term:</b> ".$term." </p>
											<p class='text-right'><b>Class:</b> ".escape(strtoupper($row['level']))."".escape(strtoupper($row['class_name']))." </p>
										</div>
									</div><br/>
									<table class='tb_o_level' width='100%' border='1'>
										<thead>
											<tr>
												<th width='6%'>#</th>
												<th>Fee name</th>
												<th>Amount</th>
											</tr>
										</thead>
										<tbody>".$html."
										<tr>
											<th colspan='2' class='text-right'>Total charges</th>
											<td><span id='total_sp'>".number_format(num_only($total))."</span>
											</td>
										</tr>
										<tr>
											<th colspan='2' class='text-right'>Payment mode</th>
											<td>
												".$mode."
											</td>
										</tr>
										<tr>
											<th colspan='2' class='text-right'>Payment Slip No:</th>
											<td>
												".$slip."
											</td>
										</tr>
									</tbody>
								</table>
								<br/><br/><br/>
								<p class='text-right'><strong>Accountant</strong></p>
								<p class='text-right'><strong>__________</strong></p>";
					}else{
						header("location: student.php");
					}
					
				}else{
					header("location: student.php");
				}
				
			}else{
				header("location: student.php");
			}
		}
	}else{
		header("location: student.php");
	}
	
	$mpdf=new mPDF('c','A5'); 

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

    $mpdf->WriteHTML($html,2);

    $mpdf->Output('mpdf.pdf','I');
    exit;
    //==============================================================
    //==============================================================
    //==============================================================
	
?>