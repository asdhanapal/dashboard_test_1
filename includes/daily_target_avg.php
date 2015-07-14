<pre>
<?php
require_once '../classes/db.class.php';
include '../includes/time_calc.php';
include_once '../admin/data_page.php';
$conn = new db();
$dbcon = $conn->dbConnect();
echo "<table border=1>";
echo "<td>Month</td><td>team</td><td>task</td><td>sub task</td><td>about cf</td><td>cf updated?</td><td>target</td><td>Avg</td></tr>";
$query="SELECT * from amz_daily_target WHERE month_from='January 2015' ORDER BY team ASC, task ASC, sub_task ASC";
$result = $conn->runsql($query, $dbcon);
while ($result_row = mysqli_fetch_object($result)) {
    echo "<tr><td>".$result_row->month_from."</td>";
    echo "<td>".$result_row->team."</td>";
    echo "<td>".$result_row->task."</td>";
    echo "<td>".$result_row->sub_task."</td>";
    echo "<td>";
    if($result_row->about_cf==1)
        echo "Manual";
    else if($result_row->about_cf==0)
        echo "Auto";
    else
        echo "NA";
    echo "</td>";
    echo "<td>".$result_row->cf_updated."</td>";
    echo "<td>".$result_row->con_fac."</td>";
    echo "<td>";
    if($result_row->con_fac)
        echo $result_row->con_fac/$result_row->con_fac*100;
    echo "</td></tr>";
}
echo "</table>";
?>