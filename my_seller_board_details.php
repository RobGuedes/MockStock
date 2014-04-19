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


