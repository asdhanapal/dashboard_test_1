<?php
session_start();
include_once './data_page.php';
require_once '../classes/db.class.php';
$conn = new db();
$dbcon = $conn->dbConnect();
?>
<script>
    $(function() {
        $("#tabs").tabs();
    });
    
    function del_cf(id)
    {
        if(confirm('Are you sure to delete the conversion factor?'))
        {
            
            $('.msgs').html('<img src="../img/loading.gif">');
            $.ajax({
                type: "POST",
                url: "del_cf.php?action=del_cf",
                data: "id="+id,
                success: function(msg)
                {
                        var html = $.trim(msg);
                        var yr=$('#filter_cf').val();
                        $( "#user_tasks_list" ).load( "./load_con_fac.php",  { "yr": yr},function() {});
                        $('.msgs').html(html);
                        $(".msgs").hide().html(html).fadeIn('slow');
                }
            });
            return false;
        }
     }
</script>
<style>
    #tabs{
        font: 82.5% "Trebuchet MS", sans-serif;
    }
</style>

<div class="row">
    <div class="large-12 columns">
        <?php
        $yr = $_POST['yr'];
        $query_select_recs = "SELECT DISTINCT month_from FROM amz_daily_target WHERE month_from LIKE '%$yr%' ORDER BY create_date desc";
        $result_select_recs = $conn->runsql($query_select_recs, $dbcon);
        $j = 1;
        $months = array(0 => "");
        if (mysqli_num_rows($result_select_recs)) {
            echo "<div id=\"tabs\"><ul>";
            while ($result_row = mysqli_fetch_object($result_select_recs)) {
                $months[] = $result_row->month_from;
                echo "<li><a href=#tabs-" . $j . ">" . $result_row->month_from . "</a></li>";
                $j++;
            }
            echo "</ul>";
        } else {
            echo "<div class=\"panel\"><center><font color=red>No records available for $yr!</font></center></div>";
        }

        for ($i = 1; $i < sizeof($months); $i++) {
            echo "<div id=\"tabs-" . $i . "\">";
            $query_select_month_recs = "SELECT * FROM amz_daily_target WHERE month_from='$months[$i]' AND cf_updated=1 ORDER BY team desc";
            ?>
        <?php
        $query_show_incomplete = "SELECT * FROM amz_daily_target WHERE month_from='$months[$i]' AND cf_updated=0 ORDER BY team desc";
        $result_show_incomplete = $conn->runsql($query_show_incomplete, $dbcon);
        if (mysqli_num_rows($result_show_incomplete))
        {
            ?>
            <div data-alert class="alert-box warning round">
            <center><span id="view_tasks" onclick="show_hide('<?php echo $i?>');" style="cursor: pointer;"><b>Still some tasks missing for this month Click here to view the missing sub tasks</b></span></center>
            <a href="#" class="close">&times;</a>
            </div>
        <table id="unentries_<?php echo $i?>"  itemid="show_warning" align="center" class="tablesorter">
            <thead><tr><td>S.No</td><td>Team</td><td>Task</td><td>Sub task</td></tr></thead>
            <?php
            $count=1;
            while ($result_row_show_incomplete = mysqli_fetch_object($result_show_incomplete))
            {
                echo "<tr>";
                echo "<td>".$count++."</td>";
                echo "<td>".$team_array[$result_row_show_incomplete->team]."</td>";
                echo "<td>".$task_array[$result_row_show_incomplete->task]."</td>";
                if (($result_row_show_incomplete->sub_task!="1") && ($result_row_show_incomplete->sub_task!="2") && ($result_row_show_incomplete->sub_task!="3") )
                       echo $result_row_show_incomplete->sub_task!=""?"<td>".$sub_task_array[$result_row_show_incomplete->sub_task]."</td>":"<td>NA</td>"; //.$result_row->sub_task
                    else
                        echo "<td>".$test_cases[$result_row_show_incomplete->sub_task]."</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        else {
            $query_update_amz_dt_manage="UPDATE amz_dt_manage SET cf_updation=1 WHERE month='$months[$i]'";
            $conn->runsql($query_update_amz_dt_manage, $dbcon);
        }
        ?>
        
        <table width="100%" class="tablesorter">
            <thead>
                <tr id="data_header">
                    <td >S.No.</td>
                    <td>Team</td>
                    <td>Task</td>
                    <td>Sub task</td>
                    <td>Target Type</td>
                    <td>Daily target</td>
                    <td>Status</td>
                    <td>WU status</td>
                    <td>Action</td>
                </tr>
                </thead>
    <?php
    $result = $conn->runsql($query_select_month_recs, $dbcon);
    $k=1;
    while ($result_row = mysqli_fetch_object($result)) {
        $class = $k % 2;
        echo $k % 2 == 0 ? "<tr id='data_row_even'  align=\"center\">" : "<tr id='data_row_odd'  align=\"center\">";
        ?>
                    <td><?php echo $k ?></td>
                    <td><?php echo $team_array[$result_row->team] ?></td>
                    <td><?php echo $task_array[$result_row->task] ?></td>
                    <td><?php if (($result_row->sub_task!="1") && ($result_row->sub_task!="2") && ($result_row->sub_task!="3") )
                        echo $result_row->sub_task!=""?$sub_task_array[$result_row->sub_task]:"NA"; //.$result_row->sub_task
                    else
                        echo $test_cases[$result_row->sub_task];?></td>
                    <td><?php echo $cf_status[$result_row->about_cf] ?></td>
                    <td><?php echo $result_row->con_fac !="" ? $result_row->con_fac : "NULL" ?></td>
                    <td><?php echo $result_row->status == "1" ? "Active" : "Inactive" ?></td>
                    <td><?php echo $result_row->wu_status == "1" ? "Updated" : "Pending" ?></td>
                    <?php if($result_row->about_cf==1) {?>
                    <td>Edit&nbsp;&nbsp;&nbsp;<a href="#" onclick="del_cf('<?php echo $result_row->s_no?>');">Delete</a></td>
                    <?php } else {?>
                    <td> -- </td>
                    <?php } ?>
                    </tr>
        <?php
        $k++;
    }
        echo "</table></div>";
        }
    ?> 
        </div>
    </div>
    </div>
<!--</div></div>-->

<script>
$(document).foundation();
</script>

<script>
$('table[itemid^="show_warning"]').hide();
function show_hide(id)
{
  $('#unentries_'+id).slideToggle('slow');
  
}
</script>
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