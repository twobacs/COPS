<?php

if(isset($_GET['idArt'])){
	include ('../autoload.php');
	$art=$_GET['idArt'];
	$user=$_GET['idUser'];
	$panier=$_GET['idPanier'];
	if($panier=='-1'){
			$sql='INSERT INTO panierPMB (id_user, date_creation) VALUES (:user,NOW())';
			$req=$pdo->prepare($sql);
			$req->bindValue('user',$user,PDO::PARAM_INT);
			$req->execute();
			$panier=$pdo->lastInsertId();
		}
	$sql='SELECT quantite FROM lignePanier WHERE id_panier=:panier AND id_article=:article';
	$req=$pdo->prepare($sql);
	$req->bindValue('panier',$panier,PDO::PARAM_INT);
	$req->bindValue('article',$art,PDO::PARAM_INT);
	$req->execute();
	$count=$req->rowCount();
	if($count=='0'){
		$sql='INSERT INTO lignePanier (id_panier, id_article, quantite) VALUES (:panier, :article , :quant)';
		$req=$pdo->prepare($sql);
		$req->bindValue('panier',$panier,PDO::PARAM_INT);
		$req->bindValue('article',$art,PDO::PARAM_INT);
		$req->bindValue('quant',1,PDO::PARAM_INT);
		$req->execute();
		}	
	echo $panier;
	}

?>