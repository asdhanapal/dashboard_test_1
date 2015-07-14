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
        
        if($value=="AUTO")
        {
            $sql1="UPDATE amz_daily_target SET about_cf=0,cf_updated=1,con_fac=NULL, wu_status=0,status=1,modified_by='$modified_by',maintain_date=now() WHERE s_no = '$id'";
        }
        if($value=="NONE")
        {
            $sql1="UPDATE amz_daily_target SET about_cf=NULL,cf_updated=0,con_fac=NULL, wu_status=0,status=1,modified_by='$modified_by',maintain_date=now() WHERE s_no = '$id'";
        }
        if($value=="-")
        {
            $sql1="UPDATE amz_daily_target SET about_cf=NULL,cf_updated=1,con_fac=NULL, wu_status=1,status=0,modified_by='$modified_by',maintain_date=now() WHERE s_no = '$id'";
        }
        if(is_numeric($value))
        {
            $sql1 = "UPDATE amz_daily_target SET about_cf=1,cf_updated=1,con_fac='$value', wu_status=0,status=1,modified_by='$modified_by',maintain_date=now() WHERE s_no = '$id'";
        }
        //echo $sql1;
        $result1=$conn->runsql($sql1,$dbcon);
	if(mysqli_affected_rows($dbcon)==1 || mysqli_affected_rows($dbcon)==0)
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