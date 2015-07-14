<?php
require_once '../includes/define.php';
require_once _BACK_TO_PRE_.DB_CLASS.DB_CLASS_FILE;
require_once _BACK_TO_PRE_._INCLUDE.SESSION_FILE;
$conn = new db();
$dbcon = $conn->dbConnect();

$team=$_POST['team'];
$release=$_POST['release'];
$release=mysqli_real_escape_string($dbcon,$release);
$added_by=USER_ID;

$query_check="SELECT release_name FROM amz_releases WHERE team_id='$team' AND release_name='$release'";
$result_check= $conn->runsql($query_check,$dbcon);
if(mysqli_num_rows($result_check))
{
    $result[0]="F";
    $result[1]="Release name already exist!";
    echo json_encode($result);
    exit();
}

$query_add_cf="INSERT INTO amz_releases(team_id,release_name,added_by) VALUES ('$team','$release',$added_by)";
$result_add_cf= $conn->runsql($query_add_cf,$dbcon);
if($result_add_cf)
{
    $result[0]="S";
    $result[1]="Release added succssfully";
}
else
{
    $result[0]="F";
    $result[1]="Internal error occured! Try again later!";
}
echo json_encode($result);
//print_r($result);
?>
