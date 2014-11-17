<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title></title>
    </head>
    <body>
       <?php
        $email = $_POST['email'];
        $password = $_POST['password'];
        $file = file_get_contents('./config.txt', FILE_USE_INCLUDE_PATH);
        $str = preg_split("/[\s,\n]+/", $file);
        $sql_host = $str[1];
        $sql_user = $str[3];
        $sql_password = $str[5];
        $sql_db = $str[7];

        $conn = mysqli_connect($sql_host, $sql_user, $sql_password, $sql_db);

        if (mysqli_connect_errno()) {
            echo "<font color='white'>Failed to connect to MySQL: " . mysqli_connect_error()."</font>";
        }

        $query = "SELECT * FROM `admin` WHERE `email` = \"$email\" AND `password` = \"$password\"";
        #echo($query);
        $result = mysqli_query($conn, $query);

        if(mysqli_num_rows($result) == 0) {
            echo("
                    <div id='login_form'>
                        Email and password do not match! <br /> <br />
                        <form action='loginForm.php' method='post'>
                            Email: <input type='email' name='email' />
                            Password: <input type='password' name='password' />
                            <input type='submit' value='login' />
                        </form>  
                     </div>
                ");
        }
        else{
            $row = mysqli_fetch_array($result);
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['user_name'] = $row['name'];
            header('Location: adminPanel.php');
        }

        mysqli_close($conn);
       ?> 
    </body>
</html>
