<?php
require_once '../includes/define.php';
require_once _BACK_TO_PRE_.DB_CLASS.DB_CLASS_FILE;
$conn = new db();
$dbcon = $conn->dbConnect();

$editing_id=$_POST['editing_id'];
$p_team=$_POST['p_team'];
$a_team=$_POST['a_team'];
$p_team_array=explode(",", $p_team);
$a_team_array=explode(",", $a_team);

if(array_diff($p_team_array, $a_team_array))
{
    $result[0]="F";
    $result[1]="Parent team should be present in available team!";
    echo json_encode($result);
    exit();

}

$query_p_team="DELETE FROM amz_pteam_info WHERE user_id='$editing_id'";
$result_p_team=$conn->runsql($query_p_team,$dbcon);
if($result_p_team)
{
    for($i=0;$i<sizeof($p_team_array);$i++)
    {
        $query_p_team_insert="INSERT INTO amz_pteam_info(user_id,team_id,start_date) VALUES ('$editing_id','$p_team_array[$i]',now())";        
        $conn->runsql($query_p_team_insert,$dbcon);
    }
}

$query_a_team="DELETE FROM amz_user_info WHERE user_id='$editing_id'";
$result_a_team=$conn->runsql($query_a_team,$dbcon);
if($result_a_team)
{
    for($i=0;$i<sizeof($a_team_array);$i++)
    {
        $query_a_team_insert="INSERT INTO amz_user_info(user_id,team_id) VALUES ('$editing_id','$a_team_array[$i]')";        
        $conn->runsql($query_a_team_insert,$dbcon);
    }
}
if($result_a_team && $result_p_team)
{
    $result[0]="S";
    $result[1]="Teams updated successfully!";
    echo json_encode($result);
    exit();
}
else
{
    $result[0]="F";
    $result[1]="Updation failed. Try again later!.";
    echo json_encode($result);
    exit();
}

?>