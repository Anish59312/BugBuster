<?php
    include("config.php");
    session_start();


    if($_SESSION["loggedin"] == false)
    {
        header("Location: login.php?message=".urlencode("login first"));
    }
    //add logged in constraint
    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['createTransactionForm'])){
        $groupName = trim($_POST["groupname"]);
        $amount = $_POST['amount'];
        $remark = $_POST['remark'];


        $createQuery = "INSERT INTO `transaction`(`transaction_amount`, `transaction_description`, `transaction_date`, `transaction_mode`, `transaction_group_id`, `transaction_user_id`, `transaction_time`) VALUES(?,?,?,'pending',?,?,?)";
        $stmtCreate = mysqli_prepare($link, $createQuery);
        $date = date("Y-m-d");
        $time = date("H:i:s");
        $groupID; 
        $userID;
        //code for userID from $_SESSION
        $userID = $_SESSION["user_id"];
        //code for userID from $_SESSION
        $groupIdQuery = "SELECT  group_id FROM groups WHERE `group_name`=?";
        $stmt = mysqli_prepare($link, $groupIdQuery);
        mysqli_stmt_bind_param($stmt, "s", $groupName);   
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);

        if($row){
            $groupID = $row['group_id'];
        }
        else{
            die("not such group");
        }
        mysqli_stmt_bind_param($stmtCreate, "isssss",$amount, $remark, $date, $groupID, $userID, $time);
        if(mysqli_stmt_execute($stmtCreate)) {
            echo("succesfull");
        }
        else{
            die("insertion failed");
        }

    }



?>



<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>create transaction</title>
  </head>
  <body>
    <div class="create-container">
        <form method="post" action="">
            <div class="form-floating mb-3">
            <input type="text" class="form-control" id="floatingInput" placeholder="group name" name= "groupname">
            <label for="floatingInput">Group Name</label>
            </div>
            <div class="form-floating">
            <input type="number" class="form-control" id="floatingInput" placeholder="amount paid by you" name="amount">
            <label for="floatingPassword">Amount</label>
            </div>
            <div class="form-floating">
            <input type="text" class="form-control" id="floatingInput" placeholder="how did you spend it?" name="remark">
            <label for="floatingInput">Remark</label>
            </div>
            <div class="col-12">
            <button class="btn btn-primary" type="submit" name="createTransactionForm">Submit</button>
            </div>
        </form>
    </div>

    <style>
        /* temporary css */
        /***          ***/
        .create-container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
        }

        .form-floating {
            margin-bottom: 20px;
        }

        /* Center the button within the column */
        .col-12 {
            display: flex;
            justify-content: center;
        }

        /* Adjust button styles */
        .btn-primary {
            width: 100%;
        }

        /* Optional: Add some padding to form elements for better appearance */
        .form-control {
            padding: 10px;
        }
        .create-container a:last-child {
            color: red;
            font-weight: bold;
        }
    </style>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script> -->
   <br>
   <a href='view-transaction.php'>View Transaction</a>
  </body>
</html>