<?php
require_once '../classes/db.class.php';
$conn = new db();
$dbcon=$conn->dbConnect();

$flag="";

if( (isset($_GET)) && ($_POST['uname']!=""))
{
    $uname=  $_POST['uname'];
    $gepassword = gen_md5_password(8);
    $password = md5($gepassword);

    $query="SELECT * FROM amz_login WHERE user_name='$uname'";
    $result_1= $conn->runsql($query,$dbcon);
    if(mysqli_num_rows($result_1))
    {
        $user_mail=$uname."@amazon.com";
        $query_update_user="UPDATE amz_login SET password='$password' WHERE user_name='$uname'";
        $result_update= $conn->runsql($query_update_user,$dbcon);
        if($result_update)
            $flag=1;
        else
           $flag=0;
    }
    
    if($flag==0)
    {
        $result[0]="F";
        $result[1]=$fail_msg;
        echo json_encode($result);
        exit();
    }
    else 
    {
       $body="As per your request, a new password has been generated for your account.<br><br>New Password:".$gepassword. "<br><br/>Please contact administrator if not requested!";
       date_default_timezone_set('America/Toronto');
       require_once('../includes/class.phpmailer.php');
       $mail             = new PHPMailer();
       $mail->IsSMTP();
       $mail->SMTPDebug  = false;            
       $mail->SMTPAuth   = true;             
       $mail->Host       = "smtp.amazon.com";
       $mail->Port       = 25;
       $mail->Username   = "no-reply@amazon.com"; 
       $mail->SetFrom('no-reply@amazon.com', 'Timesheet');
       $mail->Subject    = "Timesheet - New password";
       $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
       $mail->MsgHTML($body);
       $mail->AddAddress($user_mail, "Employee Account");
       $mail->Send();        
       if($mail->Send()) {
           $result[0]="S";
           $result[1]="New Password generated successfully!. You will receive an email shortly!.";
       }
       else
       {
            $result[0]="F";
            $result[1]="Mail can't send!. Pls try later! Reference id ".$gepassword;
       }
    }
}
else 
{
    $result[0]="F";
    $result[1]="Something went wrong";
}

echo json_encode($result);


function gen_md5_password($len=8) // GGenerate the MD5 text.
{
    return substr(md5(rand().rand()),0,$len);
}
?>