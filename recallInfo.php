<!DOCTYPE html>

<html>
<style type>

    html body{

        background: white;
        
    }

    h1{
        font-family: "PT Sans Narrow", Times, sans-serif!important;
        text-align: center;
        font-size: 60px!important;      
        color: #00C5DC!important;
        font-weight: bold!important;
    }

   
    form { 
        
        top: 50%;
        left: 50%;
        widtr:auto;
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
        width: 100%
        border-radius:100%;
        /*widtr: 40%;
        border-radius: 50%;*/
    }

    .container {
       
    }

    span.psw {
       display: block;
       float: none;
    }  	

    .sidebar{

        /*height: 768px;*/
        height: 2500px;

    }

    .sidebar a{

        color: white;

    }

    .sideBarLink{

        margin-top: 40px;
        font-size: 18px;

    }

    tr:nth-child(even){

        background: #EEEEEE;

    }

    thead tr {

        background: #00C5DC;
    }

    table{

        margin:0 auto;
        margin-top: 50px;
        max-width:960px;
        

    }

    /*table th {

        border: 2px solid black !important;         
    }*/





</style>

    <head>
            <title>Recall Info</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Stylesheets -->
     <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/bootstrap-material-design.css">
    <link href="css/navbar-fixed-side.css" rel="stylesheet" />    

    </head>
    <body>

    <div class="container-fluid main">

    <!-- The entire width of the page will be 1 row -->
    <div class="row">

    <!-- Sidebar -->
        <div class="sidebar col-lg-2" style="background: #949494;">
            <!-- normal collapsible navbar markup -->
            
            <div class="imgcontainer">
            <a href="welcome.html"><img src="images/CRIsidebar.png" 
            alt="C.R.I. logo" 
            style="margin-top: 15px; margin-left: -50px;"
            class="avatar" 
            width="170px" 
            height="146.2px"></a>
            </div>

                <div class="sideBarLink">
            <?php 
                         
            echo "<a href='welcome.html'>Logout</a><br><br>";               

             ?>

                </div>
            
        </div>

        <!-- Rest of page -->
        <div class="col-lg-10">
        

<?php
        
    require_once('path.inc');
    require_once('get_host_info.inc');
    require_once('rabbitMQLib.inc');
            
    session_start();
    $client = new rabbitMQClient("testRabbitMQ.ini","testServer");

    $request = array();
    $request['type'] = 'getRecalls';

    $request['id'] = $_GET['id'];
    
    $payload = $client->send_request($request);
    $payload =json_decode($payload, true); 
    //print_r($payload);

    if (isset($_SESSION['username'])) {
            
    echo '<h1> '.$payload[0]['year'].' '.$payload[0]['make'].' '.$payload[0]['model'].' '.'</h1>';

        echo '<div class="container">';        
               
            foreach ($payload as $x => $recall) {
                    
                echo '<table border="1" cellspacing="1">';
                echo '<thead> <tr>
                         <th>Category    </th>
                        <th>Details </th>                        
                            </tr></thead>';


                foreach ($recall as $y => $value){
                            
                    if($y == 'checked'){

                        if($value == '1'){

                            echo '<tr><td><b>'.ucfirst('Repaired?').'</b></td><td align: left>'.'<input onclick="updateCheck('.$recall['uuid'].')" type="checkbox" checked="checked">'.'</td></tr>';
                            }

                        else{

                            echo '<tr><td><b>'.ucfirst('Repaired?').'</b></td><td align: left>'.'<input onclick="updateCheck('.$recall['uuid'].')" type="checkbox">'.'</td></tr>';                      
                            }
                        
                        continue;

                        }


                    echo '<tr><td><b>'.ucfirst($y).'</b></td><td align: left>'.$value.'</td></tr>';                       
                    }

                   echo '</table><br><br>';
                    }
            
        echo '</div>'; 
    }
?>

</div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script type="text/javascript">
        
    function updateCheck(id){

        /*alert(id);*/
        $.ajax({
                type: "POST",
                url: "proxy3.php",
                data: {type: "toggleRecall", uuid: id}                
            }).done(function(result){
                //result = JSON.parse(result);                
                //console.log(result);

            });

    }



    </script>


    </body>



</html>




                   