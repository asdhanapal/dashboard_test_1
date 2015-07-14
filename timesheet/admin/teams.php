<?php
include_once '../includes/header_admin.php';
include_once './data_page.php';
//print_r($_SESSION);
?>
<div class="row">
    <div class="large-12 columns">
        <form id="form_user_tasks" data-abide>
        <div class="panel">
            <center><span class="msgs"></span></center>
            
            <div class="row">

                <div class="small-9 columns">

                    <div class="small-2 columns">
                        <label><input type="radio" name="add_team" id="add_team" onclick="show_team();" checked>&nbsp;Add Team</label>
                    </div>
                    <div class="small-2 columns">
                        <label><input type="radio" name="add_team" id="add_task" onclick="show_task();">&nbsp;Add Task</label>
                    </div>
                    <div class="small-2 columns">
                        <label><input type="radio" name="add_team" id="add_st" onclick="show_st();">&nbsp;Add Sub task</label>
                    </div>
                    <div class="small-2 columns">
                        <label><input type="radio" name="add_team" id="add_td" onclick="show_td();">&nbsp;Add Task desc</label>
                    </div>
                    <div class="small-8 columns"></div>
                </div>
            </div>
            
            <div class="row" id="show_team">
                <div class="small-2 columns">
                    <input type="text" name="txt_team" id="txt_team" placeholder="Team name" class="tiny">
                </div>
                <div class="small-2 columns">
                    <input type="button" class="button tiny" value="ADD" onclick="adding_teams();">
                </div>
                <div class="small-10 columns"></div>
            </div>
            
            <div class="row" id="show_task">
                <div class="small-2 columns">
                    <select id="team_1" required>
                        <option> Loading...</option>
                    </select>
                    <small class="error">Team is required!</small>
                </div>
                <div class="small-2 columns">
                    <input type="text" name="txt_task" id="txt_task" placeholder="Task name" class="tiny">
                </div>
                <div class="small-2 columns"><br>
                    <label>Have sub task?&nbsp;<input type="checkbox" name="decision_st" id="decision_st"></label>
                    </div>
                <div class="small-2 columns">
                    <br>
                    <label>Is operational task?&nbsp;<input type="checkbox" name="decision_ot" id="decision_ot"></label>
                    </div>
                
                
                <div class="small-2 columns">
                    <input type="button" class="button tiny" value="ADD" onclick="adding_tasks();">
                </div>
                <div class="small-10 columns"></div>
            </div>

           <div class="row" id="show_st">
                <div class="small-2 columns">
                    <select id="team_2" onchange=" load_tasks('task_1','team_2');" required>
                        <option> Loading...</option>
                    </select>
                    <small class="error">Team is required!</small>
                </div>
               <div class="small-2 columns">
                   <select id="task_1" onchange="complexity();"  required >
                        <option value=""> -- Select team first --</option>
                    </select>
                    <small class="error">Task is required!</small>
                </div>
                <div class="small-2 columns">
                    <input type="text" name="txt_stask" id="txt_stask" placeholder="Sub task" class="tiny">
                </div>
                <div class="small-2 columns" id="complexity_deside">
                    <select id="task_pr" required>
                        <option value="0"> -- Select complexity-- </option>
                        <option value="1">LC</option>
                        <option value="2">MC</option>
                        <option value="3">HC</option>
                    </select>
                    <small class="error">Complexity is required!</small>
                </div>
    
                <div class="small-2 columns">
                    <input type="button" class="button tiny" value="ADD" onclick="adding_sub_tasks();">
                </div>
                <div class="small-10 columns"></div>
            </div>
            
           <div class="row" id="show_td">
                <div class="small-2 columns">
                    <select id="team_3" onchange=" load_tasks('task_2','team_3');" required>
                        <option> Loading...</option>
                    </select>
                    <small class="error">Team is required!</small>
                </div>
               <div class="small-2 columns">
                   <select id="task_2" onchange="load_sub_tasks(this.value);" required>
                        <option value=""> -- Select team first --</option>
                    </select>
                    <small class="error">Task is required!</small>
                </div>
               
                <div class="large-2 columns">
                    <select id="sub_task" required>
                        <option value=""> -- Select task first --</option>
                    </select>
                    <small class="error">Sub task is required!</small>
                </div>
                <div class="small-2 columns">
                    <input type="text" name="txt_td" id="txt_td" placeholder="Task description" class="tiny">
                </div>
                <div class="small-2 columns">
                    <input type="button" class="button tiny" value="ADD" onclick="adding_tds();">
                </div>
                <div class="small-10 columns"></div>
            </div> 
            
        </div>
