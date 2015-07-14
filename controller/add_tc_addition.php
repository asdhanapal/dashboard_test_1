<?php
require_once '../includes/define.php';
require_once _BACK_TO_PRE_.DB_CLASS.DB_CLASS_FILE;
require_once _BACK_TO_PRE_._INCLUDE.SESSION_FILE;
$conn = new db();
$dbcon = $conn->dbConnect();

$date=$_POST['date'];
$team=$_POST['team'];
$release=$_POST['release'];
$task=$_POST['task'];
$mode=$_POST['mode'];
$count=$_POST['count'];

$added_by=USER_ID;

$query_add_cf="INSERT INTO amz_tc_addition(date,team,release_no,task,sub_task,count,added_by) VALUES ('$date','$team','$release','$task','$mode','$count','$added_by')";
$result_add_cf= $conn->runsql($query_add_cf,$dbcon);
if($result_add_cf)
{
    $result[0]="S";
    $result[1]="Added successfully!";
}
else
{
    $result[0]="F";
    $result[1]="Internal error occured! Try again later!";
}
echo json_encode($result);
?>