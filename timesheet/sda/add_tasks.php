<?php
include_once '../includes/header_sda.php';
include_once '../includes/date_picker.php';
require_once '../classes/db.class.php';
$conn = new db();
$dbcon = $conn->dbConnect();
//print_r($_SESSION);

$pending_dates = array();
$curret_user_id = $_SESSION['sda_id'];
$tot_hrs = 0;
$holidays = array();
$query = "SELECT date FROM amz_holidays where status='1' AND deleted='0'";
$result = $conn->runsql($query, $dbcon);
while ($row = mysqli_fetch_object($result))
    $holidays[] = $row->date;

$query = "SELECT user_id,user_mail,last_entry_on,create_date FROM amz_login where user_id='$curret_user_id'";
$result = $conn->runsql($query, $dbcon);
while ($row = mysqli_fetch_object($result)) 
{
    $current_user = $row->user_id;
    $last_entry_date = $row->last_entry_on == "" ? $row->create_date : $row->last_entry_on;
    $today_date = date('Y-m-d', $_SERVER['REQUEST_TIME']);
    $aryDates = createDateRangeArray($last_entry_date, $today_date);
    $size = sizeof($aryDates);
    
    for ($i = 0; $i < $size; $i++) 
    {
        if (!in_array($aryDates[$i], $holidays)) 
        {
            $single_date = $aryDates[$i];
            $time_diff = getTimes($single_date, $today_date);
            if ($time_diff >=24) 
            {
                $tot_hrs=0;
                $query_1 = "SELECT time FROM user_tasks WHERE date='$single_date' AND user_id='$current_user'";
                $result_1 = $conn->runsql($query_1, $dbcon);
                if (mysqli_num_rows($result_1)) 
                {
                    while ($result_row_1 = mysqli_fetch_object($result_1))
                    {
                        //$tot_hrs = $tot_hrs + $result_row_1->time;
                        $tot_hrs+= strtotime($result_row_1->time)-strtotime("00:00:00");
                    }
                    //echo $tot_hrs;
                    if ( $tot_hrs < 28800)
                    {
                       $pending_dates[] = $single_date;
                    }
               }
               else 
               {
                     $pending_dates[] = $single_date;
               }
            }
        }
    }
}

 //print_r($pending_dates);
function getTimes($t1, $t2) 
{
    $timeFirst = strtotime($t1);
    $timeSecond = strtotime($t2);
    $differenceInSeconds = $timeSecond - $timeFirst;
    $h = 0;
    $m = floor($differenceInSeconds / 60);
    $s = $differenceInSeconds % 60;
    if ($m >= 60) 
    {
        $h = floor($m / 60);
        $m = $m % 60;
    }
    //$tim = $h.':'.$m.':'.$s;
    $tim = $h;
    return $tim;
}

function createDateRangeArray($strDateFrom, $strDateTo) 
{
    $aryRange = array();
    $iDateFrom = mktime(1, 0, 0, substr($strDateFrom, 5, 2), substr($strDateFrom, 8, 2), substr($strDateFrom, 0, 4));
    $iDateTo = mktime(1, 0, 0, substr($strDateTo, 5, 2), substr($strDateTo, 8, 2), substr($strDateTo, 0, 4));
    if ($iDateTo >= $iDateFrom) 
    {
        array_push($aryRange, date('Y-m-d', $iDateFrom)); // first entry
        while ($iDateFrom < $iDateTo)
        {
            $iDateFrom+=86400; // add 24 hours
            array_push($aryRange, date('Y-m-d', $iDateFrom));
        }
    }
    return $aryRange;
}
?>

