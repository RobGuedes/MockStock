<?php
    session_start();
    if(!isset($_SESSION['user_email'])) {
        header('Location: login.html');
    }
    $userEmail = $_SESSION['user_email'];
    
    $userName = $_SESSION['user_name'];
    
    $file = file_get_contents('./config.txt', FILE_USE_INCLUDE_PATH);
    $str = preg_split("/[\s,\n]+/", $file);
    $sql_host = $str[1];
    $sql_user = $str[3];
    $sql_password = $str[5];
    $sql_db = $str[7];

    $conn = mysqli_connect($sql_host, $sql_user, $sql_password, $sql_db);
    
    $query2 = "SELECT `balance` FROM `users` WHERE `email` = \"$userEmail\"";
    $result2 = mysqli_query($conn, $query2);                                   
    $row2 = mysqli_fetch_array($result2);
    echo("
        <div id='balance_display'>
            <b>Balance: ".$row2['balance']."</b>
        </div>
    ");
    mysqli_close($conn);   
?>

