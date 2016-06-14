<?php

if(isset($_GET['idArt'])){
	include('../autoload.php');
	$article=new Articles($pdo);
	$sql='UPDATE articles SET id_mesure=:idMesure WHERE id_article=:idArt';
	$req=$pdo->prepare($sql);
	$req->execute(array('idMesure'=>$_GET['idMesure'],'idArt'=>$_GET['idArt']));
}

?>