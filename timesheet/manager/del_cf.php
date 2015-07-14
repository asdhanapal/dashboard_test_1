<?php
require_once '../classes/db.class.php';
$conn = new db();
$dbcon = $conn->dbConnect();

if(isset($_GET['action']))
{
	$id = $_POST['id'];
        $sql1 = "UPDATE amz_daily_target SET cf_updated=0,con_fac='NULL', wu_status=0 WHERE s_no = '$id'";
        $result1=$conn->runsql($sql1,$dbcon);
	if($result1)
	{
		echo "<font color='green'>Conversion factor deleted Successfully</font>";
	}
	else
	{
		echo "<font color='red'>Something went wrong!</font>";
	}
}
?>