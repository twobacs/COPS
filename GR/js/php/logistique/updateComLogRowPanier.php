<?php

if (isset($_GET['row'])){
	include('../autoload.php');
	$row=$_GET['row'];
	$com=$_GET['com'];
	$ligne = new Panier($pdo);
	$ligne->updateLignePanier_ComLog($row,$com);
	
}

?>