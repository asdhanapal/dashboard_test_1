<?php
require_once '../includes/define.php';
require_once _BACK_TO_PRE_.DB_CLASS.DB_CLASS_FILE;
require_once _BACK_TO_PRE_._INCLUDE.SESSION_FILE;
$conn = new db();
$dbcon = $conn->dbConnect();
$modified_by=USER_ID;
if( ($_POST['field']!="") && ($_POST['month']!="") && ($_POST['year']!="") && ($_POST['task']!="") && ($_POST['value']!="") )
{
    $field=$_POST['field'];
    $tmp_month=$_POST['month'];
    $dateObj   = DateTime::createFromFormat('!m', $tmp_month);
    $month=$dateObj->format('F');
    $year=$_POST['year'];
    $task=$_POST['task'];
    $value=$_POST['value'];
    $modified_date=$month." ".$year;
    if($field==1)
        $text="ms_non_ms='".$value."'";
    elseif($field==2)
        $text="rele_non_rele='".$value."'";
    elseif($field==3) 
        $text="op_off='".$value."'";
    else
    {
        $result[0]="F";
        $result[1]="Something went wrongly!";
        echo json_encode($result);
        exit();
    }
    
    $sql1="UPDATE amz_daily_target SET $text, modified_by='$modified_by',maintain_date=now() WHERE task = '$task' AND month_from='$modified_date'";
    $result1=$conn->runsql($sql1,$dbcon);
    if($result1)
    {
        $result[0]="S";
        $result[1]="Updated successfully!";
    }
    else
    {
        $result[0]="F";
        $result[1]="Something went wrongly!";
    }
}
else
{
    $result[0]="F";
    $result[1]="Something went wrongly!";
    echo json_encode($result);
    exit();

}
echo json_encode($result);
?>