<br>
<div class="row">
    <div class="large-12 columns">
        <form id="form_user_tasks" data-abide>
        <div class="panel">
            <center><span class="msgs"></span></center>
            
            <?php if($_SESSION['sda_team_count']>1) { ?>
            <input type="hidden" name="judge" id="judge" value="1">

            <div class="row">
                <div class="small-2 columns">
                    <label>Select Date&nbsp;<font color="red">*</font></label>
                    <input type="text" id="date" name="date" onchange="filter(); check_remaining_hrs(this.value);" placeholder="yyyy-mm-dd" required data-tooltip aria-haspopup="true" class="tip-top" title="Click inside and select date from the date picker" readonly="" value="<?php
                                if (empty($pending_dates))
                                    echo date('Y-m-d', $_SERVER['REQUEST_TIME']);
                                else
                                    echo $pending_dates[0];
                                ?>"
                                <?php if (!empty($pending_dates)) {
                                    ?>
                                onclick="show_alert();"
                                        <?php
                                }
?>>
                     <small class="error">Date is required</small>
                </div>
                <div class="small-2 columns">
                    <label>Select Team&nbsp;<font color="red">*</font></label>
                    <select id="team" name="team" onchange="load_tasks(); load_builds();" required>
                        <option value=""> Loading...</option>
                    </select>
                    <small class="error">Team is required!</small>
                </div>
                <div class="small-2 columns">
<!--                    <span id="sub_task_space">&nbsp;</span>-->
                    <div id="sub_tas">
                    <label>Sub task&nbsp;<font color="red">*</font></label>
                    <select id="sub_task" name="sub_task" required>
                        <option value=""> -- Select sub task --</option>
                    </select>
                    <small class="error" id="sub_error">Sub task is required!</small></div>
                </div>
                <div class="small-2 columns">
                    <label>Count</label>
                    <input type="number" min="1" id="qty" placeholder="Count" name="qty" required data-tooltip aria-haspopup="true" class="tip-top" title="For example: 10" value="0">
                </div>
                <div class="small-2 columns">
<!--                    <span id="noofdevice_space">&nbsp;</span>-->
                    <div id="noofdevice_div">
                        <label>No. of Devices/Platforms:&nbsp;<font color="red">*</font></label>
                        <select id="noofdevice" name="noofdevice" required>
                            <?php
                                $limit=6;
                                for($i=1;$i<=$limit;$i++) { ?>
                                    <option value="<?php echo $i;?>" <?php if($i==4) echo ' selected'?>><?php echo $i?></option>
                                <?php }?>
                        </select>
                        <small class="error">Device count required!</small>
                    </div>
                </div>
                <div class="small-2 columns" align="right">
                    <label>&nbsp;</label>
                </div>
            </div>
            
                <div class="row">
                <div class="small-2 columns">
                    <label>Select Build&nbsp;<font color="red">*</font></label>
                    <select id="build" name="build" required>
                        <option value=""> -- Select team first --</option>
                    </select>
                    <small class="error">Build is required!</small>
                </div>
                <div class="small-2 columns">
                    <label>Select Task&nbsp;<font color="red">*</font></label>
                    <select id="task" name="task" onchange="load_sub_tasks(this.value);" required>
                        <option value="" disabled=""> -- Select task --</option>
                    </select>
                    <small class="error">Task is required!</small>
                </div>
                <div class="small-2 columns">
<!--                    <span id="task_des_space">&nbsp;</span>-->
                    <div id="task_des">
                    <label>Task desc.&nbsp;<font color="red">*</font></label>
                    <select id="task_desc" name="task_desc" required >
                        <option value=""> -- Select task desc --</option>
                    </select>
                    <small class="error" id="task_des_error">Task desc is required!</small>
                    </div>
                </div>
                <div class="small-2 columns">
                    <label>Time&nbsp;<font color="red">*</font></label>
                    <input type="text" id="time" placeholder="HH:MM" name="time" required pattern="[0-0][0-8]:[0-5][0-9]+" data-tooltip aria-haspopup="true" class="tip-top" title="For example 08:00">
                    <small class="error">Can't leave empty! </small>
                </div>
                <div class="small-2 columns">
                    <label>Comments</label>
                    <textarea placeholder="Comments" id="cmds" name="cmds" ></textarea>
                </div>
                <div class="small-2 columns" align="right">
                    <label>&nbsp;</label>
                    <input type="submit" class="small button" value="Submit" onclick="add_task();" name="submit">
                    <input type="button" class="small button left-align" value="Reset">
                </div>
                </div>    

            <?php } else { ?>
            <input type="hidden" name="judge" id="judge" value="0">
                <input type="hidden" name="team" id="team" value="<?php echo $_SESSION['team_id'][0];?>">
            <div class="row">
                <div class="small-2 columns">
                    <label>Select Date&nbsp;<font color="red">*</font></label>
                    <input type="text" id="date" name="date" onchange="filter(); check_remaining_hrs(this.value);" placeholder="yyyy-mm-dd" required data-tooltip aria-haspopup="true" class="tip-top" title="Click inside and select the date from the date picker" readonly="" value="<?php
                                if (empty($pending_dates))
                                    echo date('Y-m-d', $_SERVER['REQUEST_TIME']);
                                else
                                    echo $pending_dates[0];
                                ?>">
                     <small class="error">Date is required</small>
                </div>
                       <div class="small-2 columns">
                    <label>Select Task&nbsp;<font color="red">*</font></label>
                    <select id="task" name="task" onchange="load_sub_tasks(this.value);" required>
                        <option value=""> Loading...</option>
                    </select>
                    <small class="error">Task is required!</small>
                </div>
                <div class="small-2 columns">
<!--                    <span id="task_des_space">&nbsp;</span>-->
                    <div id="task_des">
                    <label>Task desc.&nbsp;<font color="red">*</font></label>
                    <select id="task_desc" name="task_desc" required >
                        <option value=""> Select task desc</option>
                    </select>
                    <small class="error" id="task_des_error">Task desc is required!</small>
                    </div>
                </div>
                <div class="small-2 columns">
                    <label>Count</label>
                    <input type="number" min="1" id="qty" placeholder="Count" name="qty" required data-tooltip aria-haspopup="true" class="tip-top" title="For example: 10" value="0">
                </div>
                <div class="small-2 columns">
                    <label>Comments</label>
                    <textarea placeholder="Comments" id="cmds" name="cmds" ></textarea>
                </div>
                <div class="small-2 columns"></div>
            </div>
            
            <div class="row">
                <div class="small-2 columns">
                    <label>Select Build&nbsp;<font color="red">*</font></label>
                    <select id="build" name="build" required>
                        <option value=""> Loading...</option>
                    </select>
                    <small class="error">Build is required!</small>
                </div>
                <div class="small-2 columns">
<!--                    <span id="sub_task_space">&nbsp;</span>-->
                    <div id="sub_tas">
                        <label>Sub task&nbsp;<font color="red">*</font></label>
                        <select id="sub_task" name="sub_task" required>
                            <option value="">Select task first</option>
                        </select>
                        <small class="error" id="sub_error">Sub task is required!</small>
                    </div>
                </div>
                <div class="small-2 columns">
<!--                    <span id="noofdevice_space">&nbsp;</span>-->
                    <div id="noofdevice_div">
                        <label>No. of Devices/Platforms:&nbsp;<font color="red">*</font></label>
                        <select id="noofdevice" name="noofdevice" required>
                            <?php
                                $limit=6;
                                for($i=1;$i<=$limit;$i++) { ?>
                                    <option value="<?php echo $i;?>" <?php if($i==4) echo ' selected'?>><?php echo $i?></option>
                                <?php }?>
                        </select>
                        <small class="error">Device count required!</small>
                    </div>
                </div>
                <div class="small-2 columns">
                    <label>Time&nbsp;<font color="red">*</font></label>
                    <input type="text" id="time" placeholder="HH:MM" name="time" data-tooltip aria-haspopup="true" class="tip-top" title="For example 08:00" required>
                    <small class="error">Can't leave empty! </small>
                </div>
                <div class="small-2 columns">
                    <label>&nbsp;</label>
                    <input type="submit" class="button small" value="Submit" onclick="add_task();" name="submit" id="submit_1">
                    <input type="button" class="button small" value="Reset">
                </div>
                <div class="small-2 columns">
                </div>
            </div>
                
            <?php } ?>
        </div>
           </form>
        <div class="row">
            <div class="large-12 columns">
                <div class="callout panel">
                    <span id="loading_user_data">&nbsp;</span>
                    <div id="user_tasks_list"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

       $(function() {
//    $('#sub_task_space').hide();
//    $('#task_des_space').hide();
//    $('#noofdevice_space').hide();
        var judge= $("#judge").val();
        if(judge==1)
            load_teams();
        else
        {
            load_tasks();
            load_builds();
        }
                filter();
                var date=$('#date').val();
                check_remaining_hrs(date);
   });

    function load_teams()
    {
        $.ajax({
            type: "POST",
            url: "./load_teams.php",
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
            url: "./load_tasks_entries.php",
            data: "team_id=" + id,
            success: function(msg)
            {
                var html = $.trim(msg);
                $("#task").html(html);
            }
        });
    }
    
    function load_sub_tasks(task_id)
    {
        var have_st=$("#task option:selected").attr("itemtype");
        var have_dsi=$("#task option:selected").attr("itemref");
         var dsi_type=$("#task option:selected").attr("itemid");
         var device_count=$("#task option:selected").attr("itemprop");
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
        
           if(have_st==1)
        {
            //$('#sub_task_space').hide();
            $('#sub_tas').fadeTo( "slow", 1 );//show('slow');
            $('#sub_task').removeAttr('disabled');
            $('#sub_error').show().removeAttr('style');
        }
        else
        {
             $('#sub_tas').fadeTo( "slow", 0.20 );//hide();
            $("#sub_task").attr('disabled','disabled');
            $('#sub_error').hide();
            //$('#sub_task_space').show('slow');
        }
        
        if(have_dsi==1 && dsi_type==1)
        {
            //$('#task_des_space').hide();
            $('#task_desc').removeAttr('disabled');
            $('#task_des').fadeTo( "slow", 1 );//show('slow');
            $('#task_des_error').show().removeAttr('style');
            load_task_dec(task_id);
        }
        else if(have_dsi==0 || dsi_type!=1)
        {
            $('#task_des').fadeTo( "slow", 0.20 );//hide();
            $("#task_desc").attr('disabled','disabled');
            $('#task_des_error').hide();
            //$('#task_des_space').show('slow');
        }
         if(device_count==1)
        {
            //$('#noofdevice_space').hide();
            $('#noofdevice_div').fadeTo( "slow", 1 );//show('slow');
            $('#noofdevice').removeAttr('disabled');
        }
        else
        {
            $('#noofdevice_div').fadeTo( "slow", 0.20 );//hide();
            //$('#noofdevice').prop('readonly', true);
            $("#noofdevice").attr('disabled','disabled');
            //$('#noofdevice_space').show('slow');
        }
    }

   function load_task_dec(tsk)
    {
        $.ajax({
            type: "POST",
            url: "./load_task_desc.php",
            data: "task_id=" + tsk,
            success: function(msg)
            {
                var html = $.trim(msg);
                $("#task_desc").html(html);
            }
        });
    }
    
    function filter()
    {
        var date_for_rec=$('#date').val();
        $('#loading_user_data').html('<center><img src="../img/loading.gif"></center>');
        $( "#user_tasks_list" ).load( "./load_sda_entries.php",  { "date": date_for_rec},function( response, status, xhr ) {
            if ( status == "success" ) 
            {
                $('#loading_user_data').html('&nbsp;');
            }
        });
    }
    
    function check_remaining_hrs(date)
    {
        $('#remain_hrs').html('<center><img src="../img/loading.gif"></center>');
        $.ajax({
            type: "POST",
            url: "./remain_hrs.php",
            data:"date="+date,
            dataType: "json", 
            success: function(msg)
            {
                $('#remain_hrs').html(msg[0]+' hours remaining for the date:'+date);
                if(msg[1]<=0)
                {
                    $('#submit_1').prop("disabled",true);
                }
                else
                {
                    $('#submit_1').prop("disabled",false);
                }
            }
        });
    }
    
    function add_task()
    {
        var date=$('#date').val();
        var team=$('#team').val();
        var build=$('#build').val();
        var task=$('#task').val();
        var sub_task=$('#sub_task').val();
        var have_st=$("#task option:selected").attr("itemtype");
        var have_dsi=$("#task option:selected").attr("itemref");
        var dsi_type=$("#task option:selected").attr("itemid");
        var device_count=$("#task option:selected").attr("itemprop");
        var noofdevice=$('#noofdevice').val();
        var count=$('#qty').val();
        var time=$('#time').val();
        var cmds=$('#cmds').val();
        
        if(date=="" || team==""  ||  task=="" ||  time=="" || build=="") // || count=="" sub_task=="" ||
        {
            $(".msgs").html("<font color=red>Date, Build, Team, Task and Time cannot be left empty!</font>");
            return false;
        }
        if(have_st==1)
        {
            if(sub_task==null)
            {
                $(".msgs").html("<font color=red>Select sub task!</font>");
                return false;
            }
        }
        else
        {
            sub_task="NULL";
        }
        
        if(time=="00:00")
            {
                $(".msgs").html("<font color=red>Time should be more than a minute!.</font>");
                return false;
            }
        
        if(have_dsi==1 && dsi_type==1)
        {
            var task_desc=$('#task_desc').val();
            if(task_desc=="" || task_desc=='null')
            {
                $(".msgs").html("<font color=red>Task desc cannot be left empty!</font>");
                return false;
            }
            var data={date:date,team:team,build:build,task:task,sub_task:sub_task,task_desc:task_desc,time:time,count:count,device_count:device_count,noofdevice:noofdevice,cmds:cmds,have_dsi:have_dsi,dsi_type:dsi_type}
        }
        else if(have_dsi==0 || dsi_type!=1)
        {
            var data={date:date,team:team,build:build,task:task,sub_task:sub_task,time:time,count:count,device_count:device_count,noofdevice:noofdevice,cmds:cmds,have_dsi:have_dsi,dsi_type:dsi_type}
        }
        else
        {
            $(".msgs").html("<font color=red>Internal error occured! :(</font>");
            return false;
        }
        $('.msgs').html('<img src="../img/loading.gif">');
        $.ajax({
            type: "POST",
            url: "./add_tasks_inner.php",
            data:data,
            dataType: "json", 
            success: function(msg)
            {
                if(msg[0]=='F')
                {
                    $(".msgs").html("<font color=red'>"+msg[1]+"</font>");
                }
                else
                {
                    $(".msgs").html("<font color='green'>"+msg[1]+"</font>");
                    filter();
                //$('#form_user_tasks')[0].reset();
                    reset();
                check_remaining_hrs(date);
                }
            }
        });
    }
    
    function reset()
    {
//        var team=$('#team').val('');
//        var task=$('#task').val('');
//        var sub_task=$('#sub_task').val('');
//        var task_desc=$('#task_desc').val('');
        var count=$('#qty').val('');
        var time=$('#time').val('');
        var cmds=$('#cmds').val('');
        
    }
    
    function show_alert()
    {
        $(".msgs").html("<font color='red'>You are missing some dates!. Please complete those days!.</font>");
         blink(".msgs", 30, 100);
         //thanks to rlatha@ for suggestion the foreground and background color
    }
    
       
    function load_builds()
    {
        var id = $("#team").val();
        $.ajax({
            type: "POST",
            url: "./load_builds_for_user.php",
            data: "team=" + id,
            success: function(msg)
            {
                var html = $.trim(msg);
                $("#build").html(html);
            }
        });
    }


