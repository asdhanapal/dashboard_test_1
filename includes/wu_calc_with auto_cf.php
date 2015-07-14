<pre>
<h5>Run the decide work type also. Its explained in end of this script execution.</h5><br>
<?php
set_time_limit(0);
require_once '../classes/db.class.php';
include '../includes/time_calc.php';
$conn = new db();
$dbcon = $conn->dbConnect();

echo "Setting up the environment";
$setup_q_1="UPDATE `amz_dt_manage` SET `wu_updation`=0";
$conn->runsql($setup_q_1, $dbcon);
$setup_q_2="UPDATE `amz_daily_target` SET `wu_status`=0";
$conn->runsql($setup_q_2, $dbcon);
$setup_q_3="UPDATE `user_tasks` SET `cf`=NULL,`wu`=NULL,`wu_status`=0";
$conn->runsql($setup_q_3, $dbcon);
$setup_q_4="UPDATE `user_tasks_ot` SET `cf`=NULL,`wu`=NULL,`wu_status`=0";
$conn->runsql($setup_q_4, $dbcon);
echo "Start<br>";
//Select con. fac completed months
$query_3="SELECT * FROM amz_dt_manage WHERE cf_updation='1' AND wu_updation='0' AND con_fac_updation=1";
$result_3 = $conn->runsql($query_3, $dbcon);
while ($result_row_3 = mysqli_fetch_object($result_3))
{
    echo "Step 1 started...<br>";
    $month=$result_row_3->month;
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
//      echo "<br>";
        $result_100 = $conn->runsql($query_100, $dbcon);

        $query_update_con_fac_100="Update amz_daily_target SET wu_status=1 where s_no='$s_no'";
        $result_update_con_fac_100 = $conn->runsql($query_update_con_fac_100, $dbcon);
        if(!$result_5 || !$result_update_con_fac_100 || $result_100)
        {
            $query_fail=mysqli_real_escape_string($dbcon,$query_5+$query_update_con_fac_100+$query_100);
            $fail_query="INSERT INTO amz_failed_quries(query) VALUES('$query_fail')";
            $conn->runsql($fail_query, $dbcon);
        }
    }
    echo "Step 1 finished<br>";
    echo "Step 2 started...<br>";
