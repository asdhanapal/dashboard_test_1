<?php
include_once '../includes/header_manager.php';
include_once './data_page.php';
?>
<br>
<div class="row">
    <div class="large-12 columns">
        <div class="panel">

            <div class="row">
                <div class="large-2 columns">
                    <a href="profile.php.php">Change password</a><br><br>
                    <a href="password_manage.php">View profile</a>
                </div>

                <div class="large-10 columns">
                    <table width="40%" align="left" border="0">
                        <tr>
                            <td  colspan="2" align="center">
                                <h5>Change Password</h5><br>
                                <span class="err_msg"></span>
                            </td>
                        </tr>
                        <tr>
                            <td>Enter Old password:</td>
                            <td><input type="password" name="old" id="old"></td>
                        </tr>
                        <tr>
                            <td>Enter new password:</td>
                            <td><input type="password" name="new" id="new"></td>
                        </tr>
                        <tr>
                            <td>Confirm new password:</td>
                            <td><input type="password" name="connfirm" id="connfirm"></td>
                        </tr>
                        <tr>
                            <td colspan="2" align="center">
                                <input class="tiny button" type="button"  name="submit" id="submit" value="Submit" onclick="change_pwd();">
                                <input class="tiny button" type="button" name="reset" id="reset" value="Reset" onclick="reset();">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
</div>
<script>
    function reset()
    {
        $('#old').val('');
        $('#new').val('');
        $('#connfirm').val('');
    }

    function change_pwd()
    {
        var old = $('#old').val();
        var new1 = $('#new').val();
        var confirm = $('#connfirm').val();
        if (old == "")
        {
            $('.err_msg').html('Enter the Current Password');
            $('#old').focus();
            return false;
        }
        if (new1 == "")
        {
            $('.err_msg').html('Enter the New Password');
            $('#new').focus();
            return false;
        }
        if (confirm == "")
        {
            $('.err_msg').html('Enter the Confirm Password');
            $('#connfirm').focus();
            return false;
        }

        if (new1 != confirm)
        {
            $('.err_msg').html('Password Mismatch');
            reset();
            $('#old').focus();
            return false;
        }

        $('.err_msg').html('<img src="../img/loading.gif">');
        $.ajax({
            type: "POST",
            url: "./change_pwd_cfm_admin.php?action=changepwd",
            data: "oldpass=" + old + "&newpass=" + new1 + "&confirmpass=" + confirm,
            success: function(msg)
            {
                var html = $.trim(msg);
                $('.err_msg').html(html);
                reset();
            }
        });
        return false;
    }
</script>
<?php
include_once '../includes/footer.php';
?>
