<?php
require_once '../classes/db.class.php';
$conn = new db();
$dbcon=$conn->dbConnect();

$team_id=$team_name=$task_id=$task_name=$user_id=$user_name=$sub_task_name=$sub_task_id=$task_desc_id=$task_desc_name=$user_type_id=$user_type_name=$build=$build_name=$release=$release_name=array(); //$team_id=$team_name=$task_id=$task_name=$user_id=$user_name=$sub_task_id=$sub_task_name=$task_desc_id=$task_desc_name=array();
$audit_task=$audit_task_name=$imapct_array=$audit_task_array=array();
$query_1="SELECT team_id,team_name FROM amz_teams";
$result= $conn->runsql($query_1,$dbcon);
if(mysqli_num_rows($result))
{
    while($result_row=  mysqli_fetch_object($result))
    {
        $team_id[]=$result_row->team_id;
        $team_name[]=$result_row->team_name;
    }
}
$team_array=  array_combine($team_id, $team_name);

$query_2="SELECT task_id,task_name FROM amz_tasks";
$result= $conn->runsql($query_2,$dbcon);
if(mysqli_num_rows($result))
{
    while($result_row=  mysqli_fetch_object($result))
    {
        $task_id[]=$result_row->task_id;
        $task_name[]=$result_row->task_name;
    }
}
$task_array=  array_combine($task_id, $task_name);

$query_3="SELECT user_id,user_name FROM amz_login";
$result= $conn->runsql($query_3,$dbcon);
if(mysqli_num_rows($result))
{
    while($result_row=  mysqli_fetch_object($result))
    {
        $user_id[]=$result_row->user_id;
        $user_name[]=$result_row->user_name;
    }
}
$user_array=  array_combine($user_id, $user_name);

$query_3="SELECT sub_task_id,sub_task_name FROM amz_sub_tasks";
$result= $conn->runsql($query_3,$dbcon);
if(mysqli_num_rows($result))
{
    while($result_row=  mysqli_fetch_object($result))
    {
        $sub_task_id[]=$result_row->sub_task_id;
        $sub_task_name[]=$result_row->sub_task_name;
    }
}
$sub_task_array=  array_combine($sub_task_id, $sub_task_name);

$query_5="SELECT tdi_no,task_info FROM amz_task_desc";
$result= $conn->runsql($query_5,$dbcon);
if(mysqli_num_rows($result))
{
    while($result_row=  mysqli_fetch_object($result))
    {
        $task_desc_id[]=$result_row->tdi_no;
        $task_desc_name[]=$result_row->task_info;
    }
}
$task_desc_array=array_combine($task_desc_id, $task_desc_name);

$query_6="SELECT build_no,build_name FROM amz_builds";
$result= $conn->runsql($query_6,$dbcon);
if(mysqli_num_rows($result))
{
    while($result_row=  mysqli_fetch_object($result))
    {
        $build[]=$result_row->build_no;
        $build_name[]=$result_row->build_name;
    }
}
$build_array=array_combine($build, $build_name);

$query_7="SELECT s_no,release_name FROM amz_releases";
$result= $conn->runsql($query_7,$dbcon);
if(mysqli_num_rows($result))
{
    while($result_row=  mysqli_fetch_object($result))
    {
        $release[]=$result_row->s_no;
        $release_name[]=$result_row->release_name;
    }
}
$release_array=array_combine($release, $release_name);

$query_8="SELECT s_no,task_name FROM  amz_audit_tasks";
$result= $conn->runsql($query_8,$dbcon);
if(mysqli_num_rows($result))
{
    while($result_row=  mysqli_fetch_object($result))
    {
        $audit_task[]=$result_row->s_no;
        $audit_task_name[]=$result_row->task_name;
    }
}
$audit_task_array=array_combine($audit_task, $audit_task_name);

$status_array=array(""=>"Pending",1=>"Approved",0=>"Rejected");
$impact_array=array(1=>"High",2=>"Medium",3=>"Low");
$cf_status=array(""=>"No target",1=>"Manual",0=>"Auto");
$test_cases=array(1=>"LC",2=>"MC",3=>"HC");
$test_addition_task=array(1=>"Test case imported");
$test_addition_sub_task=array(1=>"Jira to tc",2=>"New test case");
//$sub_task_array=  array_merge($sub_task_array,$test_cases);
?>

