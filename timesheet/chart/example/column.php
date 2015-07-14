<html>
    <head>
        <script src="../../js/jquery.js"></script>
        <script src="../js/highcharts.js"></script>
        <script src="../js/modules/exporting.js"></script>
        <script type="text/javascript">
            function chart()
            {
                $.getJSON('data.json', function(data) {
                    var chart = new Highcharts.Chart(data);
                });
            }
        </script>
    </head>
    <body>
        <div id="container" style="width:100%; height:100%;"></div>    
        
        <input type="button" onclick="chart();" value="Click me to get chart">
    </body>
</html>
