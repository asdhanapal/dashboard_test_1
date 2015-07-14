<?php
session_start();
include_once './data_page.php';
require_once '../classes/db.class.php';
require_once '../includes/time_calc.php';
$conn = new db();
$dbcon = $conn->dbConnect();
//print_r($_POST);
$date_from=$_POST['date_from'];
$date_to=$_POST['date_to'];
$team=$_POST['team'];
if($team=="")
{
    die("<center><font color='red'>Please select the team!</font></center>");
    $tteam=$_SESSION['team_id'];
    $team=$tteam[0];
}

$task=$_POST['task'];
$s_task=$_POST['s_task'];
$task_desc=$_POST['task_desc'];
$user=$_POST['user'];

if($date_from=="" && $date_to==""){
    
}
elseif ($date_from=="" && $date_to!="") {

}
elseif ($date_from!="" && $date_to=="") {

}
else
{
    $where_1 = $where_2= " date between '$date_from' AND '$date_to' AND work_type=0 ";
    $msg = "Between $date_from and $date_to";
}

if(!empty($user))
{
    $user_text=implode(",", $user);
    $where_2=$where_1." AND user_id IN ($user_text) ";
}
?>
<script>
    $(function() {
        $("#tabs").tabs(
                { event: "click"}
                );
    });

</script>
<style>
    #tabs{
        font: 82.5% "Trebuchet MS", sans-serif;
    }
    
    
</style>
<h5><br>Report Between <?php echo $date_from;?> And <?php echo $date_to;?></h5>
<div id="tabs">
    <ul>
        <li><a href="#tabs-0">Team</a></li>
        <li><a href="#tabs-1">Task</a></li>
        <li><a href="#tabs-2">Sub task</a></li>
        <li><a href="#tabs-3">Task desc</a></li>
        <li><a href="#tabs-4">User</a></li>
    </ul>

    <div id="tabs-0">
        <table width="100%" class="tablesorter">
            <thead>
            <tr id="data_header" align="center">
                <td >S.No.</td>
                <td>Team</td>
                <td>Time&nbsp;(Normal / OT )</td>
                <td>Count&nbsp;(Normal / OT )</td>
                <td>Work units&nbsp;(Normal / OT )</td>
                <td>Per day Productivity&nbsp;(Normal / OT )</td>
                <td>Min.PDP</td>
                <td>Max.PDP</td>
            </tr>
            </thead>
            <?php
            $i = 1;
            $tot_time=$tot_time_ot="";
            $tot_count=$tot_count_ot=0;
            $secs=$secs_ot=0;
            $tot_work_units=$tot_work_units_ot=0.0;
            if (empty($team))
            {
                $query_unique_team_query = "SELECT DISTINCT team_id FROM user_tasks WHERE " . $where_2 . " ORDER BY team_id ASC";
            }
            else
            {
                $team_text=implode(",", $team);
                $query_unique_team_query = "SELECT DISTINCT team_id FROM user_tasks WHERE team_id IN ($team_text) AND " . $where_2;
            }
                //echo $query_unique_team_query;
                $result_unique_team = $conn->runsql($query_unique_team_query, $dbcon);
                while ($result_row = mysqli_fetch_object($result_unique_team)) {
                    $unique_team = $result_row->team_id;
                    $min_max=array();
                    $query_user="SELECT DISTINCT user_id FROM user_tasks WHERE team_id='$unique_team' AND " . $where_2;
                    $result_user = $conn->runsql($query_user, $dbcon);
                    while ($result_row_user = mysqli_fetch_object($result_user)) 
                    {
                        $unique_team_user = $result_row_user->user_id;
                        $query_user_2 = "SELECT time,count,wu FROM user_tasks WHERE team_id='$unique_team' AND user_id='$unique_team_user' AND " . $where_2;
                        $result_user_2 = $conn->runsql($query_user_2, $dbcon);
                        $secs_user=0;
                        $tot_work_units_user=0.0;
                        while ($result_row_user_2 = mysqli_fetch_object($result_user_2)) {
                            $secs_user+= strtotime($result_row_user_2->time)-strtotime("00:00:00");
                            $tot_work_units_user+=$result_row_user_2->wu;
                        }
                        $min_max[$unique_team_user]=round($tot_work_units_user/$secs_user*28800,2);
                    }
//                    echo "<pre>";
//                    print_r($min_max);
                    
                    $query = "SELECT time,count,wu FROM user_tasks WHERE team_id='$unique_team' AND " . $where_2;
                    $result = $conn->runsql($query, $dbcon);
                    while ($result_row = mysqli_fetch_object($result)) {
                        $tot_count+=$result_row->count;
                        $secs+= strtotime($result_row->time)-strtotime("00:00:00");
                        $tot_work_units+=$result_row->wu;
                    }
                    
                    $query_ot = "SELECT time,count,wu FROM user_tasks_ot WHERE team_id='$unique_team' AND " . $where_2." AND ot_status=1 ";
                    $result_ot = $conn->runsql($query_ot, $dbcon);
                    while ($result_row_ot = mysqli_fetch_object($result_ot)) {
                        $tot_count_ot+=$result_row_ot->count;
                        $secs_ot+= strtotime($result_row_ot->time)-strtotime("00:00:00");
                        $tot_work_units_ot+=$result_row_ot->wu;
                    }
                    
                    echo $i % 2 == 0 ? "<tr id='data_row_even' align=center>" : "<tr id='data_row_odd'  align=center>";
                    ?>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $team_array[$unique_team] ?></td>
                    <td><?php echo "<b>".sectohr($secs+$secs_ot)."</b>&nbsp;(".sectohr($secs)."&nbsp;/&nbsp;".sectohr($secs_ot).")";?></td>
                    <td><b><?php echo $tot_count+$tot_count_ot."</b>&nbsp;(".$tot_count."&nbsp;/&nbsp;".$tot_count_ot.")" ?></td>
                    <td><b><?php echo $tot_work_units+$tot_work_units_ot."</b>&nbsp;(".$tot_work_units."&nbsp;/&nbsp;".$tot_work_units_ot.")"?></td>
                    <td><?php echo "<b>".round(($tot_work_units+$tot_work_units_ot)/$secs*28800,2)."</b>&nbsp;(".round($tot_work_units/$secs*28800,2)."&nbsp;/&nbsp;".round($tot_work_units_ot/$secs*28800,2).")";?></td>
                    <td><?php echo $min=min($min_max);
                    echo "&nbsp;(". $user_array[array_search($min,$min_max)].")"?></td>
                    <td><?php echo $max=max($min_max);
                    echo "&nbsp;(". $user_array[array_search($max,$min_max)].")"?></td>
                    </tr>
                    <?php
                    
                    $tot_time=$tot_time_ot="";
                    $tot_count=$tot_count_ot=0;
                    $secs=$secs_ot=0;
                    $tot_work_units=$tot_work_units_ot=0.0; 
                }
            ?>
        </table>
    </div>
    <div id="tabs-1">
        <table width="100%" class="tablesorter">
            <thead>
            <tr id="data_header" align="center">
                <td >S.No.</td>
                <td>Team</td>
                <td>Task</td>
                <td>Time&nbsp;(Normal hrs / OT )</td>
                <td>Count&nbsp;(Normal hrs / OT )</td>
                <td>Work units&nbsp;(Normal hrs / OT )</td>
                <td>Per day productivity&nbsp;(Normal hrs / OT )</td>
                <td>Min.PDP</td>
                <td>Max.PDP</td>
            </tr></thead>
