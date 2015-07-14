<?php
include_once '../includes/header_manager.php';
include_once './data_page.php';
?>
<br>
<div class="row">
    <div class="large-12 columns">
        <div class="panel">
            <div class="row">
                <div class="large-1 columns">
                    <label>From and To </label>
                    <input type="text" id="date_from" value="<?php echo date('Y-m', $_SERVER['REQUEST_TIME'])."-01"; ?>" onchange="do_view();">
                    <input type="text" id="date_to" value="<?php echo date('Y-m-d', $_SERVER['REQUEST_TIME']); ?>" onchange="do_view();">
                </div>
                
                <div class="large-2 columns">
                    <label>Team</label>
                    <select id="team" placeholder=" -- Teams --" onchange="load_tasks(); do_view();">
                        <option> Loading...</option>
                    </select>
                </div>

                <div class="large-2 columns">
                    <label>Task</label>
                    <select id="task" multiple="multiple" onchange="do_view();" placeholder=" -- Tasks --">
                        <option value="" disabled=""> -- Select team first --</option>
                    </select>
                </div>

                <div class="large-2 columns">
                    <label>User</label>
                    <select id="user" multiple="multiple" placeholder=" -- Users --" onchange="do_view();">
                        <option value=""> Loading...</option>
                    </select>
                </div>
                
                <div class="large-1 columns">
                    <label>Trend for</label>
                    <input type="radio"  checked="" name="trend_view" value="1" onfocusout="do_view();"><label>Workunit</label><br>
                    <input type="radio" name="trend_view" value="2" onfocusout="do_view();"  disabled=""><label>Count</label><br>
                    <input type="radio" name="trend_view" value="3" onfocusout="do_view();"  disabled=""><label>Time</label>
                </div>
                
                <div class="large-4 columns">
                    <div class="row">
                        <div class="large-12 columns">
                            <div class="row">
                                
                                <div class="large-3 columns">
                                    <label>View type</label>
                                    <select id="report_view" onfocusout="do_view();" disabled="">
                                        <option value=""> Weekly</option>
                                        <option>monthly</option>
                                        <option>2 months</option>
                                        <option>3 months</option>
                                        <option>4 months</option>
                                        <option>6 yearly</option>
                                        <option>Yearly view</option>
                                    </select>
                                </div>
                                
                                <div class="large-3 columns">
                                    <label>Chart title</label>
                                    <input type="text" id="chart_title" onfocusout="do_view();">
                                </div>

                                <div class="large-3 columns">
                                    <label>X axis </label>
                                    <input type="text" id="x_axis" onfocusout="do_view();">
                                </div>

                                <div class="large-3 columns">
                                    <label>Y axis</label>
                                    <input type="text" id="y_axis" onfocusout="do_view();"  disabled="">
                                </div>
                            </div>

                            <div class="row">
                                
                                <div class="large-3 columns">
                                    <label>&nbsp;</label>
                                    <input type="radio" id="column_line_with_target" name="c_type" value="column_line_with_target" onchange="column_line_with_target();" checked="">&nbsp;Combo
                                </div>
                                
                                <div class="large-3 columns">
                                    <label>&nbsp;</label>
                                    <input type="radio" id="Pie" name="c_type" value="pie" onchange="pie();" disabled="">&nbsp;Pie
                                </div>

                                <div class="large-3 columns">
                                    <label>&nbsp;</label>
                                    <input type="radio" id="line" name="c_type" value="line" onchange="line();"  disabled="">&nbsp;Line
                                </div>

                                <div class="large-3 columns">
                                    <label>&nbsp;</label>
                                    <input type="radio" id="Column" name="c_type" value="column" onchange="column();"  disabled="">&nbsp;Column
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="large-12 columns">
                <div class="callout panel">
<!--                    <span id="loading_user_data">&nbsp;</span>-->
                    <div id="loading_user_data"></div>
                    <div id="user_tasks_list">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script>
//if (! window.jQuery) 
//{
//    alert("No internet connection! so some features missing");
//}
</script>
<script src="../js/jquery.sumoselect.js"></script>
<link href="../css/sumoselect.css" rel="stylesheet" />
<style type="text/css">
        p,div,ul,li{padding:0px; margin:0px;}
    </style>
<script type="text/javascript">
$(document).ready(function () {
    load_teams();
    load_users();
    column_line_with_target();
    $('#task').SumoSelect();
    $('#sub_task').SumoSelect();
    window.asd = $('.SlectBox').SumoSelect({ csvDispCount: 3 });
//    window.test = $('.testsel').SumoSelect({okCancelInMulti:true });
});

function load_teams()
{
    $.ajax({
        type: "POST",
        url: "./load_teams_all.php",
        success: function(msg)
        {
            var html = $.trim(msg);
            $("#team").html(html);
            $('#team').SumoSelect();
            load_tasks();
        }
    });
}

