<?php
require_once '../classes/db.class.php';
$conn = new db();
$dbcon = $conn->dbConnect();

if(isset($_GET['action']))
{
	$id = $_POST['id'];
         $sql1 = "DELETE FROM user_tasks_ot WHERE task_id = '$id'";
        $result1=$conn->runsql($sql1,$dbcon);
	if($result1)
	{
		echo "<font color='green'>Task deleted Successfully</font>";
	}
	else
	{
		echo "<font color='red'>Something went wrong!</font>";
	}
}
?>