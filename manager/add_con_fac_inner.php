<?php
require_once '../classes/db.class.php';
$conn = new db();
$dbcon = $conn->dbConnect();
session_start();

$month_from=$_POST['month'];
$team=$_POST['team'];
$task=$_POST['task'];
$s_task=$_POST['s_task'];
//$have_cf=$_POST['is_cf'];
$con_fac=$_POST['con_fac'];
$added_by=$_SESSION['manager_id'];

//if($have_cf)
//    $con_fac='NULL';

if($month_from=="" || $team=="" || $task=="" || $s_task=="" || $con_fac=="") //($have_cf==0 && $con_fac=="")
{
    $result[0]="F";
    $result[1]="Fill the all details!";
    echo json_encode($result);
    exit();

}

$query_check="SELECT * FROM amz_daily_target WHERE month_from='$month_from' AND team='$team' AND task='$task' AND sub_task='$s_task' AND cf_updated=1";
$result_check= $conn->runsql($query_check,$dbcon);
if(mysqli_num_rows($result_check))
{
    $query_add_cf="UPDATE amz_daily_target SET cf_updated=1, con_fac='$con_fac',added_by='$added_by' WHERE month_from='$month_from' AND team='$team' AND task='$task' AND sub_task='$s_task'";
    $result_add_cf= $conn->runsql($query_add_cf,$dbcon);
    if($result_add_cf)
    {
        $result[0]="S";
        $result[1]="Conversion factor updated succesfully for the month ".$month_from.".";
    }
    else
    {
        $result[0]="F";
        $result[1]="An error occured!";
    }
//    $result[0]="F";
//    $result[1]="CF already entered for selected process. Pls try in edit option for modify!";
//    echo json_encode($result);
//    exit();
}
else
{
    $query_add_cf="UPDATE amz_daily_target SET cf_updated=1, con_fac='$con_fac',added_by='$added_by' WHERE month_from='$month_from' AND team='$team' AND task='$task' AND sub_task='$s_task'";
    $result_add_cf= $conn->runsql($query_add_cf,$dbcon);
    if($result_add_cf)
    {
        $result[0]="S";
        $result[1]="Conversion factor added successfully for the month ".$month_from.".";
    }
    else
    {
        $result[0]="F";
        $result[1]="An error occured!";
    }
}
//    $query_add_cf="INSERT INTO amz_daily_target(month_from,team,task,sub_task,have_cf,con_fac,added_by,create_date,maintain_date) VALUES ('$month_from',$team,$task,$s_task,$have_cf,$con_fac,$added_by,now(),now())";
//    $result_add_cf= $conn->runsql($query_add_cf,$dbcon);
//    if($result_add_cf)
//    {
//        $result[0]="S";
//        $result[1]="Conversion factor added successfully for the month ".$month_from.".";
//    }
//    else
//    {
//        $result[0]="F";
//        $result[1]="An error occured!";
//    }
echo json_encode($result);
//print_r($result);
?>