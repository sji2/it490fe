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

         <!--    <script type="text/javascript">
    
            function goBack() {
            var link = "login.php?action=Login"
            document.getElementById("demo").innerHTML = link;
            //window.history.back()
                }
            </script> -->
    </head>
    <body>
        <form action="recallInfo.php" metrod="GET">

                <div class="imgcontainer">
                <img src="images/CRIresize.png" alt="C.R.I. logo" class="avatar">
                </div>

                <button onclick="goBack()">Go Back</button>

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
            $payload =json_decode($payload, true); 
            //echo $payload;

echo '<div class="container">
                  <table border="1" cellspacing="1">
                        <tr>
                        <td align: left><b>Year    </b></td>
                        <td align: left><b>Make    </b></td>
                        <td align: left><b>Model    </b></td>

                        <td align: left><b>Manufacturer    </b></td>
                        <td align: left><b>Campaign#   </b></td>
                        <td align: left><b>Manufacturer    </b></td>
                        <td align: left><b>Report Received On     </b></td>
                        <td align: left><b>Components    </b></td>
                        <td align: left><b>Summary    </b></td>
                        <td align: left><b>Consequence    </b></td>
                        <td align: left><b>Remedy    </b></td>
                        <td align: left><b>Notes    </b></td>
                        </tr>';
               foreach ($payload as $x => $recall) {
                        echo'<tr>';
                            foreach ($recall as $y => $value) {
                                echo '<td align: left>'.$value.'</td>';

                            }
                        echo'</tr>';
                        }


                        
            echo '</table>                  
        </div>'; 

             
        ?>


    </body>
</html>




                   