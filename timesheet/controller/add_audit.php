<?php
require_once '../includes/define.php';
require_once _BACK_TO_PRE_.DB_CLASS.DB_CLASS_FILE;
require_once _BACK_TO_PRE_._INCLUDE.SESSION_FILE;
$conn = new db();
$dbcon = $conn->dbConnect();

$date=$_POST['date'];
$team=$_POST['team'];
$user=$_POST['user'];
$audit=$_POST['audit'];
$task=$_POST['task'];
$misses=$_POST['misses'];
$impact=$_POST['impact'];
$comments=$_POST['comments'];
$good_catches=$_POST['good_catches'];

$added_by=USER_ID;

$query_add_cf="INSERT INTO amz_audits(date,team,user,task,audit,misses,impact,comments,good_catches,created_by) VALUES ('$date','$team','$user','$task','$audit','$misses','$impact','$comments','$good_catches','$added_by')";
$result_add_cf= $conn->runsql($query_add_cf,$dbcon);
if($result_add_cf)
{
    $result[0]="S";
    $result[1]="Added succssfully";
}
else
{
    $result[0]="F";
    $result[1]="Internal error occured! Try again later!";
}
echo json_encode($result);
//print_r($result);
?>
