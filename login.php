<!DOCTYPE html>
<html>
<style>
	
	html body{

		background: white;
	}

	tr:nth-child(even){

		background: #EEEEEE;

	}

	thead tr {

		background: #00C5DC;
	}

	select{
	
		background-color:white;
	}

	#addBtn{

		background: #00C5DC;
		color: white;

	}

	form label {

		color:black;
	}

	h1{	font-family: "PT Sans Narrow", Times, sans-serif; text-align: center; font-size: 60px!important; color: #00C5DC!important; font-weight: bold!important;}

	h4{font-family: "PT Sans Narrow", Times, sans-serif; font-size: 60px!important; text-align: center;}

	.sidebar{

		height: 768px;

	}

	.sidebar a{

		color: white;

	}

	.sideBarLink{

		margin-top: 40px;
		font-size: 18px;

	}

	table{

		margin-top: 50px;
		border: 2px solid black !important;

	}


</style>

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
		
		<!-- Sidebar -->
		<div class="sidebar col-lg-2" style="background: #949494;">
    		<!-- normal collapsible navbar markup -->
    		
    		<div class="imgcontainer">
	        <a href="welcome.html"><img src="images/CRIsidebar.png" 
	        alt="C.R.I. logo" 
	        style="margin-top: 15px;"
	        class="avatar" 
	        width="170px" 
	        height="146.2px"></a>
	        </div>

				<div class="sideBarLink">
    		<?php 



    		echo "<a href='#'>Profile</a><br><br>";				
    		echo "<a href='welcome.html'>Logout</a><br><br>";				

    		 ?>

				</div>
    		
  		</div>

		<!-- Rest of page -->
		<div class="col-lg-10" style="background: white;">
				
		


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
	
		if (!empty($payload['first_name'])) 

		{

				$_SESSION['username']=$username;
				$_SESSION['password']=$password;

				//echo $_SESSION['username'];
				//echo $username;

				
				echo "<h1> Profile </h1>";
				echo "<h4> Welcome back, ".$payload["first_name"]. "	" . $payload["last_name"]."</h4>";
				echo "<br><br>";

			echo " <center> <form id='searchform'>
					<label>Year</label>
					<select name='year' id='yearSelect'>
					</select>

					<label>Make</label>
					<select name='make' id='makeSelect'>
					</select>

					<label>Model</label>
					<select name='model' id='modelSelect'>
					</select>
					

					<br>
					<button type='submit' class='btn btn-default' id='addBtn' name='submit'> Add </button>                		

				</form>	</center>";


				echo "<table border='4' class='table table-bordered' cellspacing='5'>

					 	<thead> <tr>
					 	<th>Year 	</th>
					 	<th>Make </th>
					 	<th>Model 	</th>
					 	<th>Recalls	</th>
					 	<th>	</th>
					 	
					 	</tr></thead>";

					 	echo "<tr>";

				foreach ($payload ["cars"] as $x => $x_value) {
				
				
						echo "<td>" . $x_value['year'] . "</td>";
						echo "<td>" . $x_value['make'] . "</td>";
						echo "<td>" . $x_value['model'] . "</td>";
						echo "<td>" . "<a href='recallInfo.php?id=".$x_value['id']."'> View Recalls </a></td>";				
						echo "<td>" . "<a onclick= deleteUserCar('".$x_value['id']."') href='#'> Remove </a></td>";				
						echo "</tr>";
   
				}
 			
			}

		else {
			echo 'Account is invalid<br>';
			echo "<a href='login.html?action=logout'>Try again</a>";
			}

		if (isset($_GET['logout'])) {
				session_unregister('username');	
			}
	}

	
	?>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script type="text/javascript">	


	function deleteUserCar(id){

        /*alert(id);*/
        $.ajax({
                type: "POST",
                url: "proxy3.php",
                data: {type: "deleteUserCar", id: id}
            }).done(function(result){
            	location.reload();
                //result = JSON.parse(result);                
                //console.log(result);

            });

    }


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
                    
                	});
                	location.reload();


    			});

	});




	</script>

		</div>

	</div><!-- END ROW -->

</div><!-- END Container -->

<!-- Scripts -->
	
	<script type="text/javascript" src="js/material.js"></script>
	<script type="text/javascript">
	$(function () {
	    $.material.init();

	  });
	</script>
</body>
</html>