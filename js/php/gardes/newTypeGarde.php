<?php

if (isset($_GET['newType'])){
	include ('../connect.php');
	$sql='INSERT INTO z_type_garde (denomination) VALUES (:new)';
	$req=$pdo->prepare($sql);
	$req->execute(array('new'=>$_GET['newType']));
	echo 'Valeur ajoutée';
}

?>