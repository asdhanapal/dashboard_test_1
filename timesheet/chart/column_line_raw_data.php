<?php
session_start();
include_once '../sda/data_page.php';
require_once '../classes/db.class.php';
require_once '../includes/time_calc.php';
$conn = new db();
$dbcon = $conn->dbConnect();

$date_from=$_POST['from_date'];
$date_to=$_POST['to_date'];
$team= explode(",",$_POST['team']);
$task=$_POST['task'];
$user=$_POST['user'];
$title=$_POST['title'];
$x_axis=$_POST['x_axis'];
$y_axis=$_POST['y_axis'];
$subtitle="";

if(sizeof($team)!=1 || empty($team) || $team[0]=="null")
{
    $output['result_status']="F";
    $output['result_msg']="Please select exactly one team!";//die("Please select exactly one team for create chart!");
    echo json_encode($output);
    die();
}
else
    $team=$team[0];

if($task!="null")
{
    $task_query=" task_id IN ($task) AND ";
    $subtitle="and task(s)";
}
else
    $task_query="";

$where_1="team_id='$team' AND date BETWEEN '$date_from' AND '$date_to'";

if($user!="null")
{
    $where_1.=" AND user_id IN ($user) ";
    $subtitle.=" and user(s)";
}

$output['result_status']="S";
$output['title']="Report between $date_from and $date_to";

$task_list=$actual_list=$target_list=$user_wu_tot=$daily_target_temp=$daily_target_temp_in_wu=$daily_target_temp_in_wu_in_per=$wu_avg=$tot_wu=$tot_time="";
$max=100;
$query_1 = "SELECT task_id,task_name,about_chart FROM amz_tasks WHERE team_id='$team' AND $task_query about_chart != 0 ORDER BY `task_name` ASC"; 
$result_1 = $conn->runsql($query_1, $dbcon);
while ($result_row_1 = mysqli_fetch_object($result_1)) 
{
    if($result_row_1->about_chart==1)
    {
        $task_id=$result_row_1->task_id;
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
            $modified_date_with_month=date("F Y",strtotime($date_from));
            $daily_target_all_values=0;
            $final_dt="";

            $query_get_daily_target="SELECT con_fac FROM amz_daily_target WHERE task='$task_id' AND month_from='$modified_date_with_month'";
            $query_get_daily_target_result=$conn->runsql($query_get_daily_target, $dbcon);
            $num_rows=mysqli_num_rows($query_get_daily_target_result);
            while($query_get_daily_target_result_row = mysqli_fetch_object($query_get_daily_target_result)) 
            {
                $daily_target_all_values=$daily_target_all_values+$query_get_daily_target_result_row->con_fac;
            }
            $final_dt=$daily_target_all_values/$num_rows;
            $daily_target_temp.=$final_dt.",";
            if($final_dt!="" || $final_dt!=0)
            {
                $temp_var_1=$final_dt*100/$final_dt;
                $daily_target_temp_in_wu.=$temp_var_1.",";
                $daily_target_temp_in_wu_in_per.=($temp_var_1/$temp_var_1*100).",";
                $temp_var_2=round(($tot_work_units+$tot_work_units_ot)/($secs+$secs_ot)*28800,2);
                $user_wu_tot.=$temp_var_2.",";
                $temp_wu_avg=$temp_var_2/$temp_var_1*100;
                $wu_avg.=$temp_wu_avg.",";
                $tot_wu.=round(($tot_work_units+$tot_work_units_ot)).",";
                $tot_time.=($secs+$secs_ot).",";
                if($temp_wu_avg>$max)
                    $max=$temp_wu_avg;
                
            }
            else
            {
                $daily_target_temp_in_wu.="'NULL',";
                $daily_target_temp_in_wu_in_per.="100,";
                $user_wu_tot.=round(($tot_work_units+$tot_work_units_ot)/($secs+$secs_ot)*28800,2).",";
                $wu_avg.="0,";
                $tot_wu.=round(($tot_work_units+$tot_work_units_ot)).",";
                $tot_time.=($secs+$secs_ot).",";
            }
            $task_list.="\"".$result_row_1->task_name."\",";
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
                $tot_count=$tot_count_ot=0;
                $secs=$secs_ot=0;
                $tot_work_units=$tot_work_units_ot=0.0;

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
                {
                    $modified_date_with_month=date("F Y",strtotime($date_from));
                    $final_dt="";
                    
                    $query_get_daily_target="SELECT con_fac FROM amz_daily_target WHERE sub_task='$sub_task_id' AND month_from='$modified_date_with_month'";
                    $query_get_daily_target_result=$conn->runsql($query_get_daily_target, $dbcon);
                    $query_get_daily_target_result_row = mysqli_fetch_object($query_get_daily_target_result);
                    $final_dt=$query_get_daily_target_result_row->con_fac;
                    $daily_target_temp.=$final_dt.",";
                    if($final_dt!="" || $final_dt!=0)
                    {
                        $temp_var_1=$final_dt*100/$final_dt;
                        $daily_target_temp_in_wu.=$temp_var_1.",";
                        $daily_target_temp_in_wu_in_per.=($temp_var_1/$temp_var_1*100).",";
                        $temp_var_2=round(($tot_work_units+$tot_work_units_ot)/($secs+$secs_ot)*28800,2);
                        $user_wu_tot.=$temp_var_2.",";
                        $temp_wu_avg=$temp_var_2/$temp_var_1*100;
                        $wu_avg.=$temp_wu_avg.",";
                        $tot_wu.=round(($tot_work_units+$tot_work_units_ot)).",";
                        $tot_time.=($secs+$secs_ot).",";
                        if($temp_wu_avg>$max)
                            $max=$temp_wu_avg;
                    }
                    else
                    {
                        $daily_target_temp_in_wu.="'NULL',";
                        $daily_target_temp_in_wu_in_per.="100,";
                        $user_wu_tot.=round(($tot_work_units+$tot_work_units_ot)/($secs+$secs_ot)*28800,2).",";
                        $wu_avg.="0,";
                        $tot_wu.=round(($tot_work_units+$tot_work_units_ot)).",";
                        $tot_time.=($secs+$secs_ot).",";
                    }
                    $task_list.="\"".$result_row_2->sub_task_name."\",";
                }
            }
        }
    }
}

