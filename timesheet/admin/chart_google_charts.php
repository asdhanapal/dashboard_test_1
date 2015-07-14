<?php
include_once '../includes/header_admin.php';
include_once './data_page.php';
//print_r($_SESSION);
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
                    <select id="team" onchange="load_tasks();" multiple="5" onfocusout="do_view();">
                        <option> Loading...</option>
                    </select>
                </div>

                <div class="large-2 columns">
                    <label>Task</label>
                    <select id="task" multiple="5" onfocusout="do_view();" >
                        <option value="" disabled=""> -- Select team first --</option>
                    </select>
                </div>

                <div class="large-2 columns">
                    <label>User</label>
                    <select id="user"  multiple="5" onfocusout="do_view();">
                        <option value=""> Loading...</option>
                    </select>
                </div>
                
                <div class="large-1 columns">
                    <label>Trend for</label>
                    <input type="radio"  checked="" name="trend_view" value="1" onfocusout="do_view();"><label>Workunit</label><br>
                    <input type="radio" name="trend_view" value="2" onfocusout="do_view();"><label>Count</label><br>
                    <input type="radio" name="trend_view" value="3" onfocusout="do_view();"><label>Time</label>
                </div>
                
<!--                <div class="large-1 columns">
                    <label>View</label>
                    
                    
                    <input type="radio"  checked="" name="report_view" value="1" onchange="do_view();"><label>Over all</label><br>
                    <input type="radio" name="report_view" value="2" onchange="do_view();"><label>Monthly</label><br>
                    <input type="radio" name="report_view" value="3" onchange="do_view();"><label>one more input here</label>
                </div>-->

                <div class="large-4 columns">
                    <div class="row">
                        <div class="large-12 columns">
                            <div class="row">
                                
                                <div class="large-3 columns">
                                    <label>View type</label>
                                    <select id="report_view" onfocusout="do_view();">
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
                                    <input type="text" id="y_axis" onfocusout="do_view();">
                                </div>
                            </div>

                            <div class="row">
                                
                                <div class="large-3 columns">
                                    <label>&nbsp;</label>
                                    <input type="radio" id="Pie" name="c_type" value="pie" onchange="pie();">&nbsp;Combo
                                </div>
                                
                                <div class="large-3 columns">
                                    <label>&nbsp;</label>
                                    <input type="radio" id="Pie" name="c_type" value="pie" onchange="pie();">&nbsp;Pie
                                </div>

                                <div class="large-3 columns">
                                    <label>&nbsp;</label>
                                    <input type="radio" id="line" name="c_type" value="line" onchange="line();" checked="">&nbsp;Line
                                </div>

                                <div class="large-3 columns">
                                    <label>&nbsp;</label>
                                    <input type="radio" id="Column" name="c_type" value="column" onchange="column();">&nbsp;Column
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
                    <span id="loading_user_data">&nbsp;</span>
                    <div id="user_tasks_list">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
//    $("#compare_module").hide();
    $(function() {
        load_teams();
        load_users();
        line();
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
                $("#task").html(html);
            }
        });
    }

    function load_users()
    {
        $.ajax({
            type: "POST",
            url: "./load_users_all.php",
            success: function(msg)
            {
                var html = $.trim(msg);
                $("#user").html(html);
            }
        });
    }
    
    function do_view()
    {
        var report_view=$('input[name=c_type]:checked').val();
        window[report_view]();
    }
</script>

<script src="../js/jquery.js"></script>

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
//                    $("#user_tasks_list").show('slow');
$(function () {
    $('#container').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: 1,//null,
            plotShadow: false
        },
        title: {
            text: text
        },
        subtitle: {
            text: 'Based on users',
            x: -20
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Total work units',
            data: [
                ['User1',   25.0],
                ['User2',       26.8],
                {
                    name: 'User3',
                    y: 12.8,
                    sliced: true,
                    selected: true
                },
                ['User4',    8.5],
                ['User5',     16.2],
                ['User6',   10.7]
            ]
        }]
    });
});


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
$(function () {
    $('#container').highcharts({
        title: {
            text: title,
            x: -20 //center
        },
        subtitle: {
            text: 'Time sheet',
            x: -20
        },
        xAxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        },
        yAxis: {
            title: {
                text: 'Work units'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },


    yAxis: {
        min: 0,
        max: 30,
        
        title: {
                text: 'Work units'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
    },

        tooltip: {
            valueSuffix: '°C'
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: [{
            name: 'User 1',
            data: [7.0, 6.9, 9.5, 0, 18.2, 21.5, 25.2, 26.5, 23.3, 18.3, 13.9, 9.6]
        }, {
            name: 'User 2',
            data: [0.2, 0.8, 5.7, 11.3, 17.0, 22.0, 24.8, 24.1, 20.1, 14.1, 8.6, 2.5]
        }, {
            name: 'User 3',
            data: [0.9, 0.6, 3.5, 8.4, 13.5, 17.0, 18.6, 17.9, 14.3, 9.0, 3.9, 1.0]
        }, {
            name: 'User 4',
            data: [3.9, 4.2, 5.7, 8.5, 11.9, 15.2, 17.0, 16.6, 14.2, 10.3, 6.6, 4.8]
        }]
    });
});

            }
        });
    }
</script>

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
$(function () {
    $('#container').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Yearly work units: 2014'
        },
        subtitle: {
            text: 'User wise report'
        },
        xAxis: {
            categories: [
                'Jan',
                'Feb',
                'Mar',
                'Apr',
                'May',
                'Jun',
                'Jul',
                'Aug',
                'Sep',
                'Oct',
                'Nov',
                'Dec'
            ]
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Work units'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f} </b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'User 1',
            data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4]

        }, {
            name: 'User 2',
            data: [83.6, 78.8, 98.5, 93.4, 106.0, 84.5, 105.0, 104.3, 91.2, 83.5, 106.6, 92.3]

        }, {
            name: 'user 3',
            data: [48.9, 38.8, 39.3, 41.4, 47.0, 48.3, 59.0, 59.6, 52.4, 65.2, 59.3, 51.2]

        }, {
            name: 'User 4',
            data: [42.4, 33.2, 34.5, 39.7, 52.6, 75.5, 57.4, 60.4, 47.6, 39.1, 46.8, 51.1]

        }]
    });
});

            }
        });
    }
</script>



<?php
include_once '../includes/footer.php';
?>

<script src="../chart/js/highcharts.js"></script>
<script src="../chart/js/modules/exporting.js"></script>

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
$(document).foundation();
$('#myModal_warning').foundation('reveal', 'open');
$('#myModal_warning').foundation('reveal', 'close');
//alert("Please complete the incompleted dates!. until date picker not enable!");    
</script>
