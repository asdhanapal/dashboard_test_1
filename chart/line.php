<?php
//print_r($_POST);
$date_from=$_POST['from_date'];
$date_to=$_POST['to_date'];
$report_view=$_POST['report_view'];
$team=$_POST['team'];
$task=$_POST['task'];
$user=$_POST['user'];
$datetime1 = new DateTime($date_from);
$datetime2 = new DateTime($date_to);
$interval = $datetime1->diff($datetime2);
echo $interval->format('%a days');


$ts1 = strtotime($date_from);
$ts2 = strtotime($date_to);

$year1 = date('Y', $ts1);
$year2 = date('Y', $ts2);

$month1 = date('m', $ts1);
$month2 = date('m', $ts2);

echo $diff = (($year2 - $year1) * 12) + ($month2 - $month1);

?>
<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>