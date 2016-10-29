<?php 

	session_start();

	require_once('path.inc');
	require_once('get_host_info.inc');
	require_once('rabbitMQLib.inc');


	$client = new rabbitMQClient("apiQueue.ini","testServer");

	

    	$request = array();
		$request['type'] = "apiRequest";
		

		switch ($_POST['type']) 
		{
			case 'year':
				$request['param'] = array();
				break;

			case 'make':
				$request['param'] = array('year' => $_POST['param']['year']);
				break;

			case 'model':
				$request['param'] = array('year' => $_POST['param']['year'],'make' => $_POST['param']['make']);
				break;

			case 'search':
				$request['username']=$_SESSION['username'];
				$request['param'] = array('year' => $_POST['param']['year'],'make' => $_POST['param']['make'],'model'=>$_POST['param']['model']);
				break;
			
		}
		
		//response the site gets from the BE
		$payload = $client->send_request($request);

		echo $payload;


?>