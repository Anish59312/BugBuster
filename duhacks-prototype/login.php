<?php
include('config.php');
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login']) && isset($_POST['username']) && isset($_POST['password']) && !empty(trim($_POST['password'])) && !empty(trim($_POST['username']))) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $check_usr_exsist = "SELECT * FROM user WHERE user_name = '$username'";
    $result = mysqli_query($link, $check_usr_exsist);
    $_SESSION = false;
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if ($password == $row['user_password'] ) {
            $_SESSION["loggedin"] = true;
            $_SESSION["username"] = $row['user_name'];
            $_SESSION["user_id"] = $row['user_id'];
            header('Location: home1.php');
            exit();
        } else {
            echo "code for error(Invalid password)";

        }
    } else {
        echo "code for error(user does not exist please register)";
    }

}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="register.css">
</head>

<body>
    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" name="username" id = "username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <input type="submit" name="login" value="Login">
        <br>
        <br>
        <span>not registered? <a href="register.php">register</a></span>
    </form>
</body>

</html>