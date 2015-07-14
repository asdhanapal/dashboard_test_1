<?php
require_once '../classes/db.class.php';
$conn = new db();
$dbcon = $conn->dbConnect();

function get_details($)
$id = $_POST['id'];
$sql1 = "DELETE FROM amz_daily_target WHERE s_no = '$id'";
$result1=$conn->runsql($sql1,$dbcon);
if($result1)
{
        echo "<font color='green'>Conversion factor deleted Successfully</font>";
}
else
{
        echo "<font color='red'>Something went wrong!</font>";
}
?>