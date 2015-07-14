<?php
require_once '../includes/define.php';
require_once _BACK_TO_PRE_.DB_CLASS.DB_CLASS_FILE;
require_once _BACK_TO_PRE_._INCLUDE.SESSION_FILE;
require_once ID_TO_NAME_CONV;

$conn = new db();
$dbcon = $conn->dbConnect();

$year=$_POST['year'];
if($year=="")
{ ?>
    <div style="text-align: center;">Select the year!</div>
    <?php
    die();
}
$month_array=array(1=>"January","February","March","April","May","June","July","August","September","October","November","December");
?>
    <table width="100%" class="my_table">
    <tr class="tbl_header">
    <td colspan="25" align="center">User Wise Productivity</td>
    <?php
//        for($i=1;$i<sizeof($month_array);$i++)
//        {
//            echo "<td>".substr($month_array[$i],0,3)." Act</td>";
//            echo "<td>".substr($month_array[$i],0,3)." Fcst</td>";
//        }
    ?>
    </tr>
    <?php
    $query_1="SELECT team_id,team_name FROM amz_teams WHERE team_id!= 7 ORDER BY team_name ASC";
    $result_1= $conn->runsql($query_1,$dbcon);
    if(mysqli_num_rows($result_1))
    {
        while($result_row_1=  mysqli_fetch_object($result_1))
        { ?>
    <tr class="head_row" style="background-color: #EBEBEB;" onclick="view_report_2_get_full_info(<?php echo $result_row_1->team_id?>);" id="master_row_<?php echo $result_row_1->team_id?>">
            <td colspan="25">
                <?php echo $result_row_1->team_name;?>
            </td>
        </tr>
        <tr style="display: none;" id="display_team_tr_<?php echo $result_row_1->team_id?>">
            <td colspan="25" id="display_team_td_<?php echo $result_row_1->team_id?>"></td>
        </tr>
        <?php
        }
    }
    ?>
</table>