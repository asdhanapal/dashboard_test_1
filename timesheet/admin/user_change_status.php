<?php
require_once '../classes/db.class.php';
$conn = new db();
$dbcon = $conn->dbConnect();

if(isset($_GET['action']))
{
	$id = $_POST['id'];
        $status_tmp=$_POST['status'];
        $sql1 = "UPDATE amz_login SET user_status=$status_tmp WHERE user_id='$id'";
        $result1=$conn->runsql($sql1,$dbcon);
	if($result1)
	{
               echo "<font color='green'>User updation complete!</font>";
	}
	else
	{
		echo "<font color='red'>Something went wrong!</font>";
	}
}
?>