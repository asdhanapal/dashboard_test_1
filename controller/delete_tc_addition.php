<?php
require_once '../includes/define.php';
require_once _BACK_TO_PRE_.DB_CLASS.DB_CLASS_FILE;
require_once _BACK_TO_PRE_._INCLUDE.SESSION_FILE;
$conn = new db();
$dbcon = $conn->dbConnect();

$modified_by=USER_ID;
if(isset($_GET['action']))
{
	$id = $_POST['id'];
        $sql1 = "DELETE FROM amz_tc_addition WHERE s_no='$id'";
        $result1=$conn->runsql($sql1,$dbcon);
	if($result1)
	{
            echo "<font color='#3D9B60'>Deleted successfully!</font>";
	}
	else
	{
            echo "<font color='#F04124'>Unable to delete!</font>";
	}
}
?>