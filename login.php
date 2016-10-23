<!DOCTYPE html>
<html>

<style>
	

	body{
        background-color: #00c7dc;
    }

   	.imgcontainer {
        text-align: left;
        margin: auto;
        /*margin: 24px 0 12px 0;*/
    }
	
    img.avatar {
        
        border-radius: auto;
    }

    .container {
        padding: 16px;
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
        width="10%" 
        height="10%">
        </div>


<?php

	//required configuration files to connect to RabbitMQ

	require_once('path.inc');
	require_once('get_host_info.inc');
	require_once('rabbitMQLib.inc');

	session_start();
	if (isset($_POST['bttLogin'])) {

		//retrieve the username and password from the login.html
		$username = $_POST["username"];
		$password = $_POST["password"];
		//$password = sha1($password);

		//assign $client
		$client = new rabbitMQClient("testRabbitMQ.ini","testServer");

		$request = array();
		$request['type'] = "login";
		$request['username'] = $username;
		$request['password'] = $password;
		//$request['message'] = $msg;

		//response the site gets from the BE
		$payload = $client->send_request($request);
		//$response = $client->publish($request);

		//assign a payload variable to have the response from the server
		
		if (isset($payload['first_name'])) {

				$_SESSION['username']=$username;
				$_SESSION['password']=$password;

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
						

					/*echo "Car Make:	".$x_value["make"];
					echo "<br>";
					echo "Car Model:	".$x_value["model"];
					echo "<br>";
					echo "Car Year:	".$x_value["year"];
					echo "<br>";
					echo "Plate Number:	".$x_value["plateNumber"];	
					echo "<br><br><br>";

					foreach ($x_value as $y => $y_value) {
						echo "<li> ".$y_value."</li>";

					}*/

				}
				//echo "</ul>";
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

