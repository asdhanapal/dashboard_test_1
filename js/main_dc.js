$(document).ready(function() {
    //toggle `popup` / `inline` mode
    $.fn.editable.defaults.mode = 'popup';     
    
    //make username editable
    $('.dc').editable();
    
    $(document).on('click', '.editable-submit', function () {
        var x = $(this).closest('td').children('a').attr('id');
        var a = $("#"+x).text();
        var y = $('.input-medium').val();
        if(!$.isNumeric(y))
        {
            alert("Enter the number!");
            return false;
        }
        var z = $(this).closest('td').children('a');
        $.ajax({
            url: "update_daily_target_dc_ajax.php?id=" + x + "&data=" + y+"&token=2",
            type: 'GET',
            success: function (s) {
                if (s == 'pass') {
                    $(z).html(y);
                }
                else {
                    alert('Internal error. Try again later!');
                    $(z).html(a);
                }
            },
            error: function (e) {
                alert('Internal error. Try again later!');
            }
        });
    });
});