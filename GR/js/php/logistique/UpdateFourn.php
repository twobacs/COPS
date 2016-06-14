<?php

if(isset($_GET['denNewFourn'])){
	include ('../autoload.php');
	$fourn = new Fournisseurs($pdo);
	$fourn->updateFourn($_GET);
}

?>