<?php
            $i = 1;
            $tot_time=$tot_time_ot="";
            $tot_count=$tot_count_ot=0;
            $secs=$secs_ot=0;
            $oa_time=$oa_time_ot="";
            $oa_count=$oa_wu=$oa_count_ot=$oa_wu_ot=0;
            $tot_work_units=$tot_work_units_ot=0.0;
            if (empty($team) && empty($task))
            {
                $query_unique_team_query = "SELECT DISTINCT tasks_id FROM user_tasks WHERE " . $where_2 . " ORDER BY tasks_id ASC";
            }
            elseif(!empty ($team) && empty ($task))
            {
                $team_text=implode(",", $team);
                $query_unique_team_query = "SELECT DISTINCT tasks_id FROM user_tasks WHERE team_id IN ($team_text) AND " . $where_2 . " ORDER BY tasks_id ASC";
            }
            elseif((!empty ($team)) && (!empty ($task)))
            {
                $task_text=implode(",", $task);
                $query_unique_team_query = "SELECT DISTINCT tasks_id FROM user_tasks WHERE tasks_id IN ($task_text) AND " . $where_2 . " ORDER BY tasks_id ASC";
            }
            //echo $query_unique_team_query;
                $result_unique_team = $conn->runsql($query_unique_team_query, $dbcon);
                while ($result_row = mysqli_fetch_object($result_unique_team)) {
                    $unique_task = $result_row->tasks_id;
                    
                    
                    $min_max=array();
                    $query_user="SELECT DISTINCT user_id FROM user_tasks WHERE tasks_id='$unique_task' AND " . $where_2;
                    $result_user = $conn->runsql($query_user, $dbcon);
                    while ($result_row_user = mysqli_fetch_object($result_user)) 
                    {
                        $unique_team_user = $result_row_user->user_id;
                        $query_user_2 = "SELECT time,count,wu FROM user_tasks WHERE tasks_id='$unique_task' AND user_id='$unique_team_user' AND " . $where_2;
                        $result_user_2 = $conn->runsql($query_user_2, $dbcon);
                        $secs_user=0;
                        $tot_work_units_user=0.0;
                        while ($result_row_user_2 = mysqli_fetch_object($result_user_2)) {
                            $secs_user+= strtotime($result_row_user_2->time)-strtotime("00:00:00");
                            $tot_work_units_user+=$result_row_user_2->wu;
                        }
                        //echo $tot_work_units_user;
                        //echo "&nbsp;".$secs_user;
                        //echo "&nbsp;". round($tot_work_units_user/$secs_user*28800,2);
                        $min_max[$unique_team_user]=round($tot_work_units_user/$secs_user*28800,2);
                    }
//                    echo "<pre>";
//                    print_r($min_max);
                    
                    
                    
                    
                    $query = "SELECT team_id,time,count,wu FROM user_tasks WHERE tasks_id='$unique_task' AND " . $where_2;
                    $result = $conn->runsql($query, $dbcon);
                    while ($result_row = mysqli_fetch_object($result)) {
                        $tot_count+=$result_row->count;
                        $secs+= strtotime($result_row->time)-strtotime("00:00:00");
                        $team_id=$result_row->team_id;
                        $tot_work_units+=$result_row->wu;
                    }
                    echo $i % 2 == 0 ? "<tr id='data_row_even' align=center>" : "<tr id='data_row_odd' align=center>";
                    ?>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $team_array[$team_id] ?></td>
                    <td><?php echo $task_array[$unique_task] ?></td>
                    <td><?php 
                        $oa_time+=$secs;
                        echo $tot_time=sectohr($secs); ?>
                    </td>
                    <td><?php 
                        $oa_count+=$tot_count;
                        echo $tot_count ?>
                    </td>
                    <td><?php 
                        $oa_wu+=$tot_work_units;
                        echo $tot_work_units?>
                    </td>
                    
                    <td><?php echo round($tot_work_units/$secs*28800,2);?></td>
                    <td><?php echo $min=min($min_max);
                    echo "&nbsp;(". $user_array[array_search($min,$min_max)].")"?></td>
                    <td><?php echo $max=max($min_max);
                    echo "&nbsp;(". $user_array[array_search($max,$min_max)].")"?></td>
                    </tr>
                    <?php
                    $tot_time="";
                    $tot_count=0;
                    $secs=0;
                    $tot_work_units=0.0;
                }
                ?>
                    <tr align=center>
                        <td colspan="3"></td>
                        <td><?php echo sectohr($oa_time)?></td>
                        <td><?php echo $oa_count?></td>
                        <td><?php echo $oa_wu?></td>
                    </tr>
        </table>
        
    </div>
    <div id="tabs-2">
        <table width="100%" class="tablesorter">
            <thead>
            <tr id="data_header" align="center">
                <td >S.No.</td>
                <td>Team</td>
                <td>Task</td>
                <td>Sub task</td>
                <td title="Daily target set by SDA">Target (Count)</td>
                <td title="Daily target*100/Daily target">Target (WU)</td>
                
                <td title="Total time spent by all users in this task">Tot.Time</td>
                <td title="Overall count in this task">Total Count</td>
                <td title="Overall workunits calcuated with overall count and daily target">Total WU</td>
                <td title="Per  day productivity">PDP (WU)</td>
                <td title="Revised target as per PDP">PDP (Count)</td>
                <td>Min.PDP (WU)</td>
                <td title="Revised target as per Min.PDP">Min.PDP (Count)</td>
                <td>Min.PDP-User</td>
                <td>Max.PDP(WU)</td>
                <td title="Revised target as per Max.PDP">Max.PDP (Count)</td>
                <td>Max.PDP-User</td>
            </tr>
