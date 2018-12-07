<?php 

    $db = new mysqli("localhost", "root", "", "sms");

    if($db->connect_errno) {
        //echo $db->connect_error;
        die("<h1>Server currently offline!!! Please try again later</h1>");
    }

?>