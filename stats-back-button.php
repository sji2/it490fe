
<!DOCTYPE html>
<html>
<head>
    <title></title>

    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="css/bootstrap-material-design.css">
    <style>
      body{
        background-color: white;
      }

      h1{
        font-family: PT Sans Narrow;
        text-align: center;
        color: #00C5DC;
        font-size: 60px;
        font-weight: bold;
        margin-bottom: 50px;
      }
      h4{
        font-family: PT Sans Narrow;
        text-align: center;
        color: #00c5dc;
        font-size: 20px;
        font-weight: bold;        
      }

      .stat{
        margin-bottom: 100px;
      }
      .btn {position: absolute; background:white; color: #00c5dc; border-style: solid; border-color: #00c5dc; border-width: 2px; text-align: center;}
    </style>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){

            year = $("#yearSelect").val();
            $("#makeSelect").empty();
            $("#makeSelect2").empty();
            $("#makeSelect3").empty();

            //fill yearSelect2 and 3 with years from 1950-2016
            for(i = 1950; i < 2017; i++)
            {
              $('#yearSelect2').append("<option value="+i+">"+i+"</option>");
              $('#yearSelect3').append("<option value="+i+">"+i+"</option>");
            }

            $('#yearSelect2').on("change", function(){
                year = this.value;
                $("#makeSelect2").empty();
                $.ajax
                ({
                    type: "POST",
                    url: "proxy2.php",
                    data: {type: "make", param: {year: year}},
                }).done(function(result)
                {

                    result = JSON.parse(result);
                    results = result['Results'];

                    for(row in results)
                    {
                        //console.log(results[row]['Make']);
                        make = results[row]['Make'];
                        $("#makeSelect2").append("<option value="+make+">"+make+"</option>");
                    }
                });
            });

            //call api for makes for first stat
            $.ajax
            ({
                type: "POST",
                url: "proxy2.php",
                data: {type: "make", param: {year: year}},
            }).done(function(result)
            {

                result = JSON.parse(result);
                results = result['Results'];

                for(row in results)
                {
                    //console.log(results[row]['Make']);
                    make = results[row]['Make'];
                    $("#makeSelect").append("<option value="+make+">"+make+"</option>");
                }
            });


            //get recalls by make every 5 yrs
            $('#searchform').submit(function(event)
            {
                $('#stats').empty();
                year = $("#yearSelect").val();
                make = $("#makeSelect").val();

                event.preventDefault();

                $.ajax({
                    type: "POST",
                    url: "proxy2.php",
                    data: {type: "search", param: {year: year, make: make}},
                }).done(function(result)
                {
                    //console.log(result);
                    //$('#stats').append(result);

                    //turn result(json) into object
                    json = JSON.parse(result);

                    //console.log('testing:');
                    //console.log(json);

                    recallChart = [
                      ['# Recalls ', 'recalls'],
                    ];

                    for(key in json)
                      recallChart.push([key, json[key]]);

                    console.log(recallChart);
                    drawBasic(recallChart);
                });
            });

            //get recalls by make
            $('#searchform2').submit(function(event){
              year = $("#yearSelect2").val();
              make = $("#makeSelect2").val();

              event.preventDefault();

              $.ajax({
                  type: "POST",
                  url: "proxy2.php",
                  data: {type: "makeRecall", param: {year: year, make: make}},
              }).done(function(result)
              {
                  //console.log(result);

                  //turn result(json) into object
                  json = JSON.parse(result);

                  recallChart = [
                    ['Model ', '# recalls'],
                  ];

                  for(key in json)
                    recallChart.push([key, json[key]]);

                  console.log(recallChart);
                  drawDonut(recallChart, make, year);
              });

            });

            google.charts.load('current', {packages: ['corechart', 'bar']});
            //google.charts.setOnLoadCallback(drawBasic);
            function drawBasic(arr)
            {

            var data = google.visualization.arrayToDataTable(arr);

            var options = {
              title: '# of Recalls every 5 years',
              chartArea: {width: '70%'},
              hAxis: {
                title: 'Recalls',
                minValue: 0
              },
              vAxis: {
                title: 'Year'
              }
            };

            var chart = new google.visualization.BarChart(document.getElementById('chart_div'));

            chart.draw(data, options);
           }

           function drawDonut(arr, make, year) {
             var data = google.visualization.arrayToDataTable([
               ['Task', 'Hours per Day'],
               ['Work',     11],
               ['Eat',      2],
               ['Commute',  2],
               ['Watch TV', 2],
               ['Sleep',    7]
             ]);

             var data = google.visualization.arrayToDataTable(arr);

             var options = {
               title: 'Recalls per '+ year +' ' + make + ' Model',
               pieHole: 0.4,
             };

             var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
             chart.draw(data, options);
           }

        });
    </script>
</head>
<body>

  <!-- Everything must be inside a boostrap container -->
  <div class="container-fluid main">

  	<!-- The entire width of the page will be 1 row -->
  	<div class="row">

  <!-- Go Back Button -->

      <left>
      <button onclick="window.location.href='welcome.html'" type="submit" class = 'btn' id='go' name='submit'><h4>Go Back</h4></button>
      </left>

      <h1>Statistics</h1>

<!-- First Stat -->
<div class="stat">
      <center>
      <h2>Total Recalls by Manufacturer (5 year intervals)</h2>
      <h3>Starting from 1990</h3>
      <form id="searchform">
          <input type="hidden" id="yearSelect" value="1990"></input>

          <label>Make</label>
          <select name="make" id="makeSelect">
          </select>
          <input type="submit"></input>
      </form>
</center>
<div id="chart_div"></div>
</div>


<!-- Second Stat -->
<div class="stat">
      <center>
      <h2>Recalls by Model</h2>
      <form id="searchform2">
          <input type="hidden" id="yearSelect22" value="1990"></input>
          <label>Year</label>
          <select name="yearSelect2" id="yearSelect2">
          </select>

          <label>Make</label>
          <select name="make" id="makeSelect2">
          </select>
          <input type="submit"></input>
      </form>
      </center>
<div id="donutchart" style="height: 500px;"></div>
</div>

  	</div><!-- END ROW -->

  </div><!-- END Container -->

  <script type="text/javascript" src="js/material.js"></script>
	<script type="text/javascript">
	$(function () {
	    $.material.init();

	  });
	</script>
</body>
</html>
