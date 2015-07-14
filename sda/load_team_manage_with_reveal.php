<?php
require_once '../classes/db.class.php';
$conn = new db();
$dbcon = $conn->dbConnect();
session_start();
//print_r($_SESSION);
$avail_teams=$_SESSION['team_id'];
$size=  sizeof($avail_teams);
echo '<div class="accordion">';
for($i=0;$i<$size;$i++)
{
    $query_1="SELECT team_id,team_name FROM amz_teams where team_id='$avail_teams[$i]'";
    $result_1= $conn->runsql($query_1,$dbcon);
    if(mysqli_num_rows($result_1))
    {
        while($result_row_1=  mysqli_fetch_object($result_1))
        {
            $sec_team_id=$result_row_1->team_id;
            ?>
           <h2>
           <?php echo $sec_team_name=$result_row_1->team_name?>
           <a href="#" data-reveal-id="myModaledit<?php echo $sec_team_id ?>" data-reveal-ajax="./edit_data.php?val=<?php echo $sec_team_id ?>">Edit</a>&nbsp;&nbsp;&nbsp;<a href="">Delete</a>
           <div id="myModaledit<?php echo $sec_team_id ?>" class="reveal-modal small" data-reveal>
           <a class="close-reveal-modal">&#215;</a>
        </div>

           </h2>
            <div class="pane">
            <?php
            $query_2="SELECT task_id,task_name,op_type FROM amz_tasks where team_id='$sec_team_id' AND deletion=0";
            $result_2= $conn->runsql($query_2,$dbcon);
            if(mysqli_num_rows($result_2))
            {
                while($result_row_2=  mysqli_fetch_object($result_2))
                {
                    echo '<div class="accordion">';
                    $sec_task_id=$result_row_2->task_id;
                    $op_type=$result_row_2->op_type==0?"Non operation":"Operation";
                    echo "<h2>[".$op_type."]&nbsp;&nbsp;".$sec_task_name=$result_row_2->task_name."</h2><div class='pane'>";
                    $query_3="SELECT sub_task_id,sub_task_name,cf_change FROM amz_sub_tasks where task_id='$sec_task_id' AND deletion=0";
                    $result_3= $conn->runsql($query_3,$dbcon);
                    if(mysqli_num_rows($result_3))
                    {
                        while($result_row_3=  mysqli_fetch_object($result_3))
                        {
                            echo '<div class="accordion">';
                            $sec_stask_id=$result_row_3->sub_task_id;
                            $task_cat=$result_row_3->cf_change;
                            if($task_cat==1)
                                $task_cat="[LC]";
                            elseif($task_cat==2)
                                $task_cat="[MC]";
                            elseif($task_cat==3)
                                $task_cat="[HC]";
                            else
                                $task_cat="";
                            echo "<h2>".$sec_stask_name=$result_row_3->sub_task_name."&nbsp;".$task_cat."</h2><div class='pane'>";
                            $query_5="SELECT tdi_no,task_info FROM amz_task_desc where task_id='$sec_task_id'";
                            $result_5= $conn->runsql($query_5,$dbcon);
                            if(mysqli_num_rows($result_5))
                            {
                                while($result_row_5=  mysqli_fetch_object($result_5))
                                {
                                    
                                    //$result_row_5->tdi_no;
                                    echo "<h2>".$result_row_5->task_info."</h2>";
                                }
                            }
                            echo "</div>";
                            echo "</div>";
                        }
                    }
                    echo "</div>";
                echo "</div>";
                }
            }
            echo "</div>";
        }
    }
}
echo "</div>";

?>

<script>
$(document).ready(function() {
    //Initialising Accordion
    $(".accordion").tabs(".pane", {
        tabs: '> h2',
        effect: 'slide',
        initialIndex: null
    });

    //The click to hide function
    $(".accordion > h2").click(function() {
        if ($(this).hasClass("current") && $(this).next().queue().length === 0) {
            $(this).next().slideUp();
            $(this).removeClass("current");
        } else if (!$(this).hasClass("current") && $(this).next().queue().length === 0) {
            $(this).next().slideDown();
            $(this).addClass("current");
        }
    });
});
</script>

<style>
    /* root element for accordion. decorated with rounded borders and gradient background image */
.accordion {
   // background:#333;
    //width: 90%;
    border:1px solid #d5eef6;    
}

/* accordion header */
.accordion h2 {
    background:#EEEEEE;
    margin:0;
    padding:5px 15px;
    font-size:14px;
    font-weight:normal;
    border:1px solid #fff;
    border-bottom:1px solid #ddd;
    cursor:pointer;
    
}

/* currently active header */
.accordion h2.current {
    cursor:default;
    font-weight: bold;
    background-color:#E1E1E1;   // selected tap color
}

/* accordion pane */
.accordion .pane {
    display:none;
    
    padding:15px;
    //color:#fff;
    font-size:12px;
}

/* a title inside pane */
.accordion .pane h3 {
    font-weight:normal;
    margin:0 0 -5px 0;
    font-size:16px;
    //color:#999;
}
</style>
