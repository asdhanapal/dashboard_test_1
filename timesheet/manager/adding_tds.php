<?php
require_once '../classes/db.class.php';
$conn = new db();
$dbcon = $conn->dbConnect();
session_start();

$team=$_POST['team'];
$task=$_POST['task'];
$stask=$_POST['stask'];
$td=$_POST['td'];
$added_by=$_SESSION['manager_id'];

if($team=="" || $task=="" ||  $stask=="" || $td=="")
{
    $result[0]="F";
    $result[1]="Fill the all details!";
    echo json_encode($result);
    exit();

}

$query_check="SELECT task_info FROM amz_task_desc WHERE task_info='$td' AND task_id='$task'";
$result_check= $conn->runsql($query_check,$dbcon);
if(mysqli_num_rows($result_check))
{
    $result[0]="F";
    $result[1]="Task description already exist!";
    echo json_encode($result);
    exit();
}


    $query_add_cf="INSERT INTO amz_task_desc(task_id,task_info,added_by,status,deletion,create_date,maintain_date) VALUES ('$task','$td','$added_by',1,0,now(),now())";
    $result_add_cf= $conn->runsql($query_add_cf,$dbcon);
    if($result_add_cf)
    {
        $result[0]="S";
        $result[1]="Task description added succssfully";
    }
    else
    {
        $result[0]="F";
        $result[1]="An error occured!";
    }
echo json_encode($result);
//print_r($result);
?>