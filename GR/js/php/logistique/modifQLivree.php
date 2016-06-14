<?php

if(isset($_GET['panier'])){
	include ('../autoload.php');
	$valeur=($_GET['ope']=='plus') ? 'quantite_livree+1' : 'quantite_livree-1' ;
	$sql='UPDATE lignePanier SET quantite_livree = '.$valeur.' WHERE id_ligne=:ligne';
	$req=$pdo->prepare($sql);
	$req->bindValue('ligne',$_GET['panier'],PDO::PARAM_INT);
	$req->execute();
	$sql='SELECT quantite_livree FROM lignePanier WHERE id_ligne=:ligne';
	$req=$pdo->prepare($sql);
	$req->bindValue('ligne',$_GET['panier'],PDO::PARAM_INT);
	$req->execute();
	while($row=$req->fetch()){
		$quantite=$row['quantite_livree'];
	}
	echo $quantite;
}

?>