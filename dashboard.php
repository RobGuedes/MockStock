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
                $('#user_details').load('dash.php');
                $('#balance_display_holder').load('balanceDisplay.php');
                $('#my_seller_board_details_display').load('my_seller_board_details.php');
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
            <li class="current_menu"><a href="dashboard.php">Dashboard</a> |</li>
            <li><a href="trade.php"> Trade </a> |</li>
            <li><a href="IPO.php"> IPO </a> |</li>
            <li><a href="logout.php"> Logout</a> </li>
            </ul>
        </div>
        <br />
        <br />
        <table id="dashboard_main">
            <tr>
                <td>
                    <div id="user_details">
                        <table id="t1">
                            <caption><b><font color='white' size = '+2'>My shares</font></b></caption>
                            <tr>
                                <th>
                                    Company
                                </th>
                                <th>
                                    Shares
                                </th>
                                <th>
                                    Share Price (&#8377;)
                                </th>
                            </tr>

                            <?php
                                if (mysqli_connect_errno()) {
                                echo "<font color='white'>Failed to connect to MySQL: " . mysqli_connect_error()."</font>";
                                }
                                $query = "SELECT * FROM `shares_data` WHERE `email` = \"$userEmail\"";
                                $result = mysqli_query($conn, $query);
                                #echo (mysqli_num_rows($result));
                                while($row = mysqli_fetch_array($result)) {
                                    $company = $row['company'];
                                    $shares = $row['shares'];
                                    $query1 = "SELECT * FROM `companies` WHERE `name` = \"$company\"";
                                    $result1 = mysqli_query($conn, $query1);
                                    $row1 = mysqli_fetch_array($result1);
                                    $share_price = $row1['share_price'];
                                    echo("
                                        <tr>
                                            <td>
                                                ".$company."
                                            </td>
                                            <td>
                                                ".$shares."
                                            </td>
                                            <td>
                                                ".$share_price."
                                            </td>
                                        </tr>
                                    ");
                                }
                            ?>
                            
                        </table>
                    </div>
                    <br />
                    <div id="my_seller_board_details_display">
                        <?php
                            echo ("
                                <table id='my_seller_board_details'>
                                    <caption><b><font color='white' size = '+2'>My shares on the seller board</font></b></caption>
                                    <tr>
                                        <th>
                                            Company
                                        </th>
                                        <th>
                                            Shares
                                        </th>
                                        <th>
                                            Share Price (&#8377;)
                                        </th>
                                    </tr>
                            ");
                            
                            $query3 = "SELECT * FROM `seller_board` WHERE `email` = \"$userEmail\" ORDER BY `company`";
                            $result3 = mysqli_query($conn, $query3);
                            
                            while($row3 = mysqli_fetch_array($result3)) {
                                echo("
                                    <tr>
                                        <td>
                                            ".$row3['company']."
                                        </td>
                                        <td>
                                            ".$row3['shares']."
                                        </td>
                                        <td>
                                            ".$row3['share_price']."
                                        </td>
                                    </tr>
                                ");
                            }                            
                            echo("</table>");
                        ?>
                    </div>
                </td>
                <td>
                    <table>
                        <tr>
                            <td align="center">
                                <div id="balance_display_holder">
                                    <?php
                                        $query2 = "SELECT `balance` FROM `users` WHERE `email` = \"$userEmail\"";
                                        $result2 = mysqli_query($conn, $query2);                                   
                                        $row2 = mysqli_fetch_array($result2);
                                        echo("
                                            <div id='balance_display'>
                                                <b>Balance: ".$row2['balance']."</b>
                                            </div>
                                        ");
                                    ?>
                                </div>
                                <br />
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <form action="removeFromSellerBoard.php" method="post">
                                    <table id="remove_from_sellerBoard_form">
                                        <tr>
                                            <th>
                                                Remove from seller board
                                            </th>
                                        </tr>
                                        <tr>
                                            <td>
                                                Company: <input type="text" name="company" />
                                                Shares: <input type="number" name="shares" />
                                                <input type="submit" value="Remove" />
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

        </table>
    </body>
</html>
