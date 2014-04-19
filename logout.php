<?php
    session_start();
?>

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
        <?php
            unset($_SESSION['user_email']);
            unset($_SESSION['user_name']);
            header('Location: login.html');
        ?>
    </body>
</html>
