<?php
include_once '../includes/define.php';
include_once '../includes/header_admin.php';
?>
<div class="row">
    <div class="large-12 columns">
        <div class="panel">
            <div class="row">
                <div class="large-2 columns">
                    <label>Team</label>
                    <select id="team" oninput="filter();">
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
                        <option value="1">Active users</option>
                        <option value="0">Inactive users</option>
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
                <div class="callout panel" style="padding: 20px 2px 2px 20px;">
                    <span id="loading_user_data"></span>
                    <div id="user_tasks_list" style="height: 400px; overflow-y: auto;"></div>
                </div>
            </div>
        </div>

    </div>
</div>
<script>
    $(function () {
        load_teams();
    });
    function load_teams()
    {
        $.ajax({
            type: "POST",
            url: "./load_only_teams.php",
            success: function (msg)
            {
                var html = $.trim(msg);
                $("#team").html(html);
                filter();
            }
        });
    }
    function filter()
    {
        var team = $('#team').val();
        var user = $('#u_id').val();
        var f_name = $('#f_name').val();
        var status = $('#status').val();
        var new_users = $("#activation").val();
        $("#user_tasks_list").load("<?php echo _BACK_TO_PRE_ . COMMON . DISPLAY_USER_INFO ?>", {"team": team, "user": user, "f_name": f_name, "status": status, "new_users": new_users}, function (response, status, xhr) {
            if (status == "success")
            {
                $("#user_tasks_list").show('slow');
            }
        });
    }
    function do_action(id, status)
    {
        if (confirm('Are you sure?'))
        {

            $('#loading_user_data').html("<center>Sending status mail...(It may take some time)!</center>");
            $.ajax({
                type: "POST",
                url: "user_manage_inner.php?action=user_action",
                data: "id=" + id + "&&status=" + status,
                success: function (msg)
                {
                    var html = $.trim(msg);
                    $('#loading_user_data').html("<center>" + msg + "</center>").hide();
                    $('#loading_user_data').fadeIn("slow");
                    $('#loading_user_data').delay(5000).fadeOut("slow");
                }
            });
            filter();
            return false;
        }
    }
    function change_status(id, status)
    {
        if (confirm('Are you sure?'))
        {
            $.ajax({
                type: "POST",
                url: "user_change_status.php?action=user_action",
                data: "id=" + id + "&&status=" + status,
                success: function (msg)
                {
                    var html = $.trim(msg);
                    $('#loading_user_data').html("<center>" + msg + "</center>").hide();
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
