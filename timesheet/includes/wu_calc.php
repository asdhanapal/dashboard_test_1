<pre>
<h5>Run the decide work type also. It's explained in end of this script execution.</h5><br>
<?php
set_time_limit(0);
$result_row_dc_array=array();
require_once '../classes/db.class.php';
require_once '../includes/time_calc.php';

$conn = new db();
$dbcon = $conn->dbConnect();
//goto test1;
echo "Setting up the environment<br>";
//$setup_q_1="UPDATE `amz_dt_manage` SET `wu_updation`=0";
//$conn->runsql($setup_q_1, $dbcon);
//$setup_q_2="UPDATE `amz_daily_target` SET `wu_status`=0";
//$conn->runsql($setup_q_2, $dbcon);
//$setup_q_3="UPDATE `user_tasks` SET `cf`=NULL,`wu`=NULL,`wu_status`=0";
//$conn->runsql($setup_q_3, $dbcon);
//$setup_q_4="UPDATE `user_tasks_ot` SET `cf`=NULL,`wu`=NULL,`wu_status`=0";
//$conn->runsql($setup_q_4, $dbcon);
echo "Start<br>";
//die();
//test1:
//Select con. fac completed months
$query_3="SELECT * FROM amz_dt_manage WHERE cf_updation='1' AND wu_updation='0' AND con_fac_updation=1";
$result_3 = $conn->runsql($query_3, $dbcon);
while ($result_row_3 = mysqli_fetch_object($result_3))
{
    echo "Step 1 started...<br>";
    $month=$result_row_3->month;

echo $setup_q_2="UPDATE `amz_daily_target` SET `wu_status`=0 WHERE month_from='$month'";
$conn->runsql($setup_q_2, $dbcon);
$month_modiified_struct_call_1=date("Y-m",  strtotime($month));
echo $setup_q_3="UPDATE `user_tasks` SET `cf`=NULL,`wu`=NULL,`wu_status`=0 WHERE date LIKE '%$month_modiified_struct_call_1%'";
$conn->runsql($setup_q_3, $dbcon);
 echo $setup_q_4="UPDATE `user_tasks_ot` SET `cf`=NULL,`wu`=NULL,`wu_status`=0 WHERE date LIKE '%$month_modiified_struct_call_1%'";
$conn->runsql($setup_q_4, $dbcon);
//continue;

//  Updating the non- CF entries
    $query_4="SELECT s_no,team,task,sub_task FROM amz_daily_target WHERE month_from='$month' AND about_cf IS NULL AND cf_updated=1";
    $result_4 = $conn->runsql($query_4, $dbcon);
    while ($result_row_4 = mysqli_fetch_object($result_4))
    {
        $team_id=$result_row_4->team;
        $task_id=$result_row_4->task;
        $sub_task_id=$result_row_4->sub_task;
        $s_no=$result_row_4->s_no;
        $month_modiified_struct_1=date("Y-m",  strtotime($month));
        $query_5="UPDATE user_tasks SET wu_status=1 WHERE team_id='$team_id' AND tasks_id='$task_id' AND sub_task_id='$sub_task_id' AND date LIKE '%$month_modiified_struct_1%' AND wu_status='0' ";
        $result_5 = $conn->runsql($query_5, $dbcon);

//      Updating the OT entries
        $query_100="UPDATE user_tasks_ot SET wu_status=1 WHERE team_id='$team_id' AND tasks_id='$task_id' AND sub_task_id='$sub_task_id' AND date LIKE '%$month_modiified_struct_1%' AND wu_status='0' AND ot_status=1";
        $result_100 = $conn->runsql($query_100, $dbcon);

        $query_update_con_fac_100="Update amz_daily_target SET wu_status=1 where s_no='$s_no'";
        $result_update_con_fac_100 = $conn->runsql($query_update_con_fac_100, $dbcon);
        if(!$result_5 || !$result_update_con_fac_100 || !$result_100)
        {
            $query_fail=mysqli_real_escape_string($dbcon,$query_5."+".$query_update_con_fac_100."+".$query_100);
            $fail_query="INSERT INTO amz_failed_quries(query) VALUES('$query_fail')";
            $conn->runsql($fail_query, $dbcon);
        }
    }

    echo "Step 1 finished<br>";
    echo "Step 2 started...<br>";

//  Updating the Auto-CF entries
    $month_modiified_struct_2=date("Y-m",  strtotime($month));
    $query_6="SELECT s_no,team,task,sub_task FROM amz_daily_target WHERE month_from='$month' AND about_cf=0 AND cf_updated=1";
    $result_6 = $conn->runsql($query_6, $dbcon);
    while ($result_row_6 = mysqli_fetch_object($result_6))
    {
        $s_no_1=$result_row_6->s_no;
        $team_id=$result_row_6->team;
        $task_id=$result_row_6->task;
        $sub_task_id=$result_row_6->sub_task;
        if($sub_task_id!="")
        {
            $query_7="UPDATE user_tasks SET wu_status='1' WHERE team_id='$team_id' AND tasks_id='$task_id' AND sub_task_id='$sub_task_id' AND date LIKE '%$month_modiified_struct_2%' AND wu_status='0'";
        }
        else
        {
            $query_7="UPDATE user_tasks SET wu_status='1' WHERE team_id='$team_id' AND tasks_id='$task_id' AND sub_task_id IS NULL AND date LIKE '%$month_modiified_struct_2%' AND wu_status='0'";
        }
        $result_update_auto_cf_1 = $conn->runsql($query_7, $dbcon);
        if(!$result_update_auto_cf_1)
        {
            $query_fail=mysqli_real_escape_string($dbcon,$query_7);
            $fail_query="INSERT INTO amz_failed_quries(query) VALUES('$query_fail')";
            $conn->runsql($fail_query, $dbcon);
        }

        if($sub_task_id!=NULL)
        {
            $query_8="UPDATE user_tasks_ot SET wu_status='1' WHERE team_id='$team_id' AND tasks_id='$task_id' AND sub_task_id='$sub_task_id' AND date LIKE '%$month_modiified_struct_2%' AND wu_status='0' AND ot_status='1'";
        }
        else
        {
            $query_8="UPDATE user_tasks_ot SET wu_status='1' WHERE team_id='$team_id' AND tasks_id='$task_id' AND sub_task_id IS NULL AND date LIKE '%$month_modiified_struct_2%' AND wu_status='0'  AND ot_status='1'";
        }
        $result_update_auto_cf_1_ot = $conn->runsql($query_8, $dbcon);
        if(!$result_update_auto_cf_1_ot)
        {
            $query_fail=mysqli_real_escape_string($dbcon,$query_8);
            $fail_query="INSERT INTO amz_failed_quries(query) VALUES('$query_fail')";
            $conn->runsql($fail_query, $dbcon);
        }
        
        $query_update_con_fac_1="Update amz_daily_target SET wu_status=1 where s_no='$s_no_1'";
        $result_update_con_fac_1 = $conn->runsql($query_update_con_fac_1, $dbcon);
        if(!$result_update_con_fac_1)
        {
            $query_fail=mysqli_real_escape_string($dbcon,$query_update_con_fac_1);
            $fail_query="INSERT INTO amz_failed_quries(query) VALUES('$query_fail')";
            $conn->runsql($fail_query, $dbcon);
        }
    }
    echo "Step 2 finished<br>";
    echo "Step 3 started...<br>";

//  updating manual CF    
    $query_15="SELECT s_no,team,task,sub_task,con_fac FROM amz_daily_target WHERE month_from='$month' AND about_cf=1 AND cf_updated=1";
    $result_15 = $conn->runsql($query_15, $dbcon);
    while ($result_row_15 = mysqli_fetch_object($result_15))
    {
        $s_no=$result_row_15->s_no;
        $team_id=$result_row_15->team;
        $task_id=$result_row_15->task;
        $sub_task_id=$result_row_15->sub_task;
        $month_modiified_struct=date("Y-m",  strtotime($month));
        
        $query_19="SELECT task_id,count,noofdevice FROM user_tasks WHERE team_id='$team_id' AND tasks_id='$task_id' AND sub_task_id='$sub_task_id' AND date LIKE '%$month_modiified_struct%'  AND wu_status='0'  ORDER BY task_id ASC";
        $result_19 = $conn->runsql($query_19, $dbcon);
        while ($result_row_19 = mysqli_fetch_object($result_19))
        {
            $count=$result_row_19->count;
            $user_entry_task_id=$result_row_19->task_id;
            $con_fac_98=$result_row_15->con_fac;
            if ($count=="0")
            {
                echo "<br>Count is null. Unable to calculate the Work units. Ref ID:".$user_entry_task_id."<br>";
                $wu=0;
            }
            else
            {
                $noofdevice=$result_row_19->noofdevice;
                if($noofdevice>1 && $noofdevice<7)
                {
                    $query_no_of_build="SELECT percentage FROM amz_dc_units WHERE team_id='$team_id' AND task_id='$task_id' AND sub_task_id='$sub_task_id' AND noofdevice='$noofdevice' AND month='$month'";
                    $result_no_of_build = $conn->runsql($query_no_of_build, $dbcon);
                    $result_row_no_of_build = mysqli_fetch_object($result_no_of_build);
                    $slice_value=$result_row_no_of_build->percentage;
                    if($slice_value!=NULL) {
                        $count=$count+(($count/100)*$slice_value); 
                    } else {
                        continue 1;
                    }
                }
                $wu=$count*100/$con_fac_98;
                $wu=round($wu,4);
            }
            $query_20="UPDATE user_tasks SET cf='$con_fac_98', wu='$wu', wu_status='1' WHERE task_id='$user_entry_task_id'";
            $result_20 = $conn->runsql($query_20, $dbcon);
            if(!$result_20)
            {
                $query_fail=mysqli_real_escape_string($dbcon,$query_20);
                $fail_query="INSERT INTO amz_failed_quries(query) VALUES('$query_fail')";
                $conn->runsql($fail_query, $dbcon);
            }
        }
        $query_190="SELECT task_id,count,noofdevice FROM user_tasks_ot WHERE team_id='$team_id' AND tasks_id='$task_id' AND sub_task_id='$sub_task_id' AND date LIKE '%$month_modiified_struct%'  AND wu_status='0' AND ot_status='1'";
        $result_190 = $conn->runsql($query_190, $dbcon);
        while ($result_row_190 = mysqli_fetch_object($result_190))
        {
            $count=$result_row_190->count;
            $user_entry_task_id=$result_row_190->task_id;
            $con_fac_98=$result_row_15->con_fac;

            if ($count=="0")
            {
                echo "<br>Count is null. Not  able calculate Work units. ID no:".$user_entry_task_id."<br>";
                $wu=0;
            }
            else
            {
                $noofdevice=$result_row_190->noofdevice;
                if($noofdevice>1 && $noofdevice<7)
                {
                    $query_no_of_build="SELECT percentage FROM amz_dc_units WHERE team_id='$team_id' AND task_id='$task_id' AND sub_task_id='$sub_task_id' AND noofdevice='$noofdevice' AND month='$month'";
                    $result_no_of_build = $conn->runsql($query_no_of_build, $dbcon);
                    $result_row_no_of_build = mysqli_fetch_object($result_no_of_build);
                    $slice_value=$result_row_no_of_build->percentage;
                    if($slice_value!=NULL) {
                        $count=$count+(($count/100)*$slice_value);
                    } else {
                        continue 1;
                    }
                }
                $wu=$count*100/$con_fac_98;
                $wu=round($wu,4);
            }

            $query_200="UPDATE user_tasks_ot SET cf='$con_fac_98', wu='$wu', wu_status='1' WHERE task_id='$user_entry_task_id'";
            $result_200 = $conn->runsql($query_200, $dbcon);
            if(!$result_200)
            {
                $query_fail=mysqli_real_escape_string($dbcon,$query_200);
                echo "<br>6.".$fail_query="INSERT INTO amz_failed_quries(query) VALUES('$query_fail')";
                $conn->runsql($fail_query, $dbcon);
            }
        }
        
        $query_update_con_fac_1="Update amz_daily_target SET wu_status=1 where s_no='$s_no'";
        $result_update_con_fac_1 = $conn->runsql($query_update_con_fac_1, $dbcon);
        if(!$result_update_con_fac_1)
        {
            $query_fail=mysqli_real_escape_string($dbcon,$query_update_con_fac_1);
            echo "<br>7.".$fail_query="INSERT INTO amz_failed_quries(query) VALUES('$query_fail')";
            $conn->runsql($fail_query, $dbcon);
        }
    }

    $query_final_1="SELECT team,task,sub_task,s_no FROM amz_daily_target WHERE wu_status=0 AND month_from='$month'";
    $query_final_2="SELECT task_id,team_id,tasks_id,sub_task_id FROM user_tasks WHERE wu_status=0 AND date LIKE '%$month_modiified_struct%'";
    $query_final_3="SELECT task_id,team_id,tasks_id,sub_task_id FROM user_tasks_ot WHERE wu_status=0 AND date LIKE '%$month_modiified_struct%' AND ot_status='1'";
    $result_final_result_1 = $conn->runsql($query_final_1, $dbcon);
    $result_final_result_2= $conn->runsql($query_final_2, $dbcon);
    $result_final_result_3= $conn->runsql($query_final_3, $dbcon);

    if( (!mysqli_num_rows($result_final_result_1)) && (!mysqli_num_rows($result_final_result_2)) && (!mysqli_num_rows($result_final_result_3)))    
    {
        $update_final="UPDATE amz_dt_manage SET wu_updation=1 WHERE month='$month'";
        $result_final_result= $conn->runsql($update_final, $dbcon);
        if(!$result_final_result)
        {
            $query_fail=mysqli_real_escape_string($dbcon,$update_final);
            echo "<br>8.".$fail_query="INSERT INTO amz_failed_quries(query) VALUES('$query_fail')";
            $conn->runsql($fail_query, $dbcon);
        }
    }
    echo "Step 3 finished...<br>";
//    test2:
}
echo "This process completed...<br>";

?>
<br>And do one more thing.<br>
Please run the below script also.<br>
<a href="./decide_work_type.php">Click here</a> &nbsp; (Just click the link).