//  Updating the Auto-CF entries
//  Get the auto cf wu for a min.
    $month_modiified_struct_2=date("Y-m",  strtotime($month));
    $query_6="SELECT s_no,team,task,sub_task FROM amz_daily_target WHERE month_from='$month' AND about_cf=0 AND cf_updated=1";
    $result_6 = $conn->runsql($query_6, $dbcon);
    while ($result_row_6 = mysqli_fetch_object($result_6))
    {
//      print_r($result_row_6);
        $s_no_1=$result_row_6->s_no;
        $team_id=$result_row_6->team;
        $task_id=$result_row_6->task;
        $sub_task_id=$result_row_6->sub_task;
        if($sub_task_id!="")
        {
            $query_9="UPDATE user_tasks SET wu_status='1' WHERE team_id='$team_id' AND tasks_id='$task_id' AND sub_task_id='$sub_task_id' AND date LIKE '%$month_modiified_struct_2%' AND wu_status='0'";
        }
        else
        {
            $query_8="UPDATE user_tasks SET wu_status='1' WHERE team_id='$team_id' AND tasks_id='$task_id' AND sub_task_id IS NULL AND date LIKE '%$month_modiified_struct_2%' AND wu_status='0'";
        }
        
//        $query_7="SELECT con_fac FROM amz_daily_target WHERE month_from='$month' AND team=$team_id AND sub_task=2";
//        $result_7 = $conn->runsql($query_7, $dbcon);
//        if(!mysqli_num_rows($result_7))
//        {
//            $query_7="SELECT con_fac FROM amz_daily_target WHERE month_from='$month' AND team=$team_id AND sub_task=179";
//            $result_7 = $conn->runsql($query_7, $dbcon);
//        }
        
//        while ($result_row_7 = mysqli_fetch_object($result_7))
//        {
//            $con_fac=$result_row_7->con_fac;
////          print_r($result_row_7);
//            $daily_target_in_hrs=$result_row_7->con_fac/480;
//            $daily_target_in_hrs=round($daily_target_in_hrs,4);
//            if($sub_task_id!=NULL)
//                $query_8="SELECT task_id,time FROM user_tasks WHERE team_id='$team_id' AND tasks_id='$task_id' AND sub_task_id='$sub_task_id' AND date LIKE '%$month_modiified_struct_2%' AND wu_status='0'";
//            else
//                $query_8="SELECT task_id,time FROM user_tasks WHERE team_id='$team_id' AND tasks_id='$task_id' AND sub_task_id IS NULL AND date LIKE '%$month_modiified_struct_2%' AND wu_status='0'";
////          echo "<br>";
//            $result_8 = $conn->runsql($query_8, $dbcon);
//            while ($result_row_8 = mysqli_fetch_object($result_8))
//            {
////              print_r($result_row_8);
//                $user_entry_task_id_1=$result_row_8->task_id;
//                $time_in_hr=$result_row_8->time;
//                $time_in_min=h2m($time_in_hr);
//                $count_temp=$time_in_min*$daily_target_in_hrs;
//                $wu=$count_temp*100/$con_fac;
//                $wu=round($wu,4);
//                $query_9="UPDATE user_tasks SET cf='$con_fac', wu='$wu', wu_status='1' WHERE task_id='$user_entry_task_id_1'";
////              echo "<br>";
//                $result_9 = $conn->runsql($query_9, $dbcon);
//                if(!$result_9)
//                {
//                    $query_fail=mysqli_real_escape_string($dbcon,$query_9);
//                    $fail_query="INSERT INTO amz_failed_quries(query) VALUES('$query_fail')";
//                    $conn->runsql($fail_query, $dbcon);
//                }
//            }

//          Updating the OT entries
//            if($sub_task_id!=NULL)
//                $query_800="SELECT task_id,time FROM user_tasks_ot WHERE team_id='$team_id' AND tasks_id='$task_id' AND sub_task_id='$sub_task_id' AND date LIKE '%$month_modiified_struct_2%' AND wu_status='0' AND ot_status='1'";
//            else
//                $query_800="SELECT task_id,time FROM user_tasks_ot WHERE team_id='$team_id' AND tasks_id='$task_id' AND sub_task_id IS NULL AND date LIKE '%$month_modiified_struct_2%' AND wu_status='0'  AND ot_status='1'";
////          echo "<br>";
//            $result_800 = $conn->runsql($query_800, $dbcon);
//            while ($result_row_800 = mysqli_fetch_object($result_800))
//            {
////              print_r($result_row_8);
//                $user_entry_task_id_1=$result_row_800->task_id;
//                $time_in_hr=$result_row_800->time;
//                $time_in_min=h2m($time_in_hr);
//                $wu=$time_in_min*$daily_target_in_hrs;
//                $wu=round($wu,4);
//                $query_900="UPDATE user_tasks_ot SET cf='$daily_target_in_hrs', wu='$wu', wu_status='1' WHERE task_id='$user_entry_task_id_1'";
////              echo "<br>";
//                $result_900 = $conn->runsql($query_900, $dbcon);
//                if(!$result_900)
//                {
//                    $query_fail=mysqli_real_escape_string($dbcon,$query_900);
//                    $fail_query="INSERT INTO amz_failed_quries(query) VALUES('$query_fail')";
//                    $conn->runsql($fail_query, $dbcon);
//                }
//            }
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
//  echo "<br>";
    $result_15 = $conn->runsql($query_15, $dbcon);
    while ($result_row_15 = mysqli_fetch_object($result_15))
    {
        $s_no=$result_row_15->s_no;
        $team_id=$result_row_15->team;
        $task_id=$result_row_15->task;
        $sub_task_id=$result_row_15->sub_task;
        $con_fac=$result_row_15->con_fac;
//      print_r($result_row_15);
        $month_modiified_struct=date("Y-m",  strtotime($month));
        if($sub_task_id==1 || $sub_task_id==2 || $sub_task_id==3)
        {
//            $query_16="SELECT sub_task_id FROM amz_sub_tasks WHERE cf_change='$sub_task_id'";
////          echo "<br>";
//            $result_16 = $conn->runsql($query_16, $dbcon);
//            while ($result_row_16 = mysqli_fetch_object($result_16))
//            {
//                $customized_sub_task_id=$result_row_16->sub_task_id;
//                $query_17="SELECT task_id,count,time FROM user_tasks WHERE team_id='$team_id' AND tasks_id='$task_id' AND sub_task_id='$customized_sub_task_id' AND date LIKE '%$month_modiified_struct%' AND wu_status='0'";
////              echo "<br>";
//                $result_17 = $conn->runsql($query_17, $dbcon);
//                while ($result_row_17 = mysqli_fetch_object($result_17))
//                {
////                  print_r($result_row_17);
//                    $count=$result_row_17->count;
//                    if ($count=="0")
//                    {
//                        $time_in_hr_1=$result_row_17->time;
//                        $sub_task_auto_id=$team_id=="4"?"179":"2";
//                        $query_99="SELECT con_fac FROM amz_daily_target WHERE month_from='$month' AND team=$team_id AND sub_task='$sub_task_auto_id'";
//                        $result_99 = $conn->runsql($query_99, $dbcon);
////                        if(mysqli_num_rows($result_99))
//                        {
//                            $result_row_99 = mysqli_fetch_object($result_99);
////                            print_r($result_row_99);
//                            $con_fac_99=$result_row_99->con_fac;
//                            $con_fac_99=$result_row_99->con_fac/480;
//                            $con_fac_99=round($con_fac_99,4);
//                            $time_in_min=h2m($time_in_hr_1);
//                            $wu=$time_in_min*$con_fac_99;
//                            $wu=round($wu,4);
//                        }
//                    }
//                    else
//                    {
//                        $wu=$count*100/$con_fac;
//                        $wu=round($wu,4);
//                    }
//                    
//                    $user_entry_task_id=$result_row_17->task_id;
//                    $query_18="UPDATE user_tasks SET cf='$con_fac', wu='$wu', wu_status='1' WHERE task_id='$user_entry_task_id'";
////                  echo "<br>";
//                    $result_18 = $conn->runsql($query_18, $dbcon);
//                    if(!$result_18)
//                    {
//                        $query_fail=mysqli_real_escape_string($dbcon,$query_18);
//                        $fail_query="INSERT INTO amz_failed_quries(query) VALUES('$query_fail')";
//                        $conn->runsql($fail_query, $dbcon);
//                    }
//                }
//
//                $query_170="SELECT task_id,count,time FROM user_tasks_ot WHERE team_id='$team_id' AND tasks_id='$task_id' AND sub_task_id='$customized_sub_task_id' AND date LIKE '%$month_modiified_struct%' AND wu_status='0' AND ot_status='1'";
////              echo "<br>";
//                $result_170 = $conn->runsql($query_170, $dbcon);
//                while ($result_row_170 = mysqli_fetch_object($result_170))
//                {
////                    print_r($result_row_17);
//                    $count=$result_row_170->count;
//                    if ($count=="0")
//                    {
//                        $time_in_hr_1=$result_row_170->time;
//                        $sub_task_auto_id=$team_id==4?"179":"2";
//                        $query_990="SELECT con_fac FROM amz_daily_target WHERE month_from='$month' AND team=$team_id AND sub_task='$sub_task_auto_id'";
//                        $result_990 = $conn->runsql($query_990, $dbcon);
//                        $result_row_990 = mysqli_fetch_object($result_990);
//                        $con_fac_990=$result_row_990->con_fac;
//                        $con_fac=$result_row_990->con_fac/480;
//                        $con_fac=round($con_fac,4);
//                        $time_in_min=h2m($time_in_hr_1);
//                        $wu=$time_in_min*$con_fac;
//                        $wu=round($wu,4);
//                    }
//                    else
//                    {
//                        $wu=$count*100/$con_fac;
//                        $wu=round($wu,4);
//                    }
//                    
//                    $user_entry_task_id=$result_row_170->task_id;
//                    
//                    $query_180="UPDATE user_tasks_ot SET cf='$con_fac', wu='$wu', wu_status='1' WHERE task_id='$user_entry_task_id'";
////                    echo "<br>";
//                    $result_180 = $conn->runsql($query_180, $dbcon);
//                    if(!$result_180)
//                    {
//                        $query_fail=mysqli_real_escape_string($dbcon,$query_180);
//                        $fail_query="INSERT INTO amz_failed_quries(query) VALUES('$query_fail')";
//                        $conn->runsql($fail_query, $dbcon);
//                    }
//                }
//            }
        }
        else
        {
            $query_19="SELECT task_id,count,time FROM user_tasks WHERE team_id='$team_id' AND tasks_id='$task_id' AND sub_task_id='$sub_task_id' AND date LIKE '%$month_modiified_struct%'  AND wu_status='0'";
//          echo "<br>";
            $result_19 = $conn->runsql($query_19, $dbcon);
            while ($result_row_19 = mysqli_fetch_object($result_19))
            {
//              print_r($result_row_19);
                $count=$result_row_19->count;
                $user_entry_task_id=$result_row_19->task_id;
                $con_fac_98=$result_row_15->con_fac;

                if ($count=="0")
                    {
                        $time_in_hr_1=$result_row_19->time;
                        $sub_task_auto_id=$team_id==4?"179":"2";
                        $query_99="SELECT con_fac FROM amz_daily_target WHERE month_from='$month' AND team=$team_id AND sub_task='$sub_task_auto_id'";
                        $result_99 = $conn->runsql($query_99, $dbcon);
                        if(mysqli_num_rows($result_99))
                        {
                            $result_row_99 = mysqli_fetch_object($result_99);
    //                      print_r($result_row_99);
                            $con_fac_98=$result_row_99->con_fac;
                            $con_fac_98=$result_row_99->con_fac/480;
                            $con_fac_98=round($con_fac_98,4);
                            $time_in_min=h2m($time_in_hr_1);
                            $wu=$time_in_min*$con_fac_98;
                            $wu=round($wu,4);
                        }
                        else
                        {
                            echo "<br>Count is null. Not  able calculate Work units. ID no:".$user_entry_task_id;
                            $wu=0;
                        }
                    }
                    else
                    {
                        $wu=$count*100/$con_fac_98;
                        $wu=round($wu,4);
                    }
                
                $query_20="UPDATE user_tasks SET cf='$con_fac_98', wu='$wu', wu_status='1' WHERE task_id='$user_entry_task_id'";
//                echo "<br>";
                $result_20 = $conn->runsql($query_20, $dbcon);
                if(!$result_20)
                {
                    $query_fail=mysqli_real_escape_string($dbcon,$query_20);
                    $fail_query="INSERT INTO amz_failed_quries(query) VALUES('$query_fail')";
                    $conn->runsql($fail_query, $dbcon);
                }
            }

            $query_190="SELECT task_id,count,time FROM user_tasks_ot WHERE team_id='$team_id' AND tasks_id='$task_id' AND sub_task_id='$sub_task_id' AND date LIKE '%$month_modiified_struct%'  AND wu_status='0' AND ot_status='1'";
//            echo "<br>";
            $result_190 = $conn->runsql($query_190, $dbcon);
            while ($result_row_190 = mysqli_fetch_object($result_190))
            {
//                print_r($result_row_19);
                $count=$result_row_190->count;
                $user_entry_task_id=$result_row_190->task_id;
                $con_fac_98=$result_row_15->con_fac;

                if ($count=="0")
                {
                    $time_in_hr_1=$result_row_190->time;
                    $sub_task_auto_id=$team_id==4?"179":"2";
                    $query_99="SELECT con_fac FROM amz_daily_target WHERE month_from='$month' AND team=$team_id AND sub_task='$sub_task_auto_id'";
                    $result_99 = $conn->runsql($query_99, $dbcon);
                    if(mysqli_num_rows($result_99))
                    {
                        $result_row_99 = mysqli_fetch_object($result_99);
                        $con_fac_98=$result_row_99->con_fac;
                        $con_fac_98=$result_row_99->con_fac/480;
                        $con_fac_98=round($con_fac_98,4);
                        $time_in_min=h2m($time_in_hr_1);
                        $wu=$time_in_min*$con_fac_98;
                        $wu=round($wu,4);
                    }
                }
                else
                {
                    $wu=$count*100/$con_fac_98;
                    $wu=round($wu,4);
                }
                    
                $wu=$count*100/$con_fac_98;
                $wu=round($wu,4);
                $query_200="UPDATE user_tasks_ot SET cf='$con_fac_98', wu='$wu', wu_status='1' WHERE task_id='$user_entry_task_id'";
//                echo "<br>";
                $result_200 = $conn->runsql($query_200, $dbcon);
                if(!$result_200)
                {
                    $query_fail=mysqli_real_escape_string($dbcon,$query_200);
                    $fail_query="INSERT INTO amz_failed_quries(query) VALUES('$query_fail')";
                    $conn->runsql($fail_query, $dbcon);
                }
            }
        }
        
        $query_update_con_fac_1="Update amz_daily_target SET wu_status=1 where s_no='$s_no'";
        $result_update_con_fac_1 = $conn->runsql($query_update_con_fac_1, $dbcon);
        if(!$result_update_con_fac_1)
        {
            $query_fail=mysqli_real_escape_string($dbcon,$query_update_con_fac_1);
            $fail_query="INSERT INTO amz_failed_quries(query) VALUES('$query_fail')";
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
    //if( && $result_final_result_2)
    {
        $update_final="UPDATE amz_dt_manage SET wu_updation=1 WHERE month='$month'";
        $result_final_result= $conn->runsql($update_final, $dbcon);
        if(!$result_final_result)
        {
            $query_fail=mysqli_real_escape_string($dbcon,$update_final);
            $fail_query="INSERT INTO amz_failed_quries(query) VALUES('$query_fail')";
            $conn->runsql($fail_query, $dbcon);
        }
    }
    else
    {
        $query_fail=mysqli_real_escape_string($dbcon,$query_final_1+$query_final_2);
        $fail_query="INSERT INTO amz_failed_quries(query) VALUES('$query_fail')";
        $conn->runsql($fail_query, $dbcon);
    }
    echo "Step 3 finished...<br>";
}
echo "This process completed...<br>";
?>
<br>And do one more thing.<br>
Please run the below script also.<br>
<a href="./decide_work_type.php">Click here</a> &nbsp; (Just click the link).