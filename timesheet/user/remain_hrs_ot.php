<?php
session_start();
require_once '../classes/db.class.php';
require_once '../includes/time_calc.php';
$conn = new db();
$dbcon = $conn->dbConnect();
$user_id=$_SESSION['user_id'];
$date=$_POST['date'];
$secs=0;

$query="SELECT time FROM user_tasks_ot WHERE date='$date' AND user_id='$user_id'";
$result = $conn->runsql($query, $dbcon);
if (mysqli_num_rows($result)) 
{
    while ($result_row = mysqli_fetch_object($result)) 
    {
        $secs+= strtotime($result_row->time)-strtotime("00:00:00");
    }
    $remain_secs=57600-$secs;
    $remain_hrs=sectohr($remain_secs);
}
else
{
    $remain_hrs="16:00:00";
    $remain_secs="57600";

}
$json_result[0]=$remain_hrs;
$json_result[1]=$remain_secs;
echo json_encode($json_result);
?>