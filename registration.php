<!DOCTYPE html>
<html>
<style type>

    body
    {
    background-color: #00c7dc;
    }

    form { 
        /*border: 2px solid #f1f1f1;*/
        top: 50%;
        left: 50%;
        width:auto;
        padding-left: 250px;
        padding-right: 250px;
    }

    input[type=text], input[type=password] {
        widtr: 100%;
        padding: 12px 20px;
        margin: 8px 0;
        display: inline-block;
        border: 1px solid #ccc;
        box-sizing: border-box;
    }

    button {
        background-color: #4CAF50;
        color: white;
        padding: 14px 20px;
        margin: 8px 0;
        border: none;
        cursor: pointer;
        widtr: 100%;
    }

    label 
    { 
        float: left;         
        widtr: 150px;
    }

    .imgcontainer {
        text-align: center;
        margin: auto;
        /*margin: 24px 0 12px 0;*/
    }

    img.avatar {
        widtr: 100%
        border-radius:100%;
        /*widtr: 40%;
        border-radius: 50%;*/
    }

    
    .container {
        padding: 16px;
    }

    span.psw {
       display: block;
       float: none;
    }  	

</style>

    <head>
            <title>Registration</title>    

    </head>
    <body>
        <form>

                <div class="imgcontainer">
                <img src="images/CRIresize.png" alt="C.R.I. logo" class="avatar"></img>
                


<?php

	//required configuration files to connect to RabbitMQ

	require_once('path.inc');
	require_once('get_host_info.inc');
	require_once('rabbitMQLib.inc');

	//retrieve the username and password from the login.html
	$firstName = $_POST["firstName"];
	$lastName = $_POST["lastName"];
	$username = $_POST["username"];
	$password = $_POST["password"];
	//$password = sha1($password);

	//print the username and password
	//print("this is $username <br>and this is $password");

	//assign $client
	$client = new rabbitMQClient("testRabbitMQ.ini","testServer");

	$request = array();
	$request['type'] = "register";
	$request['firstName'] = $firstName;
	$request['lastName'] = $lastName;
	$request['username'] = $username;
	$request['password'] = sha1($password);
	$response = $client->send_request($request);

	echo '<br>'.$response['message'].'<br>';
	
?>

		</div>
		</form>
		<div class="imgcontainer">
		<button onclick="location.href='login.html'">Login</button>
		</div>
	</body>
</html>