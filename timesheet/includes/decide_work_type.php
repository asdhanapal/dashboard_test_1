<pre>
<?php
require_once '../classes/db.class.php';
include '../includes/time_calc.php';
$conn = new db();
$dbcon = $conn->dbConnect();

$query_0="UPDATE user_tasks SET work_type=0";
$result_0 = $conn->runsql($query_0, $dbcon);
if(!$result_0)
   echo "<br>Failed query: ".$query_0;
else
   echo "&nbsp;&nbsp;&nbsp;Setting up the environment... Pass!<br><br>";

$query_0="UPDATE user_tasks_ot SET work_type=0";
$result_0 = $conn->runsql($query_0, $dbcon);
if(!$result_0)
   echo "<br>Failed query: ".$query_0;
else
   echo "&nbsp;&nbsp;&nbsp;Setting up the environment... Pass!<br><br>";


$query_1="SELECT sub_task_id,sub_task_name FROM amz_sub_tasks WHERE about_cf IS NULL "; //AND
$result_1 = $conn->runsql($query_1, $dbcon);
while ($result_row_1 = mysqli_fetch_object($result_1))
{
    $non_work_entry=$result_row_1->sub_task_id;
    echo "<br>".$result_row_1->sub_task_name;
    $query_2="UPDATE user_tasks SET work_type=1 WHERE sub_task_id='$non_work_entry'";
    $result_2 = $conn->runsql($query_2, $dbcon);
    if(!$result_2)
        echo "<br>Failed query: ".$query_2;
    else
        echo "&nbsp;&nbsp;&nbsp;Updating... Pass!";

    $query_3="UPDATE user_tasks_ot SET work_type=1 WHERE sub_task_id='$non_work_entry'";
    $result_3 = $conn->runsql($query_3, $dbcon);
    if(!$result_3)
        echo "<br>Failed query: ".$query_3;
    else
        echo "&nbsp;&nbsp;&nbsp;Updating... Pass!";   
}
//    $query_4="UPDATE user_tasks SET wu_status=0 WHERE sub_task_id IS NULL";
//    $result_4 = $conn->runsql($query_4, $dbcon);
//    if(!$result_4)
//        echo "<br>Failed query: ".$query_4;
?>