</form>

        <div class="row">
            <div class="large-12 columns">
                <div class="callout panel">
                    <span id="loading_user_data"></span>
                    <div id="user_tasks_list">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
            filter();
            show_team();
        //show_task();
            load_teams("team_1");
            complexity();
   });

function complexity()
{
        var s_text=$( "#task_1 option:selected" ).text();
        if(s_text=="Test Run" || s_text=="Test Case Execution" || s_text=="Test Execution" )
        {
            $('#complexity_deside').show();
        }
        else
        {
            $('#complexity_deside').hide();
        }
}
    function load_teams(team_id)
    {
        $.ajax({
            type: "POST",
            url: "./load_teams.php",
            success: function(msg)
            {
                var html = $.trim(msg);
                $("#"+team_id).html(html);
            }
        });
    }
    
    function load_tasks(task_id,team)
    {
        var id = $("#"+team).val();
        $.ajax({
            type: "POST",
            url: "./load_tasks_wo_cf.php",
            data: "team_id=" + id,
            success: function(msg)
            {
                var html = $.trim(msg);
                $("#"+task_id).html(html);
            }
        });
    }
    
    function load_sub_tasks(task_id)
    {
        $.ajax({
            type: "POST",
            url: "./load_sub_tasks.php",
            data:"task_id="+task_id,
            success: function(msg)
            {
                var html = $.trim(msg);
                $("#sub_task").html(html);
            }
        });
    }
    
    function adding_teams()
    {
        alert("Under maintenance");
        return false;
        var team=$('#txt_team').val();
        $('.msgs').html('<img src="../img/loading.gif">');
        if(team=="")
        {
             $('.msgs').html('<font color=red>Please fill all fields!</font>');
             return false;
        }
        $.ajax({
            type: "POST",
            url: "./add_teams.php",
            dataType: "json", 
            data: "team=" + team,
            success: function(msg)
            {
                if(msg[0]=='F')
                {
                     filter();
                    $(".msgs").hide().html("<font color=red'>"+msg[1]+"</font>").fadeIn('slow');
                }
                else
                {
                     filter();
                    $(".msgs").hide().html("<font color=green'>"+msg[1]+"</font>").fadeIn('slow');
                }
            }
        });
    }
    
    function adding_tasks()
    {
        alert("Under maintenance");
        return false;
        var team=$('#team_1').val();
        var task=$('#txt_task').val();
        
        if($('#decision_st').is(":checked"))
            var st_status=1;
        else
            var st_status=0;

        if($('#decision_ot').is(":checked"))
            var ot_status=1;
        else
            var ot_status=0;
        
        $('.msgs').html('<img src="../img/loading.gif">');
        
        if(team=="" || task=="")
        {
             $('.msgs').html('<font color=red>Please fill all fields!</font>');
             return false;
        }

        $.ajax({
            type: "POST",
            url: "./adding_tasks.php",
            dataType: "json", 
            data: "team=" + team+"&task=" + task+"&st_status="+st_status+"&ot_status="+ot_status,
            success: function(msg)
            {
                if(msg[0]=='F')
                {
                     filter();
                    $(".msgs").hide().html("<font color=red'>"+msg[1]+"</font>").fadeIn('slow');
                }
                else
                {
                     filter();
                    $(".msgs").hide().html("<font color=green'>"+msg[1]+"</font>").fadeIn('slow');
                }
            }
        });
    }
    
    function adding_sub_tasks()
    {
        alert("Under maintenance");
        return false;
        var team=$('#team_2').val();
        var task=$('#task_1').val();
        var stask=$('#txt_stask').val();
        
        var s_text=$( "#task_1 option:selected" ).text();
       // var testrun = new Array( "Test Run", "Test Execution", "test run","Testrun" );
        //if(jQuery.inArray( s_text, testrun )==0)
if(s_text=="Test Run" || s_text=="Test Case Execution" || s_text=="Test Execution" )
        {
            var complex=$('#task_pr').val();
            if(complex=="")
            {
                alert("Select the complexity!");
                return false;
            }
        }
        else
        {
            var complex=0;
        }

        
        $('.msgs').html('<img src="../img/loading.gif">');
        if(team=="" || task=="" ||  stask=="")
        {
             $('.msgs').html('<font color=red>Please fill all fields!</font>');
             return false;
        }

        $.ajax({
            type: "POST",
            url: "./adding_stasks.php",
            dataType: "json", 
            data: "team=" + team+ "&task=" + task + "&stask="+stask + "&complex=" + complex,
            success: function(msg)
            {
                if(msg[0]=='F')
                {
                     filter();
                    $(".msgs").hide().html("<font color=red'>"+msg[1]+"</font>").fadeIn('slow');
                }
                else
                {
                     filter();
                    $(".msgs").hide().html("<font color=green'>"+msg[1]+"</font>").fadeIn('slow');
                }
            }
        });
    }
    
    
    function adding_tds()
    {
        alert("Under maintenance");
        return false;
        var team=$('#team_3').val();
        var task=$('#task_2').val();
        var stask=$('#sub_task').val();
        var td=$('#txt_td').val();
        $('.msgs').html('<img src="../img/loading.gif">');
        if(team=="" || task=="" ||  stask=="" || td=="")
        {
             $('.msgs').html('<font color=red>Please fill all fields!</font>');
             return false;
        }

        $.ajax({
            type: "POST",
            url: "./adding_tds.php",
            dataType: "json", 
            data: "team=" + team+"&task=" + task + "&stask="+stask + "&td="+td,
            success: function(msg)
            {
                if(msg[0]=='F')
                {
                     filter();
                    $(".msgs").hide().html("<font color=red'>"+msg[1]+"</font>").fadeIn('slow');
                }
                else
                {
                     filter();
                    $(".msgs").hide().html("<font color=green'>"+msg[1]+"</font>").fadeIn('slow');
                }
            }
        });
    }
    
    function filter()
    {
        $('#user_tasks_list').html('<center>Loading...</center>');
        $('#loading_user_data').html('<center><img src="../img/loading.gif"></center>');
        $( "#user_tasks_list" ).load( "load_team_manage.php",  {},function( response, status, xhr ) {
            if ( status == "success" ) 
            {
                $('#loading_user_data').html('');
            }
        });
    }

function show_team()
{
    $("#show_task").hide();
    $("#show_st").hide();
    $("#show_td").hide();
    $("#show_team").show('slow');
}

function show_task()
{
    load_teams("team_1");
    $("#show_team").hide();
    $("#show_st").hide();
    $("#show_td").hide();
    $("#show_task").show('slow');

}

function show_st()
{
    load_teams("team_2");
    $("#show_task").hide();
    $("#show_team").hide();
    $("#show_td").hide();
    $("#show_st").show('slow');
}

function show_td()
{
    load_teams("team_3");
    $("#show_task").hide();
    $("#show_st").hide();
    $("#show_team").hide();
    $("#show_td").show('slow');
}
</script>
<!--<button>Test </button>
<p>Test content for check all jquery lib are included or not?</p>
<p>Test content for all css and foundation included or not?</p>-->
<?php
include_once '../includes/footer.php';
?>
