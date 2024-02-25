<link href="group.css" rel="stylesheet">
<ul class="item-list">
<?php
    require_once "config.php";
    // session_start();

    $getGroupsId = "SELECT DISTINCT `group_id` FROM `group_user` WHERE `user_id` =".$_SESSION["user_id"];
    $result = mysqli_query($link, $getGroupsId);
    
    if(mysqli_num_rows($result) == 0){
        die("join a group first");
    }

    $getGroupName = "SELECT `group_name` FROM `groups` WHERE `group_id` = ? ";
    $stmt = mysqli_prepare($link, $getGroupName);
    
    while($row = $result->fetch_assoc()){
        $group_id = $row["group_id"];
        mysqli_stmt_bind_param($stmt, "i", $group_id);
        $stmt->execute();
        $result2 = $stmt->get_result();
        
        $row2 = $result2->fetch_assoc();
        $groupName = null;
        if($row2 != null){
            $groupName = $row2["group_name"];
        }
        else{
            // echo("group not found error");
            // var_dump($row);
            continue;
        }
        echo ("<li class='group-item'>".$groupName."</li>");
    }
    $stmt->close();

?>

</ul>
