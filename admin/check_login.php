<?php
require_once '../classes/db.class.php';
$conn = new db();
$dbcon = $conn->dbConnect();
session_start();
$fail_msg = $success_msg = '';

if ((isset($_GET)) && ($_POST['uname'] != "") && ($_POST['pass'] != "")) 
{
    $uname=$_POST['uname'];
    $pass=$_POST['pass'];
    $i=0;
    $uname = mysqli_real_escape_string($dbcon,$uname);
    $pass = mysqli_real_escape_string($dbcon,$pass);
    $pass = md5($pass);
    $query = "SELECT * FROM amz_login WHERE user_name='$uname' AND user_deletion='0'";
    $result = $conn->runsql($query, $dbcon);
    if (mysqli_num_rows($result)) 
    {
        $result_row = mysqli_fetch_object($result);
        if ($result_row->password == $pass)
        {
            if (!$result_row->user_activation) 
            {
                $fail_msg = "<font color='red'>Your request not yet validated!</font>";
            }
            elseif (!$result_row->user_status) 
            {
                $fail_msg = "<font color='red'>Your account was blocked!</font>";
            }
            else
            {
                if (($result_row->user_type==1))
                {
                    $success_msg='U';
                    $_SESSION['session_type']="user";
                    $_SESSION['user_name']=$uname;  //user's name
                    $_SESSION['user_id']=$result_row->user_id;  //auto_incremented id
                    $_SESSION['user_type']=$result_row->user_type;  //SDA or DA
                    $_SESSION['first_name']=$result_row->first_name;    //name
                    $_SESSION['user_mail']=$result_row->user_mail;  //mail
                    $_SESSION['below_on']=$result_row->below_on;    //SDA id for this user
                    $uid=$result_row->user_id;
                    $query_teams = "SELECT * FROM amz_user_info WHERE user_id='$uid' AND status='1'";
                    $result_teams = $conn->runsql($query_teams, $dbcon);
                    if (mysqli_num_rows($result_teams)) 
                    {
                        while($result_teams_array=  mysqli_fetch_object($result_teams))
                        {
                                $user_team_id[]=$result_teams_array->team_id;
                                $i++;
                        }
                    }
                    $_SESSION['team_id']=$user_team_id;
                    $_SESSION['team_count']=$i; //belongs to which team??
                }
                elseif (($result_row->user_type==2))
                {
                    $success_msg='S';
                    $_SESSION['session_type']="sda";
                    $_SESSION['sda_name']=$uname;
                    $_SESSION['sda_id']=$result_row->user_id;
                    $_SESSION['sda_user_type']=$result_row->user_type;  //SDA or DA
                    $_SESSION['sda_first_name']=$result_row->first_name;
                    $_SESSION['sda_mail']=$result_row->user_mail;
                    $uid=$result_row->user_id;
                    $query_teams = "SELECT * FROM amz_user_info WHERE user_id='$uid' AND status='1'";
                    $result_teams = $conn->runsql($query_teams, $dbcon);
                    if (mysqli_num_rows($result_teams)) 
                    {
                        while($result_teams_array=  mysqli_fetch_object($result_teams))
                        {
                            $user_team_id[]=$result_teams_array->team_id;
                            $i++;
                        }
                    }
                    $_SESSION['team_id']=$user_team_id;
                    $_SESSION['sda_team_count']=$i;
                }
                elseif (($result_row->user_type==3))
                {
                    $success_msg='M';
                    $_SESSION['session_type']="manager";
                    $_SESSION['manager_name']=$uname;
                    $_SESSION['manager_id']=$result_row->user_id;
                    $_SESSION['manager_user_type']=$result_row->user_type;  //SDA or DA
                    $_SESSION['manager_first_name']=$result_row->first_name;
                    $_SESSION['manager_mail']=$result_row->user_mail;
                    $uid=$result_row->user_id;
                    $query_teams = "SELECT * FROM amz_user_info WHERE user_id='$uid' AND status='1'";
                    $result_teams = $conn->runsql($query_teams, $dbcon);
                    if (mysqli_num_rows($result_teams))
                    {
                        while($result_teams_array=  mysqli_fetch_object($result_teams))
                        {
                                $user_team_id[]=$result_teams_array->team_id;
                                $i++;
                        }
                    }
                    $_SESSION['team_id']=$user_team_id;
                    $_SESSION['manager_team_count']=$i;
                }
                elseif (($result_row->user_type==4))
                {
                    $success_msg='A';
                    $_SESSION['session_type']="admin";
                    $_SESSION['admin_name']=$uname;
                    $_SESSION['admin_id']=$result_row->user_id;
                    $_SESSION['user_type']=$result_row->user_type;
                    $_SESSION['admin_first_name']=$result_row->first_name;
                    $_SESSION['admin_mail']=$result_row->user_mail;
                }
                else
                {
                    $fail_msg = "<font color='red'>You do not have any valid privilage!</font>";
                }
            }
        }
        else 
        {
            $fail_msg = "<font color='red'>Username and password doesn't match!</font>";
        }
    }
    else
    {
        $fail_msg = "<font color='red'>Username doesn't exist!</font>";
    }
}
echo $success_msg.$fail_msg;
?>
