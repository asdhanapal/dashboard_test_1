<?php
session_start();
require_once '../includes/define.php';
require_once _BACK_TO_PRE_.DB_CLASS.DB_CLASS_FILE;
require_once ID_TO_NAME_CONV;

$conn = new db();
$dbcon = $conn->dbConnect();

$team=$_POST['teams'];
$year=$_POST['yr'];
if($team=="")
{
   ?>
<div style="text-align: center;">Select the team!</div>
<?php
die();
}
$team_text=implode(",", $team);

if($year=="")
{
        ?>
<div style="text-align: center;">Select the year!</div>
<?php
die();
}
$month_array=array(1=>"January","February","March","April","May","June","July","August","September","October","November","December");
?>
<span class="head_text">Define the Task Types</span>
<span class="help_text">Help</span>
<div class="help_div">
<!--    <select>
        <option>Measurable/Non-Measurable</option>
    </select>
    <select>
        <option>Release/Non-Release</option>
    </select>
    <select>
        <option>Operational/Offload</option>
    </select>-->
</div>
<table class="table" border="0" align="center" width="100%">
    <tr style="background-color: #CCFFCC;">
        <th rowspan="2">Task</th>
        
        <?php for($i=0;$i<sizeof($year);$i++) {?>
        <th colspan="12" style="text-align:center;"><?php echo $year[$i]?></th>
        <?php } ?>
    </tr>
    <tr style="background-color: #CCFFCC;">
    <?php for($i=0;$i<sizeof($year);$i++) {?>
        <th>Jan</th>
        <th>Feb</th>
        <th>Mar</th>
        <th>Apr</th>
        <th>May</th>
        <th>Jun</th>
        <th>Jul</th>
        <th>Aug</th>
        <th>Sep</th>
        <th>Oct</th>
        <th>Nov</th>
        <th>Dec</th>
    <?php } ?>
    </tr>
<?php
$query_load_teams="SELECT team_name,team_id FROM amz_teams WHERE team_id IN($team_text) ORDER BY team_name ASC";
$result_load_teams = $conn->runsql($query_load_teams, $dbcon);
if(mysqli_num_rows($result_load_teams)) 
{
    while ($result_row_load_teams = mysqli_fetch_object($result_load_teams)) 
    {
        $team_id=$result_row_load_teams->team_id;
        $query_load_tasks="SELECT task_name,task_id FROM amz_tasks WHERE team_id=$team_id ORDER BY task_name Asc";
        $result_load_tasks = $conn->runsql($query_load_tasks, $dbcon);
        if(mysqli_num_rows($result_load_tasks))
        {
            while ($result_row_load_tasks = mysqli_fetch_object($result_load_tasks)) 
            {
                $task_id=$result_row_load_tasks->task_id;
                ?>
                <tr>
                    <td>
                        <?php 
                        echo $result_row_load_tasks->task_name;
                        ?>
                    </td>

                    <?php
                    for($i=0;$i<sizeof($year);$i++) 
                    {
                        for($months=1;$months<=12;$months++)
                        {?>
                            <?php
                            $month_from=$month_array[$months]." ".$year[$i];
                            $query_get_row="SELECT s_no,about_cf,op_off,ms_non_ms,rele_non_rele,status FROM amz_daily_target WHERE task=$task_id AND month_from='$month_from' AND deletion=0";
                            $result_get_row = $conn->runsql($query_get_row, $dbcon);
                            if(mysqli_num_rows($result_get_row))
                            {
                                $result_row_get_row = mysqli_fetch_object($result_get_row);
                                $op_off=$result_row_get_row->op_off;
                                $ms_non_ms=$result_row_get_row->ms_non_ms;
                                $rele_non_rele=$result_row_get_row->rele_non_rele;
                                ?>
                                <td>
                                    <select onchange="update_status(1,<?php echo $months?>,<?php echo $year[$i]?>,<?php echo $task_id?>,this.value)">
                                        <option value="" ></option>
                                        <option value="0" <?php echo $ms_non_ms=='0'?"selected":""?>>None</option>
                                        <option value="1" <?php echo $ms_non_ms=='1'?"selected":""?>>Measurable</option>
                                        <option value="2" <?php echo $ms_non_ms=='2'?"selected":""?>>Non-Measurable</option>
                                    </select>
                                    <select onchange="update_status(2,<?php echo $months?>,<?php echo $year[$i]?>,<?php echo $task_id?>,this.value)">
                                        <option value=""></option>
                                        <option value="0" <?php echo $rele_non_rele=='0'?"selected":""?>>None</option>
                                        <option value="1" <?php echo $rele_non_rele=='1'?"selected":""?>>Release</option>
                                        <option value="2" <?php echo $rele_non_rele=='2'?"selected":""?>>Non-Release</option>
                                    </select>
                                    <select onchange="update_status(3,<?php echo $months?>,<?php echo $year[$i]?>,<?php echo $task_id?>,this.value)">
                                        <option value=""></option>
                                        <option value="0" <?php echo $op_off=='0'?"selected":""?>>None</option>
                                        <option value="1" <?php echo $op_off=='1'?"selected":""?>>Operational</option>
                                        <option value="2" <?php echo $op_off=='2'?"selected":""?>>Off-Loaded</option>
                                    </select>
                                </td>
                                <?php
                            }
                            else 
                            { ?>
                                <td>-<br>-<br>-</td>
                            <?php }
                        }
                    }?>
                </tr>
                <?php
            }
        }
    }
}
?>
</table>