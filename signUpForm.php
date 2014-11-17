<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" type="text/css" href="StyleSheet.css" />
        <title>TV Trading Co., Ltd.</title>
    </head>
    <body>
        <div id="title">
            <h1>TV Trading Co., Ltd.</h1>stalking the beauty of trade since time immemorial .....
        </div>
        <hr>
        <br />

        <?php
            $name = $_POST['name'];
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

            $query = "SELECT `name` FROM `users` WHERE `email` = \"$email\"";
            #echo($query);
            $result = mysqli_query($conn, $query);

            if(mysqli_num_rows($result) == 0) {
                $query = "INSERT INTO `users` (name, email, password, balance) VALUES (\"$name\", \"$email\", \"$password\", 50000)";
                $result = mysqli_query($conn, $query);
                echo("<div id='transaction_status'><font color='green'>Player registration successful!</font></div>");
                header('Location:login.html');                
            }
            else {
                echo("
                    <div id='sign_up_form'>
                        Email already exists! <br /> <br />
                        <form action='signUpForm.php' method='post'>
                        Name: <input type='text' name='name' />
                        Email: <input type='email' name='email' />
                        Password: <input type='password' name='password' />
                        <input type='submit' value='Sign Up' />
                        </form> 
                     </div>
                ");
            }
            mysqli_close($conn);
        ?>
    </body>
</html>
