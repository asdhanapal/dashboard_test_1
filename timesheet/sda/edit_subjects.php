<?php
require_once '../classes/db.class.php';
$conn = new db();
$dbcon = $conn->dbConnect();
session_start();
$edited_by=$_SESSION['sda_id'];

$subject=$_GET['action'];

switch ($subject)
{
    case "team":
        $edit_id= $_POST['id'];
        $edit_value= mysqli_real_escape_string($dbcon,$_POST['value']);
        $query_update_team="UPDATE amz_teams SET team_name='$edit_value',last_modify=now() WHERE team_id='$edit_id'";
        $result_update= $conn->runsql($query_update_team,$dbcon);
        break;
    
    case "task":
//        print_r($_POST);
        $edit_id= $_POST['id'];
        $edit_value= mysqli_real_escape_string($dbcon,$_POST['value']);
        $have_cf=$_POST['have_cf'];
        $auto_cf=$_POST['auto_cf'];
        $have_st=$_POST['have_st'];
        $have_td=$_POST['have_td'];
        $op=$_POST['op'];
        $tdi_type=$have_td==1?'1':'0';
        $query_update_team="UPDATE amz_tasks SET task_name='$edit_value',cf_avail='$have_cf',auto_cf='$auto_cf',have_st='$have_st',have_tdi='$have_td',tdi_type='$tdi_type',op_type='$op',last_modified_by='$edited_by' WHERE task_id='$edit_id'";
        $result_update= $conn->runsql($query_update_team,$dbcon);
        break;
    
    case "subtask":
        $edit_id= $_POST['id'];
        $edit_value= mysqli_real_escape_string($dbcon,$_POST['value']);
        $auto_cf=$_POST['auto_cf'];
        $cf_type=$_POST['cf_type'];
        $query_update_team="UPDATE amz_sub_tasks SET sub_task_name='$edit_value',auto_cf='$auto_cf',cf_change='$cf_type',last_modify_by='$edited_by' WHERE sub_task_id='$edit_id'";
        $result_update= $conn->runsql($query_update_team,$dbcon);
        break;
    
    case "td":
        $edit_id= $_POST['id'];
        $edit_value= mysqli_real_escape_string($dbcon,$_POST['value']);
        $query_update_team="UPDATE amz_task_desc SET task_info='$edit_value' WHERE tdi_no='$edit_id'";
        $result_update= $conn->runsql($query_update_team,$dbcon);
        
        
        break;
    
    default:
        echo "Internal server error!";
}

if($result_update)
{
    $result[0]="S";
    $result[1]="Updation successfully.&nbsp;Press <kbd>Esc</kbd> to close the popup!<br>.";
}
else
{
    $result[0]="F";
    $result[1]="An error occured!";
}
echo json_encode($result);
?>