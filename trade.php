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
                $('#seller_board').load('sellerBoard.php');
                $('#performance_board').load('performanceBoard.php');
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
            <li class="current_menu"><a href="trade.php"> Trade </a> |</li>
            <li><a href="IPO.php"> IPO </a> |</li>
            <li><a href="logout.php"> Logout</a> </li>
            </ul>
        </div>
        <br />
        <br />
        
        <table id="trade">
            <tr>
                <td>
                    <div id="seller_board">
                        <?php
    
                            $query = "SELECT * FROM `seller_board` ORDER BY `company`";
                            $result = mysqli_query($conn, $query);
                            echo("
                                <table id='t2'>
                                    <caption><b><font color='white' size = '+2'>Seller Board</font></b></caption>
                                    <tr>
                                        <th>
                                            Seller
                                        </th>
                                        <th>
                                            Email
                                        </th>
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
                            while($row = mysqli_fetch_array($result)) {
                                echo("     
                                        <tr>
                                            <td>
                                                ".$row['name']."
                                            </td>
                                            <td>
                                                ".$row['email']."
                                            </td>
                                            <td>
                                                ".$row['company']."
                                            </td>
                                            <td>
                                                ".$row['shares']."
                                            </td>
                                            <td>
                                                ".$row['share_price']."
                                            </td>
                                        </tr>
                                    ");
                            }
                            echo("</table>");
                            

                        ?>
                    </div>
                    <br />
                    <div id="performance_board">
                        <?php
                            $query1 = "SELECT * FROM `companies` ORDER BY `name`";
                            $result1 = mysqli_query($conn, $query1);
                            echo("
                                <table id='t2'>
                                    <caption><b><font color='white' size = '+2'>Company Details</font></b></caption>
                                    <tr>
                                        <th>
                                            Company
                                        </th>
                                        <th>
                                            Share Price (&#8377;)
                                        </th>
                                        <th>
                                            Performance
                                        </th>
                                    </tr>
                             ");
                             while($row = mysqli_fetch_array($result1)) {
                                echo("     
                                        <tr>
                                            <td>
                                                ".$row['name']."
                                            </td>
                                            <td>
                                                ".$row['share_price']."
                                            </td>
                                            <td>
                                                ".$row['performance']."
                                            </td>
                                        </tr>
                                    ");
                            }
                            echo("</table>");
                        ?>
                    </div>
                </td>
                <td>
                    <div id="buy_form">
                        <form action="buyForm.php" method="post">
                            <table id="buy_form">
                                <tr>
                                    <th>
                                        Buy Shares
                                    </th>
                                </tr>

                                <tr>
                                    <td>
                                        Company:<input type="text" name="company" />
                                    
                                        Shares:<input type="number" name="shares" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Seller email:<input type="email" name="seller_email" />
                                    
                                        <input type="submit" value="Buy" />
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                    <br />
                    <div id="sell_form">
                        <form action="sellForm.php" method="post">
                            <table id="sell_form">
                                <tr>
                                    <th>
                                        Sell Shares
                                    </th>
                                </tr>

                                <tr>
                                    <td>
                                        Company:<input type="text" name="company" />
                                    
                                        Shares:<input type="number" name="shares" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Share Price:<input type="number" name="share_price" />
                                        <input type="submit" value="Sell" />
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </td>
            </tr>
        </table>

    </body>
</html>
