<style>
    .error {
        font-size: 18px;
        margin-left: 45%;
        color: red;
    }
</style>
<?php
session_start();
include_once './data_page.php';
require_once '../classes/db.class.php';
$conn = new db();
$dbcon = $conn->dbConnect();

function setHeader($excel_file_name)
{
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=$excel_file_name");
    header("Pragma: no-cache");
    header("Expires: 0");
}

if(isset($_POST['Export_2_excel']))
{ 
//    print_r($_POST);
    $query=$_POST["query"];
    $title=$_POST['title'];
    $file_name=$_POST['file_name'].".xls";
    $table="";
    $result= $conn->runsql($query,$dbcon);
    $i=1;
    if(mysqli_num_rows($result))
    {
         $table='<table border="1">
            <tr>
            <td colspan="13" >&nbsp;</td>
            </tr>
            <tr>
                <td colspan="13" align="center" style="background-color: #82e2f9;">'.$title.'</td>
            </tr>
            <tr align="center">
            <td >S.No.</td>
            <td>Date</td>
            <td>User name</td>
            <td>Team</td>
            <td>Task</td>
            <td>Sub task</td>
            <td>Task desc.</td>
            <td>Count</td>
            <td>Time</td>
            <td>Comments</td>
            
            <td>Last modify by</td>
            <td>Last modify date</td>
            </tr>';
        //<td>On Time?</td>
            while($result_row=  mysqli_fetch_object($result))
            {
                    $date=$result_row->date;
                    $user=$user_array[$result_row->user_id];
                    $team=$team_array[$result_row->team_id];
                    $task=$result_row->tasks_id!=""?$task_array[$result_row->tasks_id]:"N/A";
                    $sub_task=$result_row->sub_task_id!=""?$sub_task_array[$result_row->sub_task_id]:"N/A";
                    $task_desc=$result_row->task_desc!=""?$task_desc_array[$result_row->task_desc]:"N/A";
                    $count=$result_row->count;
                    $time=$result_row->time;
                    $cmds=$result_row->cmds;
                    $ontime=$result_row->on_time == "Y" ? "Yes" : "No";
                    $last_modify_by=$result_row->modified_by!=""? $result_row->modified_by:"N/A";
                    $last_modify_date=$result_row->maintain_date;
                    $table.='<tr>
                             <td align="center">'.$i.'</td>
                                 
                                
                                <td>'.$date.'</td>
                                <td>'.$user.'</td>
                                <td>'.$team.'</td>
                                <td>'.$task.'</td>
                                <td>'.$sub_task.'</td>
                                <td>'.$task_desc.'</td>
                                <td>'.$count.'</td>
                                <td>'.$time.'</td>
                                <td>'.$cmds.'</td>
                                
                                <td>'.$last_modify_by.'</td>
                                <td>'.$last_modify_date.'</td>';
                    $table.='</tr>';
                    //<td>'.$ontime.'</td>
                $i++;
            }
           $table.='</table>';
//echo $table;
           //die();
           setHeader($file_name);
           echo $table;
    }
    else
    {
        echo "<div class='error'>No entries available!</div>";
    }
}
?>