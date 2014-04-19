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
            <li><a href="logout.php"> Logout</a> </li>
            </ul>
        </div>
        <br />
        <br />
        <?php
            if((strlen($_POST['company']) < 1) || (strlen($_POST['shares']) < 1) || (strlen($_POST['share_price']) < 1)) {
                header('Loaction: trade.php');
            }             

            $company = $_POST['company'];
            $shares = $_POST['shares'];
            $share_price = $_POST['share_price'];
            
            $query = "SELECT * FROM `shares_data` WHERE `email` = \"$userEmail\" AND `company` = \"$company\"";
            $result = mysqli_query($conn, $query);
            
            if(mysqli_num_rows($result) == 0) {
                echo("<div id='transaction_status'><font color='red'>You do not own shares of $company!</font></div>");
            }
            else {
                $row = mysqli_fetch_array($result);
                $sharesOwned = $row['shares'];
                $query1 = "SELECT * FROM `seller_board` WHERE `email` = \"$userEmail\" AND `company` = \"$company\"";
                $result1 = mysqli_query($conn, $query1);
                if(mysqli_num_rows($result1)) {
                    $row = mysqli_fetch_array($result1);
                    $sharesOwned = $sharesOwned-$row['shares'];
                }
                if($shares <= $sharesOwned) {
                    if(mysqli_num_rows($result1)) {
                        $query2 = "UPDATE `seller_board` SET `shares` = `shares`+$shares , `share_price` = $share_price WHERE `email` = \"$userEmail\" AND `company` = \"$company\"";
                        $result2 = mysqli_query($conn, $query2); 
                    }
                    else {
                        $query3 = "INSERT INTO `seller_board` (email, company, shares, share_price, name) VALUES (\"$userEmail\", \"$company\", $shares, $share_price, \"$userName\")";
                        $result3 = mysqli_query($conn, $query3);
                    }
                    echo("<div id='transaction_status'><font color='green'>Seller board updated!</font></div>");
                }
                else{
                    echo("<div id='transaction_status'><font color='red'>You do not have so many shares of $company to sell!</font></div>");
                }
            }            
            mysqli_close($conn);
        ?>

    </body>
</html>
