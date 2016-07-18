<?php

if(isset($_GET['panier'])){
	include('../autoload.php');
	$sql='UPDATE panierPMB SET dateCloture=NOW(), idLogCloture=:log WHERE id_panier=:panier';
	$req=$pdo->prepare($sql);
	$req->bindValue('log',$_GET['log'],PDO::PARAM_INT);
	$req->bindValue('panier',$_GET['panier'],PDO::PARAM_INT);
	$req->execute();
	$count=$req->rowcount();
	echo $count;
}

?>