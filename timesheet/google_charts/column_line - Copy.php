<html>
  <head>
      <script type="text/javascript" src="file/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawVisualization);

function drawVisualization() {
  // Some raw data (not necessarily accurate)
  var options = {
    title : 'Monthly Report',
    vAxis: {title: "Work units"},
    hAxis: {title: "Tasks"},
    seriesType: "bars",
    series: {1: {type: "line"}},
    
  };
    var data = google.visualization.arrayToDataTable([
    ['Month','Tasks','Average'],
    ['Task 1',  165,   10],
    ['Task 2',  135,   500],
    ['Task 3',  157,   523],
    ['Task 4',  139,   509.4],
    ['Task 5',  139,   509.4],
    ['Task 6',  139,   509.4],
    ['Task 7',  139,   509.4],
    ['Task 8',  139,   509.4],
    ['Task 9',  136,   569.6]
  ]);

  

  var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
  chart.draw(data, options);
}
    </script>
  </head>
  <body>
    <div id="chart_div" style="width: 900px; height: 500px;"></div>
  </body>
</html>