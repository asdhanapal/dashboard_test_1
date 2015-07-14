<?php
include_once '../includes/header_sda.php';
include_once './data_page.php';
?>
<br>
<div class="row">
    <div class="large-12 columns">
        <div class="panel">
            <center><span class="msgs"></span></center>
            <form id="form_mapping_builds" data-abide onsubmit="return add_value();">
            <div class="row">
                <div class="large-9 columns">
                    <div class="row">
                        <div class="large-2 columns">
                            <label>Date
                                <input type="text" id="date" value="<?php //echo date('Y-m-d', $_SERVER['REQUEST_TIME']);?>" onchange="filter_1();" required>
                                <small class="error">Date is required!</small></label>
                        </div>
                        <div class="large-2 columns">
                            <label>Team
                                <select id="team" onchange="load_releases(this.value); filter_1();"  required>
                                    <option value=""> Loading...</option>
                                </select><small class="error">Team is required!</small></label>
                        </div>
                        <div class="large-2 columns">
                            <label>Release
                                <select id="release" required onchange="filter_1();">
                                    <option value="" disabled=""> -- Select team first --</option>
                                </select><small class="error">Release is required!</small></label>
                        </div>
                        <div class="large-2 columns">
                            <label>Task
                                <select id="task" required>
                                    <option value=""> -- Select --</option>
                                    <option value="1">Test case imported</option>
                                </select><small class="error">Required field!</small></label>
                        </div>
                        <div class="large-2 columns">
                            <label>Mode
                                <select id="mode" required onchange="filter_1();">
                                    <option value=""> -- Select --</option>
                                    <option value="1">Jira to tc</option>
                                    <option value="2">New test case</option>
                                </select><small class="error">Required field!</small></label>
                        </div>
                        <div class="large-2 columns">
                            <label>Count
                                <input type="number" id="count" value="" required>
                                <small class="error">Count is required!</small></label>
                        </div>
                    </div>
                </div>
                
                <div class="large-3 columns">
                    <div class="row">
                        <div class="large-6 columns">
                        <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="submit" class="tiny button" value="Submit" name="submit" id="submit_1">
                            <input type="Reset" class="tiny button" value="Reset" name="reset" id="reset"  onmouseout="filter_1();" title="Move the mouse out to view all records." data-tooltip aria-haspopup="true" class="tip-top">
                        </label>
                        </div>
                        <div class="large-6 columns">
                            <br>
                            <label>Reset the values to view all entries</label>
<!--                            <a href="#">Manage build</a><br><br>
                            <a href="#">Unmapped builds</a>-->
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <div class="large-12 columns">
        <div class="callout panel" style="padding: 20px 2px 20px 20px;">
            <span id="loading_user_data"></span>
            <div id="user_tasks_list" style="height: 400px; overflow-y: auto;"></div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function () {
        load_teams();
        filter_1();
    });

    function load_teams()
    {
        $.ajax({
            type: "POST",
            url: "./load_teams.php",
            success: function (msg)
            {
                var html = $.trim(msg);
                $("#team").html(html);
            }
        });
    }

    function load_releases(id)
    {
        $.ajax({
            type: "POST",
            url: "../controller/show_releases.php",
            data: "team_id=" + id,
            success: function (msg)
            {
                var html = $.trim(msg);
                $("#release").html(html);
            }
        });
    }

    function add_value()
    {
        var date=$('#date').val();
        var team=$('#team').val();
        var release=$('#release').val();
        var task=$('#task').val();
        var mode=$('#mode').val();
        var count=$('#count').val();
        if(date=="" || team=="" || release=="" || task=="" || mode=="" || count=="")
        {
             $('.msgs').html('<font color=red>Fields cant be empty!</font>');
             return false;
        }
        
        var data={date:date,team:team,release:release,task:task,mode:mode,count:count}
        $.ajax({
            type: "POST",
            url: "../controller/add_tc_addition.php",
            dataType: "json", 
            data: data,
            success: function(msg)
            {
                if(msg[0]=='F')
                {
                    filter_1();
                    $(".msgs").hide().html("<font color=red'>"+msg[1]+"</font>").fadeIn('slow');
                }
                else
                {
                    filter_1();
                    $(".msgs").hide().html("<font color=#41A868'>"+msg[1]+"</font>").fadeIn('slow');
                }
            }
        });
        return false;
    }
    
    function filter_1()
    {
        var date=$('#date').val();
        var team=$('#team').val();
        var release=$('#release').val();
        var task=$('#task').val();
        var mode=$('#mode').val();
        $('#loading_user_data').html('<center><img src="../img/loading.gif"></center>');
        $("#user_tasks_list").load("../controller/list_tc_additions.php", { "date": date, "team": team,"release":release,"task":task,"mode":mode}, function (response, status, xhr) {
            if (status == "success")
            {
                $('#loading_user_data').html('');
                $("#user_tasks_list").show('slow');
            }
        });
    }
    
    function delete_entry(id)
    {
         if(confirm('Are you sure?'))
        {
            $.ajax({
                type: "POST",
                url: "../controller/delete_tc_addition.php?action=del_rel",
                data: "id="+id,
                success: function(msg)
                {
                    var html = $.trim(msg);
                    $('.msgs').html("<center>"+msg+"</center>");
                    filter_1();
                }
            });
            return false;
        }
    }
    
    function reset_1()
    {
        alert("Not working");
        $('#date').val('');
        $('#team').val('');
        $('#release').val('');
        $('#task').val('');
        $('#mode').val('');
        
    }
</script>

<?php
include_once '../includes/footer.php';
?>

<!-- Date picker files and function start-->
<link rel="stylesheet" href="../css/jquery-ui.css">
<script src="../js/jquery-ui.js"></script>
<script>
    $(function () {
        $("#date").datepicker({
            changeMonth: true,
            changeYear: true,
            showOtherMonths: true,
            selectOtherMonths: true,
            dateFormat: "yy-mm-dd"
        });
    });
</script>
<!-- Date picker files and function end-->