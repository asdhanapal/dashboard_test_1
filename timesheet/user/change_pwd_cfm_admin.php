<?php
require_once '../classes/db.class.php';
$conn = new db();
$dbcon=$conn->dbConnect();
session_start();
$fail_msg=$success_msg='';

if( (isset($_GET)))
{
	$uid=$_SESSION['user_id'];
        $uname=$_SESSION['user_name'];
        $old_password= md5(($_POST['oldpass']));
	$new_password=  ($_POST['newpass']);
	$confirm_password=  ($_POST['confirmpass']);
        $query="SELECT password FROM amz_login WHERE user_name='$uname'";
        $result= $conn->runsql($query,$dbcon);
        if(mysqli_num_rows($result))
            list($password)=mysqli_fetch_array($result);
	
	if($password!=$old_password)
	{
            $fail_msg="Incorrect old password";
	}
	else if($new_password!=$confirm_password)
	{
            $fail_msg="Old and New password Mismatch";
	}
	else
	{
	    $new_password=md5($new_password);
            $query_update_user="UPDATE amz_login SET password='$new_password' WHERE user_name='$uname'";
            $result_update= $conn->runsql($query_update_user,$dbcon);
            if($result_update)
            {
                $success_msg="Password updated successfully";
	$body="Your Password updated successfully";
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
        $mail->Username   = "no-reply-timesheet@amazon.com";
       // $mail->Password   = "******";
        $mail->SetFrom('no-reply-timesheet@amazon.com', 'Timesheet');
        //$mail->AddReplyTo("seetharr@amazon.com","Admin");

        $mail->Subject    = "Timesheet - Password changed";
        $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
        $mail->MsgHTML($body);
        $address =$uname."@amazon.com";
        $mail->AddAddress($address, "Employee Account");
        $mail->Send();        
            }
            else
            {
                $fail_msg="Password Failed! Pls try later.";
            }
	}
	
        //if($mail->Send())
            //echo "<br>Password sent to your mailid. Pls check your mail!";
        //else
          //  echo "Mail can't send!. Pls try later";
}
if ($success_msg=="")
    echo $fail_msg;
else
echo $success_msg;
?>
