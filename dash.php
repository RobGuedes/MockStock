<?php
                    session_start();
    if(!isset($_SESSION['user_email'])) {
        header('Location: login.html');
    }
    $userEmail = $_SESSION['user_email'];
    #$userEmail = "sumukhatv@live.in";
    $userName = $_SESSION['user_name'];
    #$userName = "Sumukha TV";
    $file = file_get_contents('./config.txt', FILE_USE_INCLUDE_PATH);
    $str = preg_split("/[\s,\n]+/", $file);
    $sql_host = $str[1];
    $sql_user = $str[3];
    $sql_password = $str[5];
    $sql_db = $str[7];

    $conn = mysqli_connect($sql_host, $sql_user, $sql_password, $sql_db);

    echo ("
            <table id='t1'>
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
        ");
    
     
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
             mysqli_close($conn);

    echo ("</table>");
?>
