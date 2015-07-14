<?php
include_once '../includes/header_sda.php';
include_once '../includes/define.php';
include_once ID_TO_NAME_CONV;
//print_r($_SESSION);
?>
<br>
<div class="row">
    <div class="large-12 columns">
        <div class="panel">
            <div class="row">
                <div class="large-3 columns">&nbsp;</div>
                <div class="large-2 columns">
                    <select id="team" placeholder=" -- Teams --" multiple="multiple">
                        <option value=""> Loading...</option>
                    </select>
                </div>
                <div class="large-2 columns">
                    <select name="filter_cf" placeholder=" -- Select year here -- " id="filter_cf" multiple="multiple">
                        <?php
                           for ($i = 2014; $i <= 2030; $i++) {
                                echo "<option value='$i'";
                                if (date("Y") == $i)
                                    echo "selected='selected'";
                                echo ">" . $i . "</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="large-4 columns">
                        <input type="button" class="tiny round button" value="Set Daily target" onclick="filter_daily_target();" >
                        <input type="button" class="tiny round button" value="Define Task Type" onclick="filter_task_type();" >
                </div>
                <div class="large-1 columns"></div>
            </div>
        </div>
    </div>
    
    <div class="large-12 columns">
        <div class="callout panel" >
            <span id="loading_user_data"><center>Select the team and set daily targets or define the task types</center></span>
            <div id="user_tasks_list"></div>
        </div>
    </div>
</div>
<script>
    $(function() {
            load_teams();
            $('#filter_cf').SumoSelect();
   });
    function filter_daily_target()
    {
        var yr=$('#filter_cf').val();
        var teams=$("#team").val();
        $('#loading_user_data').html('<center><img src="../img/loading.gif"></center>');
        
        $( "#user_tasks_list" ).load( "<?php echo _BACK_TO_PRE_.COMMON.DAILY_TARGET_DISPLAY_SECTION?>",  { "yr": yr,"teams":teams},function( response, status, xhr ) {
            if ( status == "success" ) 
            {
                $('#loading_user_data').html('');
            }
        });
    }
    function filter_task_type()
    {
        var yr=$('#filter_cf').val();
        var teams=$("#team").val();
        $('#loading_user_data').html('<center><img src="../img/loading.gif"></center>');
        
        $( "#user_tasks_list" ).load( "<?php echo _BACK_TO_PRE_.COMMON.DEFINE_TASK_TYPE_DISPLAY_SECTION?>",  { "yr": yr,"teams":teams},function( response, status, xhr ) {
            if ( status == "success" ) 
            {
                $('#loading_user_data').html('');
            }
        });
    }
    
    function update_status(updation_field,month,year,task,value)
    {
        if(value=="")
        {
            alert("Select any option!");
            return false;
        }
        //$('.success_msg').html('<img src="../img/loading.gif">').show();
        $.ajax({
            type: "POST",
            url: "<?php echo _BACK_TO_PRE_.COMMON.DEFINE_TASK_TYPE_UPDATE_SECTION;?>",
            dataType: "json", 
            data: "field="+updation_field+"&month="+month+"&year="+year+"&task="+task+"&value="+value,
            success: function(msg)
            {
                if(msg[0]=='F')
                {
                    $(".fail_msg").html(msg[1]).show().delay(5000).fadeOut();
                }
                else
                {
                    $(".success_msg").html(msg[1]).show().delay(5000).fadeOut();
                }
            }
        });
    }

function load_teams()
    {
        $.ajax({
            type: "POST",
            url: "./load_teams_all.php",
            success: function(msg)
            {
                var html = $.trim(msg);
                $("#team").html(html);
                //$("#team").val($("#team option:first").val());
                $("#team option:first").attr('selected','selected');
                $('#team').SumoSelect();
                filter_task_type();
            }
        });
    }

</script>
<?php
include_once '../includes/footer.php';
?>
<style>
.head_text {
  width: 97%;
  font-size: 15px;
  font-weight: bolder;
  color: #2BB0DA!important;
  display: inline-block;
  right: 46%;
  text-align: center;
}
.help_text {
    font-size: 15px;
    font-weight: bolder;
    color: #2BB0DA!important;
    display: inline-block;
    text-align: right;
}
#success_msg
{
   background-color: #43AC6A; 
   border-color: #3a945b; 
   color: #FFFFFF; 
   border-radius: 3px; 
   border-style: solid; 
   border-width: 1px; 
   display: block; 
   font-weight: normal; 
   margin-bottom: 1.11111rem; 
   padding: 0.77778rem 1.33333rem 0.77778rem 0.77778rem; 
   transition: opacity 300ms ease-out; 
   top:95%;
   position: fixed;
   width: 100%;
   text-align: center;
   text-transform: capitalize;   
}
#fail_msg
{
    background-color: #f04124;
    border-color: #de2d0f;
    color: #FFFFFF; 
    border-radius: 3px; 
    border-style: solid; 
    border-width: 1px; 
    display: block; 
    font-weight: normal; 
    margin-bottom: 1.11111rem; 
    padding: 0.77778rem 1.33333rem 0.77778rem 0.77778rem; 
    transition: opacity 300ms ease-out; 
    top:95%;
    position: fixed;
    width: 100%;
    text-align: center;
    text-transform: capitalize;
}

.help_div{
    display: none;
}
.help_text:hover+.help_div{
    display: inline;
    position: absolute;
}
.help_div {
    border-width: 10px;
    width: 250px;
    right: 80px;
}
</style>
<link rel="stylesheet" href="../css/jquery-ui.css">
<script src="../js/jquery-ui.js"></script>
<script src="../js/jquery.sumoselect.js"></script>
<link href="../css/sumoselect.css" rel="stylesheet" />

<div id="success_msg" class="success_msg" style="display: none">
</div>
<div id="fail_msg" class="fail_msg" style="display:none">
</div>