function load_tasks()
{
    var id = $("#team").val();
    $.ajax({
        type: "POST",
        url: "./load_tasks_all.php",
        data: "team_id=" + id,
        success: function(msg)
        {
            var html = $.trim(msg);
            $('#task')[0].sumo.unload();
            $("#task").html(html);
            $('#task').SumoSelect();
        }
    });
}

function load_users()
{
    $.ajax({
        type: "POST",
        url: "./load_users_with_sep.php",
        success: function(msg)
        {
            var html = $.trim(msg);
            $("#user").html(html);
            $('#user').SumoSelect();
        }
    });
}

function do_view()
{
    var report_view=$('input[name=c_type]:checked').val();
    //alert(report_view);
        window[report_view]();
}
</script>

<style type="text/css">
    ${demo.css}
</style>

<script>
function pie()
{
    $('#loading_user_data').html('<center><img src="../img/loading.gif"></center>');
    $("#user_tasks_list").load("../chart/pie.php", {}, function(response, status, xhr) 
    {
        if (status == "success")
        {
                $('#loading_user_data').html('&nbsp;');
                var text="Monthy work units - nov 2014";
        }
    });
}
</script>

<script>
function line()
{
    $('#loading_user_data').html('<center><img src="../img/loading.gif"></center>');
    var from_date=$("#date_from").val();
    var to_date=$("#date_to").val();
    var report_view=$("#report_view").val();
    var team=$("#team").val();
    var task=$("#task").val();
    var user=$("#user").val();

    var title=$("#chart_title").val();
    var x_axis=$("#x_axis").val();
    var y_axis=$("#y_axis").val();

    $("#user_tasks_list").load("../chart/line.php", {from_date: from_date,to_date:to_date,report_view:report_view,team:team,task:task,user:user}, function(response, status, xhr)
    {
        if (status == "success")
        {
                $('#loading_user_data').html('&nbsp;');
//                    $("#user_tasks_list").show('slow');
        }
    });
}
</script>
<script src="../chart/js/highcharts.js"></script>
<script src="../chart/js/modules/exporting.js"></script>

<script>
function column()
{
    $('#loading_user_data').html('<center><img src="../img/loading.gif"></center>');
    $("#user_tasks_list").load("../chart/column.php", {}, function(response, status, xhr) 
    {
        if (status == "success")
        {
                $('#loading_user_data').html('&nbsp;');
//                    $("#user_tasks_list").show('slow');
        }
    });
}
    
function column_line_with_target()
{
    $('#loading_user_data').html('<center><img src="../img/loading.gif"></center>');
    var from_date=$("#date_from").val();
    var to_date=$("#date_to").val();
    var report_view=$("#report_view").val();
    var team=$("#team").val();
    var task=$("#task").val();
    var user=$("#user").val();
    var title=$("#chart_title").val();
    var x_axis=$("#x_axis").val();
    var y_axis=$("#y_axis").val();
         
    $.ajax({
        type: "POST",
        url: "../chart/column_line.php",
        dataType: "json", 
        data: "from_date="+ from_date + "&to_date=" + to_date + "&report_view="+ report_view + "&team="+ team + "&task=" + task + "&user=" + user + "&title=" + title + "&x_axis=" + x_axis + "&y_axis=" + y_axis,
        success: function(msg)
        {
            $('#loading_user_data').html(msg);
            if(msg['result_status']=="F")
            {
                $('#loading_user_data').html("<center><font color=#ff0000>"+msg['result_msg']+"</font></center>");
            }
            else
            {
                new Highcharts.Chart(msg);
            }
        }
    });
}
</script>

<?php
include_once '../includes/footer.php';
?>

<!-- Date picker files and function start-->
<link rel="stylesheet" href="../css/jquery-ui.css">
<script src="../js/jquery-ui.js"></script>
<script>
$(function() {
$("#date_from").datepicker({
changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        onClose: function(selectedDate) {
        $("#date_to").datepicker("option", "minDate", selectedDate);
        }
});
        $("#date_to").datepicker({
changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        onClose: function(selectedDate) {
        $("#date_from").datepicker("option", "maxDate", selectedDate);
        }
});
});
</script>
<!-- Date picker files and function end-->

<div id="myModal_warning" class="reveal-modal tiny" data-reveal>
    <center><p class="lead">Chart creation in progress!</p></center>
  <a class="close-reveal-modal">&#215;</a>
</div>

<script>
//$(document).foundation();
//$('#myModal_warning').foundation('reveal', 'open');
//$('#myModal_warning').foundation('reveal', 'close');
//alert("Please complete the incompleted dates!. until date picker not enable!");    
</script>
