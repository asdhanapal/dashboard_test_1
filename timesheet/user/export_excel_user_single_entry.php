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
            <td colspan="10" >&nbsp;</td>
            </tr>
            <tr>
                <td colspan="10" align="center" style="background-color: #82e2f9;">'.$title.'</td>
            </tr>
            <tr align="center">
            <td >S.No.</td>
            <td>Date</td>
            <td>Team</td>
            <td>Task</td>
            <td>Type of Task</td>
            <td>Time</td>
            <td>Comments</td>
            <td>On Time?</td>
            <td>Last modify by</td>
            <td>Last modify date</td>
            </tr>';
        
            while($result_row=  mysqli_fetch_object($result))
            {
                    $date=$result_row->date;
                    $team=$team_array[$result_row->team_id];
                    $task=$task_array[$result_row->tasks_id];
                    $sub_task=$sub_task_array[$result_row->sub_task_id];
                    $time=$result_row->time;
                    $cmds=$result_row->cmds;
                    $ontime=$result_row->on_time == "Y" ? "Yes" : "No";
                    $last_modify_by=$result_row->modified_by!=""? $result_row->modified_by:"N/A";
                    $last_modify_date=$result_row->maintain_date;
                    $table.='<tr>
                             <td align="center">'.$i.'</td>
                                 
                                
                                <td>'.$date.'</td>
                                <td>'.$team.'</td>
                                <td>'.$task.'</td>
                                <td>'.$sub_task.'</td>
                                <td>'.$time.'</td>
                                <td>'.$cmds.'</td>
                                <td>'.$ontime.'</td>
                                <td>'.$last_modify_by.'</td>
                                <td>'.$last_modify_date.'</td>';
                    $table.='</tr>';
                $i++;
            }
           $table.='</table>';
//           die();
           setHeader($file_name);
           echo $table;
    }
    else
    {
        echo "<div class='error'>No entries available!</div>";
    }
}
?>