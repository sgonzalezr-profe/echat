<?php

session_start();

if (array_key_exists("currentSender", $_POST) && array_key_exists("currentReceiver", $_POST) &&
    ((array_key_exists("username", $_SESSION) && $_SESSION['username']) ||
        (array_key_exists("username", $_COOKIE) && $_COOKIE['username']))) {


    include("connection.php");

    $query = "SELECT * FROM messages where ".
                " sender_id = '".mysqli_real_escape_string($link, $_POST['currentSender'])."'".
                " and receiver_id= '".mysqli_real_escape_string($link, $_POST['currentReceiver'])."'".
                " OR ".
                " sender_id = '".mysqli_real_escape_string($link, $_POST['currentReceiver'])."'".
                " and receiver_id= '".mysqli_real_escape_string($link, $_POST['currentSender'])."'".
                " order by tmessage asc";

    $result = mysqli_query($link, $query);
    $jsonData = array();
    if(mysqli_num_rows($result) > 0){
        while ($array = mysqli_fetch_assoc($result)) {
            $jsonData[] = $array;
        }
    }

    $json = json_encode($jsonData,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    echo stripslashes($json);
    

    //Do I need to close the connection?
    


} else {
    header("Location: index.php?logout=1");


}
      
?>
