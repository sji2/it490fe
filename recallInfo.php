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
            <title>Recall Info</title>    
    </head>
    <body>
        <form action="recallInfo.php" metrod="GET">

                <div class="imgcontainer">
                <img src="images/CRIresize.png" alt="C.R.I. logo" class="avatar">
                </div>

                <div class="container">

                </div>

        </form>

        <?php
            
            require_once('path.inc');
            require_once('get_host_info.inc');
            require_once('rabbitMQLib.inc');
            


            session_start();

            $client = new rabbitMQClient("testRabbitMQ.ini","testServer");

            $request = array();
            $request['type'] = 'getRecalls';

            $request['param'] = array('year' => $_GET['year'],'make' => $_GET['make'],'model'=>$_GET['model']);
            
            $payload = $client->send_request($request);
            echo $payload;


            /*echo 'tris is the user $_SESSION User: '.$_SESSION['username'];
            echo "string";
            echo '<div class="container">
                  <table border="1" cellspacing="1">
                        
                        
                        <tr><td align: left><b>Manufacturer    </b></td><td>the nerd cult</td></tr>
                        <tr><td align: left><b>Campaign#   </b></td><td>the nerd cult</td></tr>
                        <tr><td align: left><b>Summary    </b></td><td>the nerd cult</td></tr>
                        <tr><td align: left><b>Consequence    </b></td><td>the nerd cult</td></tr>
                        <tr><td align: left><b>Remedy    </b></td><td>the nerd cult</td></tr>
                        <tr><td align: left><b>Notes    </b></td><td>the nerd cultthe nerd cultthe nerd cultthe nerd cultthe nerd cultthe nerd cultthe nerd cultthe nerd cultthe nerd cultthe nerd cultthe nerd cultthe nerd cultthe nerd cultthe nerd cult</td></tr>
                        
                  </table>

                  
                </div>';*/
        ?>


    </body>
</html>




                   