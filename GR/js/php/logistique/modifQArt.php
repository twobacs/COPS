<?php

if(isset($_GET['art'])){
	include('../autoload.php');
	$art=$_GET['art'];
	$panier=$_GET['panier'];
	$op=$_GET['op'];
		
	$sql='SELECT quantite FROM lignePanier WHERE id_article=:art AND id_panier=:panier';
	$req=$pdo->prepare($sql);
	$req->bindValue('art',$art,PDO::PARAM_INT);
	$req->bindValue('panier',$panier,PDO::PARAM_INT);
	$req->execute();
	while($row=$req->fetch()){
		$Q=$row['quantite'];
	}
	
	if($op=='plus'){		
		$sql='SELECT stock FROM articles WHERE id_article=:art';
		$req=$pdo->prepare($sql);
		$req->bindValue('art',$art,PDO::PARAM_INT);
		$req->execute();
		while($row=$req->fetch()){
			$stock=$row['stock'];
		}
		$stockPrevu=$stock-$Q;
		// echo $stockPrevu;
		if($stockPrevu!=0){
			$Q++;
			$sql='UPDATE lignePanier SET quantite=quantite+1 WHERE id_article=:art AND id_panier=:panier';
		}
		else{
			$sql='UPDATE lignePanier SET quantite=quantite WHERE id_article=:art AND id_panier=:panier';
			$Q='e';
		}
	}
	else{
		if($Q>=1){
			$Q--;
			$sql='UPDATE lignePanier SET quantite=quantite-1 WHERE id_article=:art AND id_panier=:panier';
		}		
	}
	$req=$pdo->prepare($sql);
	$req->bindValue('art',$art,PDO::PARAM_INT);
	$req->bindValue('panier',$panier,PDO::PARAM_INT);
	$req->execute();
	echo $Q;
}

?>