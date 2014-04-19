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
