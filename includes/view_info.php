<form method="post">
<table>
    <tr>
        <td>Team: </td>
        <td><input type="text" name="team"></td>
    </tr>
    
    <tr>
        <td>Task: </td>
        <td><input type="text" name="task"></td>
    </tr>
    
    <tr>
        <td>Sub task: </td>
        <td><input type="text" name="stask"></td>
    </tr>
    
    <tr>
        <td>User: </td>
        <td><input type="text" name="user"></td>
    </tr>
    
    <tr>
        <td><input type="submit" name="submit"></td>
    </tr>
</table>
</form>
<?php
if(isset($_POST['submit']))
{
    require_once '../classes/db.class.php';
    include '../admin/data_page.php';
    $conn = new db();
    $dbcon = $conn->dbConnect();
    $team=$_POST['team'];
    $task=$_POST['task'];
    $stask=$_POST['stask'];
    $user=$_POST['user'];
    
    if($team!="")
        echo "<br>Team:".$team_array[$team];
    if($task!="")
        echo "<br>Task:".$task_array[$task];
    if($stask!="")
        echo "<br>Sub task:".$sub_task_array[$stask];
    if($user!="")
        echo "<br>User:".$user_array[$user];
}
?>