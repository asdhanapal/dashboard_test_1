<?php
require_once '../classes/db.class.php';
$conn = new db();
$dbcon = $conn->dbConnect();
session_start();

$team=$_POST['team'];
$added_by=$_SESSION['admin_id'];


if($team=="")
{
    $result[0]="F";
    $result[1]="Fill the all details!";
    echo json_encode($result);
    exit();

}

$query_check="SELECT team_name FROM amz_teams WHERE team_name='$team'";
$result_check= $conn->runsql($query_check,$dbcon);
if(mysqli_num_rows($result_check))
{
    $result[0]="F";
    $result[1]="Team name already exist!";
    echo json_encode($result);
    exit();
}


    $query_add_cf="INSERT INTO amz_teams(team_name,added_by,status,team_deletion,create_date,maintain_date) VALUES ('$team',$added_by,1,0,now(),now())";
    $result_add_cf= $conn->runsql($query_add_cf,$dbcon);
    if($result_add_cf)
    {
        $result[0]="S";
        $result[1]="Team added succssfully";
    }
    else
    {
        $result[0]="F";
        $result[1]="An error occured!";
    }
echo json_encode($result);
//print_r($result);
?>