<?php

class MGarde extends MBase {

	private $checkDbPDO = false;

	public function __construct($appli) {
		parent::__construct($appli);
		
	}
	
public function getNivAcces()
	{
	$sql='SELECT id_nivAcces FROM z_user_app WHERE id_user="'.$_COOKIE['iduser'].'" AND id_app="10"';
	$rep=$this->appli->dbPdo->query($sql);
	while ($row=$rep->fetch())
		{
		$nivAcces=$row['id_nivAcces'];
		}
	if(isset($nivAcces)){
	return $nivAcces;	
	}
	else return '-1';
	}
	
public function getTypesGardes(){
	// $sql='SELECT a.dateHr_debut, a.dateHr_fin, c.denomination AS denomType, b.denomination_svGarde AS denomSv 
	// FROM z_garde a
	// LEFT JOIN z_sv_garde b ON b.id_svGarde = a.id_svGarde
	// LEFT JOIN z_type_garde c ON c.id_typeGarde = b.id_typeGarde
	// LEFT JOIN services d ON d.id_service = b.id_service
	// ORDER BY a.dateHr_debut';
	// echo $sql;
	$sql='SELECT id_typeGarde, denomination FROM z_type_garde ORDER BY denomination';
	$req=$this->appli->dbPdo->query($sql);
	return $req;
	}	
	
public function ajoutTypeGarde(){
	$newGarde=$_POST['newTypeGarde'];
	$sql='SELECT COUNT(*) FROM z_type_garde WHERE denomination=:denomination';
	$req=$this->appli->dbPdo->prepare($sql);
	$req->execute(array('denomination'=>$newGarde));
	while($row=$req->fetch()){
		$count=$row['COUNT(*)'];
		}
	switch ($count){
		case 0:
			$sql='INSERT INTO z_type_garde (denomination) VALUES (:denomination)';
			$req=$this->appli->dbPdo->prepare($sql);
			$req->execute(array('denomination'=>ucfirst($newGarde)));
			$return=1;
			break;
		default:
			$return=-1;
			break;
		}
	return $return;
	}
	
public function getGardesNow($now='N'){
	$sql='SELECT a.id_svGarde, a.denomination_svGarde,
	b.denomination AS denomType,
	c.dateHr_debut, c.dateHr_fin, c.id_garde, c.id_garde_sv_pers,
	d.id_type_pers_garde AS typePers, d.id_pers, d.id_user, d.id,
	e.id_pers AS idPersExt, e.nom AS nomExt, e.prenom AS prenomExt, e.fixe AS fixeExt, e.gsm AS gsmExt, e.fax AS faxExt, e.mail AS mailExt, e.CP AS CPExt, e.ville AS villeExt, e.rue AS rueExt, e.numero AS numExt,
	f.id_user AS idPersIn, f.nom AS nomIn, f.prenom AS prenomIn, f.fixe AS fixeIn, f.gsm AS gsmIn, f.fax AS faxIn, f.mail AS mailIn, f.CP as CPIn, f.ville AS villeIn, f.rue AS rueIn, f.numero AS numIn
	FROM z_sv_garde a
	LEFT JOIN z_type_garde b ON b.id_typeGarde = a.id_typeGarde
	LEFT JOIN z_garde c ON c.id_svGarde = a.id_svGarde AND c.dateHr_fin > NOW()';
	
	if ($now=='O'){
	$sql.=' AND c.dateHr_debut < NOW()';
	}
	
	$sql.='LEFT JOIN z_garde_sv_pers d ON d.id = c.id_garde_sv_pers
	LEFT JOIN z_pers_garde e ON e.id_pers = d.id_pers
	LEFT JOIN users f ON f.id_user = d.id_user';
	// WHERE c.dateHr_fin > NOW() OR ISNULL(c.dateHr_fin)';
	if ($now=='O'){
	$sql.=' AND c.dateHr_debut < NOW() AND c.dateHr_fin > NOW()	';
	}
	$sql.=' ORDER BY b.denomination DESC, a.id_svGarde, a.denomination_svGarde ASC, c.dateHr_debut ASC';
	// echo $sql;
	return $this->appli->dbPdo->query($sql);
	}
	
public function getInfoGardeById(){
	$idGarde=$_GET['idGarde'];
	$sql='SELECT a.id_svGarde, a.denomination_svGarde,
	b.id_type_pers_garde, b.id_pers, b.id_user,
	c.nom AS nomIn, c.prenom AS prenomIn,
	d.nom AS nomEx, d.prenom AS prenomEx
	FROM z_sv_garde a
	LEFT JOIN z_garde_sv_pers b ON b.id_svGarde=a.id_svGarde
	LEFT JOIN users c ON c.id_user = b.id_user
	LEFT JOIN z_pers_garde d ON d.id_pers = b.id_pers
	WHERE a.id_svGarde=:idSv';

	$req=$this->appli->dbPdo->prepare($sql);
	$req->execute(array('idSv'=>$idGarde));
	return $req;
	}
	
public function addPersToGarde(){
	$idSvGarde=$_POST['idSvGarde'];
	$typePers=$_POST['typePers'];
	$idPers=$_POST['persToAdd'];
	$dhDeb=$_POST['dhDebut'];
	$dhFin=$_POST['dhFin'];
	
	switch($typePers){
		case 'I':
			$field='id_user';
			break;
		case 'E':
			$field='id_pers';
			break;
	}
	$sql='SELECT id FROM z_garde_sv_pers WHERE '.$field.'="'.$idPers.'"';
	$rep=$this->appli->dbPdo->query($sql);
	while($row=$rep->fetch()){
		$idSvPers=$row['id'];
	}

	$sql='INSERT INTO z_garde (id_svGarde, dateHr_debut, dateHr_fin, id_garde_sv_pers)VALUES (:idSvG, :dateHr_debut, :dateHr_fin, :idGardeSvPers)';
	$req=$this->appli->dbPdo->prepare($sql);
	$req->execute(array(
	'idSvG' => $idSvGarde,
	'dateHr_debut' => $dhDeb,
	'dateHr_fin' => $dhFin,
	'idGardeSvPers' => $idSvPers
	));
	// echo $idPers;
	}
	
public function getInfosGarde(){
	$sql='SELECT a.dateHr_debut, a.dateHr_fin ,
	c.nom, c.prenom, c.id_user,
	d.denomination_svGarde
	FROM z_garde a 
	LEFT JOIN z_garde_sv_pers b ON b.id=a.id_garde_sv_pers
	LEFT JOIN users c ON c.id_user = b.id_user
	LEFT JOIN z_sv_garde d ON d.id_svGarde = b.id_svGarde
	WHERE a.id_garde=:id';
	$req=$this->appli->dbPdo->prepare($sql);
	$req->execute(array(
	'id' => $_GET['idGarde']
	));
	$data=array();
	$data['infosGarde']=$req;
		
	$sql='SELECT id_user FROM z_garde_sv_pers WHERE id_svGarde = (SELECT id_svGarde FROM z_garde WHERE id_garde=:id)';
	$req=$this->appli->dbPdo->prepare($sql);
	$req->execute(array(
	'id' => $_GET['idGarde']
	));
	$i=0;
	$contact=array();
	while($row=$req->fetch()){
		$sqla='SELECT id_user, nom, prenom FROM users WHERE id_user=:iduser ORDER BY nom, prenom';
		$reqa=$this->appli->dbPdo->prepare($sqla);
		$reqa->execute(array(
		'iduser' => $row['id_user']
		));
		while($rowa=$reqa->fetch()){
			$contact[$i]['id_user']=$rowa['id_user'];
			$contact[$i]['nom']=$rowa['nom'];
			$contact[$i]['prenom']=$rowa['prenom'];
		}
		$i++;
	}
	$data['pers']=$contact;
	$data['idGarde']=$_GET['idGarde'];
	return $data;
}

public function getUsers(){
	include_once('/var/www/class/user.class.php');
	$users = new User($this->appli->dbPdo);
	return $users->selectAllUsers();
}

public function getPersGardesExt(){
	$sql='SELECT nom, prenom, id_pers AS id_user';	
}

public function majGarde(){
	$sql='SELECT id FROM z_garde_sv_pers WHERE id_svGarde=(SELECT id_svGarde FROM z_garde WHERE id_garde=:idGarde) AND id_user=:user';
	$req=$this->appli->dbPdo->prepare($sql);
	$req->execute(array(
	'idGarde' => $_GET['idGarde'],
	'user' => $_POST['persGarde']
	));
	while($row=$req->fetch()){
		$idUser=$row['id'];
	}
	
	$sql='UPDATE z_garde SET dateHr_debut=:debut, dateHr_fin=:fin, id_garde_sv_pers=:idPers WHERE id_garde=:idGarde';
	$req=$this->appli->dbPdo->prepare($sql);
	$req->execute(array(
	'debut' => $_POST['dhBas'],
	'fin' => $_POST['dhHaut'],
	'idPers' => $idUser,
	'idGarde' => $_GET['idGarde']
	));
	// echo $_POST['dhBas'].' '.$_POST['dhHaut'].' '.$_POST['persGarde'].' '.$_GET['idGarde'];
}
}
?>
