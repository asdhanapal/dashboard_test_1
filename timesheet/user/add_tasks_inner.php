<?php
session_start();
if(isset($_SESSION) && empty($_SESSION)) {
    $result_fail[0]="F";
    $result_fail[1]="Session Expired!. Press F5 to refresh the page.";
    echo json_encode($result_fail);
    die();
}

require_once '../classes/db.class.php';
require_once '../includes/time_calc.php';
$conn = new db();
$dbcon = $conn->dbConnect();
$user_id=$_SESSION['user_id'];
if($user_id)
{
    $temp_date= $_POST['date'];
    $date=date("Y-m-d", strtotime($temp_date));
    $team=$_POST['team'];
    $build=$_POST['build'];
    $task=$_POST['task'];
    $sub_task=$_POST['sub_task'];
    $time=$_POST['time'];
    $cmds=$_POST['cmds'];
    $cmds=mysqli_real_escape_string($dbcon,$cmds);
    $have_dsi=$_POST['have_dsi'];
    $dsi_type=$_POST['dsi_type'];
    $device_count=$_POST['device_count'];
    $noofdevice=$_POST['noofdevice'];
    
    $on_time='Y';
    
    if(!preg_match('/^(?:[01][0-9]|2[0-3]):[0-5][0-9]$/',$time)) 
    {
        $result_fail[0]="F";
        $result_fail[1]="Invalid time format!. Enter as HH:MM.";
        echo json_encode($result_fail);
        die();
    }

    $secs=0;
    $query="SELECT time FROM user_tasks WHERE date='$date' AND user_id='$user_id'";
    $result = $conn->runsql($query, $dbcon);
    if (mysqli_num_rows($result)) 
    {
        while ($result_row = mysqli_fetch_object($result)) 
        {
            $secs+= strtotime($result_row->time)-strtotime("00:00:00");
        }
        $remain_secs=28800-$secs;
        $remain_hrs=sectohr($remain_secs);
    }
    else
    {
        $remain_hrs="08:00:00";
        $remain_secs="28800";
    }

    $given_time_in_secs= strtotime($time)-strtotime("00:00:00");
    if($given_time_in_secs>$remain_secs)
    {
        $result_fail[0]="F";
        $result_fail[1]="Enter time within $remain_hrs";
        echo json_encode($result_fail);
        die();
    }
    if($have_dsi==1 && $dsi_type==1)
    {
        $task_desc=$_POST['task_desc'];
        $count=$_POST['count'];
        if($device_count)
            $query_user_task="INSERT INTO user_tasks(user_id,team_id,build,tasks_id,sub_task_id,task_desc,count,noofdevice,time,date,on_time,cmds,create_date,maintain_date) VALUES('$user_id','$team','$build','$task',$sub_task,'$task_desc','$count','$noofdevice','$time','$date','$on_time','$cmds',now(),now())";
        else
            $query_user_task="INSERT INTO user_tasks(user_id,team_id,build,tasks_id,sub_task_id,task_desc,count,time,date,on_time,cmds,create_date,maintain_date) VALUES('$user_id','$team','$build','$task',$sub_task,'$task_desc','$count','$time','$date','$on_time','$cmds',now(),now())";
    }
    else if($have_dsi==0 || $dsi_type!=1)
    {
        $count=$_POST['count'];
        if($device_count)
            $query_user_task="INSERT INTO user_tasks(user_id,team_id,build,tasks_id,sub_task_id,count,noofdevice,time,date,on_time,cmds,create_date,maintain_date) VALUES('$user_id','$team','$build','$task',$sub_task,'$count','$noofdevice','$time','$date','$on_time','$cmds',now(),now())";
        else 
            $query_user_task="INSERT INTO user_tasks(user_id,team_id,build,tasks_id,sub_task_id,count,time,date,on_time,cmds,create_date,maintain_date) VALUES('$user_id','$team','$build','$task',$sub_task,'$count','$time','$date','$on_time','$cmds',now(),now())";
    }
    //echo $query_user_task;
    $result_add_task= $conn->runsql($query_user_task,$dbcon);
    if($result_add_task)
    {
        $result_fail[0]="S";
        $result_fail[1]="Task added successfully.";
    }
    else
    {
        $result_fail[0]="F";
        $result_fail[1]="An error occured!";
    }
    echo json_encode($result_fail);
}
else
{
    $result_fail[0]="F";
    $result_fail[1]="Session Expired!. Press F5 to refresh the page.";
    echo json_encode($result_fail);
    die();
}
?>

