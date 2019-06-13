<?php

//connexion à mongoDb
require_once('mongoDb/db.php');

//require_once('bdd.php');
if (isset($_POST['delete']) && isset($_POST['id'])){

	$id = $_POST['id'];
	//Suppression de l'évènement
	$delete = new MongoDB\Driver\BulkWrite();
	$delete->delete(
			['_id' => new MongoDB\BSON\ObjectId($id)],
			['limit' => 0]
	);

	$result = $conn->executeBulkWrite($dbname, $delete);

	if($result->getDeletedCount() != 1) {
		die ('Erreur  de suppression');
	}

}elseif (isset($_POST['title']) && isset($_POST['color']) && isset($_POST['id'])){

	$id = $_POST['id'];
	$title = $_POST['title'];
	$color = $_POST['color'];

	$single_update = new MongoDB\Driver\BulkWrite();

  $single_update->update(
      ['_id' => new MongoDB\BSON\ObjectId($id)],
      ['$set' => ['title' => $title, 'color' => $color]],
      ['multi' => false, 'upsert' => false]
  );
  $result = $conn->executeBulkWrite($dbname, $single_update);

		if($result->getModifiedCount() != 1) {
			die ('Erreur de mise à jour');
		}

}
header('Location: index.php');


?>
