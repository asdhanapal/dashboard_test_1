<?php
require_once '../includes/define.php';
require_once _BACK_TO_PRE_.DB_CLASS.DB_CLASS_FILE;
require_once _BACK_TO_PRE_._INCLUDE.SESSION_FILE;
$conn = new db();
$dbcon = $conn->dbConnect();

$date=$_POST['date'];
$team=$_POST['team'];
$build=$_POST['build'];
$release=$_POST['release'];
$type=$_POST['type'];
$status=$_POST['status'];

$added_by=USER_ID;

$query_check="SELECT build_no FROM amz_build_mapping WHERE build_no='$build'";
$result_check= $conn->runsql($query_check,$dbcon);
if(mysqli_num_rows($result_check))
{
    $result[0]="F";
    $result[1]="Connection already exist!";
    echo json_encode($result);
    exit();
}

$query_add_cf="INSERT INTO amz_build_mapping(date,team,release_no,build_no,run_status,build_info,created_by) VALUES ('$date','$team','$release','$build','$status','$type','$added_by')";
$result_add_cf= $conn->runsql($query_add_cf,$dbcon);
if($result_add_cf)
{
    $result[0]="S";
    $result[1]="Connected succssfully";
}
else
{
    $result[0]="F";
    $result[1]="Internal error occured! Try again later!";
}
echo json_encode($result);
//print_r($result);
?>
