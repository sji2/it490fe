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
        width: 100%;
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
        width: 100%;
    }

    label 
    { 
        float: left;         
        width: 150px;
    }

    .imgcontainer {
        text-align: center;
        margin: auto;
        /*margin: 24px 0 12px 0;*/
    }

    img.avatar {
        width: 100%
        border-radius:100%;
        /*width: 40%;
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
        <form action="recallInfo.php" method="post">

                <div class="imgcontainer">
                <img src="images/CRIresize.png" alt="C.R.I. logo" class="avatar">
                </div>

                <div class="container">

                </div>

        </form>

        <?php
            session_start();
            echo 'this is the $_SESSION User: '.$_SESSION['username'];
            echo '<div class="container">
                                                
                </div>'
        ?>


    </body>
</html>
