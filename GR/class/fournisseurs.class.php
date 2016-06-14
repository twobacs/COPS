<?php

class Fournisseurs{
private $pdo;
private $id_fournisseur;
public $nom;
public $num_entreprise;
public $description;
public $adresse;
public $numero;
public $CP;
public $ville;
public $pays;
public $tel;
public $fax;
public $mail;
public $actif='O';

public function __construct($dbPdo){
	$this->pdo=$dbPdo;
}

public function addNewFourn($tab){
	$req=$this->pdo->prepare('INSERT INTO fournisseurs (nom,num_entreprise,description,adresse,numero,CP,ville,pays,tel,fax,mail) VALUES (:nom,:num_entreprise, :description,:adresse,:numero,:CP,:ville,:pays,:tel,:fax,:mail)');

	$req->bindParam(':nom', $tab['denNewFourn'],PDO::PARAM_STR);
	$req->bindParam(':num_entreprise',$tab['numEntr'],PDO::PARAM_STR);
	$req->bindParam(':description',$tab['descFourn'],PDO::PARAM_STR);
	$req->bindParam(':adresse',$tab['rueFourn'],PDO::PARAM_STR);
	$req->bindParam(':numero',$tab['numRueFourn'],PDO::PARAM_STR);
	$req->bindParam(':CP',$tab['cpFourn'],PDO::PARAM_STR);
	$req->bindParam(':ville',$tab['villeFourn'],PDO::PARAM_STR);
	$req->bindParam(':pays',$tab['paysFourn'],PDO::PARAM_STR);
	$req->bindParam(':tel',$tab['telFourn'],PDO::PARAM_STR);
	$req->bindParam(':fax',$tab['faxFourn'],PDO::PARAM_STR);
	$req->bindParam(':mail',$tab['mailFourn'],PDO::PARAM_STR);
	$req->execute();
}

public function updateFourn($tab){
	$sql='UPDATE fournisseurs SET nom=:nom, num_entreprise=:num_entreprise, description=:description, adresse=:adresse, numero=:numero, CP=:CP, ville=:ville, pays=:pays, tel=:tel, fax=:fax, mail=:mail WHERE id_fournisseur=:id';
	$req=$this->pdo->prepare($sql);
	$req->bindParam(':nom', $tab['denNewFourn'],PDO::PARAM_STR);
	$req->bindParam(':num_entreprise',$tab['numEntr'],PDO::PARAM_STR);
	$req->bindParam(':description',$tab['descFourn'],PDO::PARAM_STR);
	$req->bindParam(':adresse',$tab['rueFourn'],PDO::PARAM_STR);
	$req->bindParam(':numero',$tab['numRueFourn'],PDO::PARAM_STR);
	$req->bindParam(':CP',$tab['cpFourn'],PDO::PARAM_STR);
	$req->bindParam(':ville',$tab['villeFourn'],PDO::PARAM_STR);
	$req->bindParam(':pays',$tab['paysFourn'],PDO::PARAM_STR);
	$req->bindParam(':tel',$tab['telFourn'],PDO::PARAM_STR);
	$req->bindParam(':fax',$tab['faxFourn'],PDO::PARAM_STR);
	$req->bindParam(':mail',$tab['mailFourn'],PDO::PARAM_STR);
	$req->bindParam(':id',$tab['idFourn'],PDO::PARAM_INT);
	$req->execute();
}

public function getFournisseurs($actif='O'){
	$sql='SELECT id_fournisseur, nom, num_entreprise, description, adresse, numero, CP, ville, pays, tel, fax, mail FROM fournisseurs WHERE actif=:act';
	$req=$this->pdo->prepare($sql);
	$req->execute(array('act'=>$actif));
	return $req;
}

public function getFournisseurById($id){
	$sql='SELECT nom, num_entreprise, description, adresse, numero, CP, ville, pays, tel, fax, mail, actif FROM fournisseurs WHERE id_fournisseur=:idFourn';
	$req=$this->pdo->prepare($sql);
	$req->bindParam(':idFourn',$id,PDO::PARAM_INT);
	$req->execute();
	return $req;
	
}
	
}

?>