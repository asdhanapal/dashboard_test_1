<div id="footer">

    <a href="../../ts_old_versions/" target="_blank" style="color: #aaa;" title="Click here for old versions">Publisher to Reader v5.0&nbsp;&nbsp;&nbsp;&nbsp;</a>
    <a href="https://issues.labcollab.net/secure/CreateIssue!default.jspa" target="_blank" onclick="alert('Make sure you are rasing a bug in &#34;Publisher to Reader DA Internal Staging bucket!&#34;');">Report a problem/Suggestion</a>
</div>

<script src="../js/foundation.min.js"></script>
<script>
// CSS style startup... foundation startup
    $(document).foundation();
</script>

<script>
function blink(elem, times, speed) {
    if (times > 0 || times < 0) {
        if ($(elem).hasClass("blink")) $(elem).removeClass("blink");
        else $(elem).addClass("blink");
    }

    clearTimeout(function () {
        blink(elem, times, speed);
    });

    if (times > 0 || times < 0) {
        setTimeout(function () {
            blink(elem, times, speed);
        }, speed);
        times -= .5;
    }
}
</script>
<style>
    .blink {
    //color:  !important;
    background: #FFFF00;
}

#footer{
    	height: 50px;
    	position: absolute;
/*    	bottom: -20px;*/
    	left: -3%;
/*    	line-height: 50px;*/
    	color: #aaa;
    	text-align: right;
    	width: 100%;
    }
</style>
</body>
</html>
