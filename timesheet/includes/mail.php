<form action="" method="post">
    <input type="text" name="to" placeholder="To: xxx@amazon.com"><br>
    <input type="text" name="cc" placeholder="CC: yyy@amazon.com"><br>
    <input type="text" name="sub" placeholder="Subject"><br>
    <textarea name="msg" placeholder="Your message goes here"></textarea><br>
    <input type="submit">
</form>


<?php
require_once '../classes/db.class.php';
$conn = new db();
$dbcon=$conn->dbConnect();
session_start();
$fail_msg=$success_msg='';
print_r($_POST);
if(!empty($_POST))
{
	$to=$_POST['to'];
        $cc=$_POST['cc'];
        $sub=$_POST['sub'];
        $body= $_POST['msg'];
	
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
        $mail->SetFrom('no-reply-timesheet@amazon.com', 'Timesheet');

        $mail->Subject    = $sub;
        $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
        $mail->MsgHTML($body);
        $address =$to."@amazon.com";
        $mail->AddAddress($address, "Employee Account");
        $mail->Send();        
        if($mail->Send())
            echo "Mail sent!";
        else
            echo "Mail can't send!. Pls try later";
}?>
