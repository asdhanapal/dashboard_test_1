<?php
include_once '../includes/header_sda.php';
include_once './data_page.php';
?>
<br>
<div class="row">
    <div class="large-12 columns">
        <div class="panel">
            <center><span class="msgs"></span></center>
            <form id="form_audit" data-abide>
            <div class="row">
                <div class="large-10 columns">
                    <div class="row">
                        <div class="large-2 columns">
                            <label>Date*
                                <input type="text" id="date" value="" onchange="filter_1();" required>
                            <small class="error">Date is required!</small></label>
                        </div>
                        <div class="large-2 columns">
                            <label>Team*
                                <select id="team" onchange="load_users(this.value); load_tasks(this.value); filter_1();" required>
                                    <option value=""> Loading...</option>
                                </select>
                            <small class="error">Team is required!</small></label>
                        </div>
                        <div class="large-2 columns">
                            <label>User*
                                <select id="user" required onchange="filter_1();">
                                    <option value="" disabled=""> -- Select team first --</option>
                                </select>
                            <small class="error">Build is required!</small></label>
                        </div>
                        <div class="large-2 columns">
                            <label>Task*
                                <a href="../controller/manage_audit_tasks.php" target="Dhanapal"><span style="float: right;"><img src="../img/b_plus.png" data-tooltip aria-haspopup="true" class="tip-top" title="Click here to manage tasks"></span></a>
                                <select id="task" required onchange="filter_1();">
                                    <option value="" disabled=""> -- Select team first --</option>
                                </select>
                            <small class="error">Task is required!</small></label>
                        </div>
                        <div class="large-2 columns">
                            <label>No. of Audits*
                                <input type="number" name="audit" value="" id="audit" placeholder="No. of Audit Count" required>
                                <small class="error">Count is required!</small>
                                </label>
                        </div>
                        <div class="large-2 columns">
                            <label>No. of Misses*
                                <input type="number" name="misses" value="" id="misses" placeholder="No. of Miisses Count" required>
                                <small class="error">Count is required!</small>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="large-2 columns">
                    <div class="row">
                        <div class="large-10 columns">
                            <label>Impact*
                                <select id="impact" required onchange="filter_1();">
                                    <option value="" disabled="" selected=""> -- Select --</option>
                                    <option value="1">High</option>
                                    <option value="2">Medium</option>
                                    <option value="3">Low</option>
                                </select>
                                <small class="error">Impact required!</small>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
                
            <div class="row">
                <div class="large-10 columns">
                    <div class="row">
                        <div class="large-6 columns">
                            <label>Comments*
                                <textarea id="comments" required placeholder=""></textarea>
                                <small class="error">Comments required!</small>
                            </label>
                        </div>
                        <div class="large-6 columns">
                            <label>Good Catches*
                                <textarea id="good_catches" required placeholder=""></textarea><small class="error">Good catches required!</small>
                            </label></label>
                        </div>
                    </div>
                </div>
                
                <div class="large-2 columns">
                    <div class="row">
                        <div class="large-12 columns">
                            <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                <input type="button" class="small button" value="Submit" onclick="add_record();" name="submit">
                                <input type="Reset" class="small button" value="Reset" name="reset" id="reset"  onmouseout="filter_1();" title="Move the mouse out to view all records." data-tooltip aria-haspopup="true" class="tip-top">
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

    function load_users(id)
    {
        $.ajax({
            type: "POST",
            url: "../controller/load_users_team_based.php",
            data: "team=" + id,
            success: function (msg)
            {
                var html = $.trim(msg);
                $("#user").html(html);
            }
        });
    }
    
    function load_tasks(id)
    {
        $.ajax({
            type: "POST",
            url: "../controller/load_audit_tasks.php",
            data: "team=" + id,
            success: function (msg)
            {
                var html = $.trim(msg);
                $("#task").html(html);
            }
        });
    }

    function add_record()
    {
        var date=$('#date').val();
        var team=$('#team').val();
        var user=$('#user').val();
        var task=$('#task').val();
        var audit=$('#audit').val();
        var misses=$('#misses').val();
        var impact=$('#impact').val();
        var comments=$('#comments').val();
        var good_catches=$('#good_catches').val();
//        alert(user);
        if(date=="" || team=="" || user=="" || audit=="" || task=="" || misses=="" || impact=="" || comments=="" || good_catches=="")
        {
             $('.msgs').html('<font color=red>Fields cant be empty!</font>');
             return false;
        }
        
        var data={date:date,team:team,user:user,audit:audit,task:task,misses:misses,impact:impact,comments:comments,good_catches:good_catches};
        $.ajax({
            type: "POST",
            url: "../controller/add_audit.php",
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
    }
    
    function filter_1()
    {
        var date = $('#date').val();
        var team = $('#team').val();
        var user = $('#user').val();
        var task = $('#task').val();
        var impact = $('#impact').val();
        $('#loading_user_data').html('<center><img src="../img/loading.gif"></center>');
        $("#user_tasks_list").load("../controller/load_exist_audits.php", { "date": date, "team": team,"user":user,"task":task,"impact":impact}, function (response, status, xhr) {
            if (status == "success")
            {
                $('#loading_user_data').html('');
                $("#user_tasks_list").show('slow');
            }
        });
    }
    
    function delete_audit(id)
    {
         if(confirm('Are you sure?'))
        {
            $.ajax({
                type: "POST",
                url: "../controller/delete_audit.php?action=del_rel",
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
</script>

<?php
include_once '../includes/footer.php';
?>

<!--<div id="add_audit_task" class="reveal-modal medium" data-reveal>
    <a class="close-reveal-modal">&#215;</a>
</div>
<script>
$(document).foundation();
</script>-->

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
