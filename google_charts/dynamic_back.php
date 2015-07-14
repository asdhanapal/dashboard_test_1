<pre>

<?php //
session_start();
include_once '../sda/data_page.php';
require_once '../classes/db.class.php';
$conn = new db();
$dbcon = $conn->dbConnect();

$rows = array();
//flag is not needed
$flag = true;
$table = array();
$table['cols'] = array(

    // Labels for your chart, these represent the column titles
    // Note that one column is in "string" format and another one is in "number" format as pie chart only required "numbers" for calculating percentage and string will be used for column title
    array('label' => 'Weekly Task', 'type' => 'string'),
    array('label' => 'Percentage', 'type' => 'number')

);

$rows = array();


        $query_2 = "SELECT time,count,wu FROM user_tasks limit 1,10";
        $result_2 = $conn->runsql($query_2, $dbcon);
        while ($result_row_2 = mysqli_fetch_object($result_2))
{
    $temp = array();
    // the following line will be used to slice the Pie chart
    $temp[] = array('v' => (string) $result_row_2->count); 

    // Values of each slice
    $temp[] = array('v' => (int) $result_row_2->wu); 
    $rows[] = array('c' => $temp);
}

$table['rows'] = $rows;

print_r($table);
$jsonTable = json_encode($table);
//echo $jsonTable;
?>

<html>
  <head>
    <!--Load the Ajax API-->
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script type="text/javascript">

    // Load the Visualization API and the piechart package.
    google.load('visualization', '1', {'packages':['corechart']});

    // Set a callback to run when the Google Visualization API is loaded.
    google.setOnLoadCallback(drawChart);

    function drawChart() {

      // Create our data table out of JSON data loaded from server.
      var data = new google.visualization.DataTable(<?=$jsonTable?>);
      var options = {
           title: 'My Weekly Plan',
          is3D: 'true',
          width: 800,
          height: 600
        };
      // Instantiate and draw our chart, passing in some options.
      // Do not forget to check your div ID
      var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }
    </script>
  </head>

  <body>
    <!--this is the div that will hold the pie chart-->
    <div id="chart_div"></div>
  </body>
</html>
<?php
////$data=  array(
////    array("Tasks","Actual","Target")
////);
//
//$rows = array();
//$table = array();
//$table['cols'] = array(
//    array('label' => 'Weekly Task', 'type' => 'string'),
//    array('label' => 'Percentage', 'type' => 'number')
//);
//
//
//$temp = array();
//    // the following line will be used to slice the Pie chart
//    $temp[] = array('v' => "1000"); 
//
//    // Values of each slice
//    $temp[] = array('v' => "2000"); 
//    $rows[] = array('c' => $temp);
//
//$table['rows'] = $rows;
//
//
//print_r($table);
//die();
//$date_from="2015-02-01";
//$date_to="2015-02-23";
//$team="1";
//if(sizeof($team)!=1 ||empty($team))
//    die("Please select exactly one team for create chart!");
//else
//    $team=$team[0];
//$where_1="team_id='$team' AND date BETWEEN '$date_from' AND '$date_to'";
//
//$query_1 = "SELECT task_id,task_name,about_chart FROM amz_tasks WHERE team_id='$team' AND about_chart != 0 ORDER BY `task_name` ASC"; 
//$result_1 = $conn->runsql($query_1, $dbcon);
//while ($result_row_1 = mysqli_fetch_object($result_1)) 
//{
////    print_r($result_row_1);
//    if($result_row_1->about_chart==1)
//    {
//        $task_id=$result_row_1->task_id;
//        $tot_time=$tot_time_ot="";
//        $tot_count=$tot_count_ot=0;
//        $secs=$secs_ot=0;
//        $tot_work_units=$tot_work_units_ot=0.0;
//
//        $query_2 = "SELECT time,count,wu FROM user_tasks WHERE tasks_id='$task_id' AND ". $where_1;
//        $result_2 = $conn->runsql($query_2, $dbcon);
//        while ($result_row_2 = mysqli_fetch_object($result_2))
//        {
//            $tot_count+=$result_row_2->count;
//            $secs+= strtotime($result_row_2->time)-strtotime("00:00:00");
//            $tot_work_units+=$result_row_2->wu;
//        }
//
//        $query_ot = "SELECT time,count,wu FROM user_tasks_ot WHERE tasks_id='$task_id' AND ". $where_1 ." AND ot_status='1'";
//        $result_ot = $conn->runsql($query_ot, $dbcon);
//        while ($result_row_ot = mysqli_fetch_object($result_ot)) 
//        {
//            $tot_count_ot+=$result_row_ot->count;
//            $secs_ot+= strtotime($result_row_ot->time)-strtotime("00:00:00");
//            $tot_work_units_ot+=$result_row_ot->wu;
//        }
//        if($secs!=0 && ($tot_work_units!=0 || $tot_work_units_ot!=0) )
//        {
//            $data[]=  array($result_row_1->task_name,round(($tot_work_units+$tot_work_units_ot)/$secs*28800,2),100);
//        }
//    }
//    else if($result_row_1->about_chart==2)
//    {
//        $task_id=$result_row_1->task_id;
//        
//        $query_2 = "SELECT sub_task_id,sub_task_name,about_chart FROM amz_sub_tasks WHERE team_id='$team' AND task_id='$task_id' AND about_chart != 0 ORDER BY `sub_task_name` ASC"; 
//        $result_2 = $conn->runsql($query_2, $dbcon);
//        while ($result_row_2 = mysqli_fetch_object($result_2)) 
//        {
//            $sub_task_id=$result_row_2->sub_task_id;
//            if($result_row_2->about_chart==1)
//            {
//                $tot_time=$tot_time_ot="";
//                $tot_count=$tot_count_ot=0;
//                $secs=$secs_ot=0;
//                $tot_work_units=$tot_work_units_ot=0.0;
////
//                $query_3 = "SELECT time,count,wu FROM user_tasks WHERE tasks_id='$task_id' AND sub_task_id='$sub_task_id' AND ". $where_1;
//                $result_3 = $conn->runsql($query_3, $dbcon);
//                while ($result_row_3 = mysqli_fetch_object($result_3))
//                {
//                    $tot_count+=$result_row_3->count;
//                    $secs+= strtotime($result_row_3->time)-strtotime("00:00:00");
//                    $tot_work_units+=$result_row_3->wu;
//                }
//
//                $query_ot = "SELECT time,count,wu FROM user_tasks_ot WHERE tasks_id='$task_id'  AND sub_task_id='$sub_task_id' AND ". $where_1 ." AND ot_status='1'";
//               $result_ot = $conn->runsql($query_ot, $dbcon);
//                while ($result_row_ot = mysqli_fetch_object($result_ot)) 
//                {
//                    $tot_count_ot+=$result_row_ot->count;
//                    $secs_ot+= strtotime($result_row_ot->time)-strtotime("00:00:00");
//                    $tot_work_units_ot+=$result_row_ot->wu;
//                }
//                if($secs!=0 && ($tot_work_units!=0 || $tot_work_units_ot!=0) )
//                    $data[]=  array($result_row_2->sub_task_name,round(($tot_work_units+$tot_work_units_ot)/$secs*28800,2),100);
//            }
//        }
//    }
//}
//print_r($data);
//echo json_encode($data);
 ?>
