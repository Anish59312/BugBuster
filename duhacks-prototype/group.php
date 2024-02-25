<!-- it will add group members to group-user table and create  row in group table -->
<!-- in case loggedin user doesn't type his/her username they will auomatically be part of the group -->
<!-- multiple values are added using comma seperation. use concept of , speration with javascript to replace , sepration with add buttom. javascript will do comma sepration behind html -->
<?php
require_once "config.php";
session_start();
if ($_SESSION["loggedin"] == "true") {
  if (isset($_POST["groupCreateSubmit"])) {
    $groupName = $_POST["groupName"];
    $groupDescription = $_POST["groupDescription"];
    $users = $_POST["userName"];
    $usersID = [];

    $users = explode(",", $users);
    if(!in_array($_SESSION["username"],$users)){
      $users[]  = $_SESSION["username"];
    }
      $numberOfMembers = count($users);


    $insetGroupsQuery = "INSERT INTO `groups`(`group_name`, `group_description`, `number_of_members`) VALUES(?,?,?)";

    $stmt = mysqli_prepare($link, $insetGroupsQuery);
    mysqli_stmt_bind_param($stmt, "ssi", $groupName, $groupDescription, $numberOfMembers);
    $result = mysqli_stmt_execute($stmt);

    //check if group name is already taken
    if ($result == false) {
      die('group name already taken');
    }

    $findGroupIDQuery = "SELECT `group_id` FROM `groups` WHERE `group_name`=?";

    $stmt = mysqli_prepare($link, $findGroupIDQuery);
    mysqli_stmt_bind_param($stmt, "s", $groupName);
    $stmt->execute();
    $result = $stmt->get_result();

    $groupID = $result->fetch_assoc()['group_id'];

    $insertInUserGroupsQuery = "INSERT INTO `group_user`(`group_id`, `user_id`) VALUES(?,?)";
    $stmt = mysqli_prepare($link, $insertInUserGroupsQuery);

    $findUserIdQuery = "SELECT `user_id` FROM `user` WHERE `user_name` = ?";
    $stmt2 = mysqli_prepare($link, $findUserIdQuery);
;
    $usersID = [];

    foreach ($users as $usr) {
      mysqli_stmt_bind_param($stmt2, "s", $usr);
      $stmt2->execute();  
      $resultUserID = $stmt2->get_result();
      if ($resultUserID->num_rows == 0) {
        die("user not found");
        break;
      }
      $ID = $resultUserID->fetch_assoc()['user_id'];
      array_push($usersID, $ID);
    }
    foreach ($usersID as $usrid) {
      mysqli_stmt_bind_param($stmt, "ii", $groupID, $usrid);
      $stmt->execute();
    }

  }
} else {
  header("Location: login.php");
}
?>


<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bootstrap demo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid ">
      <a class="navbar-brand" href="home.php">SPLITWISE</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
  <div id="addGroupContainer">
    <form method="post" action="">
      <div class="form-group">
        <label for="exampleInputEmail1">Group Name</label>
        <input type="text" class="form-control" name="groupName" placeholder="group name">
      </div>
      <div class="form-group">
        <label for="exampleInputEmail1">Group Description</label>
        <input type="textarea" class="form-control" name="groupDescription" placeholder="description">
      </div>
      <div class="form-group">
        <label for="userName">Friends</label>
        <input type="textarea" class="form-control" name="userName" placeholder="Enter friends' username">
      </div>
      <button type="submit" class="btn btn-primary" name="groupCreateSubmit">Submit</button>
    </form>
  </div>

  <div class="view-group-container">
    <?php include("view-group.php");?>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"></script>
</body>

</html>