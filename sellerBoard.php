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
    mysqli_close($conn);

?>

