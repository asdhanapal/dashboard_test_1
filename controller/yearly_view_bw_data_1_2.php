<?php
require_once '../includes/define.php';
require_once _BACK_TO_PRE_.DB_CLASS.DB_CLASS_FILE;
require_once _BACK_TO_PRE_._INCLUDE.SESSION_FILE;
require_once ID_TO_NAME_CONV;
require_once _BACK_TO_PRE_._INCLUDE.TIME_CONV;
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
$user_type=array(1=>"L2",2=>"L3");
?>
<table width="100%" class="my_table">
<tr class="tbl_header">
<td>Tasks</td>
<?php
    for($i=1;$i<sizeof($month_array);$i++)
    {
        echo "<td>".substr($month_array[$i],0,3)."</td>";
    }
?>
<td>Total</td>
</tr>
<?php
$secs=$secs_ot=0;

foreach ($user_type as $key => $value) {
    echo "<tr class='tbl_header' style='background-color: #EBEBEB;'><td colspan=15>BW utilization - $value</td></tr>";
    $query_1="SELECT team_id,team_name FROM amz_teams WHERE team_id='$team' ORDER BY team_name ASC";
    $result_1= $conn->runsql($query_1,$dbcon);
    if(mysqli_num_rows($result_1))
    {
        while($result_row_1=  mysqli_fetch_object($result_1))
        {
            $team_id=$result_row_1->team_id;
            $query_get_tasks="SELECT task_id,task_name FROM amz_tasks WHERE team_id='$team_id' ORDER BY task_name ASC";
            $result_get_tasks= $conn->runsql($query_get_tasks,$dbcon);
            if(mysqli_num_rows($result_get_tasks))
            {
                while($result_row_get_tasks=  mysqli_fetch_object($result_get_tasks))
                {
                    echo "<tr><td>".$result_row_get_tasks->task_name."</td>";
                    $task_id=$result_row_get_tasks->task_id;
                    $size=sizeof($month_array);
                    $tot_sec=0;
                    for($i=1;$i<$size;$i++)
                    {
                        $month=$month_array[$i]." ".$year;
                        $month_no=$year."-".  date('m',strtotime($month_array[$i]))."-";
                        $sql_check_list_1="SELECT wu_updation FROM amz_dt_manage WHERE month='$month'";
                        $result_check_list_1= $conn->runsql($sql_check_list_1,$dbcon);
                        if(mysqli_num_rows($result_check_list_1))
                        {
                            $result_row_check_list_1=  mysqli_fetch_object($result_check_list_1);
                            if($result_row_check_list_1->wu_updation==0)
                            {
                                echo "<td><span class='fail'>Err:101</span></td>";
                                continue;
                            }
                            else
                            {
                                $query_check_list_2="SELECT s_no FROM amz_daily_target WHERE task='$task_id' AND month_from='$month' AND about_cf=0 AND (ms_non_ms IS NULL OR rele_non_rele IS NULL OR wu_status=0)";
                                $result_check_list_2= $conn->runsql($query_check_list_2,$dbcon);
                                if(mysqli_num_rows($result_check_list_2))
                                {
                                    echo "<td><span class='fail'>Err:102</span></td>";
                                   continue;
                                }
                                $query_get_sub_tasks="SELECT sub_task FROM amz_daily_target WHERE task='$task_id' AND month_from='$month' AND wu_status=1";//AND about_cf=0 
                                $result_get_sub_tasks= $conn->runsql($query_get_sub_tasks,$dbcon);
                                if(mysqli_num_rows($result_get_sub_tasks))
                                {
                                    while($result_row_get_sub_tasks=  mysqli_fetch_object($result_get_sub_tasks))
                                    {
                                        $sub_task_id=$result_row_get_sub_tasks->sub_task;
                                        if($sub_task_id==NULL)
                                           $query_get_values="SELECT time FROM user_tasks WHERE tasks_id='$task_id' AND wu_status=1 AND date LIKE '%$month_no%' AND user_type='$key'";
                                        else
                                            $query_get_values="SELECT time FROM user_tasks WHERE sub_task_id='$sub_task_id' AND wu_status=1 AND date LIKE '%$month_no%' AND user_type='$key'";

                                        $result_get_values= $conn->runsql($query_get_values,$dbcon);
                                        if(mysqli_num_rows($result_get_values))
                                        {
                                            while($result_row_get_values=  mysqli_fetch_object($result_get_values))
                                            {
                                                $secs+= strtotime($result_row_get_values->time)-strtotime("00:00:00");
                                            }
                                        }
                                        
                                        if($sub_task_id==NULL)
                                            $query_get_values_ot="SELECT time FROM user_tasks_ot WHERE tasks_id='$task_id' AND wu_status=1 AND date LIKE '%$month_no%' AND ot_status=1 AND user_type='$key'";
                                        else 
                                            $query_get_values_ot="SELECT time FROM user_tasks_ot WHERE sub_task_id='$sub_task_id' AND wu_status=1 AND date LIKE '%$month_no%' AND ot_status=1  AND user_type='$key'";
                                        
                                        $result_get_values_ot= $conn->runsql($query_get_values_ot,$dbcon);
                                        if(mysqli_num_rows($result_get_values_ot))
                                        {
                                            while($result_row_get_values_ot=  mysqli_fetch_object($result_get_values_ot))
                                            {
                                                $secs+= strtotime($result_row_get_values_ot->time)-strtotime("00:00:00");
                                            }
                                        }
                                    }
                                }
                            }
                            if($secs)
                            {
                                echo "<td><span class='success'>";
                                echo sectohr($secs);
                                $tot_sec+=$secs;
                                echo "</span></td>";
                            }
                            else
                            {
                                echo "<td>-</td>";
                            }
                            $secs=0;
                        }
                        else 
                            echo "<td></td>";
                    }
                    echo "<td>";
                    if ($tot_sec)
                        echo sectohr($tot_sec);
                    else
                        echo "-";
                    echo "</td></tr>";
                }
            }
        }
    }
}
?>
<tr>
    <td></td>
</tr>
</table>