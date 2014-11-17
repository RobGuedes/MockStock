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
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="StyleSheet.css" />
        <script type="text/javascript" src="jquery-2.1.0.min.js"></script>
        <title>TV Trading Co., Ltd.</title>
    </head>
    <body>
        <div id="title">
            <h1>TV Trading Co., Ltd.</h1>stalking the beauty of trade since time immemorial .....
            <div id="user_info">
            <?php
                echo ("
                       $userName
                ");
            ?>
            </div>
        </div>
        
        <div id="menu">
            <ul>
            <li><a href="adminPanel.php">Dashboard</a> |</li>
            <li><a href="logout.php"> Logout</a> </li>
            </ul>
        </div>
        <br />
        <br />
        <?php
            $company = $_POST['company'];
            $performance = floatval($_POST['performance']);            
            $query = "UPDATE `companies` SET `performance` = $performance, `share_price` = `share_price`+`share_price`*($performance/100) WHERE `name` = \"$company\"";
            #echo $query;
            $result = mysqli_query($conn, $query);
            if($result == 1){
                echo("<div id='transaction_status'><font color='green'>Update successful!</font></div>");
            }
            else {
                echo("<div id='transaction_status'><font color='red'>Update unsuccessful!</font></div>");
            }
        ?>
    </body>
</html>
