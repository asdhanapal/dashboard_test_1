<?php
require_once '../classes/db.class.php';
$conn = new db();
$dbcon = $conn->dbConnect();
session_start();

$team=$_POST['team'];
$task=$_POST['task'];
$st_status=$_POST['st_status'];
$ot_status=$_POST['ot_status'];
$added_by=$_SESSION['sda_id'];
$cf_type=$_POST['cf_type'];
$complexity=$_POST['complexity'];

if($team=="" || $task=="")
{
    $result[0]="F";
    $result[1]="Fill the all details!";
    echo json_encode($result);
    exit();

}

$query_check="SELECT task_name FROM amz_tasks WHERE task_name='$task' AND team_id='$team'";
$result_check= $conn->runsql($query_check,$dbcon);
if(mysqli_num_rows($result_check))
{
    $result[0]="F";
    $result[1]="Task name already exist!";
    echo json_encode($result);
    exit();
}
    $query_add_cf="INSERT INTO amz_tasks(team_id,task_name,added_by,status,about_cf,have_st,have_tdi,tdi_type,op_type,have_complexity,deletion,create_date,maintain_date) "
                                    . "VALUES ('$team','$task',$added_by,'1',$cf_type,'$st_status',0,0,'$ot_status','$complexity',0,now(),now())";
//die();
$result_add_cf= $conn->runsql($query_add_cf,$dbcon);
    if($result_add_cf)
    {
        $result[0]="S";
        $result[1]="Task added succssfully";
    }
    else
    {
        $result[0]="F";
        $result[1]="An error occured!";
    }
echo json_encode($result);
//print_r($result);
?>
