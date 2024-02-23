


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid ">
        <a class="navbar-brand" href="#">SPLITWISE</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Home</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="group.php">Group</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="#">Create</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="#">Transactions</a>
            </li>
        </ul>
        </div>
    </div>
    </nav>

<form method="post" action="" >
  <div class="form-group">
    <label for="exampleInputEmail1">Group id</label>
    <input type="text" class="form-control" id="" name="group_id" placeholder="Enter group id">
  </div>
  <div class="form-group">
    <label for="exampleInputEmail1">User id</label>
    <input type="text" class="form-control" id="" name="user_id" placeholder="Enter group id">
  </div>
  <button type="submit" class="btn btn-primary" name="cgsub">Submit</button>
</form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>

<?php

    require_once "config.php";

    if(isset($_POST["cgsub"])){
        $group_id = intval($_POST["group_id"]);
        $user_id = intval($_POST["user_id"]);

        var_dump($user_id);
        echo("<br>");

        $users = explode(",",$user_id);
        //array of users

        $users = array_map('intval', $users);
        //users as int

        var_dump($users);
        echo("<br>");

        $query = "INSERT INTO `friendcircle`(`group_id`, `user_id`) VALUES(?, ?)";
        
        $stmt = mysqli_prepare($link, $query);

        foreach($users as $usr){
            mysqli_stmt_bind_param($stmt, "ii", $group_id, $usr);
            if(mysqli_stmt_execute($stmt)){
                echo($group_id . " - ". $usr."<br>");
            }
            else{
                echo("error");
            }
        }

        echo("concept: comma seperated values for multiple insert <br> further develop this for better ui");
    }

?>
