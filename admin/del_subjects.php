<?php
require_once '../classes/db.class.php';
$conn = new db();
$dbcon = $conn->dbConnect();
session_start();
$edited_by=$_SESSION['admin_id'];

$subject=$_GET['action'];
switch ($subject)
{
    case "team":
        $edit_id= $_POST['id'];
        $query_delete="UPDATE amz_teams SET team_deletion='1',last_modify=now() WHERE team_id='$edit_id'";
        $result_update= $conn->runsql($query_delete,$dbcon);
        break;
    
    case "task":
        $edit_id= $_POST['id'];         
        $query_delete="UPDATE amz_tasks SET deletion='1',last_modified_by='$edited_by' WHERE task_id='$edit_id'";
        $result_update= $conn->runsql($query_delete,$dbcon);
        break;
    
    case "subtask":
        $edit_id= $_POST['id'];
        $query_delete="UPDATE amz_sub_tasks SET deletion='1',last_modify_by='$edited_by' WHERE sub_task_id='$edit_id'";
        $result_update= $conn->runsql($query_delete,$dbcon);
        break;
    
    case "taskdes":
        $edit_id= $_POST['id'];
        $query_delete="UPDATE amz_task_desc SET deletion='1' WHERE tdi_no='$edit_id'";
        $result_update= $conn->runsql($query_delete,$dbcon);
        break;
    
    default:
        echo "Internal server error!";
}

if($result_update)
{
    $result[0]="S";
    $result[1]="Deletion successfully.";
}
else
{
    $result[0]="F";
    $result[1]="An error occured!";
}
echo json_encode($result);
?>