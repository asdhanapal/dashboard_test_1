<?php
include_once '../includes/header_sda.php';
include_once './data_page.php';
//print_r($_SESSION);
?>
<div class="row">
    <div class="large-12 columns">
        <div class="panel">

            <div class="row">
                <div class="large-2 columns">
                    <label>Team</label>
                    <select id="team" multiple="3"  oninput="filter();" disabled="">
                        <option> Loading...</option>
                    </select>
                </div>
                
                <div class="large-2 columns">
                    <label>User ID</label>
                    <input type="text" name="u_id" id="u_id" placeholder="User ID" oninput="filter();">
                </div>
                
                <div class="large-2 columns">
                    <label>First name</label>
                    <input type="text" name="f_name" id="f_name" placeholder="First name"  oninput="filter();">
                </div>
                
                <div class="large-2 columns">
                    <label>Status</label>
                    <select id="status"  oninput="filter();">
                        <option value="">-- All --</option>
                        <option value="1">Activate users</option>
                        <option value="0">Inactivate users</option>
                    </select>
                </div>
                
                <div class="large-2 columns">
                    <label>Any new users?</label>
                    <select id="activation"  oninput="filter();">
                        <option value="">-- All --</option>
                        <option value="1">Approved users</option>
                        <option value="0">Pending users</option>
                    </select>
                </div>
                
                <div class="large-7 columns"></div>
            </div>
        </div>
</form>

        <div class="row">
            <div class="large-12 columns">
                <div class="callout panel" style="padding: 0px 0px 20px 20px;">
                    <span id="loading_user_data">&nbsp;&nbsp;</span>
                    <div id="user_tasks_list" style="height: 400px; overflow-y: auto;">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // sample script for check all are working or not

    $(function() {
            load_teams();
            filter();
   });

    function load_teams()
    {
        $.ajax({
            type: "POST",
            url: "./load_only_teams.php",
            success: function(msg)
            {
                var html = $.trim(msg);
                $("#team").html(html);
            }
        });
    }

    function filter()
    {
        var team=$('#team').val();
        var user=$('#u_id').val();
        var f_name= $('#f_name').val();
        var status = $('#status').val();
//        if($('#new_users').is(":checked"))
//            var new_users=1;
//        else
        var new_users=$("#activation").val();
        //$('#loading_user_data').html('<center><img src="../img/loading.gif"></center>');
        $("#user_tasks_list").load("./load_all_users.php", {"team": team,"user":user,"f_name":f_name,"status":status,"new_users":new_users}, function(response, status, xhr) {
            if (status == "success")
            {
          //      $('#loading_user_data').html('&nbsp;');
                $("#user_tasks_list").show('slow');
            }
        });
    }
    
    function do_action(id,status)
    {
         if(confirm('Are you sure?'))
        {
            
//            $('.msgs').html('<img src="../img/loading.gif">');
            $.ajax({
                type: "POST",
                url: "user_manage_inner.php?action=user_action",
                data: "id="+id+"&&status="+status,
                success: function(msg)
                {
                        var html = $.trim(msg);
                        $('#loading_user_data').html("<center>"+msg+"</center>").hide();
                        $('#loading_user_data').fadeIn("slow");
                        $('#loading_user_data').delay(5000).fadeOut("slow");
                }
            });
            filter();
            return false;
        }
    }
    
    function change_status(id,status)
    {
         if(confirm('Are you sure?'))
        {
            
//            $('.msgs').html('<img src="../img/loading.gif">');
            $.ajax({
                type: "POST",
                url: "user_change_status.php?action=user_action",
                data: "id="+id+"&&status="+status,
                success: function(msg)
                {
                        var html = $.trim(msg);
                        $('#loading_user_data').html("<center>"+msg+"</center>").hide();
                        $('#loading_user_data').fadeIn("slow");
                        $('#loading_user_data').delay(5000).fadeOut("slow");
                }
            });
            filter();
            return false;
        }
    }


</script>
<?php
include_once '../includes/footer.php';
?>

<div id="myModal_warning" class="reveal-modal medium" data-reveal>
    <center><p class="lead">Please complete the incompleted dates!. until date picker not enable! After complete please refresh the page to enable the date picker.</p></center>
  <a class="close-reveal-modal">&#215;</a>
</div>

<script>
$(document).foundation();
//$('#myModal_warning').foundation('reveal', 'open');
$('#myModal_warning').foundation('reveal', 'close');
//alert("Please complete the incompleted dates!. until date picker not enable!");    
</script>
