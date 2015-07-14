<?php
include_once '../includes/header_admin.php';
include_once '../includes/define.php';
include_once ID_TO_NAME_CONV;
//print_r($_SESSION);
?>
<br>
<div class="row">
    <div class="large-12 columns">
        <div class="panel">
            <div class="row">
                <div class="large-5 columns">&nbsp;</div>
                <div class="large-2 columns">
                    <select name="year" id="year" onchange="view_report();">
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
                <div class="large-5 columns"></div>
            </div>
        </div>
    </div>
    
    <div class="large-12 columns">
        <div class="callout panel" >
            <div id="user_tasks_list_1"></div>
            <div id="user_tasks_list_2">
                
            </div>
            <table align="center" width="70%">
                <tr>
                    <th>Error code</th>
                    <th>Explanation</th>
                </tr>
                <tr>
                    <td>Err:101</td>
                    <td>Work units not calculated yet!</td>
                </tr>
                <tr>
                    <td>Err:102</td>
                    <td>Daily target or Task type not defined for the specified month!</td>
                </tr>
            </table>
        </div>
    </div>
    
</div>
<script>
$( document ).ready(function() {
    view_report_2_get_teams();
});

function view_report_2_get_teams()
{
    var year=$('#year').val();
    $('#user_tasks_list_2').html('<center><img src="../img/loading.gif"> Loading User wise Report...</center>');
    $("#user_tasks_list_2").load( "<?php echo _BACK_TO_PRE_.COMMON.YEARLY_VIEW_BW_U_DATA_D1_1?>",  { "year": year},function( response, status, xhr ) {
    if ( status === "success" )
    {
        $('#loading_user_data').html('');
    }
    });
}

function view_report_2_get_full_info(team_id)
{
    var year=$('#year').val();
    $('#display_team_tr_'+team_id).show('slow');
    $('#display_team_td_'+team_id).html('<center><img src="../img/loading.gif"></center>');
    var data= {year:year,team:team_id}
    $.ajax({
        type: "POST",
        url: "<?php echo _BACK_TO_PRE_.COMMON.YEARLY_VIEW_BW_U_DATA_D1_2?>",
        data:data,
        success: function(msg)
        {
            var html = $.trim(msg);
            $('#display_team_td_'+team_id).html(html);
            $('#master_row_'+team_id).attr("onclick","show_hide("+team_id+")");
        }
    });
}

function show_hide(id)
{
    $('#display_team_tr_'+id).toggle('slow');
}
</script>

<style type="text/css">
    .project_odd:hover, .project_even:hover  {
        background-size: 100% 100%;
        background: -webkit-gradient(linear, left top, left bottom, from(#ced6df), to(#b6c6d7));
        background: -webkit-linear-gradient(top, #ced6df, #b6c6d7);
        background: -moz-linear-gradient(top, #ced6df, #b6c6d7);
        background: -ms-linear-gradient(top, #ced6df, #b6c6d7);
        background: -o-linear-gradient(top, #ced6df, #b6c6d7);
        color: #000;
    }
    .tbl_header {
       font-size: 2px;
       color: #222222; 
       font-weight: bold; 
       background: #f3f3f3; 
       background-image: url(../css/svg_gradient.php?from=ffffff&to=eeeeee); 
       background-size: 100% 100%; 
       background: -webkit-gradient(linear, left top, left bottom, from(#ffffff), to(#eeeeee)); 
       background: -webkit-linear-gradient(top, #ffffff, #eeeeee);
       background: -moz-linear-gradient(top, #ffffff, #eeeeee);
       background: -ms-linear-gradient(top, #ffffff, #eeeeee);
       background: -o-linear-gradient(top, #ffffff, #eeeeee);
    }
    .fail {
        color:red;
    }
    .success {
        color: green;
    }
</style>
<?php
include_once '../includes/footer.php';
?>
<link rel="stylesheet" href="../css/jquery-ui.css">
<script src="../js/jquery-ui.js"></script>