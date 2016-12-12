<!DOCTYPE html>
<html>
<head>
	<title></title>

	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Stylesheets -->
	 <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/bootstrap-material-design.css">
	<link href="css/navbar-fixed-side.css" rel="stylesheet" />

	
</head>
<body>

<!-- Everything must be inside a boostrap container -->
<div class="container-fluid main">

	<!-- The entire width of the page will be 1 row -->
	<div class="row">
		
		<!-- Inside this row we will have 2 columns 
			 one for the sidebar the other for the rest of the page 
			
			 Width in Bootstrap:
			 Bootstrap always divides the width in the entire page into columns. The smallest column width is 1 and the largest is 12(entire page). We can have any number of columns in page as long as their total widths add up to 12.
			 
			 In our case we want the column of the sidebar to be 2 columns long and the rest of the page will be 10 long(12-2=10).  

			 *Columns must always be inside a row*
	
			 Check for more into on "grid system"
			 http://www.w3schools.com/bootstrap/bootstrap_grid_basic.asp
		-->

		<!-- Sidebar -->
		<div class="col-lg-1" style="background: #949494; height: 760px;">
    		<!-- normal collapsible navbar markup -->
    		SIDEBAR
  		</div>

		<!-- Rest of page -->
		<div class="col-lg-11" style="background: white; height: 760px;">
				
		<div class="imgcontainer">
        <img src="images/CRIresize.png" 
        alt="C.R.I. logo" 
        class="avatar" 
        width="20%" 
        height="20%">
        </div>


<?php

	//required configuration files to connect to RabbitMQ

	require_once('path.inc');
	require_once('get_host_info.inc');
	require_once('rabbitMQLib.inc');

	ini_set('session_cache_limiter()', 'public');
	session_cache_limiter(false);
	
	session_start();

	if (!empty($_POST)) {

		//retrieve the username and password from the login.html
		$username = $_POST["username"];
		//$password = $_POST["password"];		
		$password = sha1($_POST["password"]);		
		$type = $_POST["type"];

		//assign $client
		$client = new rabbitMQClient("testRabbitMQ.ini","testServer");

		$request = array();
		$request['type'] = $type;
		$request['username'] = "$username";
		$request['password'] = "$password";
		//$request['message'] = $msg;

		//response the site gets from the BE
		$payload = $client->send_request($request);
		
		
		//assign a payload variable to have the response from the server
	
		echo "<div class='container'>";
		
		

		if (!empty($payload['first_name'])) 

		{

				$_SESSION['username']=$username;
				$_SESSION['password']=$password;

				//echo $_SESSION['username'];
				//echo $username;

				echo "<div style='text-align:right'><a href='login.html?action=Logout'>Logout</a></div><br><br>";				
				echo "<h1> Welcome ".$payload["first_name"]. "	" . $payload["last_name"]."</h1>";
				echo "<br><br>";

			echo "<form id='searchform'>
					<label>Year</label>
					<select name='year' id='yearSelect'>
					</select>

					<label>Make</label>
					<select name='make' id='makeSelect'>
					</select>

					<label>Model</label>
					<select name='model' id='modelSelect'>
					</select>
					
					<button type='submit' name='submit'> Add </button>                		

				</form>	";


				echo "<table border='4' class='stats' cellspacing='5'>

					 	<tr>
					 	<th>Year	</th>					 	
					 	<th>Make	</th>
					 	<th>Model 	</th>
					 	</tr>";

					 	echo "<tr>";

				foreach ($payload ["cars"] as $x => $x_value) {
				
				 	foreach ($x_value as $y => $y_value) {
							
					            echo "<td>" . "$y_value" . "</td>";
						}
						echo "<td>" . "<a href='recallInfo.php?year=".$x_value['year']."&make=".$x_value['make']."&model=".$x_value['model']."'> View Recalls </a>". "</td>";
						echo "</tr>";
   			
   
				}
 
			echo "</div>";
			}

		else {
			echo 'Account is invalid<br>';
			echo "<a href='login.html?action=logout'>Try again</a>";
			}

		if (isset($_GET['logout'])) {
				session_unregister('username');	
			}

		
		

	}
	/*echo "client received response: ".PHP_EOL;
	print_r($response);
	echo "\n\n";
	*/

	
	?>


	<script type="text/javascript">		
	


	$(document).ready(function(){	


			


	//call api for model years
		$.ajax({
                type: "POST",
                url: "proxy.php",
                data: {type: "year"}                
            }).done(function(result){
                result = JSON.parse(result);                
                results = result['Results'];
                                

                for(row in results)
                {
                    //skip first row
                    if(row > 0)
                    {
                        year = results[row]['ModelYear'];
                        $("#yearSelect").append("<option value="+year+">"+year+"</option>");
                    }
                }
            });
    //event handler when year is selected
    $("#yearSelect").on("change", function(){
                year = this.value;
                $("#makeSelect").empty();
                $("#modelSelect").empty();

                //call api for makes
                $.ajax({
                    type: "POST",
                    url: "proxy.php",
                    data: {type: "make", param: {year: year}},
                }).done(function(result){
                    
                    result = JSON.parse(result);
                    results = result['Results'];

                    for(row in results)
                    {
                        //console.log(results[row]['Make']);
                        make = results[row]['Make'];
                        $("#makeSelect").append("<option value="+make+">"+make+"</option>");
                    }
                });
            });
    //event handler when Make is selected
    $("#makeSelect").on("change", function(){
                make = this.value;
                year = $("#yearSelect").val();
                $("#modelSelect").empty();

                //call api for models
                $.ajax({
                    type: "POST",
                    url: "proxy.php",
                    data: {type: "model", param: {year: year, make: make}},
                }).done(function(result){
                    result = JSON.parse(result);
                    results = result['Results'];  

                    for(row in results)
                    {
                        model = results[row]['Model'];
                        $("#modelSelect").append("<option value="+model+">"+model+"</option>");
                    }   
                });
            });

    $('#searchform').submit(function(event){
    			
    			//Get year make and model using jquery selecter

    			year = $("#yearSelect").val();
    			make = $("#makeSelect").val();
				model = $("#modelSelect").val();
				


    			event.preventDefault();
    			 $.ajax({
                    type: "POST",
                    url: "proxy.php",
                    data: {type: "search", param: {year: year, make: make, model:model}},
                }).done(function(result){
                    console.log(results);
                    result = JSON.parse(result);
                    results = result['Results'];  
                    console.log(results);

                    $('.stats').append("<tr><td>"+year+"</td><td>"+make+"</td><td>"+model+"</td></tr>");

                    

// adding to a row
                    
                	});


    			});

	});




	</script>

		</div>

	</div><!-- END ROW -->

</div><!-- END Container -->

<!-- Scripts -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script type="text/javascript" src="js/material.js"></script>
	<script type="text/javascript">
	$(function () {
	    $.material.init();

	  });
	</script>
</body>
</html>