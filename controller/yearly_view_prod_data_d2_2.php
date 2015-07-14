<?php
require_once '../includes/define.php';
require_once _BACK_TO_PRE_.DB_CLASS.DB_CLASS_FILE;
require_once _BACK_TO_PRE_._INCLUDE.SESSION_FILE;
require_once ID_TO_NAME_CONV;
$conn = new db();
$dbcon = $conn->dbConnect();

$year=$_POST['year'];
$team=$_POST['team'];
if($year=="")
{ ?>
    <div style="text-align: center;">Select the year!</div>
    <?php
    die();
}
$month_array=array(1=>"January","February","March","April","May","June","July","August","September","October","November","December");
?>
    <table width="100%" class="my_table">
    <tr class="tbl_header">
    <td>Users\Month</td>
    <?php
        for($i=1;$i<sizeof($month_array);$i++)
        {
            echo "<td>".substr($month_array[$i],0,3)." Act</td>";
            echo "<td>".substr($month_array[$i],0,3)." Fcst</td>";
        }
    ?>
    <td>Avg Act</td>
    </tr>
    <?php
    $secs=$secs_ot=0;
    $wu=$wu_ot=0.0;
    $daily_target=0;
    
    $query_1="SELECT team_id,team_name FROM amz_teams WHERE team_id='$team' ORDER BY team_name ASC";
    $result_1= $conn->runsql($query_1,$dbcon);
    if(mysqli_num_rows($result_1))
    {
        while($result_row_1=  mysqli_fetch_object($result_1))
        {
            $tr_color_decide_check=0;
            $team_id=$result_row_1->team_id;
            $query_get_users="SELECT DISTINCT user_id FROM amz_user_info WHERE team_id='$team_id'";
            $result_get_users= $conn->runsql($query_get_users,$dbcon);
            if(mysqli_num_rows($result_get_users))
            {
                while($result_row_get_users=  mysqli_fetch_object($result_get_users))
                {
                    $user_id=$result_row_get_users->user_id;
                    $tr_txt=(($tr_color_decide_check%2)==0)?"even":"odd";
                    echo "<tr class='project_$tr_txt'><td>".$user_array[$user_id]."</td>";
                    $tr_color_decide_check++;
                    $avg_act=$avg_fcst=$count=0;
                    $size=sizeof($month_array);
                    for($i=1;$i<$size;$i++)
                    {
                        $month=$month_array[$i]." ".$year;
                        $month_no=$year."-".  date('m',strtotime($month_array[$i]))."-";
                        $sql_check_list_1="SELECT wu_updation FROM amz_dt_manage WHERE month='$month'";
                        $result_check_list_1= $conn->runsql($sql_check_list_1,$dbcon);
                        if(mysqli_num_rows($result_check_list_1))
                        {
                            echo "<td>";
                            $result_row_check_list_1=  mysqli_fetch_object($result_check_list_1);
                            if($result_row_check_list_1->wu_updation==0)
                            {
                                echo "<span class='fail'>Err:101</span></td><td></td>";
                                continue;
                            }
                            else
                            {
                                $query_get_tasks="SELECT task_id FROM amz_tasks WHERE team_id='$team_id' AND have_st='1'";
                                $result_get_tasks= $conn->runsql($query_get_tasks,$dbcon);
                                if(mysqli_num_rows($result_get_tasks))
                                {
                                    while($result_row_get_tasks=  mysqli_fetch_object($result_get_tasks))
                                    {
                                        $task_id=$result_row_get_tasks->task_id;
                                        $query_check_list_2="SELECT s_no FROM amz_daily_target WHERE task='$task_id' AND month_from='$month' AND about_cf=1 AND (ms_non_ms IS NULL OR wu_status=0)";
                                        $result_check_list_2= $conn->runsql($query_check_list_2,$dbcon);
                                        if(mysqli_num_rows($result_check_list_2))
                                        {
                                            echo "<span class='fail'>Err:102</span></td><td></td>";
                                            continue 2;
                                        }
                                        else
                                        {
                                            $query_get_sub_tasks="SELECT sub_task,con_fac FROM amz_daily_target WHERE task='$task_id' AND month_from='$month' AND ms_non_ms='1' AND about_cf=1 AND wu_status=1";
                                            $result_get_sub_tasks= $conn->runsql($query_get_sub_tasks,$dbcon);
                                            if(mysqli_num_rows($result_get_sub_tasks))
                                            {
                                                while($result_row_get_sub_tasks=  mysqli_fetch_object($result_get_sub_tasks))
                                                {
                                                    $daily_target+=$result_row_get_sub_tasks->con_fac;
                                                    $sub_task_id=$result_row_get_sub_tasks->sub_task;
                                                    $query_get_values="SELECT time,wu FROM user_tasks WHERE sub_task_id='$sub_task_id' AND wu_status=1 AND date LIKE '%$month_no%' AND user_id='$user_id'";
                                                    $result_get_values= $conn->runsql($query_get_values,$dbcon);
                                                    if(mysqli_num_rows($result_get_values))
                                                    {
                                                        while($result_row_get_values=  mysqli_fetch_object($result_get_values))
                                                        {
                                                            $wu+=$result_row_get_values->wu;
                                                            $secs+= strtotime($result_row_get_values->time)-strtotime("00:00:00");
                                                        }
                                                    }
                                                    
                                                    $query_get_values_ot="SELECT time,wu FROM user_tasks_ot WHERE sub_task_id='$sub_task_id' AND wu_status=1 AND date LIKE '%$month_no%' AND user_id='$user_id' AND ot_status=1";
                                                    $result_get_values_ot= $conn->runsql($query_get_values_ot,$dbcon);
                                                    if(mysqli_num_rows($result_get_values_ot))
                                                    {
                                                        while($result_row_get_values_ot=  mysqli_fetch_object($result_get_values_ot))
                                                        {
                                                            $wu+=$result_row_get_values_ot->wu;
                                                            $secs+= strtotime($result_row_get_values_ot->time)-strtotime("00:00:00");
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            if($secs)
                            {
                                echo "<span class='success'>";
                                echo round($wu/$secs*28800,2);
                                echo "</span></td><td><span class='success'>100</span>";
                                $avg_fcst+=100;
                                $avg_act+=round($wu/$secs*28800,2);
                                $count+=1;
                            }
                            else
                            {
                                echo "</td><td>";
                            }
                            $wu=0;
                            $secs=0;
                            $daily_target=0;
                            echo "</td>";
                        }
                        else
                        {
                            echo "<td></td><td></td>";
                        }
                    }
                    echo "<td>";
                    if($count)
                        echo round($avg_act/$count,2);
                    echo "</td>";
                    echo "</tr>";
                }
            }
        }
    }
    ?>
</table>