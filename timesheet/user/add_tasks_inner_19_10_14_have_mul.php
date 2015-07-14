<?php
//include_once '../includes/session_admin.php';
require_once '../classes/db.class.php';
$conn = new db();
$dbcon = $conn->dbConnect();
session_start();
$user_id=$_SESSION['user_id'];
$team_id=$_SESSION['team_id'];
$arr=$_POST;
    $temp_date= $_POST['date'];
 $date=date("Y-m-d", strtotime($temp_date));
$no_of_rows=$_POST['hidden_add_row'];
$task=$sub_task=$count=$time=$cmds=$result=array();

for($i=0;$i<$no_of_rows;$i++)
{
    $task[]=$arr['task_'.$i];
    $sub_task[]=$arr['sub_task_'.$i];
    $count[]=$arr['qty_'.$i];
    $time[]=$arr['time_'.$i];
    $cmds[]=$arr['cmds_'.$i];
}

if ( count($sub_task) != count(array_unique($sub_task)) ) 
{
    $result[0]="F";
    $result[1]="Pls select different sub tasks for every entry!";
    echo json_encode($result);
    exit();
}
if(array_sum($time)!=8)
{
    $result[0]="F";
    $result[1]="Time doesnt match with 8 hrs. Pls validate and submit again!";
    echo json_encode($result);
    exit();
}

// else {
//    $result[0]="S";
//    $result[1]="Success!";
//}
$on_time='Y';

for($i=0;$i<$no_of_rows;$i++)
{
    $query_add_task="INSERT INTO user_tasks(user_id,team_id,tasks_id,date,on_time,time,cmds,sub_task_id,create_date,maintain_date) VALUES ('$user_id','$team_id','$task[$i]','$date','$on_time','$time[$i]','$cmds[$i]','$sub_task[$i]',now(),now());";
    $result_add_task= $conn->runsql($query_add_task,$dbcon);
    if($result_add_task)
    {
        $result[0]="S";
        $result[1]="Task added successfully.";
        //echo json_encode($result);
//        exit();
    }
    else
    {
        $result[0]="F";
        $result[1]="An error occured!";
        
//        exit();
    }
}
echo json_encode($result);
?>