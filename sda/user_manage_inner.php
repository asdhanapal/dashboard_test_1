<?php
require_once '../classes/db.class.php';
$conn = new db();
$dbcon = $conn->dbConnect();

if(isset($_GET['action']))
{
    //print_r($_POST);
	$id = $_POST['id'];
        $status_tmp=$_POST['status'];
        $status=$status_tmp=='A'?" user_activation='1', user_status='1' ":" user_deletion='1' ";
        $sql1 = "UPDATE amz_login SET $status WHERE user_id='$id'";
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