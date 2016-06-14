<?php

class Cart{
private $pdo;

public function __construct($dbPdo){
	$this->pdo=$dbPdo;
}

public function getNewOrders(){
	$sql='
	SELECT a.id_panier, a.id_user, a.date_creation,
	b.nom, b.prenom
	FROM panierPMB a
	LEFT JOIN users b ON b.id_user=a.id_user
	WHERE a.date_envoi!=:dateE AND date_avisLog=:dateA';
	$req=$this->pdo->prepare($sql);
	$req->bindValue('dateE','0000-00-00',PDO::PARAM_INT);
	$req->bindValue('dateA','0000-00-00',PDO::PARAM_INT);
	$req->execute();
	return $req;
}

public function getOrderDetailsById($id){
	$sql='
	SELECT a.quantite, a.id_panier, a.id_article, a.id_ligne,
	b.denomination AS denomArticle
	FROM lignePanier a
	LEFT JOIN articles b ON b.id_article=a.id_article
	WHERE a.id_panier=:id
	';
	$req=$this->pdo->prepare($sql);
	$req->bindValue('id',$id,PDO::PARAM_INT);
	$req->execute();
	$data['article']=$req;
	$sql='
	SELECT a.date_envoi, b.nom, b.prenom
	FROM  panierPMB a
	LEFT JOIN users b ON b.id_user = a.id_user
	WHERE a.id_panier=:id
	';
	$req=$this->pdo->prepare($sql);
	$req->bindValue('id',$id,PDO::PARAM_INT);
	$req->execute();
	$data['demandeur']=$req;
	return $data;
}

}
?>