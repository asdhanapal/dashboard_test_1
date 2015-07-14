<?php
require_once '../classes/db.class.php';
include '../includes/time_calc.php';
$conn = new db();
$dbcon = $conn->dbConnect();

$query="SELECT month FROM amz_dt_manage WHERE con_fac_updation=0";
$result = $conn->runsql($query, $dbcon);
while ($result_row = mysqli_fetch_object($result)) 
{
    $month=$result_row->month;
    $query_1="SELECT team_id FROM amz_teams WHERE team_deletion='0' AND status='1'";
    $result_1 = $conn->runsql($query_1, $dbcon);
    while ($result_row_1 = mysqli_fetch_object($result_1)) 
    {
        $team=$result_row_1->team_id;
        $query_2="SELECT task_id,have_complexity,about_cf,device_count FROM amz_tasks WHERE deletion='0' AND have_st='1' AND status='1' AND team_id='$team'"; //AND about_cf='1'
        $result_2 = $conn->runsql($query_2, $dbcon);
        while ($result_row_2 = mysqli_fetch_object($result_2))
        {
            $task=$result_row_2->task_id;
            if(!$result_row_2->have_complexity)
            {
                $query_3="SELECT sub_task_id,about_cf FROM amz_sub_tasks WHERE deletion='0' AND task_status='1' AND task_id='$task'"; //AND about_cf='1' 
                $result_3 = $conn->runsql($query_3, $dbcon);
                while ($result_row_3 = mysqli_fetch_object($result_3)) 
                {
                    $sub_task=$result_row_3->sub_task_id;
                    $about_cf=$result_row_3->about_cf;
                    $cf_updated=$about_cf==1?0:1;
                    
                    if($about_cf=="")
                        $about_cf='NULL';

                    $query_check_exist="SELECT * FROM amz_daily_target WHERE month_from='$month' AND team='$team' AND task='$task' AND sub_task='$sub_task'";
                    $result_check_exist = $conn->runsql($query_check_exist, $dbcon);
                    if (!mysqli_num_rows($result_check_exist))
                    {
                        echo $query_cf_update="INSERT INTO amz_daily_target(month_from,team,task,sub_task,about_cf,cf_updated,create_date,maintain_date) VALUES ('$month','$team','$task','$sub_task',$about_cf,'$cf_updated',now(),now())";
                        echo "<br>";
                        $conn->runsql($query_cf_update, $dbcon);
                    }
                    
                    if($result_row_2->device_count)
                    {
                        //Add the device count table
                        $query_check_exist_dc="SELECT month FROM amz_dc_units WHERE month='$month' AND team_id='$team' AND task_id='$task' AND sub_task_id='$sub_task'";
                        $result_check_exist_dc = $conn->runsql($query_check_exist_dc, $dbcon);
                        if (!mysqli_num_rows($result_check_exist_dc))
                        {
                            for ($j=2;$j<7;$j++) {
                                echo $query_dc_update="INSERT INTO amz_dc_units(month,team_id,task_id,sub_task_id,noofdevice) VALUES ('$month','$team','$task','$sub_task','$j')";
                                echo "<br>";
                                $conn->runsql($query_dc_update, $dbcon);
                            }
                        }
                    }
                    
                }
            }
            else
            {
                echo "This feature is deprecated!<br>";
                //echo "Insert LC MC HC";
//                for($i=1;$i<=3;$i++)
//                {
//                    $query_check_exist="SELECT * FROM amz_daily_target WHERE month_from='$month' AND team='$team' AND task='$task' AND sub_task='$i'";
//                    $result_check_exist = $conn->runsql($query_check_exist, $dbcon);
//                    if (!mysqli_num_rows($result_check_exist))
//                    {
//                        echo $query_cf_update="INSERT INTO amz_daily_target(month_from,team,task,sub_task,about_cf,create_date,maintain_date) VALUES ('$month','$team','$task','$i','1',now(),now())";
//                        echo "<br>";
//                        $conn->runsql($query_cf_update, $dbcon);
//                    }
//                }
            }
        }
        
        $query_10="SELECT task_id FROM amz_tasks WHERE deletion='0' AND have_st='0' AND status='1' AND team_id='$team'";
        $result_10 = $conn->runsql($query_10, $dbcon);
        while ($result_row_10 = mysqli_fetch_object($result_10))
        {
            $task=$result_row_10->task_id;
            $query_check_exist="SELECT * FROM amz_daily_target WHERE month_from='$month' AND team='$team' AND task='$task'";
            $result_check_exist = $conn->runsql($query_check_exist, $dbcon);
            if (!mysqli_num_rows($result_check_exist))
            {
                echo $query_cf_update="INSERT INTO amz_daily_target(month_from,team,task,about_cf,cf_updated,create_date,maintain_date) VALUES ('$month','$team','$task','0','1',now(),now())";
                echo "<br>";
                $conn->runsql($query_cf_update, $dbcon);
            }
        }
    }
    echo $query_update_amz_dt_manage="UPDATE amz_dt_manage SET con_fac_updation=1 WHERE month='$month'";
    echo "<br>";
    $conn->runsql($query_update_amz_dt_manage, $dbcon);
}
?>