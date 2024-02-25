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
      if(!$stmt->execute()){
        die("duplicate entry error");
      }
    
      header("Location: group.php");

    echo("<script>showSuccessNotification();</script>");
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

<style>
        /* Optional: Add some basic styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa; /* Light gray background */
            padding: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        #userName {
            display: none;
        }

        /* Style the "Add" button */
        .btn-add {
            background-color: blue ; 
            color: #fff; /* White text */
            border: none;
            padding: auto;
            cursor: pointer;
            margin-top: 10px;

        }

        /* Hide the textarea input */
        #userName {
            display: none;
        }

        .success-notification {
          display: none;
          background-color: #28a745; /* Bootstrap success color */
          color: #fff;
          padding: 10px;
          border-radius: 5px;
          position: fixed;
          top: 0px;
          left: 5px;
          margin-top: 10px;
          box-shadow: rgba(0, 0, 0, 0.16) 0px 10px 36px 0px, rgba(0, 0, 0, 0.06) 0px 0px 0px 1px;;
          z-index: 1000;
        }
    </style>

<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light justify-content-end">
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
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop" style="width:100%;">
  Click Here to Create a Group
</button>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Create Group</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
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
        <label for="newFriend">Enter Friend's Name:</label>
        <input type="text" class="form-control" id="newFriend" placeholder="Enter friend's username">
        <button class="btn-add btn" type="button" onclick="addFriend()">Add</button>
      </div>
      <div class="form-group">
        <ul id="list-in-modal">
        </ul>
      </div>
      <div class="form-group">
        <input type="textarea" class="form-control" id="userName" name="userName" placeholder="Enter friends' username">
      </div>
      <button type="submit" class="btn btn-primary" name="groupCreateSubmit">Submit</button>
    </form>
  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


  <div class="view-group-container">
    <?php include("view-group.php");?>
  </div>


  <div class="success-notification" id="successNotification">
    Friend added successfully!
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"></script>
    <script src="script.js"></script>
</body>

</html>