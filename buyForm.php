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
            <li><a href="dashboard.php">Dashboard</a> |</li>
            <li class="current_menu"><a href="trade.php"> Trade </a> |</li>
            <li><a href="IPO.php"> IPO </a> |</li>
            <li><a href="logout.php"> Logout</a> </li>
            </ul>
        </div>
        <br />
        <br />

        <?php
            if((strlen($_POST['company']) < 1) || (strlen($_POST['shares']) < 1) || (strlen($_POST['seller_email']) < 1)) {
                header('Loaction: trade.php');
            }            

            $company = $_POST['company'];
            $shares = $_POST['shares'];
            $seller_email = $_POST['seller_email'];

            $query = "SELECT * FROM `seller_board` WHERE `company` = \"$company\" AND `email` = \"$seller_email\"";
            $result = mysqli_query($conn, $query);
            if(mysqli_num_rows($result) == 0) {
                echo("<div id='transaction_status'><font color='red'>Transaction failed! No such entry in the seller board!</font></div>");
            }
            else{
                $query1 = "SELECT `balance` FROM `users` WHERE `email` = \"$userEmail\"";
                $result1 = mysqli_query($conn, $query1);
                $row1 = mysqli_fetch_array($result1);
                $balance = $row1['balance'];

                $row = mysqli_fetch_array($result);
                $share_price = $row['share_price'];
                $seller_shares = $row['shares'];
                $reqCash = $shares*$share_price;
                
                if($shares > $seller_shares){
                    echo("<div id='transaction_status'><font color='red'>Transaction failed! Not enough shares to buy!</font></div>");
                }
                else {
                if($balance > $reqCash) {
                    $query2 = "SELECT `company` FROM `shares_data` WHERE `email` = \"$userEmail\" AND `company` = \"$company\"";
                    $result2 = mysqli_query($conn, $query2);
                    if(mysqli_num_rows($result2)) {
                        $query3 = "UPDATE `shares_data` SET `shares` = `shares`+$shares WHERE `email` = \"$userEmail\" AND `company` = \"$company\"";
                        mysqli_query($conn, $query3);
                        #echo("<script>alert('Transaction Sucessfull!');</script>");
                        
                    }
                    else {
                        $query3 = "INSERT INTO `shares_data` (email, company, shares) VALUES (\"$userEmail\", \"$company\", $shares)";
                        mysqli_query($conn, $query3);
                        #echo("<script>alert('Transaction Sucessfull!');</script>");
                        
                    }
                    
                    $query4 = "UPDATE `users` SET `balance` = `balance`-$reqCash WHERE `email` = \"$userEmail\"";
                    mysqli_query($conn, $query4);

                    if($shares < $seller_shares) {
                        $query5 = "UPDATE `seller_board` SET `shares` = `shares`-$shares WHERE `email` = \"$seller_email\" AND `company` = \"$company\"";
                        mysqli_query($conn, $query5);
                    }
                    else{
                        $query6 = "DELETE FROM `seller_board` WHERE `email` = \"$seller_email\" AND `company` = \"$company\"";
                        mysqli_query($conn, $query6);
                    }

                    $query7 = "UPDATE `users` SET `balance`=`balance`+$reqCash WHERE `email`=\"$seller_email\"";
                    mysqli_query($conn, $query7);
                    
                    $query8 = "SELECT * FROM `shares_data` WHERE `email` = \"$seller_email\" AND `company` = \"$company\"";
                    $result8 = mysqli_query($conn, $query8);

                    $row = mysqli_fetch_array($result8);
                    $slrShrs = $row['shares'];

                    if($slrShrs == $shares) {
                        $query9 = "DELETE FROM `shares_data` WHERE `email` = \"$seller_email\" AND `company` = \"$company\"";
                        mysqli_query($conn, $query9);
                    }
                    else {
                        $query10 = "UPDATE `shares_data` SET `shares` = `shares`-$shares WHERE `email` = \"$seller_email\" AND `company` = \"$company\"";
                        mysqli_query($conn, $query10);
                    }
                    echo("<div id='transaction_status'><font color='green'>Transaction successful!</font></div>");
                    
                }

                }
            }
            mysqli_close($conn);
        ?>

    </body>
</html>
