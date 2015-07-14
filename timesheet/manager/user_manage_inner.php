<?php
require_once '../classes/db.class.php';
$conn = new db();
$dbcon = $conn->dbConnect();

if(isset($_GET['action']))
{
    //print_r($_POST);
	$id = $_POST['id'];
        $status_tmp=$_POST['status'];
        $status=$status_tmp=='A'?" user_activation='1', user_status='1' ":" user_deletion='1' ";
        $sql1 = "UPDATE amz_login SET $status WHERE user_id='$id'";
        $result1=$conn->runsql($sql1,$dbcon);
	if($result1)
	{
               echo "<font color='green'>User updation complete!</font>";
               $query_getemail="SELECT user_mail FROM amz_login where user_id='$id'";
               $result_1= $conn->runsql($query_getemail,$dbcon);
               $user_mail=  mysqli_fetch_object($result_1);
               $address=$user_mail->user_mail;

               $body="Your account is activated! Please login and change your password!";
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

               $mail->Subject    = "Timesheet - Profile activation confirmation.";
               $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
               $mail->MsgHTML($body);
       //echo        $address =$user_mail."@amazon.com";
               $mail->AddAddress($address, "Employee Account");
               $mail->Send();        
               if($mail->Send()) {}
       //            echo "<br>Password sent to your mailid. Pls check your mail!";
               else
                   echo "Mail can't send!. Pls try later";
	}
	else
	{
		echo "<font color='red'>Something went wrong!</font>";
               $query_getemail="SELECT user_mail FROM amz_login where user_id='$id'";
               $result_1= $conn->runsql($query_getemail,$dbcon);
               $user_mail=  mysqli_fetch_object($result_1);
               $address=$user_mail->user_mail;

               $body="Your account is rejected!<br> Please reach your SDA/Manager for more details!";
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

               $mail->Subject    = "Timesheet - Profile Rejection Notification.";
               $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
               $mail->MsgHTML($body);
       //echo        $address =$user_mail."@amazon.com";
               $mail->AddAddress($address, "Employee Account");
               $mail->Send();        
               if($mail->Send()) {}
       //            echo "<br>Password sent to your mailid. Pls check your mail!";
               else
                   echo "Mail can't send!. Pls try later";
	
	}
        
}
?>