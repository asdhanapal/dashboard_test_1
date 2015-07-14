<?php
require_once '../includes/define.php';
require_once _BACK_TO_PRE_.DB_CLASS.DB_CLASS_FILE;
require_once _BACK_TO_PRE_._INCLUDE.SESSION_FILE;

$conn = new db();
$dbcon = $conn->dbConnect();

$modified_by=USER_ID;
if(isset($_GET['id']) && $_GET['id']!="")
{
	$id = substr(strrchr($_GET['id'],"_"),1);
        $value=$_GET['data'];

        if(is_numeric($value))
        {
            $sql1 = "UPDATE amz_dc_units SET percentage='$value',modified_by='".$modified_by."',maintain_date=now() WHERE s_no = '$id'";
        }
        $result1=$conn->runsql($sql1,$dbcon);
	if(mysqli_affected_rows($dbcon)==1)
	{
            echo "pass";
	}
	else
	{
            echo "error";
	}
}
 else {
    echo "error";
}
?>