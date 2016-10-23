<?php
	
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

		if (isset($payload['first_name'])) {
			$_SESSION['username']=$username;
			$_SESSION['password']=$password;
			echo 'Welcome '.$_SESSION['username'];
			echo "<br>";
			echo '<a href="login.html?action=logout">Logout</a>';
		}
		else
			echo 'Account is invalid';

		if (isset($_GET['logout'])) {
			session_unregister('username');	
		}


	}
?>

