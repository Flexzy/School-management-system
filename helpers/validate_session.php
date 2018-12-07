<?php 
	
	if(!isset($_SESSION['user']) && escape($_SESSION['user']) == "") {
		header("location: signout.php");
	}

    $verify_token = $db->query("SELECT emails, pass, session_id
                                FROM login
                                WHERE emails = '".escape($_SESSION['user'])."'
                                LIMIT 1
                            ") or die($db->error);
		
	if($verify_token->num_rows) {
		
	/*	$row_user = $verify_token->fetch_assoc();
		$user = escape($_SESSION['user']);
		$salt = escape($_SESSION['user_salt']);
		
		if(md5($user.$salt) !== escape($row_user['session_id'])) {
								
			header("location: signout.php");
			
		}*/
		
	}else{
		
		header("location: signout.php");
		
	}
	
?>