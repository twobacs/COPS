<?php

class NivAcces{
	
	private $pdo;
	public $user;
	public $app;
	
	public function __construct($dbPdo)
	{
	$this->pdo = $dbPdo;
	}

public function getNivAcces($app,$user){
	$sql='SELECT niv_acces FROM grh_droits WHERE app=:app AND id_user=:user';
	$req=$this->pdo->prepare($sql);
	$req->execute(array(
	'app' => $app,
	'user' => $user
	));
	while($row=$req->fetch()){
		$niv=(isset($row['niv_acces'])) ? $row['niv_acces'] : '0';
	}
	if(isset($niv)){
		$_SESSION['appli']=$app;
		$_SESSION['acces']=$niv;
		return $niv;
	}
	else return 0;
}

public function getAdminSite($admin=''){
	$sql='SELECT b.nom, b.prenom, b.id_user, a.app, a.id
	FROM grh_droits a
	LEFT JOIN users b ON b.id_user = a.id_user';
	if($admin=='administrateur'){
		$sql.=' WHERE a.app="administrateur"';
	}
	else{
		$sql.=' WHERE a.app!="administrateur"';
	}
	return $this->pdo->query($sql);
}

public function getDtsByApp($app){
	$sql='SELECT b.nom, b.prenom, b.id_user, a.niv_acces, a.id
	FROM grh_droits a
	LEFT JOIN users b ON b.id_user=a.id_user
	WHERE a.app=:app';
	$req=$this->pdo->prepare($sql);
	$req->execute(array(
	'app'=>$app
	));
	return $req;
}

public function addAdminSite($id){
	$sql='SELECT COUNT(*) FROM grh_droits WHERE app=:app AND id_user=:user';
	$req=$this->pdo->prepare($sql);
	$req->execute(array(
	'app' => "administrateur",
	'user' => $id,
	));
	while($row=$req->fetch()){
		$count=$row['COUNT(*)'];
	}
	if((!isset($count))||($count==0)){
		$sql='INSERT INTO grh_droits (app, id_user, niv_acces) VALUES (:app, :user, :niv)';
		$req=$this->pdo->prepare($sql);
		$req->execute(array(
		'app' => "administrateur",
		'user' => $id,
		'niv' => "1"));
	}
}

public function delAdminSite($user){
	$sql='DELETE FROM grh_droits WHERE app="administrateur" AND id_user=:user';
	$req=$this->pdo->prepare($sql);
	$req->execute(array(
	'user' => $user
	));
}

public function addDroitModule($module,$user,$niv){
	$count=$this->countRowsDroitsById_app($user,$module,$niv);
	if($count=='0'){
		$sql='INSERT INTO grh_droits (app, id_user, niv_acces) VALUES (:app, :user, :niv)';
		$req=$this->pdo->prepare($sql);
		$req->execute(array(
		'app' => $module,
		'user' => $user,
		'niv' => $niv
		));
		if($module=='personnel'){
		$sql='INSERT INTO grh_droits (app, id_user, niv_acces) VALUES (:app, :user, :niv)';
		$req=$this->pdo->prepare($sql);
		$req->execute(array(
		'app' => 'user',
		'user' => $user,
		'niv' => '10'));
		}
		else {
		$sql='INSERT INTO grh_droits (app, id_user, niv_acces) VALUES (:app, :user, :niv)';
		$req=$this->pdo->prepare($sql);
		$req->execute(array(
		'app' => 'user',
		'user' => $user,
		'niv' => '5'));
		}
		return 0;
	}
	else return 1;
}

public function delDroitModule($idRow,$session){
	$sql='SELECT id_user FROM grh_droits WHERE id=:idRow';
	$req=$this->pdo->prepare($sql);
	$req->execute(array(
	'idRow' => $idRow
	));
	while($row=$req->fetch()){
		$user=$row['id_user'];
	}
	if($user!=$session){
		$sql='DELETE FROM grh_droits WHERE id=:idRow';
		$req=$this->pdo->prepare($sql);
		$req->execute(array(
		'idRow' => $idRow
		));
		
		return 0;
	}
	else return 1;
}

function countRowsDroitsById_app($user,$module,$niv){
	$sql='SELECT COUNT(*) FROM grh_droits WHERE app=:app AND id_user=:id';
	$req=$this->pdo->prepare($sql);
	$req->execute(array(
	'app' => $module,
	'id' => $user
	));
	while($row=$req->fetch()){
		$count=$row['COUNT(*)'];
	}
	return $count;
}

public function getLogisticiens(){
	$sql='SELECT b.nom, b.prenom, b.id_user
	FROM grh_droits a
	LEFT JOIN users b ON b.id_user = a.id_user
	WHERE a.app="logistique" AND (a.niv_acces="10" OR a.niv_acces="8")
	ORDER BY b.nom, b.prenom';
	return $this->pdo->query($sql);;
}
}
?>