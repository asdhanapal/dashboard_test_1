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

if($date_from=="" && $date_to=="")
{
    
}
elseif ($date_from=="" && $date_to!="") {

}
elseif ($date_from!="" && $date_to=="") {

}
else //currently this option only always working
{
    $where_1 = " date between '$date_from' AND '$date_to' ";
    $msg = "Between $date_from and $date_to";
}
?>
<script>
    $(function() {
        $("#tabs").tabs(
                { event: "mouseover"}
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
        <table width="100%">
            <tr id="data_header" align="center">
                <td >S.No.</td>
                <td>Team</td>
                <td>Time</td>
                <td>Count</td>
                <td>Work units</td>
            </tr>
            <?php
            $i = 1;
            $tot_time="";
            $tot_count=0;
            $secs=0;
            $tot_work_units=0.0;
            if ($team == "") {
                $query_unique_team_query = "SELECT DISTINCT team_id FROM user_tasks_ot WHERE " . $where_1 . " ORDER BY team_id ASC";
                $result_unique_team = $conn->runsql($query_unique_team_query, $dbcon);
                while ($result_row = mysqli_fetch_object($result_unique_team)) {
                    $unique_team = $result_row->team_id;
                    $query = "SELECT time,count,wu FROM user_tasks_ot WHERE team_id='$unique_team' AND " . $where_1;
                    $result = $conn->runsql($query, $dbcon);
                    while ($result_row = mysqli_fetch_object($result)) {
                        $tot_count+=$result_row->count;
                        $secs+= strtotime($result_row->time)-strtotime("00:00:00");
                        $tot_work_units+=$result_row->wu;
                    }
                    echo $i % 2 == 0 ? "<tr id='data_row_even' align=center>" : "<tr id='data_row_odd'  align=center>";
                    ?>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $team_array[$unique_team] ?></td>
                    <td><?php echo $tot_time=sectohr($secs); ?></td>
                    <td><?php echo $tot_count ?></td>
                    <td><?php echo $tot_work_units?></td>
                    </tr>
                    <?php
                    $tot_time="";
                    $tot_count=0;
                    $secs=0;
                    $tot_work_units=0.0;
                }
            } else {
                $query = "SELECT time,count,wu FROM user_tasks_ot WHERE team_id='$team' AND " . $where_1;
                    $result = $conn->runsql($query, $dbcon);
                    while ($result_row = mysqli_fetch_object($result)) {
                        $tot_count+=$result_row->count;
                        $secs+= strtotime($result_row->time)-strtotime("00:00:00");
                        $tot_work_units+=$result_row->wu;
                    }
                    echo $i % 2 == 0 ? "<tr id='data_row_even'  align=center>" : "<tr id='data_row_odd'  align=center>";
                    ?>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $team_array[$team] ?></td>
                    <td><?php echo $tot_time=sectohr($secs); ?></td>
                    <td><?php echo $tot_count ?></td>
                    <td><?php echo $tot_work_units?></td>
                    </tr>
            <?php }
            ?>
        </table>
    </div>
    <div id="tabs-1">
        <table width="100%">
            <tr id="data_header" align="center">
                <td >S.No.</td>
                <td>Team</td>
                <td>Task</td>
                <td>Time</td>
                <td>Count</td>
                <td>Work units</td>
            </tr>
<?php
            $i = 1;
            $tot_time="";
            $tot_count=0;
            $secs=0;
            $oa_time="";
            $oa_count=$oa_wu=0;
            $tot_work_units=0.0;
            if ($task== "") {
                if($team=="" && $task=="")
                    $query_unique_team_query = "SELECT DISTINCT tasks_id FROM user_tasks_ot WHERE " . $where_1 . " ORDER BY tasks_id ASC";
                elseif($team!="" && $task=="")
                    $query_unique_team_query = "SELECT DISTINCT tasks_id FROM user_tasks_ot WHERE team_id='$team' AND " . $where_1 . " ORDER BY tasks_id ASC";
                
                $result_unique_team = $conn->runsql($query_unique_team_query, $dbcon);
                while ($result_row = mysqli_fetch_object($result_unique_team)) {
                    $unique_task = $result_row->tasks_id;
                    $query = "SELECT team_id,time,count,wu FROM user_tasks_ot WHERE tasks_id='$unique_task' AND " . $where_1;
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
            <?php
            } else {
                
                $query = "SELECT team_id,time,count,wu FROM user_tasks_ot WHERE tasks_id='$task' AND " . $where_1;
                    $result = $conn->runsql($query, $dbcon);
                    if (mysqli_num_rows($result)) {
                    while ($result_row = mysqli_fetch_object($result)) {
                        $tot_count+=$result_row->count;
                        $secs+= strtotime($result_row->time)-strtotime("00:00:00");
                        $team_id=$result_row->team_id;
                        $tot_work_units+=$result_row->wu;
                    }
                    echo $i % 2 == 0 ? "<tr id='data_row_even' align=center>" : "<tr id='data_row_odd'  align=center>";
                    ?>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $team_array[$team_id]?></td>
                    <td><?php echo $task_array[$task] ?></td>
                    <td><?php echo $tot_time=sectohr($secs); ?></td>
                    <td><?php echo $tot_count ?></td>
                    <td><?php echo $tot_work_units?></td>
                    </tr>
            <?php } else echo "<tr><td colspan=6 align=center>No entries available!</td></tr>";
            }
            ?>
        </table>
        
    </div>
    <div id="tabs-2">
        <table width="100%">
            <tr id="data_header" align="center">
                <td >S.No.</td>
                <td>Team</td>
                <td>Task</td>
                <td>Sub task</td>
                <td>Time</td>
                <td>Count</td>
                <td>Con. Fac</td>
                <td>Work units</td>
            </tr>

<?php
            $i = 1;
            $tot_time="";
            $tot_count=0;
            $secs=0;
            $cf=0;
            $tot_work_units=0.0;
            $oa_time="";
            $oa_count=$oa_wu=0;
            if ($s_task== "") {
                if($team=="" && $task=="")
                    $query_unique_team_query = "SELECT DISTINCT sub_task_id FROM user_tasks_ot WHERE " . $where_1 . " ORDER BY sub_task_id ASC";
                elseif($team!="" && $task=="")
                    $query_unique_team_query = "SELECT DISTINCT sub_task_id FROM user_tasks_ot WHERE team_id='$team' AND " . $where_1 . " ORDER BY sub_task_id ASC";
                elseif($team!="" && $task!="")
                    $query_unique_team_query = "SELECT DISTINCT sub_task_id FROM user_tasks_ot WHERE team_id='$team' AND tasks_id='$task' AND " . $where_1 . " ORDER BY sub_task_id ASC";
                
                $result_unique_team = $conn->runsql($query_unique_team_query, $dbcon);
                while ($result_row = mysqli_fetch_object($result_unique_team)) {
                    $unique_s_task = $result_row->sub_task_id;
                    $query = "SELECT team_id,tasks_id,time,count,wu,cf FROM user_tasks_ot WHERE sub_task_id='$unique_s_task' AND " . $where_1;
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
                    <td><?php echo $sub_task_array[$unique_s_task]; ?></td>
                    <td><?php 
                    $oa_time+=$secs;
                    echo $tot_time=sectohr($secs); ?></td>
                    <td><?php 
                    $oa_count+=$tot_count;
                    echo $tot_count ?></td>
                    <td><?php echo $cf ?></td>
                    <td><?php 
                    $oa_wu+=$tot_work_units;
                    echo $tot_work_units?></td>
                    </tr>
                    <?php
                    $tot_time="";
                    $tot_count=0;
                    $secs=0;
                    $tot_work_units=0.0;
                }?>
                                    <tr align=center>
                        <td colspan="4"></td>
                        <td><?php echo sectohr($oa_time)?></td>
                        <td><?php echo $oa_count?></td>
                        <td></td>
                        <td><?php echo $oa_wu?></td>
                    </tr>
<?php
            } else {
                
                $query = "SELECT team_id,tasks_id,time,count,cf,wu FROM user_tasks_ot WHERE sub_task_id='$s_task' AND " . $where_1;
                    $result = $conn->runsql($query, $dbcon);
                    if (mysqli_num_rows($result)) {
                    while ($result_row = mysqli_fetch_object($result)) {
                        $tot_count+=$result_row->count;
                        $secs+= strtotime($result_row->time)-strtotime("00:00:00");
                        $team_id=$result_row->team_id;
                        $tasks_id=$result_row->tasks_id;
                        $cf=$result_row->cf;
                        $tot_work_units+=$result_row->wu;
                        
                    }
                    echo $i % 2 == 0 ? "<tr id='data_row_even'>" : "<tr id='data_row_odd'>";
                    ?>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $team_array[$team_id]?></td>
                    <td><?php echo $task_array[$tasks_id] ?></td>
                    <td><?php echo $sub_task_array[$s_task] ?></td>
                    <td><?php echo $tot_time=sectohr($secs); ?></td>
                    <td><?php echo $tot_count ?></td>
                    <td><?php echo $cf ?></td>
                    <td><?php echo $tot_work_units?></td>
                    </tr>
            <?php } else echo "<tr><td colspan=6 align=center>No entries available!</td></tr>";
            }
            ?>
        </table>
    </div>
    <div id="tabs-3">
        <table width="100%">
            <tr id="data_header" align="center">
                <td >S.No.</td>
                <td>Team</td>
                <td>Task</td>
                <td>Sub task</td>
                <td>Task desc</td>
                <td>Time</td>
                <td>Count</td>
                <td>Con. Fac</td>
                <td>Work units</td>
            </tr>
<?php
            $i = 1;
            $tot_time="";
            $tot_count=0;
            $secs=0;
            $cf=0;
            $tot_work_units=0.0;
                        $oa_time="";
            $oa_count=$oa_wu=0;

            if ($task_desc== "") {
                if($team=="" && $task=="" && $s_task=="")
                    $query_unique_task_desc_query= "SELECT DISTINCT task_desc FROM user_tasks_ot WHERE task_desc IS NOT NULL AND " . $where_1 . " ORDER BY task_desc ASC";
                elseif($team!="" && $task=="" )
                    $query_unique_task_desc_query= "SELECT DISTINCT task_desc FROM user_tasks_ot WHERE team_id='$team' AND task_desc IS NOT NULL AND " . $where_1 . " ORDER BY task_desc ASC";
                elseif($team!="" && $task!="" && $s_task=="")
                    $query_unique_task_desc_query= "SELECT DISTINCT task_desc FROM user_tasks_ot WHERE team_id='$team' AND tasks_id='$task' AND task_desc IS NOT NULL AND " . $where_1 . " ORDER BY task_desc ASC";
                elseif($team!="" && $task!="" && $s_task!="")
                    $query_unique_task_desc_query= "SELECT DISTINCT task_desc FROM user_tasks_ot WHERE team_id='$team' AND tasks_id='$task' AND sub_task_id='$s_task' AND task_desc IS NOT NULL AND " . $where_1 . " ORDER BY task_desc ASC";
              //  echo $query_unique_task_desc_query;
                $result_unique_team = $conn->runsql($query_unique_task_desc_query, $dbcon);
                while ($result_row = mysqli_fetch_object($result_unique_team)) {
                    $unique_task_desc = $result_row->task_desc;
                    if($s_task=="")
                        $query = "SELECT team_id,tasks_id,sub_task_id,time,count,cf,wu FROM user_tasks_ot WHERE task_desc='$unique_task_desc' AND " . $where_1;
                    else
                        $query = "SELECT team_id,tasks_id,sub_task_id,time,count,cf,wu FROM user_tasks_ot WHERE sub_task_id='$s_task' AND task_desc='$unique_task_desc' AND " . $where_1;
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
                    $oa_wu+=$tot_work_units;
                    echo $tot_work_units?></td>
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
                        <td></td>
                        <td><?php echo $oa_wu?></td>
                    </tr>
<?php
            } else {
                
                $query = "SELECT team_id,tasks_id,sub_task_id,time,count,cf,wu FROM user_tasks_ot WHERE team_id='$team' AND tasks_id='$task' AND sub_task_id='$s_task' AND task_desc='$task_desc' AND " . $where_1;
                    $result = $conn->runsql($query, $dbcon);
                    if (mysqli_num_rows($result)) {
                    while ($result_row = mysqli_fetch_object($result)) {
                        $tot_count+=$result_row->count;
                        $secs+= strtotime($result_row->time)-strtotime("00:00:00");
                        $team_id=$result_row->team_id;
                        $tasks_id=$result_row->tasks_id;
                        $sub_task_id=$result_row->sub_task_id;
                        $cf=$result_row->cf;
                        $tot_work_units+=$result_row->wu;
                        
                    }
                    echo $i % 2 == 0 ? "<tr id='data_row_even'>" : "<tr id='data_row_odd'>";
                    ?>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo $team_array[$team_id]?></td>
                    <td><?php echo $task_array[$tasks_id] ?></td>
                    <td><?php echo $sub_task_array[$sub_task_id] ?></td>
                    <td><?php echo $task_desc_array[$task_desc] ?></td>
                    <td><?php echo $tot_time=sectohr($secs); ?></td>
                    <td><?php echo $tot_count ?></td>
                    <td><?php echo $cf ?></td>
                    <td><?php echo $tot_work_units?></td>
                    </tr>
            <?php } else echo "<tr><td colspan=6 align=center>No entries available!</td></tr>";
            }
            ?>

        </table>
    </div>
    <div id="tabs-4">
        <table width="100%">
        <?php
            $i = 1;
            $tot_time="";
            $tot_count=0;
            $secs=0;
            $cf=0;
            $tot_work_units=0.0;
            
            
            if($team!="" && $task!="" && $s_task!="" && $task_desc!="")
            {
            ?>
             <tr id="data_header" align="center">
                    <td >S.No.</td>
                    <td>User</td>
                    <td>Team</td>
                    <td>Task</td>
                    <td>Sub task</td>
                    <td>Task desc</td>
                    <td>Time </td>
                    <td>Count</td>
                    <td>Work units</td>
             </tr>
             <?php
                if($user=="")
                {
                    $query = "SELECT DISTINCT user_id FROM user_tasks_ot WHERE team_id='$team' AND tasks_id='$task' AND sub_task_id='$s_task' AND task_desc='$task_desc' AND " . $where_1 . " ORDER BY user_id ASC";
                    $result_unique_team = $conn->runsql($query, $dbcon);
                    while ($result_row1 = mysqli_fetch_object($result_unique_team)) {
                        $unique_user = $result_row1->user_id;
                        $query = "SELECT time,count,cf,wu FROM user_tasks_ot WHERE team_id='$team' AND tasks_id='$task' AND sub_task_id='$s_task' AND  task_desc='$task_desc' AND user_id='$unique_user' AND " . $where_1;
                        $result = $conn->runsql($query, $dbcon);
                        while ($result_row = mysqli_fetch_object($result)) {
                            $tot_count+=$result_row->count;
                            $secs+= strtotime($result_row->time)-strtotime("00:00:00");
                            $tot_work_units+=$result_row->wu;
                        }
                        echo $i % 2 == 0 ? "<tr id='data_row_even' align=center>" : "<tr id='data_row_odd' align=center>";
                        ?>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $user_array[$unique_user]?></td>
                        <td><?php echo $team_array[$team]?></td>
                        <td><?php echo $task_array[$task]?></td>
                        <td><?php echo $sub_task_array[$s_task]?></td>
                        <td><?php echo $task_desc_array[$task_desc]?></td>
                        <td><?php echo $tot_time=sectohr($secs)?></td>
                        <td><?php echo $tot_count ?></td>
                        <td><?php echo $tot_work_units?></td>
                        </tr>
                        <?php
                        $tot_time="";
                        $tot_count=0;
                        $secs=0;
                        $tot_work_units=0.0;
                    }
                }
                else
                {
                        $query = "SELECT time,count,cf,wu FROM user_tasks_ot WHERE team_id='$team' AND tasks_id='$task' AND sub_task_id='$s_task' AND task_desc='$task_desc' AND user_id='$user' AND " . $where_1;
                        $result = $conn->runsql($query, $dbcon);
                        if (mysqli_num_rows($result)) {
                        while ($result_row = mysqli_fetch_object($result)) {
                            $tot_count+=$result_row->count;
                            $secs+= strtotime($result_row->time)-strtotime("00:00:00");
                            $tot_work_units+=$result_row->wu;
                        }
                        echo $i % 2 == 0 ? "<tr id='data_row_even' align=center>" : "<tr id='data_row_odd' align=center>";
                        ?>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $user_array[$user]?></td>
                        <td><?php echo $team_array[$team]?></td>
                        <td><?php echo $task_array[$task]?></td>
                        <td><?php echo $sub_task_array[$s_task]?></td>
                        <td><?php echo $task_desc_array[$task_desc]?></td>
                        <td><?php echo $tot_time=sectohr($secs)?></td>
                        <td><?php echo $tot_count ?></td>
                        <td><?php echo $tot_work_units?></td>
                        </tr>
<?php                
                        } else echo "<tr><td colspan=9 align=center>No entries available!</td></tr>";
                  }

            }
            elseif($team!="" && $task!="" && $s_task!="")
            {
                    ?>
                 <tr id="data_header" align="center">
                    <td >S.No.</td>
                    <td>User</td>
                    <td>Team</td>
                    <td>Task</td>
                    <td>Sub task</td>
                    <td>Time </td>
                    <td>Count</td>
                    <td>Work units</td>
                </tr>
                <?php
                if($user=="")
                {
                    $query = "SELECT DISTINCT user_id FROM user_tasks_ot WHERE team_id='$team' AND tasks_id='$task' AND sub_task_id='$s_task' AND " . $where_1 . " ORDER BY user_id ASC";
                    $result_unique_team = $conn->runsql($query, $dbcon);
                    while ($result_row1 = mysqli_fetch_object($result_unique_team)) {
                        $unique_user = $result_row1->user_id;
                        $query = "SELECT time,count,cf,wu FROM user_tasks_ot WHERE team_id='$team' AND tasks_id='$task' AND sub_task_id='$s_task' AND user_id='$unique_user' AND " . $where_1;
                        $result = $conn->runsql($query, $dbcon);
                        while ($result_row = mysqli_fetch_object($result)) {
                            $tot_count+=$result_row->count;
                            $secs+= strtotime($result_row->time)-strtotime("00:00:00");
                            $tot_work_units+=$result_row->wu;
                        }
                        echo $i % 2 == 0 ? "<tr id='data_row_even' align=center>" : "<tr id='data_row_odd' align=center>";
                        ?>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $user_array[$unique_user]?></td>
                        <td><?php echo $team_array[$team]?></td>
                        <td><?php echo $task_array[$task]?></td>
                        <td><?php echo $sub_task_array[$s_task]?></td>
                        <td><?php echo $tot_time=sectohr($secs)?></td>
                        <td><?php echo $tot_count ?></td>
                        <td><?php echo $tot_work_units?></td>
                        </tr>
                        <?php
                        $tot_time="";
                        $tot_count=0;
                        $secs=0;
                        $tot_work_units=0.0;
                    }
                }
                else
                {
                        $query = "SELECT time,count,cf,wu FROM user_tasks_ot WHERE team_id='$team' AND tasks_id='$task' AND sub_task_id='$s_task' AND user_id='$user' AND " . $where_1;
                        $result = $conn->runsql($query, $dbcon);
                        while ($result_row = mysqli_fetch_object($result)) {
                            $tot_count+=$result_row->count;
                            $secs+= strtotime($result_row->time)-strtotime("00:00:00");
                            $tot_work_units+=$result_row->wu;
                        }
                        echo $i % 2 == 0 ? "<tr id='data_row_even' align=center>" : "<tr id='data_row_odd' align=center>";
                        ?>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $user_array[$user]?></td>
                        <td><?php echo $team_array[$team]?></td>
                        <td><?php echo $task_array[$task]?></td>
                        <td><?php echo $sub_task_array[$s_task]?></td>
                        <td><?php echo $tot_time=sectohr($secs)?></td>
                        <td><?php echo $tot_count ?></td>
                        <td><?php echo $tot_work_units?></td>
                        </tr>
<?php                }
            }
            elseif($team!="" && $task!="")
            {
                ?>
             <tr id="data_header" align="center">
                <td >S.No.</td>
                <td>User Name</td>
                <td>Team</td>
                <td>Task</td>
                <td>Time </td>
                <td>Count</td>
                <td>Work units</td>
            </tr>
            <?php
                if($user=="")
                {
                    $query = "SELECT DISTINCT user_id FROM user_tasks_ot WHERE team_id='$team' AND tasks_id='$task' AND " . $where_1 . " ORDER BY user_id ASC";
                    $result_unique_team = $conn->runsql($query, $dbcon);
                    while ($result_row1 = mysqli_fetch_object($result_unique_team)) {
                        $unique_user = $result_row1->user_id;
                        $query = "SELECT time,count,cf,wu FROM user_tasks_ot WHERE team_id='$team' AND tasks_id='$task' AND user_id='$unique_user' AND " . $where_1;
                        $result = $conn->runsql($query, $dbcon);
                        while ($result_row = mysqli_fetch_object($result)) {
                            $tot_count+=$result_row->count;
                            $secs+= strtotime($result_row->time)-strtotime("00:00:00");
                            $tot_work_units+=$result_row->wu;
                        }
                        echo $i % 2 == 0 ? "<tr id='data_row_even' align=center>" : "<tr id='data_row_odd' align=center>";
                        ?>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $user_array[$unique_user]?></td>
                        <td><?php echo $team_array[$team]?></td>
                        <td><?php echo $task_array[$task]?></td>
                        <td><?php echo $tot_time=sectohr($secs)?></td>
                        <td><?php echo $tot_count ?></td>
                        <td><?php echo $tot_work_units?></td>
                        </tr>
                        <?php
                        $tot_time="";
                        $tot_count=0;
                        $secs=0;
                        $tot_work_units=0.0;
                    }
                }
                else
                {
                        $query = "SELECT time,count,cf,wu FROM user_tasks_ot WHERE team_id='$team' AND tasks_id='$task' AND user_id='$user' AND " . $where_1;
                        $result = $conn->runsql($query, $dbcon);
                        if (mysqli_num_rows($result)) {
                        while ($result_row = mysqli_fetch_object($result)) {
                            $tot_count+=$result_row->count;
                            $secs+= strtotime($result_row->time)-strtotime("00:00:00");
                            $tot_work_units+=$result_row->wu;
                        }
                        echo $i % 2 == 0 ? "<tr id='data_row_even' align=center>" : "<tr id='data_row_odd' align=center>";
                        ?>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $user_array[$user];?></td>
                        <td><?php echo $team_array[$team] ?></td>
                        <td><?php echo $task_array[$task] ?></td>
                        <td><?php echo $tot_time=sectohr($secs); ?></td>
                        <td><?php echo $tot_count ?></td>
                        <td><?php echo $tot_work_units?></td>
                        </tr>
                <?php
                        } { echo "<tr><td colspan=7 align=center>No entries available!</td></tr>";}
                }
            }
            elseif($team!="")
            {
              ?>  <tr id="data_header" align="center">
                <td >S.No.</td>
                <td>User Name</td>
                <td>Team</td>
                <td>Time </td>
                <td>Count</td>
                <td>Work units</td>
            </tr><?php 
                if($user=="")
                {
                    $query_unique_team_query = "SELECT DISTINCT user_id FROM user_tasks_ot WHERE team_id='$team' AND " . $where_1 . " ORDER BY team_id ASC";
                    $result_unique_team = $conn->runsql($query_unique_team_query, $dbcon);
                    while ($result_row = mysqli_fetch_object($result_unique_team)) {
                        $unique_team = $result_row->user_id;
                        $query = "SELECT time,count,cf,wu FROM user_tasks_ot WHERE user_id='$unique_team' AND team_id='$team' AND " . $where_1;
                        $result = $conn->runsql($query, $dbcon);
                        while ($result_row = mysqli_fetch_object($result)) {
                            $tot_count+=$result_row->count;
                            $secs+= strtotime($result_row->time)-strtotime("00:00:00");
                            $tot_work_units+=$result_row->wu;
                        }
                        echo $i % 2 == 0 ? "<tr id='data_row_even' align=center>" : "<tr id='data_row_odd'  align=center>";
                        ?>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $user_array[$unique_team] ?></td>
                        <td><?php echo $team_array[$team] ?></td>
                        <td><?php echo $tot_time=sectohr($secs); ?></td>
                        <td><?php echo $tot_count ?></td>
                        <td><?php echo $tot_work_units?></td>
                        </tr>
                        <?php
                        $tot_time="";
                        $tot_count=0;
                        $secs=0;
                        $tot_work_units=0.0;
                    }
                }
                else
                {
                        $query = "SELECT time,count,cf,wu FROM user_tasks_ot WHERE user_id='$user' AND team_id='$team' AND " . $where_1;
                        $result = $conn->runsql($query, $dbcon);
                        if (mysqli_num_rows($result)) {
                            while ($result_row = mysqli_fetch_object($result)) {
                                $tot_count+=$result_row->count;
                                $secs+= strtotime($result_row->time)-strtotime("00:00:00");
                                $tot_work_units+=$result_row->wu;
                            }
                            echo $i % 2 == 0 ? "<tr id='data_row_even' align=center>" : "<tr id='data_row_odd'  align=center>";
                            ?>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo $user_array[$user] ?></td>
                            <td><?php echo $team_array[$team] ?></td>
                            <td><?php echo $tot_time=sectohr($secs); ?></td>
                            <td><?php echo $tot_count ?></td>
                            <td><?php echo $tot_work_units?></td>
                            </tr>
                <?php 
                        }
                        else { echo "<tr><td colspan=6 align=center>No entries available!</td></tr>";}
                }
            }
            else 
            {
                ?>
            <tr id="data_header" align="center">
                <td >S.No.</td>
                <td>User Name</td>
                <td>Time </td>
                <td>Count</td>
                <td>Work units</td>
            </tr>
            <?php
                if($user=="")
                {
                    $query_unique_user_query= "SELECT DISTINCT user_id FROM user_tasks_ot WHERE " . $where_1 . " ORDER BY user_id ASC";
                    $result_unique_user = $conn->runsql($query_unique_user_query, $dbcon);
                    while ($result_row = mysqli_fetch_object($result_unique_user)) {
                        $unique_user = $result_row->user_id;
                        $query = "SELECT team_id,tasks_id,sub_task_id,task_desc,time,count,wu,cf FROM user_tasks_ot WHERE user_id='$unique_user' AND " . $where_1;
                        $result = $conn->runsql($query, $dbcon);
                        while ($result_row = mysqli_fetch_object($result)) {
                            $tot_count+=$result_row->count;
                            $secs+= strtotime($result_row->time)-strtotime("00:00:00");
                            $tot_work_units+=$result_row->wu;
                        }
                        echo $i % 2 == 0 ? "<tr id='data_row_even' align=center>" : "<tr id='data_row_odd' align=center>";
                        ?>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $user_array[$unique_user] ?></td>
                        <td><?php echo $tot_time=sectohr($secs); ?></td>
                        <td><?php echo $tot_count?></td>
                        <td><?php echo $tot_work_units?></td>
                        </tr>
               <?php
                        $tot_time="";
                        $tot_count=0;
                        $secs=0;
                        $tot_work_units=0.0;
                    }
                }
                else
                {
                    $query = "SELECT team_id,tasks_id,sub_task_id,task_desc,time,count,cf,wu FROM user_tasks_ot WHERE user_id='$user' AND " . $where_1;
                        $result = $conn->runsql($query, $dbcon);
                        while ($result_row = mysqli_fetch_object($result)) {
                            $tot_count+=$result_row->count;
                            $secs+= strtotime($result_row->time)-strtotime("00:00:00");
                            $tot_work_units+=$result_row->wu;
                        }
                        echo $i % 2 == 0 ? "<tr id='data_row_even' align=center>" : "<tr id='data_row_odd' align=center>";
                        ?>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $user_array[$user] ?></td>
                        <td><?php echo $tot_time=sectohr($secs); ?></td>
                        <td><?php echo $tot_count?></td>
                        <td><?php echo $tot_work_units?></td>
                        </tr>
                <?php
                }
            }?>
</table>
</div>
</div>