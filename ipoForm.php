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
        <script>
            function show_data() {
                $('#ipo').load('ipoUpdater.php');
                }
            setInterval('show_data()', 2000);
        </script>
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
            <li><a href="dashboard.php">Dashboard</a> |</li>
            <li><a href="trade.php"> Trade </a> |</li>
            <li class="current_menu"><a href="IPO.php"> IPO </a> |</li>
            <li><a href="logout.php"> Logout</a> </li>
            </ul>
        </div>
        <br />
        <br />

        <?php
            if((strlen($_POST['company']) < 1) || (strlen($_POST['shares']) < 1)) {
                header('Loaction: IPO.php');
            } 
            $company = $_POST['company'];
            $shares = $_POST['shares'];        
            $query = "SELECT * FROM `ipo` WHERE `company` = \"$company\"";
            $result = mysqli_query($conn, $query);
            if(mysqli_num_rows($result) == 0) {
                echo("<div id='transaction_status'><font color='red'>No such IPO!</font></div>");
            }
            else {
                $row = mysqli_fetch_array($result);
                $availableShrs = $row['shares'];
                if($shares > $availableShrs) {
                    echo("<div id='transaction_status'><font color='red'>So many shares are not available!</font></div>");
                }
                else {
                    $query1 = "SELECT `balance` FROM `users` WHERE `email` = \"$userEmail\"";
                    $result1 = mysqli_query($conn, $query1);
                    $row1 = mysqli_fetch_array($result1);
                    $balance = $row1['balance'];

                    $query2 = "SELECT `share_price` FROM `companies` WHERE `name` = \"$company\"";
                    $result2 = mysqli_query($conn, $query2);
                    $row2 = mysqli_fetch_array($result2);
                    $reqCash = $shares*$row2['share_price'];
                    if($balance > $reqCash) {
                        $query3 = "SELECT * FROM `shares_data` WHERE `email` = \"$userEmail\" AND `company` = \"$company\"";
                        $result3 = mysqli_query($conn, $query3);
                        if(mysqli_num_rows($result3) > 0) {
                            $query4 = "UPDATE `shares_data` SET `shares` = `shares`+$shares WHERE `email` = \"$userEmail\" AND `company` = \"$company\"";
                            $result4 = mysqli_query($conn, $query4);
                        }
                        else {
                            $query5 = "INSERT INTO `shares_data` (email, company, shares) VALUES (\"$userEmail\", \"$company\", $shares)";
                            $result5 = mysqli_query($conn, $query5);
                        }
                        $query6 = "UPDATE `users` SET `balance` = `balance`-$reqCash WHERE `email` = \"$userEmail\"";
                        $result6 = mysqli_query($conn, $query6);
                        if($shares == $availableShrs) {
                            $query7 = "DELETE FROM `ipo` WHERE `company` = \"$company\"";
                            $result7 = mysqli_query($conn, $query7);
                        }
                        else {
                            $query8 = "UPDATE `ipo` SET `shares` = `shares`-$shares WHERE `company` = \"$company\"";
                            $result8 = mysqli_query($conn, $query8);
                        }
                        echo("<div id='transaction_status'><font color='green'>Transaction successful!</font></div>");
                    }
                    else {
                        echo("<div id='transaction_status'><font color='red'>You do not have enough cash!</font></div>");
                    }
                }
            }
            mysqli_close($conn);
        ?>
        
    </body>
</html>
