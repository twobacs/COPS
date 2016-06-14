<?php

class Panier{
	
private $pdo;
	
public function __construct($dbPdo){
	$this->pdo=$dbPdo;
}
	
public function loadSessionIdPanier(){
	$sql='SELECT id_panier FROM panierPMB WHERE id_user=:user AND date_envoi=:date';
	$req=$this->pdo->prepare($sql);
	$req->bindValue('user',$_SESSION['idUser'],PDO::PARAM_STR);
	$req->bindValue('date','0000-00-00',PDO::PARAM_STR);
	$req->execute();
	$count=$req->rowCount();
	if($count==0){
		$_SESSION['panierPMB']=-1;
	}
	else{
		while($row=$req->fetch()){
		$_SESSION['panierPMB']=$row['id_panier'];
		}
	}
	return $_SESSION['panierPMB'];
}

public function getPanierActifByUser(){
	$idPanier=$this->loadSessionIdPanier();
	$sql='
	SELECT a.id_article, a.quantite,
	b.denomination,
	c.denomination AS mesure
	FROM lignePanier a 
	LEFT JOIN articles b ON b.id_article=a.id_article
	LEFT JOIN unite_mesure c ON c.id_uMesure=b.id_mesure
	WHERE id_panier=:panier';
	$req=$this->pdo->prepare($sql);
	$req->bindValue('panier',$idPanier,PDO::PARAM_INT);
	$req->execute();
	return $req;	
}

public function validCart(){
	$commentaire_demandeur=$_POST['commentaire'];
	$idPanier=$_SESSION['panierPMB'];	
	$sql='UPDATE panierPMB SET date_envoi=NOW(), commentaireDemandeur=:com WHERE id_panier=:idPanier';
	$req=$this->pdo->prepare($sql);
	$req->bindValue('com',$commentaire_demandeur,PDO::PARAM_STR);
	$req->bindValue('idPanier',$idPanier,PDO::PARAM_INT);
	$req->execute();
	$count=$req->rowCount();
	if($count==1){
		$_SESSION['panierPMB']=-1;	
	}
	return $count;
}

public function getInfosLignePanier($id){
	$sql='
	SELECT a.quantite_livree, a.date_livraison, a.com_logistique, a.idLog_livraison, a.idLog_livraison,
	b.nom, b.prenom
	FROM lignePanier a
	LEFT JOIN users b ON b.id_user=a.idLog_livraison
	WHERE a.id_ligne=:id
	';
	$req=$this->pdo->prepare($sql);
	$req->bindValue('id',$id,PDO::PARAM_INT);
	$req->execute();
	return $req;
}

public function updateLignePanier_Log($idPanier, $idLog){
	$sql='UPDATE lignePanier SET idLog_livraison=:log WHERE id_ligne=:ligne';
	$req=$this->pdo->prepare($sql);
	$req->bindValue('log',$idLog,PDO::PARAM_INT);
	$req->bindValue('ligne',$idPanier,PDO::PARAM_INT);
	$req->execute();
	return $req->rowCount();
}

public function updateLignePanier_Date($idPanier, $date){
	$sql='UPDATE lignePanier SET date_livraison=:date WHERE id_ligne=:ligne';
	$req=$this->pdo->prepare($sql);
	$req->bindValue('date',$date,PDO::PARAM_INT);
	$req->bindValue('ligne',$idPanier,PDO::PARAM_INT);
	$req->execute();
	return $req->rowCount();
}

public function updateLignePanier_ComLog($row,$com){
	$sql='UPDATE lignePanier SET com_logistique=:com WHERE id_ligne=:ligne';
	$req=$this->pdo->prepare($sql);
	$req->bindValue('com',$com,PDO::PARAM_INT);
	$req->bindValue('ligne',$row,PDO::PARAM_INT);
	$req->execute();
	return $req->rowCount();
}
	
}
?>