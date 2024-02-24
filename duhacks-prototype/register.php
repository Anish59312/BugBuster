<?php
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    $check_usr_exsist = "SELECT `user_id` FROM `user` WHERE `user_name` = '$username'";
    $result = mysqli_query($link, $check_usr_exsist);
    if(mysqli_num_rows($result) > 0){
        header("Location: register.php?message=".urlencode("username already exsist"));
    }
    else{
        $registerTimestamp = date("Y-m-d H:i:s");
        $insert_query = "INSERT INTO `user`(`user_name`, `user_password`, `user_email`, `register_time`) VALUES(?, ?, ?, ?)";
        $stmt = $link->prepare($insert_query);
        $stmt->bind_param("ssss",$username,$password,$email,$registerTimestamp);
        if($stmt->execute()){
            echo("succesfully registered");
            header("Location: home1.php");
        }
        else
            echo("eror".$stmt->error);
        $stmt->close();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Registration</title>
    <link rel="stylesheet" type="text/css" href="register.css">
</head>
<body>
    <h2>User Registration</h2>
    <form method="post" action="">
        <label for="email">Email:</label>
        <input type="email" name="email" required>
        <br>
        <label for="username">Username:</label>
        <input type="text" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <br>
        <input type="submit" name="register" value="Register">
        <br>
        <br>
        <span>already registered? <a href="login.php">login</a></span>
    </form>

    <p><?php 
    if(isset( $_GET['message'])){
        echo ($_GET['message']);
    }
    ?>
    </p>
</body>
</html>