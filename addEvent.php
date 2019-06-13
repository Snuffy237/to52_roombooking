<?php
//connexion à mongoDb
require_once('mongoDb/db.php');
$bulk = new MongoDB\Driver\BulkWrite;
//echo $_POST['title'];
if (isset($_POST['title']) &&  isset($_POST['name']) && isset($_POST['start'])  && isset($_POST['end']) && isset($_POST['color'])){

	$title = $_POST['title'];
	$name = $_POST['name'];
	//Récupération de la date via le cookie du ldap
	//$name = 'Roméo DONTIO';
	//$start = $_POST['start'];
	$end = $_POST['end'];
	$color = $_POST['color'];

	$dateStart = date($_POST['start']); //Date de début
	$start = new MongoDB\BSON\UTCDateTime(strtotime($dateStart)*1000);

	$dateEnd = date($_POST['end']); //Date de fin
	$end = new MongoDB\BSON\UTCDateTime(strtotime($dateEnd)*1000);


	//définitions des filtres
	$filterStart = [
		'$and' => [
					['start' => ['$lte' => $start]],
					['end' => ['$gte' => $start]]
		]
	];
	$filterEnd = [
		'$and' => [
					['start' => ['$lte' => $end]],
					['end' => ['$gte' => $end]]
		]
	];

	$option = [];
	//Vérfication de $start
	$readStart = new MongoDB\Driver\Query($filterStart, $option);
	//fetch records
	$recordsStart = $conn->executeQuery($dbname, $readStart);
	//Vérfication de $end
	$readEnd = new MongoDB\Driver\Query($filterEnd, $option);
	//fetch records
	$recordsEnd = $conn->executeQuery($dbname, $readEnd);
	//Test $start
	$testStart = (iterator_count($recordsStart) == 0) ? true : false;
	//Test $start
	$testEnd = (iterator_count($recordsEnd) == 0) ? true : false;

	if($testStart && $testEnd){
		//Insertion dans la base mongoDB
		$event = [
					'_id' => new MongoDB\BSON\ObjectId,
					'name' => $name,
					'title' => $title,
					'color' => $color,
					'start' => $start,
					'end' => $end
				];
				//Insertion proprement dite
				try{
					$bulk->Insert($event);
					$result = $conn->executeBulkWrite($dbname,$bulk);
					//die('OK');
					//redirection vers la page d'accueil avec le param already à false
					echo "<script type='text/javascript'>document.location.replace('index.php?already=false');</script>";
				}catch(MongoDB\Driver\Exception\Exception $e){
					die("Error encountered : ".$e);
				}
		//echo json_encode(array("message" => "No Data Found"));
	}elseif(!$testStart){
		//start
		//die("Booking already exist");
			echo "<script type='text/javascript'>document.location.replace('index.php?already=true');</script>";

		//header('Location : index.php?already=true');
		//$resultsStart = $conn->executeQuery($dbname, $readStart);
		//echo json_encode(iterator_to_array($resultsStart));
	}elseif (!$testEnd) {
		//end
		//die("Booking already exist");
		//header('Location : index.php?already=true');
		echo "<script type='text/javascript'>document.location.replace('index.php?already=true');</script>";
		//$resultsEnd = $conn->executeQuery($dbname, $readEnd);
		//echo json_encode(iterator_to_array($resultsEnd));
	}


	//Vérifier si le créneau n'est pas déjà présent
	/*$filter = [
			'$and' =>[
					['start' => ['$lte' => $start]],
					['end' => ['$gte' => $start]]
			]
	];

	$option = [];
	$read = new MongoDB\Driver\Query($filter, $option);
	$search = $conn->executeQuery($dbname, $read);

	if(count($search) != 0){
		//Insertion dans la base mongoDB
	 	$event = [
	        '_id' => new MongoDB\BSON\ObjectId,
	        'name' => $name,
	        'title' => $title,
					'color' => $color,
					'start' => $start,
					'end' => $end
	      ];
	      //Insertion proprement dite
	      try{
	        $bulk->Insert($event);
	        $result = $conn->executeBulkWrite($dbname,$bulk);
					//die('OK');
	      }catch(MongoDB\Driver\Exception\Exception $e){
	        die("Error encountered : ".$e);
	      }

	}else{
		die("Booking already exist");
	}*/

}
//header('Location: '.$_SERVER['HTTP_REFERER']);
?>
