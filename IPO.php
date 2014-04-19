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
        <table id="ipo_main">
            <tr>
                <td>
                    <div id="ipo">
                        <?php
                            echo("
                                <table id='ipo'>
                                    <caption><b><font color='white' size = '+2'>Companies offering IPO</font></b></caption>
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
                            $query = "SELECT * FROM `ipo` ORDER BY `company`";
                            $result = mysqli_query($conn, $query);
                            while($row = mysqli_fetch_array($result)) {
                                $company = $row['company'];
                                $query1 = "SELECT `share_price` FROM `companies` WHERE `name` = \"$company\"";
                                $result1 = mysqli_query($conn, $query1);
                                $row1 = mysqli_fetch_array($result1);

                                echo("
                                    <tr>
                                        <td>
                                            ".$row['company']."
                                        </td>
                                        <td>
                                            ".$row['shares']."
                                        </td>
                                        <td>
                                            ".$row1['share_price']."
                                        </td>
                                    </tr>
                                ");
                            }
                            echo("</table>");
                        ?>
                    </div>
                </td>

                <td>
                    <form action="ipoForm.php" method="post">
                        <table id="ipo_form">
                            <tr>
                                <th>
                                    Buy shares
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    Company: <input type="text" name="company"/>
                                    Shares: <input type="number" name="shares" />
                                    <input type="submit" value="Buy" />
                                </td>
                            </tr>
                        </table>
                    </form>
                </td>
            </tr>
        </table>        
    </body>
</html>
