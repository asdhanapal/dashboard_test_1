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
<span class="head_text">Daily Target Setup</span>
<table style="border-color: #dbb7b7;" border='1'  class="table"  align="center">
    <tr>
        <td style=" text-align: center;"><b>None</b> -> Target not required (For ex: Leave)</t>
        <td style=" text-align: center;"><b>Auto</b> -> Auto Target</td>
        <td style=" text-align: center;"><b>-(Single 'Dash')</b> -> Disabled for current month</td>
        <!--<td><b>----(Four 'Dash's')</b> -> Unable to enter target! Please contact Timesheet admin</td>-->
    </tr>
</table>

<table class="table" border="0" align="center">
    
    <tr style="background-color: #CCFFCC;">
        <!--<th rowspan="2">Team</th>-->
        <th rowspan="2">Task</th>
        <th rowspan="2">Sub task</th>
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
if(mysqli_num_rows($result_load_teams)) {
    while ($result_row_load_teams = mysqli_fetch_object($result_load_teams)) 
    {
        $team_id=$result_row_load_teams->team_id;
        $query_load_tasks="SELECT task_name,task_id,device_count FROM amz_tasks WHERE team_id=$team_id ORDER BY have_st DESC, task_name Asc";
        $result_load_tasks = $conn->runsql($query_load_tasks, $dbcon);
        if(mysqli_num_rows($result_load_tasks))
        {
            while ($result_row_load_tasks = mysqli_fetch_object($result_load_tasks)) 
            {
                $task_id=$result_row_load_tasks->task_id;
                $query_load_sub_tasks="SELECT sub_task_name,sub_task_id FROM amz_sub_tasks WHERE task_id=$task_id ORDER BY sub_task_name ASC";
                $result_load_sub_tasks = $conn->runsql($query_load_sub_tasks, $dbcon);
                ?>
                <tr>
                    <td rowspan="<?php echo mysqli_num_rows($result_load_sub_tasks)+1;?>">
                        <?php 
                            echo $result_row_load_tasks->task_name;
                            if($result_row_load_tasks->device_count)
                            {
                                ?>
                                    <br><a href="<?php echo _BACK_TO_PRE_.COMMON.DAILY_TARGET_COUNT_ENTRY;?>?id=<?php echo $task_id;?>&&year=<?php echo $year[0];?>" target="Dhanapal">Device count</a>
                                <?php
                            }
                        ?>
                    </td>
                <?php
                if(mysqli_num_rows($result_load_sub_tasks)) 
                {
                    while ($result_row_load_sub_tasks = mysqli_fetch_object($result_load_sub_tasks)) 
                    {
                        ?>
                            <tr>
                                <td><?php echo $result_row_load_sub_tasks->sub_task_name?></td>
                                <?php 
                                for($i=0;$i<sizeof($year);$i++) 
                                {
                                    $sub_task_id=$result_row_load_sub_tasks->sub_task_id;
                                    for($months=1;$months<=12;$months++)
                                    {
                                    echo "<td>";
                                    $month_from=$month_array[$months]." ".$year[$i];
                                    $query_load_con_fac="SELECT s_no,about_cf,con_fac,status FROM amz_daily_target WHERE sub_task=$sub_task_id AND month_from='$month_from' AND deletion=0";
                                    $result_load_con_fac = $conn->runsql($query_load_con_fac, $dbcon);
                                    if(mysqli_num_rows($result_load_con_fac)==1)
                                    {
                                        $result_row_load_con_fac = mysqli_fetch_object($result_load_con_fac);
                                        if($result_row_load_con_fac->status=="1")
                                        {
                                            if($result_row_load_con_fac->about_cf=="1")
                                            {
                                            ?>
                                                <a href="#" class="monthly_target" id="monthly_target_<?php echo $result_row_load_con_fac->s_no;?>" data-type="text" data-placement="top" data-title="Enter Target" 
                                                <?php echo $result_row_load_con_fac->con_fac==""?'style="border-bottom: none;"><font size="2px;"></font>':'>'.$result_row_load_con_fac->con_fac;?>
                                                </a>
                                            <?php
                                            }
                                            elseif ($result_row_load_con_fac->about_cf=="0")
                                            {
                                            ?>
                                                <a href="#" class="monthly_target" id="monthly_target_<?php echo $result_row_load_con_fac->s_no;?>" data-type="text" data-placement="top" data-title="Enter Target" style="border-bottom: none;"><font size="2px;">Auto</font></a>
                                            <?php
                                            }
                                            else
                                            {
                                            ?>
                                                <a href="#" class="monthly_target" id="monthly_target_<?php echo $result_row_load_con_fac->s_no;?>" data-type="text" data-placement="top" data-title="Enter Target" style="border-bottom: none;"><font size="2px;">None</font></a><!-- style="border-bottom: none;"><font size="2px;"-->
                                            <?php
                                            }
                                        }
                                        else
                                        {
                                        ?>
                                            <a href="#" class="monthly_target" id="monthly_target_<?php echo $result_row_load_con_fac->s_no;?>" data-type="text" data-placement="top" data-title="Enter Target" style="border-bottom: none;"><font size="2px;">-</font></a>
                                        <?php
                                        }
                                    }
                                    else 
                                        { ?><a href="#" onclick="alert('Unable to add the target! Please contact adminstrator!')" style="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a><?php }
                                    ?>
                                    </td>
                                    <?php }?>
                          <?php }?>
                            </tr>
                        <?php
                    }
                }
                else
                {
                    ?>
                    <td>--</td>
                    <?php for($i=0;$i<sizeof($year);$i++) {
                        for($months=1;$months<=12;$months++) {
                        ?>
                    <td onclick="alert('This task don\'t have any manual targets. System automatically creates the target. For more info find in https://../docs_1202.html');"><font size="2px;">Auto</font></td>
                    <?php }  }?>
                    <?php
                }
                ?>
                </tr>
                <?php
            }
        }
    }
}
?>
</table>

<!-- bootstrap -->
<link href="../css/bootstrap-combined.min.css" rel="stylesheet">
<script src="../js/bootstrap.min.js"></script>  

<!-- x-editable (bootstrap version) -->
<link href="../css/bootstrap-editable.css" rel="stylesheet"/>
<script src="../js/bootstrap-editable.min.js"></script>

<!-- main.js -->
<script>
$(document).ready(function() {
    $.fn.editable.defaults.mode = 'popup';     //toggle `popup` / `inline` mode
    $('.monthly_target').editable();
    $(document).on('click', '.editable-submit', function () {
        var x = $(this).closest('td').children('a').attr('id');
        var a = $("#"+x).text();
        var y = $('.input-medium').val();
        var checkpoint_txt=x.substr(0,x.indexOf("_") +1);
        if(checkpoint_txt!="monthly_")
            return false;
        if(!$.isNumeric(y))
        {
            y=y.toUpperCase();
            var arr = ["AUTO","NONE","-"];
            if(($.inArray( y, arr ))==-1)
            {
                alert("Incorrect input!");
                return false;
            }
        }
        var z = $(this).closest('td').children('a');
        $.ajax({
            url: "<?php echo _BACK_TO_PRE_.COMMON.DAILY_TARGET_UPDATION_PAGE?>?id=" + x + "&data=" + y+"&token=1",
            type: 'GET',
            success: function (s) {
                if (s == 'pass') {
                    $(z).html(y);
                }
                else {
                    alert('Internal error. Try again later!');
                    $(z).html(a);
                }
            },
            error: function (e) {
                alert('Internal error. Try again later!');
            }
        });
    });
});
</script>