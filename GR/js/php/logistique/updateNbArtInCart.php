<?php

if(isset($_GET['idPanier'])){
	include ('../autoload.php');
	$sql='SELECT id_article FROM lignePanier WHERE id_panier=:idP';
	$req=$pdo->prepare($sql);
	$req->bindParam('idP',$_GET['idPanier'],PDO::PARAM_INT);
	$req->execute();
	$count=$req->rowCount();
	$html='<p class="text-left"><a class="btn btn-default" href="?component=logistique&action=gestPanierPMB" role="button">Vous avez '.$count.' article';
	$html.=($count>1) ? 's' : '';
	$html.=' dans votre panier</a></p>';
	echo $html;
}


?>