$task_list=rtrim($task_list,",");
$daily_target_temp=rtrim($daily_target_temp,",");
$daily_target_temp_in_wu_in_per=rtrim($daily_target_temp_in_wu_in_per,",");
$user_wu_tot=rtrim($user_wu_tot,",");
$wu_avg=rtrim($wu_avg,",");
$tot_wu=rtrim($tot_wu,",");
$tot_time=rtrim($tot_time,",");

$task_list_array=explode(",",$task_list);
$daily_target_temp_array=explode(",",$daily_target_temp);
$daily_target_temp_in_wu_array=explode(",",$daily_target_temp_in_wu);
$daily_target_temp_in_wu_in_per_array=explode(",",$daily_target_temp_in_wu_in_per);
$user_wu_tot_array=explode(",",$user_wu_tot);
$wu_avg_array=explode(",",$wu_avg);
$tot_wu_array=explode(",",$tot_wu);
$tot_time_array=explode(",",$tot_time);

$max=$max+50;
echo "<table>";
echo "<tr>";
echo "<td>Task list</td>";
echo "<td>Daily target</td>";
echo "<td>Daily target(in WU)</td>";
echo "<td>Daily target(%)</td>";
echo "<td>Work units(tot)</td>";
echo "<td>Total time</td>";
echo "<td>Per day WU(pro)</td>";
echo "<td>Per day WU(in %)</td>";
echo "</tr>";

for($kkk=0;$kkk<sizeof($task_list_array);$kkk++)
{
    echo "<tr>";
    echo "<td>".$task_list_array[$kkk]."</td>";
    echo "<td>".$daily_target_temp_array[$kkk]."</td>";
    echo "<td>".$daily_target_temp_in_wu_array[$kkk]."</td>";
    echo "<td>".$daily_target_temp_in_wu_in_per_array[$kkk]."</td>";
    echo "<td>".$tot_wu_array[$kkk]."</td>";
    echo "<td>".sectohr($tot_time_array[$kkk])."</td>";
    echo "<td>".$user_wu_tot_array[$kkk]."</td>";
    echo "<td>".$wu_avg_array[$kkk]."</td>";
    echo "</tr>";
}
echo "</table>";
?>