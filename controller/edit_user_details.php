<?php
require_once '../includes/define.php';
require_once _BACK_TO_PRE_.DB_CLASS.DB_CLASS_FILE;
require_once ID_TO_NAME_CONV;
require_once _BACK_TO_PRE_._INCLUDE.SESSION_FILE;
$conn = new db();
$dbcon = $conn->dbConnect();

if($_GET['val']!="")
{
    $user_id=$_GET['val'];
?>
<input type="hidden" id="editing_id" value="<?php echo $user_id?>">
<span class="user_info"><center><b>User:&nbsp;<?php echo $user_array[$user_id]?></b></center></span>
<table width="100%">
    <tr>
        <td colspan="2" align="center">
            <span class="msg">&nbsp;</span>
<!--            <div data-alert class="alert-box warning round">
                updatd successfully!
                <a href="#" class="close">&times;</a>
            </div>-->
        </td>
    </tr>
    <tr>
        <td width="50%">Field</td>
        <td>Value</td>
    </tr>
    
    <tr>
        <td>Parent team:</td>
        <td>
            <?php
            $parent_teams=array();
            $query_parent_team="SELECT team_id FROM amz_pteam_info WHERE user_id='$user_id'";
            $result_parent_team= $conn->runsql($query_parent_team,$dbcon);
            while($result_row_parent_team=  mysqli_fetch_object($result_parent_team))
            {
                $parent_teams[]=$result_row_parent_team->team_id;
            }
            ?>
            <select id="p_team" multiple="multiple" placeholder=" -- Parent team --" class="teams">
            <?php
            $teams=  json_decode(USER_TEAMS);
            foreach ($teams as $key => $value)
            { ?>
                <option value="<?php echo $value?>" <?php if (in_array($value, $parent_teams)) echo "Selected";?>><?php echo $team_array[$value]?></option>
            <?php }
            ?>
            </select>
        </td>
    </tr>
    
    <tr>
        <td>Available teams:</td>
        <td>
            <?php
            $available_teams=array();
            $query_available_team="SELECT team_id FROM amz_user_info WHERE user_id='$user_id'";
            $result_available_team= $conn->runsql($query_available_team,$dbcon);
            while($result_row_available_team=  mysqli_fetch_object($result_available_team))
            {
                $available_teams[]=$result_row_available_team->team_id;
            }
            ?>
            <select id="a_team" multiple="" placeholder=" -- Available team --" class="teams">
            <?php
            foreach ($teams as $key => $value)
            { ?>
                <option value="<?php echo $value?>" <?php if (in_array($value, $available_teams)) echo "Selected";?>><?php echo $team_array[$value]?></option>
            <?php }
            ?>
            </select>
        </td>
    </tr>
    
    <tr>
        <td colspan="2" align="center"><input type="button" class="tiny button" value="Update info" onclick="update_info();" ></td>
    </tr>
</table>
<?php  
}  else {
die("Something went wrong!");    
}
?>
<script src="../js/jquery.sumoselect.js"></script>
<link href="../css/sumoselect.css" rel="stylesheet" />
<script>
    $(function() {
    $('.teams').SumoSelect();
   });
   
   function update_info()
    {
        var p_team=$('#p_team').val();
        var a_team=$("#a_team").val();
        if(p_team==null || a_team==null)
        {
            $(".msg").css("text-color","red").html("Both Parent and Available team fields can't be left empty!");
            return false;
        }
        
        var editing_id=$("#editing_id").val();
        $('.msg').html('<center><img src="../img/loading.gif"></center>');
        $.ajax({
            type: "POST",
            url: "<?php echo _BACK_TO_PRE_.COMMON.UPDATE_USER_DETAILS;?>",
            dataType: "json", 
            data: "p_team="+p_team+"&a_team="+a_team+"&editing_id="+editing_id,
            success: function(msg)
            {
                if(msg[0]=='F')
                {
                    $(".msg").html(msg[1]).show().delay(5000).fadeOut();
                }
                else
                {
                    $(".msg").html(msg[1]).show().delay(5000).fadeOut();
                }
            }
        });
    }
</script>    

<style>
    .user_info {
    }
</style>