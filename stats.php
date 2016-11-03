
<!DOCTYPE html>
<html>
<style>
    

    body{
        /*background-color: #ffffff;*/
        background-color: #00c7dc;
    }

    .imgcontainer {
        /*background-color: #ffffff;*/
        text-align: left;
        margin: auto;
        /*margin: 24px 0 12px 0;*/
    }
    
    img.avatar {
        
        border-radius: auto;
    }

    .container {
        padding: 16px;
        text-align: center;
    }

</style>



<head>
    <title>Statistics Page</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

    <script type="text/javascript">
        
        $(document).ready(function(){

            year = $("#yearSelect").val();
            $("#makeSelect").empty();

            //call api for makes
            $.ajax
            ({
                type: "POST",
                url: "proxy2.php",
                data: {type: "make", param: {year: year}},
            }).done(function(result)
            {
                
                result = JSON.parse(result);
                results = result['Results'];

                for(row in results)
                {
                    //console.log(results[row]['Make']);
                    make = results[row]['Make'];
                    $("#makeSelect").append("<option value="+make+">"+make+"</option>");
                }
            });



            $('#searchform').submit(function(event)
            {
                $('#stats').empty();
                year = $("#yearSelect").val();
                make = $("#makeSelect").val();

                event.preventDefault();

                $.ajax({
                    type: "POST",
                    url: "proxy2.php",
                    data: {type: "search", param: {year: year, make: make}},
                }).done(function(result)
                {
                    console.log(result);
                    $('#stats').append(result);


                });
            });

        });
    </script>
</head>
<body>
        <div class="imgcontainer">
        <img src="images/CRIresize.png" 
        alt="C.R.I. logo" 
        class="avatar" 
        width="20%" 
        height="20%">
        </div>

<h1># Recalls per 5 years given a make and model</h1>
<h2>Starting from 1990</h2>
<form id="searchform">
    <input type="hidden" id="yearSelect" value="1990"></input>  

    <label>Make</label>
    <select name="make" id="makeSelect">
    </select>


    <input type="submit"></input>
</form>

<p id="stats">

</p>

  

</body>
</html>
