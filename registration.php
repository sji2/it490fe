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
$request['password'] = $password;
//$request['message'] = $msg;
$response = $client->send_request($request);
//$response = $client->publish($request);

$payload = json_encode($response);
echo $payload;

/*echo "client received response: ".PHP_EOL;
print_r($response);
echo "\n\n";
*/

?>
