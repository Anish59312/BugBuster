<?php
include("config.php");
session_start();
//add logged in constraint
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['createTransactionForm'])) {
    $groupName = trim($_POST["groupname"]);
    $amount = $_POST['amount'];
    $remark = $_POST['remark'];
    $createQuery = "INSERT INTO transaction(transaction_amount, transaction_description, transaction_date, transaction_mode, transaction_group_id, transaction_user_id, transaction_time) VALUES(?,?,?,'pending',?,?,?)";
    $stmtCreate = mysqli_prepare($link, $createQuery);
    $date = date("Y-m-d");
    $time = date("H:i:s");
    $groupID;
    $userID;
    //code for userID from $_SESSION
    $userID = $_SESSION["user_id"];
    //code for userID from $_SESSION
    $groupIdQuery = "SELECT  group_id FROM groups WHERE group_name=?";
    $stmt = mysqli_prepare($link, $groupIdQuery);
    mysqli_stmt_bind_param($stmt, "s", $groupName);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $groupID = $row['group_id'];
    } else {
        die("not such group");
    }
    mysqli_stmt_bind_param($stmtCreate, "isssss", $amount, $remark, $date, $groupID, $userID, $time);
    if (mysqli_stmt_execute($stmtCreate)) {
        echo ("succesfull");
    } else {
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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>create transaction</title>
</head>

<body>
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
    <div class="create-container">
        <form method="post" action="">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="floatingInput" placeholder="group name" name="groupname">
                <label for="floatingInput">Group Name</label>
            </div>
            <div class="form-floating">
                <input type="number" class="form-control" id="floatingInput" placeholder="amount paid by you"
                    name="amount">
                <label for="floatingPassword">Amount</label>
            </div>
            <div class="form-floating">
                <input type="text" class="form-control" id="floatingInput" placeholder="how did you spend it?"
                    name="remark">
                <label for="floatingInput">Remark</label>
            </div>
            <div class="col-12">
                <button class="btn btn-primary" type="submit" name="createTransactionForm">Submit</button>
            </div>
        </form>
    </div>
    <br>
    <div>
        <button class="btn  mx-2" id="viewTransactionsBtn">View Transactions</button>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="viewTransactionModal" tabindex="-1" role="dialog" aria-labelledby="viewTransactionLable"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewTransactionLable">Transaction History</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <?php
    // Fetch pending transactions
    $sql = "SELECT t.transaction_id, g.group_name, t.transaction_date, t.transaction_amount 
            FROM transaction t
            JOIN groups g ON t.transaction_group_id = g.group_id
            WHERE t.transaction_mode = 'pending' AND t.transaction_user_id = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    // Display pending transactions
    echo '<div class="media my-3">
            <div class="media-body">
                <h4>Pending...</h4>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<p class="my-0"><b>' . $row["group_name"] . '</b> on ' . date('F j, Y', strtotime($row["transaction_date"])) . '</p>
                  Amount: ' . $row["transaction_amount"] . '
                  <button type="button" class="btn btn-primary btn-group-sm mt-2">Mark As Completed</button>';
    }
    echo '</div></div>';

    // Fetch successful transactions
    $sql = "SELECT t.transaction_id, g.group_name, t.transaction_date, t.transaction_amount 
            FROM transaction t
            JOIN groups g ON t.transaction_group_id = g.group_id
            WHERE t.transaction_mode = 'successful' AND t.transaction_user_id = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    // Display successful transactions
    echo '<div class="media my-3">
            <div class="media-body">
                <hr><h4>Successful...</h4>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<p class="my-0"><b>' . $row["group_name"] . '</b> on ' . date('F j, Y', strtotime($row["transaction_date"])) . '</p>
                  Amount: ' . $row["transaction_amount"];
    }
    echo '</div></div>';

    // Fetch failed transactions
    $sql = "SELECT t.transaction_id, g.group_name, t.transaction_date, t.transaction_amount 
            FROM transaction t
            JOIN groups g ON t.transaction_group_id = g.group_id
            WHERE t.transaction_mode = 'error' AND t.transaction_user_id = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    // Display failed transactions
    echo '<div class="media my-3">
            <div class="media-body">
                <hr><h4>Failed...</h4>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<p class="my-0"><b>' . $row["group_name"] . '</b> on ' . date('F j, Y', strtotime($row["transaction_date"])) . '</p>
                  Amount: ' . $row["transaction_amount"];
    }
    echo '</div></div>';
    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Option 1: Bootstrap Bundle with Popper -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> -->
    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
        crossorigin="anonymous"></script>
    <script>
        // Add an event listener to the View Transactions button
        document.getElementById('viewTransactionsBtn').addEventListener('click', function () {
            // Show the modal
            var myModal = new bootstrap.Modal(document.getElementById('viewTransactionModal'));
            myModal.show();
        });
    </script>
    

</body>

</html>