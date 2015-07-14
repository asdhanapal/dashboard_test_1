<?php
session_start();
include_once '../sda/data_page.php';
require_once '../classes/db.class.php';
$conn = new db();
$dbcon = $conn->dbConnect();
?>

<?php
$data=  array(
    array("Tasks","Actual","Target")
);
$date_from=$_POST['from_date'];
$date_to=$_POST['to_date'];
$team=$_POST['team'];
if(sizeof($team)!=1 ||empty($team))
    die("Please select exactly one team for create chart!");
else
    $team=$team[0];
$where_1="team_id='$team' AND date BETWEEN '$date_from' AND '$date_to'";

$query_1 = "SELECT task_id,task_name,about_chart FROM amz_tasks WHERE team_id='$team' AND about_chart != 0 ORDER BY `task_name` ASC"; 
$result_1 = $conn->runsql($query_1, $dbcon);
while ($result_row_1 = mysqli_fetch_object($result_1)) 
{
//    print_r($result_row_1);
    if($result_row_1->about_chart==1)
    {
        $task_id=$result_row_1->task_id;
        $tot_time=$tot_time_ot="";
        $tot_count=$tot_count_ot=0;
        $secs=$secs_ot=0;
        $tot_work_units=$tot_work_units_ot=0.0;

        $query_2 = "SELECT time,count,wu FROM user_tasks WHERE tasks_id='$task_id' AND ". $where_1;
        $result_2 = $conn->runsql($query_2, $dbcon);
        while ($result_row_2 = mysqli_fetch_object($result_2))
        {
            $tot_count+=$result_row_2->count;
            $secs+= strtotime($result_row_2->time)-strtotime("00:00:00");
            $tot_work_units+=$result_row_2->wu;
        }

        $query_ot = "SELECT time,count,wu FROM user_tasks_ot WHERE tasks_id='$task_id' AND ". $where_1 ." AND ot_status='1'";
        $result_ot = $conn->runsql($query_ot, $dbcon);
        while ($result_row_ot = mysqli_fetch_object($result_ot)) 
        {
            $tot_count_ot+=$result_row_ot->count;
            $secs_ot+= strtotime($result_row_ot->time)-strtotime("00:00:00");
            $tot_work_units_ot+=$result_row_ot->wu;
        }
        if($secs!=0 && ($tot_work_units!=0 || $tot_work_units_ot!=0) )
        {
            $data[]=  array($result_row_1->task_name,round(($tot_work_units+$tot_work_units_ot)/$secs*28800,2),100);
        }
    }
    else if($result_row_1->about_chart==2)
    {
        $task_id=$result_row_1->task_id;
        
        $query_2 = "SELECT sub_task_id,sub_task_name,about_chart FROM amz_sub_tasks WHERE team_id='$team' AND task_id='$task_id' AND about_chart != 0 ORDER BY `sub_task_name` ASC"; 
        $result_2 = $conn->runsql($query_2, $dbcon);
        while ($result_row_2 = mysqli_fetch_object($result_2)) 
        {
            $sub_task_id=$result_row_2->sub_task_id;
            if($result_row_2->about_chart==1)
            {
                $tot_time=$tot_time_ot="";
                $tot_count=$tot_count_ot=0;
                $secs=$secs_ot=0;
                $tot_work_units=$tot_work_units_ot=0.0;
//
                $query_3 = "SELECT time,count,wu FROM user_tasks WHERE tasks_id='$task_id' AND sub_task_id='$sub_task_id' AND ". $where_1;
                $result_3 = $conn->runsql($query_3, $dbcon);
                while ($result_row_3 = mysqli_fetch_object($result_3))
                {
                    $tot_count+=$result_row_3->count;
                    $secs+= strtotime($result_row_3->time)-strtotime("00:00:00");
                    $tot_work_units+=$result_row_3->wu;
                }

                $query_ot = "SELECT time,count,wu FROM user_tasks_ot WHERE tasks_id='$task_id'  AND sub_task_id='$sub_task_id' AND ". $where_1 ." AND ot_status='1'";
               $result_ot = $conn->runsql($query_ot, $dbcon);
                while ($result_row_ot = mysqli_fetch_object($result_ot)) 
                {
                    $tot_count_ot+=$result_row_ot->count;
                    $secs_ot+= strtotime($result_row_ot->time)-strtotime("00:00:00");
                    $tot_work_units_ot+=$result_row_ot->wu;
                }
                if($secs!=0 && ($tot_work_units!=0 || $tot_work_units_ot!=0) )
                    $data[]=  array($result_row_2->sub_task_name,round(($tot_work_units+$tot_work_units_ot)/$secs*28800,2),100);
            }
        }
    }
}
//print_r($data);
echo json_encode($data);
 ?>
