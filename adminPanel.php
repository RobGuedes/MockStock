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
            <li class="current_menu"><a href="adminPanel.php">Dashboard</a> |</li>
            <li><a href="logout.php"> Logout</a> </li>
            </ul>
        </div>
        <br />
        <br />
        <div id="company_performance_editor">
            <form action="company_perf_editor.php" method="post">
                <table id="company_performance_editor">
                    <tr>
                        <th>
                            Update Performance
                        </th>
                    </tr>
                    <tr>
                        <td>
                            Company: <input type="text" name="company" />
                            Performance: <input type="number" step="any" name="performance" />
                            <input type="submit" value="Update" />
                        </td>    
                    </tr>
                </table>
            </form>
        </div>
        <br />
		<!--
        <div id="ipo_editor">
            <form action="ipo_editor.php" method="post">
                <table id="ipo_editor">
                    <tr>
                        <th>
                            Update IPO
                        </th>
                    </tr>
                    <tr>
                        <td>
                            Company: <input type="text" name="company" />
                            Shares: <input type="number" step="any" name="shares" />
                            <input type="submit" value="Update" />
                        </td>    
                    </tr>
                </table>
            </form>
        </div>
		-->
    </body>
</html>
