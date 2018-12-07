<?php 
	
	//error_reporting(0);
	require "helpers/init.php";
	
	$result = "";
	
	$year = date('Y');
	$month = date('m');
	$calendar = array();
	
	//Generate Options for months and make the current month selected
	$month_op = "";
	$month_array = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
	for($i = 1; $i <= 12; $i++) {
		if($i == $month) {
			$month_op .= "<option value='".$i."' selected>".$month_array[$i - 1]."</option>";
		}else{
			$month_op .= "<option value='".$i."'>".$month_array[$i - 1]."</option>";
		}
	}
	
	//Generate Options for years and make the current year selected
	$year_op = "";
	for($i = 2010; $i <= $year; $i++) {
		if($i == $year) {
			$year_op .= "<option value='".$i."' selected>".$i."</option>";
		}else{
			$year_op .= "<option value='".$i."'>".$i."</option>";
		}
	}
	
	$d = cal_days_in_month(CAL_GREGORIAN,$month,$year);
	$hold_arr = array();
	$weeks = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
	
	for($i = 1; $i <= $d; $i++) {
		
		//echo strtoupper(strftime('%a', strtotime("$i January 2018")))."<br/>";
		$date1=date_create(strftime("%Y-%m-%d", time()));
		$date2=date_create("".$year."-".$month."-".$i."");
		$diff=date_diff($date1,$date2, false);
		
		$days = $diff->format("%R%a");
		
		$date = $date1->modify("".$days." day");
		$show = $date->format("D");
		
		$hold_arr[] = $date->format("D-d");
		
	}
	print_r($hold_arr);
	$fir = explode("-", $hold_arr[0]);
	$key = array_search($fir[0], $weeks);
	
	for($i = $key; $i >= 1; $i--) {
		array_unshift($hold_arr, $weeks[$i]."-0");
	}
	
	echo cal_days_in_month(CAL_GREGORIAN,5,2018);
	
	$student = array('Musa', 'Ibrahim', 'Idris', 'Mai Gida');
	
?>

