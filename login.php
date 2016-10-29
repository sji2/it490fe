<!DOCTYPE html>
<html>

<style>
	

	body{
        /*background-color: #ffffff;*/
        background-color: #00c7dc;
    }

   	.imgcontainer {
   		/*background-color: #ffffff;*/
        text-align: left;
        margin: auto;
        /*margin: 24px 0 12px 0;*/
    }
	
    img.avatar {
        
        border-radius: auto;
    }

    .container {
        padding: 16px;
        text-align: center;
    }

</style>

	<head>
		<title>Profile</title>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

	</head>

	<body>
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
	
	session_start();
	
	//echo $_POST['username'];
	//print_r($_POST);


	if (!empty($_POST)) {

		//retrieve the username and password from the login.html
		$username = $_POST["username"];
		$password = $_POST["password"];
		//$password = sha1($password);
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

				echo "<div style='text-align:right'><a href='login.html?action=logout'>Logout</a></div><br><br>";				
				echo "Welcome ".$payload["first_name"] . "	" . $payload["last_name"];
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
   			echo "</div>";
   
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


	</body>
</html>

