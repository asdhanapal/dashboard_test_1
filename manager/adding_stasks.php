<?php
require_once '../classes/db.class.php';
$conn = new db();
$dbcon = $conn->dbConnect();
session_start();

$team=$_POST['team'];
$task=$_POST['task'];
$stask=$_POST['stask'];
$priority=$_POST['complex'];
$added_by=$_SESSION['manager_id'];

if($team=="" || $task=="")
{
    $result[0]="F";
    $result[1]="Fill the all details!";
    echo json_encode($result);
    exit();

}

$query_check="SELECT sub_task_name FROM amz_sub_tasks WHERE sub_task_name='$stask' AND team_id='$team' AND task_id='$task'";
$result_check= $conn->runsql($query_check,$dbcon);
if(mysqli_num_rows($result_check))
{
    $result[0]="F";
    $result[1]="Sub task already exist!";
    echo json_encode($result);
    exit();
}

    $query_add_cf="INSERT INTO amz_sub_tasks(team_id,task_id,sub_task_name,cf_change,auto_cf,task_status,deletion,create_date,maintain_date) VALUES ('$team','$task','$stask',$priority,0,1,0,now(),now())";
    $result_add_cf= $conn->runsql($query_add_cf,$dbcon);
    if($result_add_cf)
    {
        $result[0]="S";
        $result[1]="Sub task added succssfully";
    }
    else
    {
        $result[0]="F";
        $result[1]="An error occured!";
    }
echo json_encode($result);
//print_r($result);
?>