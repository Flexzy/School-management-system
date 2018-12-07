<?php 
	echo "<h2>Prime here</h2>";
	$flag = true;
	
	for($i = 2; $i <= 100; $i++) {
		for($j = 2; $j <= $i; $j++) {
			if($i % $j == 0) {
				$flag = false;
				break;
			}
			
			if($flag == true) {
				echo "$i is prime<br/>";
			}
		}
	}
	
?>