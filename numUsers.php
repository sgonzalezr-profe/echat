<?php
//header doesn't worj ?!
//header('Content-Type:application/json');
session_start();

if ((array_key_exists("username", $_SESSION) && $_SESSION['username']) || 
    (array_key_exists("username", $_COOKIE) && $_COOKIE['username'])) {
   

    include("connection.php");

    //I was able to get username from $_SESSION['username'] but I wanted to test how $.ajax works with post parameters
    $query = "SELECT COUNT(*) as numUsers FROM users";
    $result = mysqli_query($link, $query);
    $jsonData = array();
    if(mysqli_num_rows($result) > 0){
        $jsonData[] = mysqli_fetch_assoc($result);
    }
    $json = json_encode($jsonData);
    echo stripslashes($json);
    
    
    //Do I need to close the connection?
    
} else {
    header("Location: index.php?logout=1");

}
      
?>
