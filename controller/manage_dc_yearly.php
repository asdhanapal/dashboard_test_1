<style>
table 
{
  display: none;
}
#loading_gif {
     position: absolute;
     top: 40%;
     left: 50%;
     margin-left: -(X/2)px;
     margin-top: -(X/2)px;
 }
</style>
<?php
include_once '../includes/define.php';
require_once _BACK_TO_PRE_.DB_CLASS.DB_CLASS_FILE;
require_once _BACK_TO_PRE_._INCLUDE.SESSION_FILE;
require_once ID_TO_NAME_CONV; //Convert the ID to name.
$conn = new db();
$dbcon = $conn->dbConnect();

if(isset($_GET['id']))
{
    $task_id = $_GET['id'];
    $years = $_GET['year'];

$month_array=array(1=>"January","February","March","April","May","June","July","August","September","October","November","December");
?>
<span id="loading"><img id="loading_gif" src="../img/preloader.gif"></span> 
<table align="center" id="content">
    <tr class="header">
        <td colspan="13"  align="center">
            <b>Task: </b><?php echo $task_array[$task_id]?>
            <b>Year: </b><?php echo $years?>
        </td>
    </tr>

    <tr>
        <th>Sub task</th>
        <th>Device count</th>
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
    </tr>
   
    <?php 
        $sql="SELECT DISTINCT sub_task_id FROM amz_dc_units WHERE month like '%$years%' AND task_id='$task_id'";
        $result = $conn->runsql($sql, $dbcon);
        if(mysqli_num_rows($result))
        {
            while ($result_row = mysqli_fetch_object($result)) 
            {
                $sub_task=$result_row->sub_task_id;
                echo "<tr><td>";
                echo "<b>".$sub_task_array[$sub_task]."</b>";
                echo "</td>";
                for($i=2;$i<7;$i++)
                { ?>
                    <tr><td></td><td align="center"><?php echo $i?></td>
                    <?php
                    for($months=1;$months<=12;$months++)
                    {
                        echo "<td>";
                        $month_from=$month_array[$months]." ".$years;
                        $query_load_dc="SELECT s_no,month,team_id,task_id,sub_task_id,noofdevice,percentage FROM amz_dc_units WHERE task_id=$task_id AND sub_task_id= '$sub_task' AND month='$month_from' AND noofdevice=$i";
                        $result_load_dc = $conn->runsql($query_load_dc, $dbcon);
                        if(mysqli_num_rows($result_load_dc)==1)
                        {
                            $result_row_load_dc = mysqli_fetch_object($result_load_dc);
                            ?>
                                <a href="#" class="dc" id="dc_<?php echo $result_row_load_dc->s_no;?>" data-type="text" data-placement="top" data-title="Enter the percentage value"><?php echo $result_row_load_dc->percentage;?></a>
                            <?php 
                        }
                        else
                            echo "-";
                        
                        echo "</td>";
                    }
                    echo "</tr>";
                }
                echo "</tr>";
            }
        }
    ?>
</table>

<link rel="stylesheet" href="../css/foundation_1.css" />
<script src="../js/modernizr.js"></script>
<script src="../js/jquery.js"></script>
<script src="../js/foundation.min.js"></script>
<link rel="stylesheet" href="../css/styles.css">

<link rel="stylesheet" href="../css/jquery-ui.css">
<script src="../js/jquery-ui.js"></script>

<!-- bootstrap -->
<link href="../css/bootstrap-combined.min.css" rel="stylesheet">
<script src="../js/bootstrap.min.js"></script>  

<!-- x-editable (bootstrap version) -->
<link href="../css/bootstrap-editable.css" rel="stylesheet"/>
<script src="../js/bootstrap-editable.min.js"></script>

<!-- main.js -->
<script>
$(document).ready(function() {
    $('#content').show('slow');
    $.fn.editable.defaults.mode = 'popup';     
    var path="update_daily_target_dc_ajax";
    $('.dc').editable();
    $(document).on('click', '.editable-submit', function () {
        var x = $(this).closest('td').children('a').attr('id');
        var a = $("#"+x).text();
        var y = $('.input-medium').val();
        if(!$.isNumeric(y))
        {
            alert("Incorrect input!");
            return false;
        }
        var z = $(this).closest('td').children('a');
        $.ajax({
            url: path + ".php?id=" + x + "&data=" + y,
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

$(window).load(function() {
  //When the page has loaded
  $('#loading').hide();
  $("table").slideDown("slow");
});
</script>

<style>
.fixed {
  position: fixed;
  //left:auto;
 }
 
</style>

<script>
    $(window).scroll(function(){
  var sticky = $('.header');
      scroll = $(window).scrollTop();

  if (scroll >= 50) sticky.addClass('fixed');
  else sticky.removeClass('fixed');
});
</script>
<?php } ?>