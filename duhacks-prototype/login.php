<?php

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login']) == TRUE){

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
        <input type="text" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <br>
        <input type="submit" name="login" value="Login">
        <br>
        <br>
        <span>not registered? <a href="register.php">register</a></span>
    </form>
</body>
</html>