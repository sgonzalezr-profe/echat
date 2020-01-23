<?php
//header doesn't worj ?!
//header('Content-Type:application/json');
session_start();

if (array_key_exists("currentSender", $_POST) && array_key_exists("currentReceiver", $_POST) &&
    ((array_key_exists("username", $_SESSION) && $_SESSION['username']) ||
        (array_key_exists("username", $_COOKIE) && $_COOKIE['username']))) {


    include("connection.php");

    $query = "SELECT COUNT(*) AS numMessages FROM messages where ".
                " sender_id = '".mysqli_real_escape_string($link, $_POST['currentSender'])."'".
                " and receiver_id= '".mysqli_real_escape_string($link, $_POST['currentReceiver'])."'".
                " OR ".
                " sender_id = '".mysqli_real_escape_string($link, $_POST['currentReceiver'])."'".
                " and receiver_id= '".mysqli_real_escape_string($link, $_POST['currentSender'])."'";

    $result = mysqli_query($link, $query);
    $jsonData = array();
    if(mysqli_num_rows($result) > 0){
        $jsonData[] = mysqli_fetch_assoc($result);
    }
    $json = json_encode($jsonData);
    echo stripslashes($json);
    
    mysqli_close($link);
    
} else {
    header("Location: index.php?logout=1");

}
      
?>
