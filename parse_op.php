<?php 
	
	require_once "helpers/init.php";
	
	if($_SERVER['REQUEST_METHOD'] == 'POST') {
		//////////////////////////////////////////////////////////
		///////////////////Make Emolument Additions/Subtraction////
		if(isset($_POST['num1']) && isset($_POST['num2']) && isset($_POST['num3']) && isset($_POST['operation'])) {
			$num1 = num_only($_POST['num1']);
			$num2 = num_only($_POST['num2']);
			$de_total = num_only($_POST['num3']);
			$operation = escape(strtolower($_POST['operation']));
			$em_total = 0;
			
			if($num1 != "" && $num2 != "" && $de_total != "" && $operation != "") {
				if($operation == "add") {
					$em_total = $num1 + $num2;
					
				}elseif($operation == "subtract") {
					$em_total = $num1 - $num2;
				}
				$total = $em_total - $de_total;
				echo json_encode(array('em' => number_format($em_total), 'em_dis' => number_format($em_total, 2), 'tot_dis' => number_format($total, 2)));
			}
			
		}
		
		
		//////////////////////////////////////////////////////////
		///////////////////Make Deduction Additions/Subtraction////
		if(isset($_POST['num_1']) && isset($_POST['num_2']) && isset($_POST['num_3']) && isset($_POST['operation'])) {
			$de_total = num_only($_POST['num_1']);
			$num2 = num_only($_POST['num_2']);
			$em_total = num_only($_POST['num_3']);
			$operation = escape(strtolower($_POST['operation']));
			
			if($de_total != "" && $num2 != "" && $em_total != "" && $operation != "") {
				if($operation == "add") {
					$de_total = $de_total + $num2;
					
				}elseif($operation == "subtract") {
					$de_total = $de_total - $num2;
				}
				$total = $em_total - $de_total;
				echo json_encode(array('de' => number_format($de_total), 'de_dis' => number_format($de_total, 2), 'tot_dis' => number_format($total, 2)));
			}
			
		}
	}
		
?>