</thead>
<?php
            $i = 1;
            $tot_time="";
            $tot_count=0;
            $secs=0;
            $cf=0;
            $tot_work_units=0.0;
            $oa_time="";
            $oa_count=$oa_wu=0;
            if (empty($team))
            {
                $query_unique_team_query = "SELECT DISTINCT sub_task_id FROM user_tasks WHERE " . $where_2 . " ORDER BY sub_task_id ASC";
            }
            elseif (!empty ($team) && empty ($task))
            {
                $team_text=implode(",", $team);
                $query_unique_team_query = "SELECT DISTINCT sub_task_id FROM user_tasks WHERE team_id IN ($team_text) AND " . $where_2 . " ORDER BY sub_task_id ASC";
            }
            elseif(!empty ($team) && !empty ($task) && empty ($s_task))
            {
                $task_text=implode(",", $task);
                $query_unique_team_query = "SELECT DISTINCT sub_task_id FROM user_tasks WHERE tasks_id IN ($task_text) AND " . $where_2 . " ORDER BY sub_task_id ASC";
            }
            else
            {
                $s_task_text=implode(",", $s_task);
                $query_unique_team_query = "SELECT DISTINCT sub_task_id FROM user_tasks WHERE sub_task_id IN ($s_task_text) AND " . $where_2 . " ORDER BY sub_task_id ASC";
            }
            //echo $query_unique_team_query;
                $result_unique_team = $conn->runsql($query_unique_team_query, $dbcon);
                while ($result_row = mysqli_fetch_object($result_unique_team)) {
                    if($result_row->sub_task_id!="")
                    {
                    $unique_s_task = $result_row->sub_task_id;

                    
                    
                    
                    $min_max=array();
                    $query_user="SELECT DISTINCT user_id FROM user_tasks WHERE sub_task_id='$unique_s_task' AND " . $where_2;
                    $result_user = $conn->runsql($query_user, $dbcon);
                    while ($result_row_user = mysqli_fetch_object($result_user)) 
                    {
                        $unique_team_user = $result_row_user->user_id;
                        $query_user_2 = "SELECT time,count,wu FROM user_tasks WHERE sub_task_id='$unique_s_task' AND user_id='$unique_team_user' AND " . $where_2;
                        $result_user_2 = $conn->runsql($query_user_2, $dbcon);
                        $secs_user=0;
                        $tot_work_units_user=0.0;
                        while ($result_row_user_2 = mysqli_fetch_object($result_user_2)) {
                            $secs_user+= strtotime($result_row_user_2->time)-strtotime("00:00:00");
                            $tot_work_units_user+=$result_row_user_2->wu;
                        }
                        //echo $tot_work_units_user;
                        //echo "&nbsp;".$secs_user;
                        //echo "&nbsp;". round($tot_work_units_user/$secs_user*28800,2);
                        $min_max[$unique_team_user]=round($tot_work_units_user/$secs_user*28800,2);
                    }
//                    echo "<pre>";
//                    print_r($min_max);
                    $query = "SELECT team_id,tasks_id,time,count,wu,cf FROM user_tasks WHERE sub_task_id='$unique_s_task' AND " . $where_2;
                    $result = $conn->runsql($query, $dbcon);
                    while ($result_row = mysqli_fetch_object($result)) {
                        $tot_count+=$result_row->count;
                        $secs+= strtotime($result_row->time)-strtotime("00:00:00");
                        $team_id=$result_row->team_id;
                        $tasks_id=$result_row->tasks_id;
                        $tot_work_units+=$result_row->wu;
                        $cf=$result_row->cf;
                    }
                    echo $i % 2 == 0 ? "<tr id='data_row_even' align=center>" : "<tr id='data_row_odd' align=center>";
                    ?>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $team_array[$team_id] ?></td>
                    <td><?php echo $task_array[$tasks_id] ?></td>
                    <td><?php echo $sub_task_array[$unique_s_task];?></td>
                    <td><?php echo $cf ?></td>
                    <td><?php
                        if($cf)  {
                        $wu_tmp=$cf*100/$cf;
                        echo round($wu_tmp,4); } else echo "--";
                    ?></td>
                    
                    <td><?php 
                    $oa_time+=$secs;
                    echo $tot_time=sectohr($secs); ?></td>
                    <td><?php 
                    $oa_count+=$tot_count;
                    echo $tot_count ?></td>
                    <td><?php 
                    $oa_wu+=$tot_work_units;
                    echo $tot_work_units?></td>
                    <td><?php echo $temp_wu=round($tot_work_units/$secs*28800,2);?></td>
                    <td><?php
                        echo $temp_wu/100*$cf;
                    ?></td>
                    <td><?php echo $min=min($min_max);
                    ?></td>
                    <td><?php echo $min/100*$cf?></td>
                    <td><?php echo $user_array[array_search($min,$min_max)]?></td>
                    <td><?php echo $max=max($min_max);
                    ?></td>
                    <td><?php echo $max/100*$cf?></td>
                    <td><?php echo $user_array[array_search($max,$min_max)]?></td>
                    </tr>
                    <?php
                    $tot_time="";
                    $tot_count=0;
                    $secs=0;
                    $tot_work_units=0.0;
            } }?>
                    <tr align=center>
                        <td colspan="4"></td>
                        <td><?php echo sectohr($oa_time)?></td>
                        <td><?php echo $oa_count?></td>
                        <td colspan="2"></td>
                        <td><?php echo $oa_wu?></td>
                    </tr>
        </table>
    </div>
    <div id="tabs-3">
        <table width="100%" class="tablesorter"><thead>
            <tr id="data_header" align="center">
                <td >S.No.</td>
                <td>Team</td>
                <td>Task</td>
                <td>Sub task</td>
                <td>Task desc</td>
                <td>Time</td>
                <td title="Overall count done by selected criteria">Count</td>
                <td title="Daily target set by SDA">Target</td>
                <td title="Daily target*100/Daily target">Target (In WU)</td>
                <td title="Overall workunits calcuated with overall count and daily target">Work units</td>
                <td>Per day productivity</td>
                <td>Min.PDP</td>
                <td>Max.PDP</td>

            </tr></thead>
