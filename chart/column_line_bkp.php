<?php
session_start();
include_once '../sda/data_page.php';
require_once '../classes/db.class.php';
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
if(sizeof($team)!=1 ||empty($team))
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
}else
    $task_query="";

$where_1="team_id='$team' AND date BETWEEN '$date_from' AND '$date_to'";

if($user!="null")
{
    $where_1.=" AND user_id IN ($user) ";
    $subtitle.=" and user(s)";
}


$output['result_status']="S";
$output['title']="Report between $date_from and $date_to";

$task_list=$actual_list=$target_list=$actual_list_tot=$daily_target_temp="";
$query_1 = "SELECT task_id,task_name,about_chart FROM amz_tasks WHERE team_id='$team' AND $task_query about_chart != 0 ORDER BY `task_name` ASC"; 
$result_1 = $conn->runsql($query_1, $dbcon);
while ($result_row_1 = mysqli_fetch_object($result_1)) 
{
    $modified_date=date("F Y",strtotime($date_from));
    $daily_target_avg=0;
    echo $daily_target_query="SELECT con_fac FROM amz_daily_target WHERE task='$result_row_1->task_id' AND month_from='$modified_date'";
    $daily_target_query=$conn->runsql($daily_target_query, $dbcon);
    $num_rows=mysqli_num_rows($daily_target_query);
    echo $task_array[$result_row_1->task_id]."<br>";
    while ($result_row_daily_target = mysqli_fetch_object($daily_target_query)) 
    {
        $daily_target_avg=$daily_target_avg+$result_row_daily_target->con_fac;
    }
    $dt_avg=$daily_target_avg/$num_rows;
    $daily_target_temp.=$dt_avg.",";
    
    if($result_row_1->about_chart==1)
    {

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
//            //$data[]=  array($result_row_1->task_name,round(($tot_work_units+$tot_work_units_ot)/$secs*28800,2),100);
//            $task_list.="\"".$result_row_1->task_name."\",";
//            $actual_list_tot.=round(($tot_work_units+$tot_work_units_ot)/$secs*28800,2).",";
//            if($dt_avg!=0)
//                $actual_list.=(((round(($tot_work_units+$tot_work_units_ot)/$secs*28800,2))/$dt_avg)*100).",";
//            else
//                $actual_list.=round(($tot_work_units+$tot_work_units_ot)/$secs*28800,2).",";
//            if($dt_avg!=0)
//                $target_list.=($dt_avg/$dt_avg*100).",";
//            else
//                $target_list.='100'.",";
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
//                {
//                    //$data[]=  array($result_row_2->sub_task_name,round(($tot_work_units+$tot_work_units_ot)/$secs*28800,2),100);
//                    $task_list.=$result_row_2->task_name.",";
//                    $actual_list_tot.=round(($tot_work_units+$tot_work_units_ot)/$secs*28800,2).",";
//                    if($dt_avg!=0)
//                        $actual_list.=(((round(($tot_work_units+$tot_work_units_ot)/$secs*28800,2))/$dt_avg)*100).",";
//                    else
//                        $actual_list.=round(($tot_work_units+$tot_work_units_ot)/$secs*28800,2).",";
//                    if($dt_avg!=0)
//                        $target_list.=($dt_avg/$dt_avg*100).",";
//                    else
//                        $target_list.='100'.",";
//                }
//                    
//            }
//        }
    }
}
//print_r($data);
//$output['tasks']=$task_list;
//$output['actual']=$actual_list;
//$output['target']=$target_list;
//echo json_encode($output);Based on: team <?php echo $subtitle
$task_list=rtrim($task_list,",");
//$actual_list=rtrim($actual_list,",");
//$actual_list_tot=rtrim($actual_list_tot,",");
//$target_list=rtrim($target_list,",");
//$daily_target_temp=rtrim($daily_target_temp,",");
//
//$task_list_array=explode(",",$task_list);
//$actual_list_array=explode(",",$actual_list);
//$actual_list_tot_array=explode(",",$actual_list_tot);
//$target_list_array=explode(",",$target_list);
//$daily_target_temp_array=explode(",",$daily_target_temp);

?>

{
"chart": {
        "zoomType": "xy",
        "type": "column",
        "renderTo": "user_tasks_list"
},
"title": {
        "text": "<?php echo $title!=""?$title:$output['title']?>"
},
"subtitle": {
        "text": "Sub title goes here"
},
    "xAxis": [{
        "categories":[<?php echo $task_list?>],
        "crosshair": "true"
    }],
    "yAxis": [
    { 
        "labels": {
            "format": "{value}",
            "style": {
                "color": "Highcharts.getOptions().colors[1]"
            }
        },
        "title": {
            "text": "<?php echo $x_axis==""?'Work Units (in %)':$x_axis?>",
            "style": {
                "color": "Highcharts.getOptions().colors[1]"
            }
        },
        "max":"500"
    }, { 
        "title": {
            "text": "<?php echo $x_axis==""?'Work Units (in %)':$x_axis?>",
            "style": {
                "color": "Highcharts.getOptions().colors[0]"
            }
        },
        "labels": {
            "format": "{value}",
            "style": {
                "color": "Highcharts.getOptions().colors[0]"
            }
        },
        "opposite": "true",
        "max":"500"
        
    }],
    "tooltip": {
        "shared": "true"
    },
    "legend": {
        "layout": "vertical",
        "align": "left",
        "x": "0",
        "verticalAlign": "top",
        "y": 0,
        "floating": true
        
    },
    "series": [{
        "name": "Actual",
        "type": "column",
        "yAxis": 1,
        "data": [<?php echo $actual_list?>],
        "tooltip": {
            "valueSuffix": " %"
        }

    }, {
        "name": "Target",
        "type": "spline",
        "data": [<?php echo $target_list?>],
        "tooltip": {
            "valueSuffix": " %"
        }
    }]
}