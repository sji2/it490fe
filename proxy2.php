<?php
    if(isset($_POST['type']))
    {

    	$urlBase = 'http://www.nhtsa.gov/webapi/api/Recalls/vehicle';

    	switch ($_POST['type']) 
    	{
   
    		case 'make':
    			$year = $_POST['param']['year'];

    			$url = $urlBase . '/modelyear/'. $year .'?format=json';
		        echo file_get_contents($url); 
    			break;

            case 'search':

                $year = $_POST['param']['year'];
                $make = $_POST['param']['make'];
                $model = '';

                foreach(range($year, 2016, 5) as $yr)
                {
                    $url = $urlBase . '/modelyear/'. $yr .'/make/'. $make .'?format=json';
                    $res = json_decode(file_get_contents($url), true)['Results']; 


                    $numRecalls = 0; //# of total recalls for a make's current year

                    //loop through all models for a year
                    foreach($res as $makeyear)
                    {
                        $model = $makeyear['Model'];
                        //echo $makeyear['Model'] . " ";

                        //get the total # of recalls for given model
                        $recallUrl = $urlBase . '/modelyear/'. $yr .'/make/'. $make . '/model/' . $model .'?format=json';

                        $result = json_decode(file_get_contents($recallUrl), true);
                        $numRecalls += $result['Count'];
                    }
                    echo "Year " . $yr . ": " . $numRecalls . " ";
                }                             
    	}

    }


?>