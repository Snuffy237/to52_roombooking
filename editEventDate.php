<?php

//connexion à mongoDb
require_once('mongoDb/db.php');

if (isset($_POST['Event'][0]) && isset($_POST['Event'][1]) && isset($_POST['Event'][2])){


	$id = $_POST['Event'][0];
	$dateEnd = date($_POST['Event'][2]); //Date de début
	$end = new MongoDB\BSON\UTCDateTime(strtotime($dateEnd)*1000);

	$dateStart = date($_POST['Event'][1]); //Date de début
	$start = new MongoDB\BSON\UTCDateTime(strtotime($dateStart)*1000);

	//$start = $_POST['Event'][1];
	//$end = $_POST['Event'][2];

	$single_update = new MongoDB\Driver\BulkWrite();

  $single_update->update(
      ['_id' => new MongoDB\BSON\ObjectId($id)],
      ['$set' => ['start' => $start, 'end' => $end]],
      ['multi' => false, 'upsert' => false]
  );
  $result = $conn->executeBulkWrite($dbname, $single_update);

		if($result->getModifiedCount() != 1) {
			die ('Erreur de mise à jour');
		}else{
			die('OK');
		}
}
header('Location: '.$_SERVER['HTTP_REFERER']);


?>
