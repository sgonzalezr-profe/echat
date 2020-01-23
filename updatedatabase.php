<?php

    session_start();

    if (array_key_exists("content", $_POST)) {
        $content = $_POST['content'];
        $currentSender =  $_POST['currentSender'];
        $currentReceiver = $_POST['currentReceiver'];
        include("connection.php");
        
        $query = "INSERT INTO messages (sender_id,receiver_id,content) values ('".mysqli_real_escape_string($link,$currentSender)."','".
        																		mysqli_real_escape_string($link,$currentReceiver)."','".
        																		mysqli_real_escape_string($link,$content)."')";

        if (mysqli_query($link, $query)) {
        	$query = "SELECT tmessage FROM messages WHERE sender_id = '".mysqli_real_escape_string($link,$currentSender)."' AND ".
        													"receiver_id = '".mysqli_real_escape_string($link,$currentReceiver)."' ORDER BY tmessage DESC LIMIT 1";
        	 $row = mysqli_fetch_array(mysqli_query($link, $query));
        	 echo $row["tmessage"];

        	 mysqli_close($link);

        }
        else {
        	echo 0;
        }      
    }

?>