</script>

<?php
include_once '../includes/footer.php';
?>
<?php
if (empty($pending_dates)) {
    ?>
<!-- Date picker files and function start-->
<link rel="stylesheet" href="../css/jquery-ui.css">
<script src="../js/jquery-ui.js"></script>
<script>
  $(function() {
    $( "#date" ).datepicker({
        changeMonth: true,
        changeYear:true,
        dateFormat:"yy-mm-dd" });
  });
</script>
<!-- Date picker files and function end-->

<?php }
if (!empty($pending_dates)){
?>
<div id="myModal_warning" class="reveal-modal medium" data-reveal>
    <center><p class="lead">Please fill the incompleted dates!. Until unable to change the date!<br> Once you complete please reload the page for enter next date!</p></center>
  <a class="close-reveal-modal">&#215;</a>
</div>

<script>
$(document).foundation();
$('#myModal_warning').foundation('reveal', 'open');
$('#myModal_warning').foundation('reveal', 'close');
//alert("Please complete the incompleted dates!. until date picker not enable!");    
</script>
<?php
}
else
{
?>
<!--
<div id="myModal_info" class="reveal-modal medium" data-reveal>
    <center><p class="lead">View your all days entries under <a href="./reports.php">Weekly/monthly reports</a> menu</p></center>
  <a class="close-reveal-modal">&#215;</a>
</div>
-->
<script>
$(document).foundation();
//$('#myModal_info').foundation('reveal', 'open');
//$('#myModal_info').foundation('reveal', 'close');
//alert("Please complete the incompleted dates!. until date picker not enable!");    
</script>
<?php } ?>
<script>
function check_time(time_1)
{
//    if(exe_hrs>worked_hrs)
//    {        window.alert("Execution hours shoud be a numerical value and should not exceed  '"+ worked_hrs +"hrs' ");
//        <!-- $('.msg_cum_load').html('Execution hours shoud not exceed  '+ worked_hrs);-->
//        return false;
//    }
//    if(exe_hrs>8)
//    {       window.alert("Execution hours should not exceed 8 hrs");
//        <!--  $('.msg_cum_load').html('Execution hours should not exceed 8 hrs');-->
//        return false;
//    }
//    if(time_1<0.15)
//    { 
//        $(".msgs").html("<font color=red'>Execution hours should be minimum of 0.15 hrs</font>");
//    }
}

function check_avail_time(time_1)
{
//    if(exe_hrs>worked_hrs)
//    {        window.alert("Execution hours shoud be a numerical value and should not exceed  '"+ worked_hrs +"hrs' ");
//        <!-- $('.msg_cum_load').html('Execution hours shoud not exceed  '+ worked_hrs);-->
//        return false;
//    }
//    if(exe_hrs>8)
//    {       window.alert("Execution hours should not exceed 8 hrs");
//        <!--  $('.msg_cum_load').html('Execution hours should not exceed 8 hrs');-->
//        return false;
//    }
//    if(time_1<0.15)
//    { 
//        $(".msgs").html("<font color=red'>Execution hours should be minimum of 0.15 hrs</font>");
//    }
}


</script>
