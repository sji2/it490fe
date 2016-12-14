<?php 

	session_start();

	require_once('path.inc');
	require_once('get_host_info.inc');
	require_once('rabbitMQLib.inc');


	$client = new rabbitMQClient("testRabbitMQ.ini","testServer");

	

    	$request = array();
		$request['type'] = "toggleRecall";
		

		switch ($_POST['type']) 
		{
			case 'toggleRecall':
				$request['uuid'] = $_POST['uuid'];
				break;
			
		}
		
		//response the site gets from the BE
		$payload = $client->send_request($request);

		echo $payload;


?>