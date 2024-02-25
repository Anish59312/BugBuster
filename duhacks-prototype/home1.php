<?php 
  include("config.php");
  session_start();
  if (!$_SESSION["loggedin"])
    header("Location: login.php");
?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet">
  </head>
  <body>
    <div class="banner-area">
      <nav class="navbar navbar-expand-lg navbar-light justify-content-end custom-nav-style">
        <div class="container-fluid ">
        <a class="navbar-brand" href="home1.php">SPLITEASE</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="home1.php">Home</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="group.php">Group</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="create-transaction.php">Create</a>
            </li>
        </ul>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
              <a class="nav-link" href="login.php">login</a>
            </li>
        </ul>
        </div>
        </div>
      </nav>
      <div class="banner-text">
        <h1>
        <?php 
          echo("Welcome, ");
          echo($_SESSION["username"]);
        ?>
        </h1>
      </div>
    </div>
    <div class="footer">
      <h2>About Us</h2>
      <p>At Splitease, we understand the joy of spending time with friends and the shared moments that make life memorable. Our mission is to simplify your financial interactions when you're out and about with your friends.</p>
      <p>Contact us at: <a href="mailto:info@example.com">info@example.com</a></p>
    </div> 
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>