<?php
//include_once '../includes/session_admin.php';
require_once '../classes/db.class.php';
$conn = new db();
$dbcon = $conn->dbConnect();

session_start();
if($_POST['task_id']!="" && $_POST['status']!="")
{
    $task_id=$_POST['task_id'];
    $status=$_POST['status'];
    $cmds=$_POST['cmds'];
    $user_id=$_SESSION['admin_id'];
    
    $query_update_ot_status="UPDATE user_tasks_ot SET ot_status=$status,act_by='$user_id',admin_cmds='$cmds' WHERE task_id='$task_id'";
    $result_update_ot_status= $conn->runsql($query_update_ot_status,$dbcon);
    if($result_update_ot_status)
    {
        echo "OT status updated!";
    }
    else 
    {
        echo "OT updation failed!";
    }
}
else
{
    echo "Internal error occured! Try again laater.";
}
?>