<?php

if (isset($_GET['idGarde'])){
	include ('../connect.php');
	$sql='DELETE FROM z_garde WHERE id_garde=:idGarde';
	$req=$pdo->prepare($sql);
	$req->execute(array('idGarde' => $_GET['idGarde']));
	}


?>