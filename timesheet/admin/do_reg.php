<?php
require_once '../classes/db.class.php';
$conn = new db();
$host_name= $_SERVER['REMOTE_ADDR'];//gethostbyaddr($_SERVER['REMOTE_ADDR']);

$dbcon=$conn->dbConnect();
session_start();
$fail_msg=$success_msg='';
//print_r($_POST);
if( (isset($_GET)) && ($_POST['uname']!=""))
{
    $uname=  $_POST['uname'];
    $team= explode(",",$_POST['teams']);
    $team_count=  sizeof($team);
    
    $gepassword = gen_md5_password(8);
    $password = md5($gepassword);

    $query="SELECT * FROM amz_login WHERE user_name='$uname'";
    $result= $conn->runsql($query,$dbcon);
    if(mysqli_num_rows($result))
    {
        $result_row=  mysqli_fetch_object($result);
        if($result_row->user_deletion=='0')
        {
            $fail_msg="Username already exist!";
        }
//        $query="SELECT * FROM amz_login WHERE host_name_1='$host_name'";
//        $result= $conn->runsql($query,$dbcon);
//        if(mysqli_num_rows($result)!=0)
//        {
//		$fail_msg="Host name already exist. Please register from your own computer";
//        }
        else if($result_row->user_deletion=='1')
        {
            $user_mail=$uname."@amazon.com";
            $query_update_user="UPDATE amz_login SET password='$password', user_type=1, user_deletion='0',team_count='$team_count', user_status=0, user_activation=0 WHERE user_name='$uname'";
            $result_update= $conn->runsql($query_update_user,$dbcon);
            if($result_update)
            {
                $success_msg="User request sent.";
                $query_insert_user_teams="SELECT user_id FROM amz_login WHERE user_name='$uname'";
                $result_insert_user_teams=$conn->runsql($query_insert_user_teams,$dbcon);
                $result_row_insert_user_teams=  mysqli_fetch_object($result_insert_user_teams);
                $user_id=$result_row_insert_user_teams->user_id;
                $query_del_teams="DELETE FROM amz_user_info WHERE user_id='$user_id'";
                $conn->runsql($query_del_teams,$dbcon);
                for($i=0;$i<$team_count;$i++)
                {
                    $query_insert_user_teams_obo="INSERT INTO amz_user_info(user_id,team_id,status,create_date) VALUES('$user_id','$team[$i]','1',now())";
                    $conn->runsql($query_insert_user_teams_obo,$dbcon);
                }
                
            }
            else
            {
                $fail_msg="Something went wrong! Pls try later..";
            }
        }
    }
    else
    {
        $user_mail=$uname."@amazon.com";
      //$query_insert_user="INSERT INTO amz_login(user_name,password,user_type,mot_team,user_mail,user_status,host_name,manager,user_activation,user_deletion,create_date, maintain_date) VALUES ('$uname','$password','3','$mot_team','$user_mail','0','$host_name','$manager','0','0',now(),now())";
        $query_insert_user="INSERT INTO amz_login(first_name,user_name,team_count,password,user_type,user_mail,host_name_1,manager,user_status,user_activation,create_date, maintain_date) VALUES('$uname'  ,'$uname' ,'$team_count','$password','1','$user_mail','$host_name','1','0','0',now(),now())";
        $result_update= $conn->runsql($query_insert_user,$dbcon);
        if($result_update)
        {
            $success_msg="User request sent.";
            $query_insert_user_teams="SELECT user_id FROM amz_login WHERE user_name='$uname'";
            $result_insert_user_teams=$conn->runsql($query_insert_user_teams,$dbcon);
            $result_row_insert_user_teams=  mysqli_fetch_object($result_insert_user_teams);
            $user_id=$result_row_insert_user_teams->user_id;
            for($i=0;$i<$team_count;$i++)
            {
                $query_insert_user_teams_obo="INSERT INTO amz_user_info(user_id,team_id,status,create_date) VALUES('$user_id','$team[$i]','1',now())";
                $conn->runsql($query_insert_user_teams_obo,$dbcon);
            }
        }
        else
        {
            $fail_msg="Something went wrong! Pls try later.";
        }
    }
    
    $ip=$_SERVER['REMOTE_ADDR'];
    $query="INSERT INTO amz_ip_list(ip_address,create_date,maintain_date) VALUES('$ip',now(),now())";
    $result_update= $conn->runsql($query,$dbcon);
    
    if($fail_msg!="")
        echo $fail_msg;
    else 
    {
       // Mail sending start
       $body="Your request registered! <br>Your User Id:".$uname . "<br/> Password:".$gepassword. "<br/> You will get an email once your account is validated!.";
       date_default_timezone_set('America/Toronto');
       require_once('../includes/class.phpmailer.php');
       $mail             = new PHPMailer();
       $mail->IsSMTP();
       // $mail->Host       = "smtp.amazon.com";
       $mail->SMTPDebug  = false;            
       $mail->SMTPAuth   = true;             
       // $mail->SMTPSecure = "ssl";            
       $mail->Host       = "smtp.amazon.com";
       $mail->Port       = 25;
       $mail->Username   = "no-reply@amazon.com"; 
       // $mail->Password   = "******";
       $mail->SetFrom('no-reply@amazon.com', 'Timesheet');

       $mail->Subject    = "Timesheet - Profile request confirmation.";
       $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
       $mail->MsgHTML($body);
       //echo        $address =$user_mail."@amazon.com";
       $mail->AddAddress($user_mail, "Employee Account");
       $mail->Send();        
       if($mail->Send()) {}
       //            echo "<br>Password sent to your mailid. Pls check your mail!";
       else
           echo "Mail can't send!. Pls try later! Reference id ".$gepassword;//.$user_mail.$gepassword;

//               $admin_mails=array();
//               $query="SELECT user_mail FROM amz_login WHERE user_type='3' AND user_status='1' AND user_activation='1' AND user_deletion='0'";
//               $result_mails= $conn->runsql($query,$dbcon);
//               while($result_mail=  mysqli_fetch_object($result_mails))
//               {
//                   $admin_mails[]=$result_mail->user_mail;
//               }
//               $size=  sizeof($admin_mails);
//               for($i=0;$i<$size;$i++)
//               {
//                    $body="New user registerd!. Please login and validate the requested account.";
//                    date_default_timezone_set('America/Toronto');
//                    require_once('../includes/class.phpmailer.php');
//                    $mail             = new PHPMailer();
//                    $mail->IsSMTP();
//                   // $mail->Host       = "smtp.amazon.com";
//                    $mail->SMTPDebug  = false;            
//                    $mail->SMTPAuth   = true;             
//                   // $mail->SMTPSecure = "ssl";            
//                    $mail->Host       = "smtp.amazon.com";
//                    $mail->Port       = 25;
//                    $mail->Username   = "no-reply@amazon.com"; 
//                   // $mail->Password   = "******";
//                    $mail->SetFrom('no-reply@amazon.com', 'Timesheet');
//
//                    $mail->Subject    = "Timesheet - New profile request information.";
//                    $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
//                    $mail->MsgHTML($body);
//                    //echo $address =$user_mail."@amazon.com";
//                    $mail->AddAddress($admin_mails[$i], "Employee Account");
//                    $mail->Send();        
//                    if($mail->Send()) {}
//                    //echo "<br>Password sent to your mailid. Pls check your mail!";
//  //                  else
////                        echo "Mail can't send!. Pls try later";//.$admin_mails[$i];
//               }

        echo $success_msg." Approval will be sent to your mail.";
    }
}
else 
{
    echo "Something went wrong";
}

function gen_md5_password($len=8)
{
    return substr(md5(rand().rand()),0,$len);
}
//include_once 'ldap_update.php';
?>