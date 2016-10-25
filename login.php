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
	
	echo $_POST['username'];
	print_r($_POST);


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

		if (!empty($payload['first_name'])) {

				$_SESSION['username']=$username;
				$_SESSION['password']=$password;

				//echo $_SESSION['username'];
				//echo $username;

				echo "<div style='text-align:right'><a href='login.html?action=logout'>Logout</a></div><br>";
				echo "<br>";
				echo "Welcome ".$payload["first_name"] . "	" . $payload["last_name"];
				echo "<br><br>";

				
				//echo "<ul>";
				echo "<table border='4' class='stats' cellspacing='5'>

					 	<tr>
					 	<th>Username	</th>
					 	<th>License Plate	</th>
					 	<th>Year	</th>
					 	<th>Make	</th>
					 	<th>Model 	</th>
					 	</tr>";

					 	echo "<tr>";

				foreach ($payload ["cars"] as $x => $x_value) {
				
					//TO MAKE A TABLE LOOK AT THE FORM.PHP IN 202 FOLDER Downloads/TestSite
					 
					 	foreach ($x_value as $y => $y_value) {
							
					            echo "<td>" . "$y_value" . "</td>";
						}
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
	


	</body>
</html>