<?php
            $i = 1;
            $tot_time="";
            $tot_count=0;
            $secs=0;
            $cf=0;
            $tot_work_units=0.0;
            $oa_time="";
            $oa_count=$oa_wu=0;

            if (empty($task_desc))
            {
            	if(empty($team) && empty($task) && empty($s_task))
            	{
            		$query_unique_task_desc_query= "SELECT DISTINCT task_desc FROM user_tasks WHERE task_desc IS NOT NULL AND " . $where_2 . " ORDER BY task_desc ASC";
            	}
               elseif(!empty($team) && empty($task))
               {
               	$team_text=implode(",", $team);
               	$query_unique_task_desc_query= "SELECT DISTINCT task_desc FROM user_tasks WHERE team_id IN ($team_text) AND task_desc IS NOT NULL AND " . $where_2 . " ORDER BY task_desc ASC";
               }
               elseif(!empty($team) && !empty($task) && empty($s_task))
               {
               	$team_text=implode(",", $team);
               	$task_text=implode(",", $task);
               	$query_unique_task_desc_query= "SELECT DISTINCT task_desc FROM user_tasks WHERE team_id IN ($team_text) AND tasks_id IN ($task_text) AND task_desc IS NOT NULL AND " . $where_2 . " ORDER BY task_desc ASC";
               }
               elseif(!empty($team) && !empty($task) && !empty($s_task))
               {
               	$query_unique_task_desc_query= "SELECT DISTINCT task_desc FROM user_tasks WHERE team_id IN ($team_text) AND tasks_id IN ($task_text) AND sub_task_id  IN ($s_task_text) AND task_desc IS NOT NULL AND " . $where_2 . " ORDER BY task_desc ASC";
               }
           	}
           	else
           	{
                    $team_text=implode(",", $team);
                    $task_text=implode(",", $task);
                    $task_desc_text=implode(",", $task_desc);
                    if(!empty($s_task))
                    {
                            $s_task_text=implode(",", $s_task);
                            $query_unique_task_desc_query = "SELECT DISTINCT task_desc FROM user_tasks WHERE team_id IN ($team_text) AND tasks_id IN ($task_text) AND sub_task_id IN($s_task_text) AND task_desc IN($task_desc_text) AND " . $where_2;
                    }
                    else
                    {
                            $query_unique_task_desc_query = "SELECT DISTINCT task_desc FROM user_tasks WHERE team_id IN ($team_text) AND tasks_id IN ($task_text) AND task_desc IN($task_desc_text) AND " . $where_2;
                    }           			
                }
	        //echo $query_unique_task_desc_query;
                $result_unique_team = $conn->runsql($query_unique_task_desc_query, $dbcon);
                while ($result_row = mysqli_fetch_object($result_unique_team)) {
                    $unique_task_desc = $result_row->task_desc;
                    
                    
                    $min_max=array();
                    $query_user="SELECT DISTINCT user_id FROM user_tasks WHERE task_desc='$unique_task_desc' AND " . $where_2;
                    $result_user = $conn->runsql($query_user, $dbcon);
                    while ($result_row_user = mysqli_fetch_object($result_user)) 
                    {
                        $unique_team_user = $result_row_user->user_id;
                        $query_user_2 = "SELECT time,count,wu FROM user_tasks WHERE task_desc='$unique_task_desc' AND user_id='$unique_team_user' AND " . $where_2;
                        $result_user_2 = $conn->runsql($query_user_2, $dbcon);
                        $secs_user=0;
                        $tot_work_units_user=0.0;
                        while ($result_row_user_2 = mysqli_fetch_object($result_user_2)) {
                            $secs_user+= strtotime($result_row_user_2->time)-strtotime("00:00:00");
                            $tot_work_units_user+=$result_row_user_2->wu;
                        }
                        //echo $tot_work_units_user;
                        //echo "&nbsp;".$secs_user;
                        //echo "&nbsp;". round($tot_work_units_user/$secs_user*28800,2);
                        $min_max[$unique_team_user]=round($tot_work_units_user/$secs_user*28800,2);
                    }
//                    echo "<pre>";
//                    print_r($min_max);

                    
                    if(empty($s_task))
                        $query = "SELECT team_id,tasks_id,sub_task_id,time,count,cf,wu FROM user_tasks WHERE task_desc='$unique_task_desc' AND " . $where_2;
                    else
                        $query = "SELECT team_id,tasks_id,sub_task_id,time,count,cf,wu FROM user_tasks WHERE sub_task_id IN ($s_task_text) AND task_desc='$unique_task_desc' AND " . $where_2;
                    $result = $conn->runsql($query, $dbcon);
                    while ($result_row = mysqli_fetch_object($result)) {
                        $tot_count+=$result_row->count;
                        $secs+= strtotime($result_row->time)-strtotime("00:00:00");
                        $team_id=$result_row->team_id;
                        $tasks_id=$result_row->tasks_id;
                        $sub_task_id=$result_row->sub_task_id;
                        $cf=$result_row->cf;
                        $tot_work_units+=$result_row->wu;
                    }	
                    echo $i % 2 == 0 ? "<tr id='data_row_even' align=center>" : "<tr id='data_row_odd' align=center>";
                    ?>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $team_array[$team_id] ?></td>
                    <td><?php echo $task_array[$tasks_id] ?></td>
                    <td><?php echo $sub_task_array[$sub_task_id]; ?></td>
                    <td><?php echo $task_desc_array[$unique_task_desc]; ?></td>
                    <td><?php 
                    $oa_time+=$secs;
                    echo $tot_time=sectohr($secs); ?></td>
                    <td><?php 
                    $oa_count+=$tot_count;
                    echo $tot_count ?></td>
                    <td><?php echo $cf ?></td>
                    <td><?php
                        if($cf)  {
                        $wu_tmp=$cf*100/$cf;
                        echo round($wu_tmp,4); } else echo "--";
                    ?></td>
                    <td><?php 
                    $oa_wu+=$tot_work_units;
                    echo $tot_work_units?></td>
                    <td><?php echo round($tot_work_units/$secs*28800,2);?></td>
                    <td><?php echo $min=min($min_max);
                    echo "&nbsp;(". $user_array[array_search($min,$min_max)].")"?></td>
                    <td><?php echo $max=max($min_max);
                    echo "&nbsp;(". $user_array[array_search($max,$min_max)].")"?></td>
                    </tr>
                    <?php
                    $tot_time="";
                    $tot_count=0;
                    $secs=0;
                    $tot_work_units=0.0;
                }
                ?>
                   <tr align=center>
                        <td colspan="5"></td>
                        <td><?php echo sectohr($oa_time)?></td>
                        <td><?php echo $oa_count?></td>
                        <td colspan="2"></td>
                        <td><?php echo $oa_wu?></td>
                    </tr>
        </table>
    </div>
    <div id="tabs-4">
        <table width="100%" class="tablesorter">
        <?php
            $i = 1;
            $tot_time=$tot_time_ot="";
            $tot_count=$tot_count_ot=0;
            $secs=$secs_ot=0;
            $cf=$cf_ot=0;
            $tot_work_units=$tot_work_units_ot=0.0;
            
            if(!empty($team) && !empty($task) && !empty($s_task) && !empty($task_desc))
            {
            ?>
                <thead>
                    <tr id="data_header" align="center" class="tablesorter">
                        <td >S.No.</td>
                        <td>User</td>
                        <td>Team</td>
                        <td>Task</td>
                        <td>Sub task</td>
                        <td>Task desc</td>
                        <td>Time (Normal / OT)</td>
                        <td>Count (Normal / OT)</td>
                        <td>Work units (Normal / OT)</td>
                        <td>Per day productivity (Normal / OT)</td>
                 </tr>
                 </thead>
                <?php
                $team_text=implode(",", $team);
           	$task_text=implode(",", $task);
                $s_task_text=implode(",", $s_task);
           	$task_desc_text=implode(",", $task_desc);
                if(empty($user))
                {
                    $query = "SELECT DISTINCT user_id FROM user_tasks WHERE team_id IN ($team_text) AND tasks_id IN ($task_text) AND sub_task_id IN ($s_task_text) AND task_desc IN ($task_desc_text) AND " . $where_2 . " ORDER BY user_id ASC";
                }
                else
                {
                    $user_text=implode(",", $user);
                    $query = "SELECT DISTINCT user_id FROM user_tasks WHERE team_id IN ($team_text) AND tasks_id IN ($task_text) AND sub_task_id IN ($s_task_text) AND task_desc IN ($task_desc_text) AND user_id IN ($user_text) AND " . $where_2 . " ORDER BY user_id ASC";
                }
                $result_unique_team = $conn->runsql($query, $dbcon);
                while ($result_row1 = mysqli_fetch_object($result_unique_team)) {
                    $unique_user = $result_row1->user_id;
                    $query = "SELECT time,count,cf,wu FROM user_tasks WHERE team_id IN ($team_text) AND tasks_id IN ($task_text) AND sub_task_id IN ($s_task_text) AND task_desc IN ($task_desc_text) AND user_id='$unique_user' AND " . $where_2;
                    $result = $conn->runsql($query, $dbcon);
                    while ($result_row = mysqli_fetch_object($result)) {
                        $tot_count+=$result_row->count;
                        $secs+= strtotime($result_row->time)-strtotime("00:00:00");
                        $tot_work_units+=$result_row->wu;
                    }

                    $query_ot = "SELECT time,count,cf,wu FROM user_tasks_ot WHERE team_id IN ($team_text) AND tasks_id IN ($task_text) AND sub_task_id IN ($s_task_text) AND task_desc IN ($task_desc_text) AND user_id='$unique_user' AND " . $where_2 ." AND ot_status='1'";
                    $result_ot = $conn->runsql($query_ot, $dbcon);
                    while ($result_row_ot = mysqli_fetch_object($result_ot)) {
                        $tot_count_ot+=$result_row_ot->count;
                        $secs_ot+= strtotime($result_row_ot->time)-strtotime("00:00:00");
                        $tot_work_units_ot+=$result_row_ot->wu;
                    }

                    echo $i % 2 == 0 ? "<tr id='data_row_even' align=center>" : "<tr id='data_row_odd' align=center>";
                    ?>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $user_array[$unique_user]?></td>
                    <td><?php echo $team_array[$team]?></td>
                    <td><?php echo $task_array[$task]?></td>
                    <td><?php echo $sub_task_array[$s_task]?></td>
                    <td><?php echo $task_desc_array[$task_desc]?></td>

                    <td><?php echo sectohr($secs+$secs_ot)."&nbsp;(".sectohr($secs)."&nbsp;/&nbsp;".sectohr($secs_ot).")";?></td>
                    <td><?php echo $tot_count+$tot_count_ot."&nbsp;(".$tot_count."&nbsp;/&nbsp;".$tot_count_ot.")" ?></td>
                    <td><?php echo $tot_work_units+$tot_work_units_ot."&nbsp;(".$tot_work_units."&nbsp;/&nbsp;".$tot_work_units_ot.")"?></td>
                    <td><?php echo round(($tot_work_units+$tot_work_units_ot)/$secs*28800,2)."&nbsp;(".round($tot_work_units/$secs*28800,2)."&nbsp;/&nbsp;".round($tot_work_units_ot/$secs*28800,2).")";?></td>
                    </tr>
                    <?php
                    $tot_time=$tot_time_ot="";
                    $tot_count=$tot_count_ot=0;
                    $secs=$secs_ot=0;
                    $tot_work_units=$tot_work_units_ot=0.0;
                }
            }
            elseif(!empty ($team) && !empty ($task) && !empty ($s_task))
            {
                ?>
                <thead>
                    <tr id="data_header" align="center" >
                       <td >S.No.</td>
                       <td>User</td>
                       <td>Team</td>
                       <td>Task</td>
                       <td>Sub task</td>
                       <td>Time (Normal / OT)</td>
                       <td>Count (Normal / OT)</td>
                       <td>Work units (Normal / OT)</td>
                       <td>Per day productivity (Normal / OT)</td>
                   </tr>
                </thead>
                <?php
                $team_text=implode(",", $team);
           	$task_text=implode(",", $task);
                $s_task_text=implode(",", $s_task);
                if(empty($user))
                {
                    $query = "SELECT DISTINCT user_id FROM user_tasks WHERE team_id IN ($team_text) AND tasks_id IN ($task_text) AND sub_task_id IN ($s_task_text) AND " . $where_1 . " ORDER BY user_id ASC";
                }
                else
                {
                    $user_text=implode(",", $user);
                    $query = "SELECT DISTINCT user_id FROM user_tasks WHERE team_id IN ($team_text) AND tasks_id IN ($task_text) AND sub_task_id IN ($s_task_text) AND user_id IN ($user_text) AND " . $where_1 . " ORDER BY user_id ASC";
                }
                    $result_unique_team = $conn->runsql($query, $dbcon);
                    while ($result_row1 = mysqli_fetch_object($result_unique_team)) {
                        $unique_user = $result_row1->user_id;
                        $query = "SELECT time,count,cf,wu FROM user_tasks WHERE team_id IN ($team_text) AND tasks_id IN ($task_text) AND sub_task_id IN ($s_task_text) AND user_id='$unique_user' AND " . $where_1;
                        $result = $conn->runsql($query, $dbcon);
                        while ($result_row = mysqli_fetch_object($result)) {
                            $tot_count+=$result_row->count;
                            $secs+= strtotime($result_row->time)-strtotime("00:00:00");
                            $tot_work_units+=$result_row->wu;
                        }
                        
                        $query_ot = "SELECT time,count,cf,wu FROM user_tasks_ot WHERE team_id IN ($team_text) AND tasks_id IN ($task_text) AND sub_task_id IN ($s_task_text) AND user_id='$unique_user' AND " . $where_1. " AND ot_status='1'";
                        $result_ot = $conn->runsql($query_ot, $dbcon);
                        while ($result_row_ot = mysqli_fetch_object($result_ot)) {
                            $tot_count_ot+=$result_row_ot->count;
                            $secs_ot+= strtotime($result_row_ot->time)-strtotime("00:00:00");
                            $tot_work_units_ot+=$result_row_ot->wu;
                        }
                        
                        echo $i % 2 == 0 ? "<tr id='data_row_even' align=center>" : "<tr id='data_row_odd' align=center>";
                        ?>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $user_array[$unique_user]?></td>
                        <td><?php //echo $team_array[$team]
                        for($kk=0;$kk<sizeof($team);$kk++)
                            echo $team_array[$team[$kk]].",&nbsp;";
                        ?></td>
                        <td><?php //echo $task_array[$task]
                          for($kk=0;$kk<sizeof($task);$kk++)
                            echo $task_array[$task[$kk]].",&nbsp;";
                       ?></td>
                        <td><?php //echo $sub_task_array[$s_task]
                            for($kk=0;$kk<sizeof($s_task);$kk++)
                            echo $sub_task_array[$s_task[$kk]].",&nbsp;";

                        ?></td>
                        <td><?php echo sectohr($secs+$secs_ot)."&nbsp;(".sectohr($secs)."&nbsp;/&nbsp;".sectohr($secs_ot).")";?></td>
                        <td><?php echo $tot_count+$tot_count_ot."&nbsp;(".$tot_count."&nbsp;/&nbsp;".$tot_count_ot.")" ?></td>
                        <td><?php echo $tot_work_units+$tot_work_units_ot."&nbsp;(".$tot_work_units."&nbsp;/&nbsp;".$tot_work_units_ot.")"?></td>
                        <td><?php echo round(($tot_work_units+$tot_work_units_ot)/$secs*28800,2)."&nbsp;(".round($tot_work_units/$secs*28800,2)."&nbsp;/&nbsp;".round($tot_work_units_ot/$secs*28800,2).")";?></td>

                        </tr>
                        <?php
                        $tot_time=$tot_time_ot="";
                        $tot_count=$tot_count_ot=0;
                        $secs=$secs_ot=0;
                        $cf=$cf_ot=0;
                        $tot_work_units=$tot_work_units_ot=0.0;
                    }
            }
            elseif(!empty ($team) && !empty ($task))
            {
                ?>
                <thead>
                    <tr id="data_header" align="center">
                       <td >S.No.</td>
                       <td>User Name</td>
                       <td>Team</td>
                       <td>Task</td>
                       <td>Time (Normal / OT)</td>
                       <td>Count (Normal / OT)</td>
                       <td>Work units (Normal / OT)</td>
                       <td>Per day productivity (Normal / OT)</td>
                   </tr>
                </thead>
                <?php
                $team_text=implode(",", $team);
                $task_text=implode(",", $task);
                if(empty($user))
                {
                    $query = "SELECT DISTINCT user_id FROM user_tasks WHERE team_id IN ($team_text) AND tasks_id IN ($task_text) AND " . $where_1 . " ORDER BY user_id ASC";
                }
                else
                {
                    $user_text=implode(",", $user);
                    $query = "SELECT DISTINCT user_id FROM user_tasks WHERE team_id IN ($team_text) AND tasks_id IN ($task_text) AND " . $where_1 . " ORDER BY user_id ASC";
                }
                //echo $query;
                    $result_unique_team = $conn->runsql($query, $dbcon);
                    while ($result_row1 = mysqli_fetch_object($result_unique_team)) {
                        $unique_user = $result_row1->user_id;
                        $query = "SELECT time,count,cf,wu FROM user_tasks WHERE team_id IN ($team_text) AND tasks_id IN ($task_text) AND user_id='$unique_user' AND " . $where_1;
                        $result = $conn->runsql($query, $dbcon);
                        while ($result_row = mysqli_fetch_object($result)) {
                            $tot_count+=$result_row->count;
                            $secs+= strtotime($result_row->time)-strtotime("00:00:00");
                            $tot_work_units+=$result_row->wu;
                        }
                        
                        $query_ot = "SELECT time,count,cf,wu FROM user_tasks_ot WHERE team_id IN ($team_text) AND tasks_id IN ($task_text) AND user_id='$unique_user' AND " . $where_1." AND ot_status='1'";
                        $result_ot = $conn->runsql($query_ot, $dbcon);
                        while ($result_row_ot = mysqli_fetch_object($result_ot)) {
                            $tot_count_ot+=$result_row_ot->count;
                            $secs_ot+= strtotime($result_row_ot->time)-strtotime("00:00:00");
                            $tot_work_units_ot+=$result_row_ot->wu;
                        }
                        
                        echo $i % 2 == 0 ? "<tr id='data_row_even' align=center>" : "<tr id='data_row_odd' align=center>";
                        ?>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $user_array[$unique_user]?></td>
                        <td><?php //echo $team_array[$team]
                              for($kk=0;$kk<sizeof($team);$kk++)
                            echo $team_array[$team[$kk]].",&nbsp;";
                  
                        ?></td>
                        <td><?php //echo $task_array[$task]
                              for($kk=0;$kk<sizeof($task);$kk++)
                            echo $task_array[$task[$kk]].",&nbsp;";
                  
                        ?></td>
                        <td><?php echo sectohr($secs+$secs_ot)."&nbsp;(".sectohr($secs)."&nbsp;/&nbsp;".sectohr($secs_ot).")";?></td>
                        <td><?php echo $tot_count+$tot_count_ot."&nbsp;(".$tot_count."&nbsp;/&nbsp;".$tot_count_ot.")" ?></td>
                        <td><?php echo $tot_work_units+$tot_work_units_ot."&nbsp;(".$tot_work_units."&nbsp;/&nbsp;".$tot_work_units_ot.")"?></td>
                        <td><?php echo round(($tot_work_units+$tot_work_units_ot)/$secs*28800,2)."&nbsp;(".round($tot_work_units/$secs*28800,2)."&nbsp;/&nbsp;".round($tot_work_units_ot/$secs*28800,2).")";?></td>

                        </tr>
                        <?php
                        $tot_time=$tot_time_ot="";
                        $tot_count=$tot_count_ot=0;
                        $secs=$secs_ot=0;
                        $tot_work_units=$tot_work_units_ot=0.0;
                    }
                
            }
            elseif(!empty($team))
            {
                ?>
                <thead>
                    <tr id="data_header" align="center">
                    <td >S.No.</td>
                    <td>User Name</td>
                    <td>Team</td>
                    <td>Time (Normal / OT)</td>
                    <td>Count (Normal / OT)</td>
                    <td>Work units (Normal / OT)</td>
                    <td>Per day productivity (Normal / OT)</td>
                    </tr>
                </thead>
                <?php
                $team_text=implode(",", $team);
                if(empty($user))
                {
                    $query_unique_team_query = "SELECT DISTINCT user_id FROM user_tasks WHERE team_id In ($team_text) AND " . $where_1 . " ORDER BY team_id ASC";
                }
                else
                {
                    $user_text=implode(",", $user);
                    $query_unique_team_query = "SELECT DISTINCT user_id FROM user_tasks WHERE team_id In ($team_text) AND user_id IN ($user_text) AND " . $where_1 . " ORDER BY team_id ASC";
                }
                //echo $query_unique_team_query;
                    $result_unique_team = $conn->runsql($query_unique_team_query, $dbcon);
                    while ($result_row = mysqli_fetch_object($result_unique_team)) {
                        $unique_team = $result_row->user_id;
                        $query = "SELECT time,count,cf,wu FROM user_tasks WHERE user_id='$unique_team' AND team_id IN ($team_text) AND " . $where_1;
                        $result = $conn->runsql($query, $dbcon);
                        while ($result_row = mysqli_fetch_object($result)) {
                            $tot_count+=$result_row->count;
                            $secs+= strtotime($result_row->time)-strtotime("00:00:00");
                            $tot_work_units+=$result_row->wu;
                        }
                        
                        $query_ot = "SELECT time,count,cf,wu FROM user_tasks_ot WHERE user_id='$unique_team' AND team_id  In ($team_text) AND " . $where_1. " AND ot_status='1'";
                        $result_ot = $conn->runsql($query_ot, $dbcon);
                        while ($result_row_ot = mysqli_fetch_object($result_ot)) {
                            $tot_count_ot+=$result_row_ot->count;
                            $secs_ot+= strtotime($result_row_ot->time)-strtotime("00:00:00");
                            $tot_work_units_ot+=$result_row_ot->wu;
                        }
                        
                        echo $i % 2 == 0 ? "<tr id='data_row_even' align=center>" : "<tr id='data_row_odd'  align=center>";
                        ?>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $user_array[$unique_team] ?></td>
                        <td><?php 
                                //echo $team_array[$team_text]
                        for($kk=0;$kk<sizeof($team);$kk++)
                            echo $team_array[$team[$kk]].",&nbsp;";
                        ?></td>
                        <td><?php echo sectohr($secs+$secs_ot)."&nbsp;(".sectohr($secs)."&nbsp;/&nbsp;".sectohr($secs_ot).")";?></td>
                        <td><?php echo $tot_count+$tot_count_ot."&nbsp;(".$tot_count."&nbsp;/&nbsp;".$tot_count_ot.")" ?></td>
                        <td><?php echo $tot_work_units+$tot_work_units_ot."&nbsp;(".$tot_work_units."&nbsp;/&nbsp;".$tot_work_units_ot.")"?></td>
                        <td><?php echo round(($tot_work_units+$tot_work_units_ot)/$secs*28800,2)."&nbsp;(".round($tot_work_units/$secs*28800,2)."&nbsp;/&nbsp;".round($tot_work_units_ot/$secs*28800,2).")";?></td>

                        </tr>
                        <?php
                        $tot_time=$tot_time_ot="";
                        $tot_count=$tot_count_ot=0;
                        $secs=$secs_ot=0;
                        $tot_work_units=$tot_work_units_ot=0.0;
                    }
            }
            else 
            {
                ?>
                <thead>
                    <tr id="data_header" align="center">
                        <td >S.No.</td>
                        <td>User Name</td>
                        <td>Time (Normal / OT)</td>
                        <td>Count (Normal / OT)</td>
                        <td>Work units (Normal / OT)</td>
                        <td>Per day productivity (Normal / OT)</td>
                    </tr>
                </thead>
                <?php
                if(empty($user))
                {
                    $query_unique_user_query= "SELECT DISTINCT user_id FROM user_tasks WHERE " . $where_1 . " ORDER BY user_id ASC";
                    $result_unique_user = $conn->runsql($query_unique_user_query, $dbcon);
                    while ($result_row = mysqli_fetch_object($result_unique_user)) {
                        $unique_user = $result_row->user_id;
                        $query = "SELECT team_id,tasks_id,sub_task_id,task_desc,time,count,wu,cf FROM user_tasks WHERE user_id='$unique_user' AND " . $where_1;
                        $result = $conn->runsql($query, $dbcon);
                        while ($result_row = mysqli_fetch_object($result)) {
                            $tot_count+=$result_row->count;
                            $secs+= strtotime($result_row->time)-strtotime("00:00:00");
                            $tot_work_units+=$result_row->wu;
                        }
                        
                        $query_ot = "SELECT team_id,tasks_id,sub_task_id,task_desc,time,count,wu,cf FROM user_tasks_ot WHERE user_id='$unique_user' AND " . $where_1. " AND  ot_status='1'";
                        $result_ot = $conn->runsql($query_ot, $dbcon);
                        while ($result_row_ot = mysqli_fetch_object($result_ot)) {
                            $tot_count_ot+=$result_row_ot->count;
                            $secs_ot+= strtotime($result_row_ot->time)-strtotime("00:00:00");
                            $tot_work_units_ot+=$result_row_ot->wu;
                        }
                        echo $i % 2 == 0 ? "<tr id='data_row_even' align=center>" : "<tr id='data_row_odd' align=center>";
                        ?>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $user_array[$unique_user] ?></td>
                        <td><?php echo "<b>".sectohr($secs+$secs_ot)."</b>&nbsp;(".sectohr($secs)."&nbsp;/&nbsp;".sectohr($secs_ot).")";?></td>
                        <td><b><?php echo $tot_count+$tot_count_ot."</b>&nbsp;(".$tot_count."&nbsp;/&nbsp;".$tot_count_ot.")" ?></td>
                        <td><b><?php echo $tot_work_units+$tot_work_units_ot."&nbsp;</b>(".$tot_work_units."&nbsp;/&nbsp;".$tot_work_units_ot.")"?></td>
                        <td><?php echo "<b>".round(($tot_work_units+$tot_work_units_ot)/$secs*28800,2)."</b>&nbsp;(".round($tot_work_units/$secs*28800,2)."&nbsp;/&nbsp;".round($tot_work_units_ot/$secs*28800,2).")";?></td>
                        </tr>
                       <?php
                        $tot_time=$tot_time_ot="";
                        $tot_count=$tot_count_ot=0;
                        $secs=$secs_ot=0;
                        $tot_work_units=$tot_work_units_ot=0.0;
                    }
                }
                else
                {
                	$user_text=implode(",", $user);
                        $query = "SELECT team_id,tasks_id,sub_task_id,task_desc,time,count,cf,wu FROM user_tasks WHERE user_id IN ($user_text) AND " . $where_1;
                        $result = $conn->runsql($query, $dbcon);
                        while ($result_row = mysqli_fetch_object($result)) {
                            $tot_count+=$result_row->count;
                            $secs+= strtotime($result_row->time)-strtotime("00:00:00");
                            $tot_work_units+=$result_row->wu;
                        }
                        
                        $query_ot = "SELECT team_id,tasks_id,sub_task_id,task_desc,time,count,cf,wu FROM user_tasks_ot WHERE user_id='$user' AND " . $where_1." AND ot_status='1'";
                        $result_ot = $conn->runsql($query_ot, $dbcon);
                        while ($result_row_ot = mysqli_fetch_object($result_ot)) {
                            $tot_count_ot+=$result_row_ot->count;
                            $secs_ot+= strtotime($result_row_ot->time)-strtotime("00:00:00");
                            $tot_work_units_ot+=$result_row_ot->wu;
                        }
        
                        echo $i % 2 == 0 ? "<tr id='data_row_even' align=center>" : "<tr id='data_row_odd' align=center>";
                        ?>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $user_array[$user] ?></td>
                        
                        <td><b><?php echo sectohr($secs+$secs_ot)."</b>&nbsp;(".sectohr($secs)."&nbsp;/&nbsp;".sectohr($secs_ot).")";?></td>
                        <td><b><?php echo $tot_count+$tot_count_ot."</b>&nbsp;(".$tot_count."&nbsp;/&nbsp;".$tot_count_ot.")" ?></td>
                        <td><b><?php echo $tot_work_units+$tot_work_units_ot."</b>&nbsp;(".$tot_work_units."&nbsp;/&nbsp;".$tot_work_units_ot.")"?></td>
                        <td><b><?php echo round(($tot_work_units+$tot_work_units_ot)/$secs*28800,2)."</b>&nbsp;(".round($tot_work_units/$secs*28800,2)."&nbsp;/&nbsp;".round($tot_work_units_ot/$secs*28800,2).")";?></td>
                        
                        </tr>
                <?php
                }
            }
        ?>
</table>
</div>
</div>

<link href="../css/theme.default.css" rel="stylesheet">
<script src="../js/jquery.tablesorter.min.js"></script>
<script>
$(function(){
    $('table').tablesorter({
        widgets        : ['zebra', 'columns'],
        usNumberFormat : false,
        sortReset      : true,
        sortRestart    : true
    });
});
</script>