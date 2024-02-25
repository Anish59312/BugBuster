<div class="viweTransactionContainer">
<?php
    require_once "config.php";
    session_start();

    $groupIdSelect = "SELECT DISTINCT `group_id` FROM `group_user` WHERE `user_id` = '".$_SESSION['user_id']."'";
    $result = mysqli_query($link, $groupIdSelect);
    $groupIds = [];

    if(mysqli_num_rows($result) == 0)
        die("join a group first");
    
    while ($row = mysqli_fetch_assoc($result)) {
        $groupIds[] = $row['group_id'];
    }

    mysqli_free_result($result);

    $query = "SELECT FROM"
?>
</div>