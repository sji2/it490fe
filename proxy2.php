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

          $json = array();
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
              $json[$yr] = $numRecalls;
              //echo "Year " . $yr . ": " . $numRecalls . " ";
          }
          $json = json_encode($json);
          echo $json;
          break;


        case 'makeRecall':
          $year = $_POST['param']['year'];
          $make = $_POST['param']['make'];
          $model = '';

          $json = array();

          //get all models for given make and year
          $url = $urlBase . '/modelyear/'. $year .'/make/'. $make .'?format=json';
          $res = json_decode(file_get_contents($url), true)['Results'];

          //loop through models getting recalls for each
          foreach($res as $makeyear)
          {
              $model = $makeyear['Model'];
              $recallUrl = $urlBase . '/modelyear/'. $year .'/make/'. $make . '/model/' . $model .'?format=json';
              $result = json_decode(file_get_contents($recallUrl), true);
              //print_r($result);
              //echo $year . ' ' . $make . ' ' . $model . ' ' . $result['Count'] . ' ';
              $json[$model] = $result['Count'];
          }
          $json = json_encode($json);
          echo $json;
          break;

        case 'partRecall'

    	}

    }


?>
