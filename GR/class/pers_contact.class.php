<?php

class Pers_contact
{
public $id_contact;
public $nom;
public $prenom;
public $CodePost;
public $Commune;
public $Rue;
public $Numero;
public $Tf;
public $GSM;
public $Parente;
public $Prior;
public $id_user;
private $pdo;



public function __construct($dbPdo)
	{
	$this->pdo = $dbPdo;
	}
private function setParams($data){
	$this->id_contact=(empty($data['id_contact'])) ? 'NC' : strtoupper(htmlentities($data['id_contact']));
	$this->nom=(empty($data['nomCont'])) ? 'NC' : strtoupper(htmlentities($data['nomCont']));
	$this->prenom=(empty($data['prenomCont'])) ? 'NC' : ucfirst(htmlentities($data['prenomCont']));
	$this->CodePost=(empty($data['CPCont'])) ? 'NC' : htmlentities($data['CPCont']);
	$this->Commune=(empty($data['villeCont'])) ? 'NC' : htmlentities($data['villeCont']);
	$this->Rue=(empty($data['rueCont'])) ? 'NC' : htmlentities($data['rueCont']);
	$this->Numero=(empty($data['numCont'])) ? 'NC' : htmlentities($data['numCont']);
	$this->Tf=(empty($data['telCont'])) ? 'NC' : htmlentities($data['telCont']);
	$this->GSM=(empty($data['gsmCont'])) ? 'NC' : htmlentities($data['gsmCont']);
	$this->Parente=(empty($data['parenteCont'])) ? 'NC' : htmlentities($data['parenteCont']);
	$this->Prior=(empty($data['prioCont'])) ? 'NC' : htmlentities($data['prioCont']);
	$this->id_user=(empty($data['id_user'])) ? 'NC' : htmlentities($data['id_user']);
}

public function addNewContact($data){
	$sql='SELECT COUNT(*) FROM pers_contact WHERE nom=:nom AND prenom=:prenom AND id_user=:id_user';
	$req=$this->pdo->prepare($sql);
	// print_r($req).'<br />';
	$req->execute(array(
	'nom' => (empty($data['nomCont'])) ? 'NC' : strtoupper(htmlentities($data['nomCont'])),
	'prenom' => (empty($data['prenomCont'])) ? 'NC' : ucfirst(htmlentities($data['prenomCont'])),
	'id_user' => (empty($data['user'])) ? 'NC' : $data['user']
	));
	
	while($row=$req->fetch()){
		$count=$row['COUNT(*)'];
	}
	if($count==0){
		$sql='INSERT INTO pers_contact (nom, prenom, CodePost, Commune, Rue, Numero, Tf, GSM, Parente, Prior, id_user) VALUES (:nom, :prenom, :CodePost, :Commune, :Rue, :Numero, :Tf, :GSM, :Parente, :Prior, :id_user)';
		$req=$this->pdo->prepare($sql);
		$req->execute(array(
		'nom' => (empty($data['nomCont'])) ? 'NC' : strtoupper(htmlentities($data['nomCont'])),
		'prenom' => (empty($data['prenomCont'])) ? 'NC' : ucfirst(htmlentities($data['prenomCont'])),
		'CodePost' => (empty($data['CPCont'])) ? 'NC' : htmlentities($data['CPCont']),
		'Commune' => (empty($data['villeCont'])) ? 'NC' : strtoupper(htmlentities($data['villeCont'])),
		'Rue' =>(empty($data['rueCont'])) ? 'NC' : htmlentities($data['rueCont']),
		'Numero' => (empty($data['numCont'])) ? 'NC' : htmlentities($data['numCont']),
		'Tf' => (empty($data['telCont'])) ? 'NC' : htmlentities($data['telCont']),
		'GSM' => (empty($data['gsmCont'])) ? 'NC' : htmlentities($data['gsmCont']),
		'Parente' => (empty($data['parenteCont'])) ? 'NC' : htmlentities($data['parenteCont']),
		'Prior' => (empty($data['prioCont'])) ? 'NC' : htmlentities($data['prioCont']),
		'id_user' => (empty($data['user'])) ? 'NC' : $data['user']
		));
	}
}

function getInfosPersUrg($user){
	$sql='SELECT id_contact, nom, prenom, CodePost, Commune, Rue, Numero, Tf, GSM, parente, Prior FROM pers_contact WHERE id_user=:user ORDER BY Prior';
	$req=$this->pdo->prepare($sql);
	$req->execute(array(
	'user' => $user
	));
	return $req;
}

function updateNewContact($data){
	$this->setParams($data);
	$sql='UPDATE pers_contact SET nom=:nom, prenom=:prenom, CodePost=:CodePost, Commune=:Commune, Rue=:Rue, Numero=:Numero, Tf=:Tf, GSM=:GSM, Parente=:Parente, Prior=:Prior WHERE id_contact=:id_contact AND id_user=:id_user';
	$req=$this->pdo->prepare($sql);
	$req->execute(array(
	'nom' => $this->nom,
	'prenom' => $this->prenom,
	'CodePost' => $this->CodePost,
	'Commune' => $this->Commune,
	'Rue' => $this->Rue,
	'Numero' => $this->Numero,
	'Tf' => $this->Tf,
	'GSM' => $this->GSM,
	'Parente' => $this->Parente,
	'Prior' => $this->Prior,
	'id_contact' => $this->id_contact,
	'id_user' => $this->id_user
	));
}

function DelPersUrg($data){
	$user=$data['id_user'];
	$contact=$data['idContact'];
	$sql='DELETE FROM pers_contact WHERE id_contact=:contact AND id_user=:user';
	$req=$this->pdo->prepare($sql);
	$req->execute(array(
	'contact' => $contact,
	'user' => $user));
}
}
?>