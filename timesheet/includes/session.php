<?php
session_start();
if(isset($_SESSION) && empty($_SESSION)) {
   header("location:../"); 
   die();
}
session_validate($_SESSION['session_type']);

function session_validate($session_type)
{
    require_once '../classes/db.class.php';
    $conn = new db();
    $dbcon = $conn->dbConnect();
    
    $i=0;
    $sql="SELECT team_id FROM amz_teams";
    $result = $conn->runsql($sql, $dbcon);
    while ($result_row = mysqli_fetch_object($result)) {
        $user_team_id[]=$result_row->team_id;
        $i++;
    }
    if($session_type=="sda")
    {
        define("USER_ID", $_SESSION['sda_id'],TRUE);
        define("USER_NAME", $_SESSION['sda_name'],TRUE);
        define("USER_FIRST_NAME", $_SESSION['sda_first_name'],TRUE);
        define("USER_MAIL", $_SESSION['sda_mail'],TRUE);
        define("USER_TEAMS", json_encode($_SESSION['team_id']),TRUE);
        define("USER_TOT_TEAMS", $_SESSION['sda_team_count'],TRUE);
    }
    elseif($session_type=="manager")
    {
        define("USER_ID", $_SESSION['manager_id'],TRUE);
        define("USER_NAME", $_SESSION['manager_name'],TRUE);
        define("USER_FIRST_NAME", $_SESSION['manager_first_name'],TRUE);
        define("USER_MAIL", $_SESSION['manager_mail'],TRUE);
        define("USER_TEAMS", json_encode($_SESSION['team_id']),TRUE);
        define("USER_TOT_TEAMS", $_SESSION['manager_team_count'],TRUE);
    }
    elseif($session_type=="admin")
    {
        define("USER_ID", $_SESSION['admin_id'],TRUE);
        define("USER_NAME", $_SESSION['admin_name'],TRUE);
        define("USER_FIRST_NAME", $_SESSION['admin_first_name'],TRUE);
        define("USER_MAIL", $_SESSION['admin_mail'],TRUE);
        define("USER_TEAMS", json_encode($user_team_id),TRUE);
        define("USER_TOT_TEAMS", $i,TRUE);
    }
    else
    {
        header("location:../");
    }
}
?>