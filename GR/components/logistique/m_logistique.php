<?php

class MLogistique extends MBase {

	private $checkDbPDO = false;

	public function __construct($appli) {
		parent::__construct($appli);
		
	}

private function htmltosql($text){
	$rep=htmlentities($text,ENT_QUOTES, "UTF-8");
	return $rep;
}

private function uploadPDF($rep){
	$target_dir = './docroom/uploaded_files/'.$rep.'/';
	$target_file = $target_dir . basename($_FILES['fileToUpload']['name']);
	$uploadOk = 1;
	$fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	if($fileType=='pdf'){
		$nomFichier=md5(uniqid(rand(), true)).'.'.$fileType;
		$fichier=$target_dir.$nomFichier;
		$resultat = move_uploaded_file($_FILES['fileToUpload']['tmp_name'],$fichier);
		}
	return $nomFichier;
}

public function nivAcces(){
	include_once('./class/nivacces.class.php');
	$acces=new NivAcces($this->appli->dbPdo);
	return $acces->getNivAcces($_GET['component'], $_SESSION['idUser']);
}

public function getDroitsLog(){
	include_once('./class/nivacces.class.php');
	$acces=new NivAcces($this->appli->dbPdo);
	return $acces->getDtsByApp($_GET['component']);
}

public function getUsers(){
	include_once('./class/users.class.php');
	$users=new Users($this->appli->dbPdo);
	return $users->getAllUsers();
}

public function getArmes(){
	include_once('./class/armes.class.php');
	$armes=new Armes($this->appli->dbPdo);
	return $armes->getAllArmes();
}

public function getBrassards(){
	include_once('./class/brassards.class.php');
	$brassards=new Brassards($this->appli->dbPdo);
	return $brassards->getAllBrassards();
}

public function getRadios(){
	include_once('./class/radios.class.php');
	$radios=new Radios($this->appli->dbPdo);
	return $radios->getAllRadios();
}

public function getBatons(){
	include_once('./class/batons.class.php');
	$batons=new Batons($this->appli->dbPdo);
	return $batons->getAllBatons();	
}

public function getUserArme(){
	$tri=(isset($_GET['tri'])) ? $_GET['tri'] : '';
	$sql='SELECT a.num_arme, a.marque_arme, a.coffre, b.nom, b.prenom, b.id_user 
	FROM armes a
	LEFT JOIN users b ON b.id_user=a.id_user
	WHERE a.id_user > "0"
	ORDER BY ';
	switch($tri){
		case '':
			$sql.='b.nom ASC';
			break;
		case 'nomASC':
			$sql.='b.nom ASC';
			break;
		case 'nomDESC':
			$sql.='b.nom DESC';
			break;
		case 'prenomASC':
			$sql.='b.prenom ASC';
			break;
		case 'prenomDESC':
			$sql.='b.prenom DESC';
			break;	
		case 'numArmeASC':
			$sql.='a.num_arme ASC';
			break;
		case 'numArmeDESC':
			$sql.='a.num_arme DESC';
			break;
		case 'modArmeASC':
			$sql.='a.marque_arme ASC';
			break;
		case 'modArmeDESC':
			$sql.='a.marque_arme DESC';
			break;	
		default:
			$sql.='b.nom ASC';
			break;
	}
	$data['userArme']=$this->appli->dbPdo->query($sql);	
	return $data;
}

public function getUserBrassard(){
	$tri=(isset($_GET['tri'])) ? $_GET['tri'] : '';
	$sql='SELECT a.num_brassard, b.nom, b.prenom, b.id_user 
	FROM brassards a
	LEFT JOIN users b ON b.id_user=a.id_user
	WHERE a.id_user > "0"
	ORDER BY ';
	switch($tri){
		case '':
			$sql.='b.nom ASC';
			break;
		case 'nomASC':
			$sql.='b.nom ASC';
			break;
		case 'nomDESC':
			$sql.='b.nom DESC';
			break;
		case 'prenomASC':
			$sql.='b.prenom ASC';
			break;
		case 'prenomDESC':
			$sql.='b.prenom DESC';
			break;	
		case 'numBrassardASC':
			$sql.='a.num_brassard ASC';
			break;
		case 'numBrassardDESC':
			$sql.='a.num_brassard DESC';
			break;
		default:
			$sql.='b.nom ASC';
			break;
	}
	$data['userbrassard']=$this->appli->dbPdo->query($sql);	
	return $data;
}

public function getUserRadio(){
	$tri=(isset($_GET['tri'])) ? $_GET['tri'] : '';
	$sql='SELECT a.num_TEI, a.num_ISSI, b.nom, b.prenom, b.id_user 
	FROM radios a
	LEFT JOIN users b ON b.id_user=a.id_user
	WHERE a.id_user > "0"
	ORDER BY ';
	switch($tri){
		case '':
			$sql.='b.nom ASC';
			break;
		case 'nomASC':
			$sql.='b.nom ASC';
			break;
		case 'nomDESC':
			$sql.='b.nom DESC';
			break;
		case 'prenomASC':
			$sql.='b.prenom ASC';
			break;
		case 'prenomDESC':
			$sql.='b.prenom DESC';
			break;	
		case 'numTEIASC':
			$sql.='a.num_TEI ASC';
			break;
		case 'numTEIDESC':
			$sql.='a.num_TEI DESC';
			break;
		case 'numISSIASC':
			$sql.='a.num_ISSI ASC';
			break;
		case 'numISSIDESC':
			$sql.='a.num_ISSI DESC';
			break;			
		default:
			$sql.='b.nom ASC';
			break;
	}
	$data['userradio']=$this->appli->dbPdo->query($sql);	
	return $data;	
}

public function getUserBatons(){
	$tri=(isset($_GET['tri'])) ? $_GET['tri'] : '';
	$sql='SELECT a.num_baton, a.marque_baton, b.nom, b.prenom, b.id_user 
	FROM batons a
	LEFT JOIN users b ON b.id_user=a.id_user
	WHERE a.id_user > "0"
	ORDER BY ';
	switch($tri){
		case '':
			$sql.='b.nom ASC';
			break;
		case 'nomASC':
			$sql.='b.nom ASC';
			break;
		case 'nomDESC':
			$sql.='b.nom DESC';
			break;
		case 'prenomASC':
			$sql.='b.prenom ASC';
			break;
		case 'prenomDESC':
			$sql.='b.prenom DESC';
			break;	
		case 'numBatonASC':
			$sql.='a.num_baton ASC';
			break;
		case 'numBatonDESC':
			$sql.='a.num_baton DESC';
			break;
		case 'modBatonASC':
			$sql.='a.marque_baton ASC';
			break;
		case 'modBatonDESC':
			$sql.='a.marque_baton DESC';
			break;	
		default:
			$sql.='b.nom ASC';
			break;
	}
	$data['userBaton']=$this->appli->dbPdo->query($sql);	
	return $data;	
}


public function getInfosRetrait(){
	$mat=$_GET['subAction'];
	$matos=array();
	$data=array();
	include_once('./class/users.class.php');
	$user = NEW Users($this->appli->dbPdo);
	$infosUser=$user->getInfosUserById($_GET['idUser']);
	switch ($mat){
		case 'arme' :
			$sql='SELECT num_arme, marque_arme FROM armes WHERE id_user=:id_user';
			$req=$this->appli->dbPdo->prepare($sql);
			$req->execute(array('id_user'=>$_GET['idUser']));
			while($row=$req->fetch()){
				$matos['num_arme']=$row['num_arme'];
				$matos['modele']=$row['marque_arme'];
			}
			$sql='SELECT id FROM histo_arme WHERE num_arme=:num_arme AND id_user=:user AND dateR="0000-00-00"';
			$req=$this->dbPdo->prepare($sql);
			$req->execute(array(
			'num_arme'=>$matos['num_arme'],
			'user'=>$_GET['idUser']
			));
			while($row=$req->fetch()){$idRow=$row['id'];}
			break;
		case 'brassard':
			$sql='SELECT num_brassard FROM brassards WHERE id_user=:id_user';
			$req=$this->appli->dbPdo->prepare($sql);
			$req->execute(array('id_user'=>$_GET['idUser']));
			while($row=$req->fetch()){
				$matos['num_brassard']=$row['num_brassard'];
			}
			$sql='SELECT id FROM histo_brassard WHERE num_brassard=:num_brassard AND id_user=:user AND dateR="0000-00-00"';
			$req=$this->dbPdo->prepare($sql);
			$req->execute(array(
			'num_brassard'=>$matos['num_brassard'],
			'user'=>$_GET['idUser']
			));
			while($row=$req->fetch()){$idRow=$row['id'];}			
			break;
		case 'radio':
			$sql='SELECT num_TEI FROM radios WHERE id_user=:id_user';
			$req=$this->appli->dbPdo->prepare($sql);
			$req->execute(array('id_user'=>$_GET['idUser']));
			while($row=$req->fetch()){
				$matos['num_TEI']=$row['num_TEI'];
			}
			$sql='SELECT id FROM histo_radio WHERE num_TEI=:num_TEI AND id_user=:user AND dateR="0000-00-00"';
			$req=$this->dbPdo->prepare($sql);
			$req->execute(array(
			'num_TEI'=>$matos['num_TEI'],
			'user'=>$_GET['idUser']
			));
			while($row=$req->fetch()){$idRow=$row['id'];}			
			break;	
		case 'baton':
			$sql='SELECT num_baton FROM batons WHERE id_user=:id_user';
			$req=$this->appli->dbPdo->prepare($sql);
			$req->execute(array('id_user'=>$_GET['idUser']));
			while($row=$req->fetch()){
				$matos['num_baton']=$row['num_baton'];
			}
			$sql='SELECT id FROM histo_baton WHERE num_baton=:num_baton AND id_user=:user AND dateR="0000-00-00"';
			$req=$this->dbPdo->prepare($sql);
			$req->execute(array(
			'num_baton'=>$matos['num_baton'],
			'user'=>$_GET['idUser']
			));
			while($row=$req->fetch()){$idRow=$row['id'];}			
			break;	
	}
	$data['matos']=$matos;
	$data['user']=$infosUser;
	$data['idRow']=$idRow;
	return $data;
}

public function retrait(){
	$mat=$_GET['subAction'];
	$idRow=$_GET['idRow'];
	$idmotif=$_POST['motif'];
	$sql='SELECT cause, dispo FROM motifs_objet WHERE id=:idmotif';
	$req=$this->appli->dbPdo->prepare($sql);
	$req->execute(array('idmotif'=>$idmotif));
	while($row=$req->fetch()){
		$cause=$row['cause'];
		$dispo=$row['dispo'];
	}
	switch($mat){
		case 'arme' :
			$table='histo_arme';
			switch ($dispo){
				case 'O':
					$sql='SELECT num_arme FROM histo_arme WHERE id=:idRow';
					$req=$this->appli->dbPdo->prepare($sql);
					$req->execute(array('idRow'=>$idRow));
					while($row=$req->fetch()){$serial=$row['num_arme'];}
					$sql='UPDATE armes SET disponible=:dispo, id_user="0", coffre="O" WHERE num_arme=:num';
					$req=$this->appli->dbPdo->prepare($sql);
					$req->execute(array('num'=>$serial, 'dispo'=>$dispo));
					break;
				case 'N':
					$sql='SELECT num_arme FROM histo_arme WHERE id=:idRow';
					$req=$this->appli->dbPdo->prepare($sql);
					$req->execute(array('idRow'=>$idRow));
					while($row=$req->fetch()){$serial=$row['num_arme'];}
					$sql='UPDATE armes SET coffre="O" WHERE num_arme=:num';
					$req=$this->appli->dbPdo->prepare($sql);
					$req->execute(array('num'=>$serial));					
					break;
			}
			break;
		case 'brassard':
			$table='histo_brassard';
			$sql='SELECT num_brassard FROM histo_brassard WHERE id=:idRow';
			$req=$this->appli->dbPdo->prepare($sql);
			$req->execute(array('idRow'=>$idRow));
			while($row=$req->fetch()){$serial=$row['num_brassard'];}
			$sql='UPDATE brassards SET ';
			$sql.=($cause=='Perte') ? 'disponible="P"' : 'disponible="'.$dispo.'"';
			$sql.=', id_user="0" WHERE num_brassard=:num';
			$req=$this->appli->dbPdo->prepare($sql);
			$req->execute(array('num'=>$serial));			
			break;
		case 'radio':
			$table='histo_radio';
			$sql='SELECT num_TEI FROM histo_radio WHERE id=:idRow';
			$req=$this->appli->dbPdo->prepare($sql);
			$req->execute(array('idRow'=>$idRow));
			while($row=$req->fetch()){$serial=$row['num_TEI'];}
			$sql='UPDATE radios SET ';
			$sql.=($cause=='Perte') ? 'disponible="P"' : 'disponible="'.$dispo.'"';
			$sql.=', id_user="0" WHERE num_TEI=:num';
			$req=$this->appli->dbPdo->prepare($sql);
			$req->execute(array('num'=>$serial));			
			break;
		case 'baton':
			$table='histo_baton';
			$sql='SELECT num_baton FROM histo_baton WHERE id=:idRow';
			$req=$this->appli->dbPdo->prepare($sql);
			$req->execute(array('idRow'=>$idRow));
			while($row=$req->fetch()){$serial=$row['num_baton'];}
			$sql='UPDATE batons SET ';
			$sql.=($cause=='Perte') ? 'disponible="P"' : 'disponible="'.$dispo.'"';
			$sql.=', id_user="0" WHERE num_baton=:num';
			$req=$this->appli->dbPdo->prepare($sql);
			$req->execute(array('num'=>$serial));			
			break;			
	}
	$sql='UPDATE '.$table.' SET dateR=:dateR, motifR=:motif WHERE id=:id';
	$req=$this->appli->dbPdo->prepare($sql);
	$req->execute(array('motif' => $this->htmltosql($cause),'dateR'=>$_POST['dateRest'], 'id' => $idRow));
}

public function getInfosMatos(){
	switch ($_GET['type']){
		case 'arme':
			include_once('./class/armes.class.php');
			$armes=new Armes($this->appli->dbPdo);
			$data=$armes->getAllArmes('O','I');
			break;
		case 'brassard':
			include_once('./class/brassards.class.php');
			$brassards=new Brassards($this->appli->dbPdo);
			$data=$brassards->getAllBrassards('O');
			break;
		case 'radio':
			include_once('./class/radios.class.php');
			$radio=new Radios($this->appli->dbPdo);
			$data=$radio->getAllRadios('O');
			break;
		case 'baton':
			include_once('./class/batons.class.php');
			$baton=new Batons($this->appli->dbPdo);
			$data=$baton->getAllBatons('O');
			break;
	}
	return $data;
}

public function getLogisticiens(){
	include_once('./class/nivacces.class.php');
	$log=new NivAcces($this->appli->dbPdo);
	return $log->getLogisticiens();
}

public function getUsersNonEq(){
	$data=array();
	include_once('./class/users.class.php');
	$user=NEW Users($this->appli->dbPdo);
	$users=$user->getAllUsers('O');	
	switch($_GET['type']){
		case 'arme' :
			$table='histo_arme';
			break;
		case 'brassard':
			$table='histo_brassard';
			break;
		case 'radio':
			$table='histo_radio';
			break;
		case 'baton':
			$table='histo_baton';
			break;			
	}
	$sql='SELECT id_user FROM '.$table.' WHERE dateR="0000-00-00"';
	$rep=$this->appli->dbPdo->query($sql);
	$i=0;
	$equipe=array();
	while($row=$rep->fetch()){
		$equipe[$i]=$row['id_user'];
		$i++;
	}
	$j=0;
	if(sizeof($equipe)>0){
		while($row=$users->fetch()){
			for($k=0;$k<sizeof($equipe);$k++){
				if($row['id_user']!=$equipe[$k]){
					if(!in_array($row['id_user'],$equipe)){
						$data[$j]['nom']=$row['nom'];
						$data[$j]['prenom']=$row['prenom'];
						$data[$j]['id_user']=$row['id_user'];
					}
				}
			}
		$j++;	
		}
	}
	else{
		while($row=$users->fetch()){
			$data[$j]['nom']=$row['nom'];
			$data[$j]['prenom']=$row['prenom'];
			$data[$j]['id_user']=$row['id_user'];
			$j++;
		}
	}
	$data['lastIndex']=$j;
	return $data;
}

public function attribMat(){
	$typeMat=$_GET['type'];
	$user=$_POST['id_user'];
	$idObjet=$_POST['objet'];
	$date=$_POST['date'];
	$logisticien=$_POST['logisticien'];
	$motif=$_POST['motifA'];
	switch($typeMat){
		case 'arme':
			$table='histo_arme';
			$fieldId='num_arme';
			$sql='UPDATE armes SET disponible = "N", id_user=:user, coffre="N" WHERE num_arme=:serial';
			$req=$this->appli->dbPdo->prepare($sql);
			$req->execute(array('serial' => $idObjet,'user'=>$user));			
			break;
		case 'brassard':
			$table='histo_brassard';
			$fieldId='num_brassard';
			$sql='UPDATE brassards SET disponible = "N", id_user=:user WHERE num_brassard=:serial';
			$req=$this->appli->dbPdo->prepare($sql);
			$req->execute(array('serial' => $idObjet,'user'=>$user));						
			break;
		case 'radio':
			$table='histo_radio';
			$fieldId='num_TEI';
			$sql='UPDATE radios SET disponible = "N", id_user=:user WHERE num_TEI=:serial';
			$req=$this->appli->dbPdo->prepare($sql);
			$req->execute(array('serial' => $idObjet,'user'=>$user));						
			break;
		case 'baton':
			$table='histo_baton';
			$fieldId='num_baton';
			$sql='UPDATE batons SET disponible = "N", id_user=:user WHERE num_baton=:serial';
			$req=$this->appli->dbPdo->prepare($sql);
			$req->execute(array('serial' => $idObjet,'user'=>$user));						
			break;
	}
	$sql='INSERT INTO '.$table.'('.$fieldId.', id_user, dateA, motifA) VALUES (:idObjet,:user, :date, :motif)';
	$req=$this->appli->dbPdo->prepare($sql);
	$req->execute(array('idObjet' => $idObjet, 'user' => $user, 'date' => $date, 'motif' => $this->htmltosql($motif)));
	
}

public function getInfoMatByUser(){
	$typeMat=$_GET['subAction'];
	$user=$_GET['idUser'];
	switch($typeMat){
		case 'arme' :
			$sql='SELECT num_arme, marque_arme FROM armes WHERE id_user=:id_user';
			$req=$this->appli->dbPdo->prepare($sql);
			$req->execute(array('id_user'=>$user));
			while($row=$req->fetch()){
				$data['num_arme']=$row['num_arme'];
				$data['marque_arme']=$row['marque_arme'];
			}
			$sql='SELECT id, motifR, dateR FROM histo_arme WHERE id_user=:user AND num_arme=:arme';
			$req=$this->appli->dbPdo->prepare($sql);
			$req->execute(array('user' => $user, 'arme' => $data['num_arme']));
			while($row=$req->fetch()){
				$data['motifR']=$row['motifR'];
				$data['dateR']=$row['dateR'];				
				$data['idRow']=$row['id'];
			}		
			break;
	}
	$sql='SELECT nom, prenom FROM users WHERE id_user=:user';
	$req=$this->appli->dbPdo->prepare($sql);
	$req->execute(array('user' => $user));
	while($row=$req->fetch()){
		$data['nom']=$row['nom'];
		$data['prenom']=$row['prenom'];
		$data['idUser']=$user;
	}
	$data['type']=$typeMat;
	// print_r($data);
	return $data;	
}

public function restitution(){
	switch ($_GET['subAction']){
		case 'arme':
			$user=$_GET['idUser'];
			$numArme=$_GET['num'];
			$dateRest=$_POST['dateRest'];
			$motifRest=$_POST['motifRest'];
			$sql='INSERT INTO histo_arme (num_arme, id_user, dateA, motifA) VALUES (:arme, :user, :date, :motif)';
			$req=$this->appli->dbPdo->prepare($sql);
			$req->execute(array('arme'=>$numArme, 'user'=>$user, 'date'=>$dateRest, 'motif'=>$this->htmltosql($motifRest)));
			$sql='UPDATE armes SET coffre="N" WHERE id_user=:user AND num_arme=:arme';
			$req=$this->appli->dbPdo->prepare($sql);
			$req->execute(array('user'=>$user, 'arme'=>$numArme));
			break;
	}
}

public function getHistoArmes($from){
	include_once('./class/armes.class.php');
	$arme=new Armes($this->appli->dbPdo);
	return $arme->getHisto($from);
}

public function getHistoBrassards($from){
	include_once('./class/brassards.class.php');
	$brassard=new Brassards($this->appli->dbPdo);
	return $brassard->getHisto($from);
}

public function getHistoRadios($from){
	include_once('./class/radios.class.php');
	$radio=new Radios($this->appli->dbPdo);
	return $radio->getHisto($from);	
}

public function getHistoBatons($from){
	include_once('./class/batons.class.php');
	$baton=new Batons($this->appli->dbPdo);
	return $baton->getHisto($from);	
}

public function addMat(){
	switch ($_GET['type']){
		case 'arme':
		$sql='SELECT COUNT(*) FROM armes WHERE num_arme=:num';
		$req=$this->appli->dbPdo->prepare($sql);
		$req->execute(array('num'=>$_POST['numArme']));
		while ($row=$req->fetch()){
			$count=$row['COUNT(*)'];
		}
		if($count=='0'){
			$nom='';
				if(isset($_FILES['fileToUpload']['name'])){
					$nomFichier=$this->uploadPDF('armement');
				}			
				include_once('./class/armes.class.php');
				$arme = new Armes($this->appli->dbPdo);
				$arme->insertNewArme($_POST['modArme'], $_POST['numArme'], $_POST['calibre'], $_POST['dateAcquis'], $nomFichier, $_POST['typeArme']);
				return 0;
		}
		else return 1;
		break;
		
		case 'brassard':
		$sql='SELECT COUNT(*) FROM brassards WHERE num_brassard=:num';
		$req=$this->appli->dbPdo->prepare($sql);
		$req->execute(array('num'=>$_POST['numBrassard']));
		while ($row=$req->fetch()){
			$count=$row['COUNT(*)'];
		}
		if($count=='0'){
			$nom='';
				if(isset($_FILES['fileToUpload']['name'])){
					$nomFichier=$this->uploadPDF('armement');
				}			
				include_once('./class/brassards.class.php');
				$arme = new Brassards($this->appli->dbPdo);
				$arme->insertNewBrassard($_POST['numBrassard'], $_POST['dateAcquis'], $nomFichier);
				return 0;
		}
		else return 1;
		break;
		
		case 'radio':
		$sql='SELECT COUNT(*) FROM radios WHERE num_TEI=:num';
		$req=$this->appli->dbPdo->prepare($sql);
		$req->execute(array('num'=>$_POST['numTEI']));
		while ($row=$req->fetch()){
			$count=$row['COUNT(*)'];
		}
		if($count=='0'){
			$nom='';
				if(isset($_FILES['fileToUpload']['name'])){
					$nomFichier=$this->uploadPDF('armement');
				}			
				include_once('./class/radios.class.php');
				$arme = new Radios($this->appli->dbPdo);
				$arme->insertNewRadio($_POST['modRadio'], $_POST['numISSI'], $_POST['numTEI'], $_POST['dateAcquis'], $nomFichier);
				return 0;
		}
		else return 1;
		break;
		
		case 'baton':
		$sql='SELECT COUNT(*) FROM batons WHERE num_baton=:num';
		$req=$this->appli->dbPdo->prepare($sql);
		$req->execute(array('num'=>$_POST['numBaton']));
		while ($row=$req->fetch()){
			$count=$row['COUNT(*)'];
		}
		if($count=='0'){
			$nom='';
				if(isset($_FILES['fileToUpload']['name'])){
					$nomFichier=$this->uploadPDF('armement');
				}			
				include_once('./class/batons.class.php');
				$arme = new Batons($this->appli->dbPdo);
				$arme->insertNewBaton($_POST['modBaton'], $_POST['numBaton'], $_POST['dateAcquis'], $nomFichier);
				return 0;
		}
		else return 1;
		break;

		case 'ETT':
		$sql='SELECT COUNT(*) FROM z_ETT WHERE id_ETT=:num';
		$req=$this->appli->dbPdo->prepare($sql);
		$req->execute(array('num'=>$_POST['numero']));
		while($row=$req->fetch()){
			$count=$row['COUNT(*)'];
		}
		if ($count=='0'){
				if(isset($_FILES['fileToUpload']['name'])){
					$nomFichier=$this->uploadPDF('armement');
				}			
				include_once('./class/ett.class.php');
				$ett = new ETT($this->appli->dbPdo);
				$ett->insertNewETT($_POST['marque'], $_POST['modele'], $_POST['numero'], $_POST['dateAcquis'], $_POST['dateVal'], $nomFichier);
				return 0;			
		}
		else return 1;
		break;
	}
}

public function getFullInfos(){
	$type=$_GET['type'];
	$id=$_GET['id'];
	switch ($type){
		case 'arme':
			include_once('./class/armes.class.php');
			$arme=new Armes($this->appli->dbPdo);
			return $arme->getFullInfos($id);
			break;
		case 'brassard':
			include_once('./class/brassards.class.php');
			$brassard=new Brassards($this->appli->dbPdo);
			return $brassard->getFullInfos($id);
			break;
		case 'radio':
			include_once('./class/radios.class.php');
			$radio=new Radios($this->appli->dbPdo);
			return $radio->getFullInfos($id);
			break;		
		case 'baton':
			include_once('./class/batons.class.php');
			$baton=new Batons($this->appli->dbPdo);
			return $baton->getFullInfos($id);
			break;
		case 'ETT':
			include_once('./class/ett.class.php');
			$ett = new ETT ($this->appli->dbPdo);
			return $ett->getFullInfos($id);
			break;
	}
}

public function addDoc(){
	switch($_GET['type']){
		case 'arme' :
			if(isset($_FILES['fileToUpload']['name'])){
				$nomFichier=$this->uploadPDF('armement');
			}
			$sql='INSERT INTO mat_fichier (type_obj, id_obj, nom_fichier, type_doc) VALUES (:typeO, :id, :nom, :typeD)';
			echo $sql;
			$req=$this->appli->dbPdo->prepare($sql);
			$req->execute(array(
			'typeO' => 'arme', 
			'id' => $_GET['id'], 
			'nom' => $nomFichier, 
			'typeD' => $this->htmltosql($_POST['name'])
			));			
			break;
		case 'brassard' :
			if(isset($_FILES['fileToUpload']['name'])){
				$nomFichier=$this->uploadPDF('armement');
			}
			$sql='INSERT INTO mat_fichier (type_obj, id_obj, nom_fichier, type_doc) VALUES (:typeO, :id, :nom, :typeD)';
			echo $sql;
			$req=$this->appli->dbPdo->prepare($sql);
			$req->execute(array(
			'typeO' => 'brassard', 
			'id' => $_GET['id'], 
			'nom' => $nomFichier, 
			'typeD' => $this->htmltosql($_POST['name'])
			));			
			break;
		case 'radio' :
			if(isset($_FILES['fileToUpload']['name'])){
				$nomFichier=$this->uploadPDF('armement');
			}
			$sql='INSERT INTO mat_fichier (type_obj, id_obj, nom_fichier, type_doc) VALUES (:typeO, :id, :nom, :typeD)';
			echo $sql;
			$req=$this->appli->dbPdo->prepare($sql);
			$req->execute(array(
			'typeO' => 'radio', 
			'id' => $_GET['id'], 
			'nom' => $nomFichier, 
			'typeD' => $this->htmltosql($_POST['name'])
			));			
			break;	
		case 'baton' :
			if(isset($_FILES['fileToUpload']['name'])){
				$nomFichier=$this->uploadPDF('armement');
			}
			$sql='INSERT INTO mat_fichier (type_obj, id_obj, nom_fichier, type_doc) VALUES (:typeO, :id, :nom, :typeD)';
			echo $sql;
			$req=$this->appli->dbPdo->prepare($sql);
			$req->execute(array(
			'typeO' => 'baton', 
			'id' => $_GET['id'], 
			'nom' => $nomFichier, 
			'typeD' => $this->htmltosql($_POST['name'])
			));			
			break;
		case 'ETT' :
			if(isset($_FILES['fileToUpload']['name'])){
				$nomFichier=$this->uploadPDF('armement');
			}
			$sql='INSERT INTO mat_fichier (type_obj, id_obj, nom_fichier, type_doc) VALUES (:typeO, :id, :nom, :typeD)';
			echo $sql;
			$req=$this->appli->dbPdo->prepare($sql);
			$req->execute(array(
			'typeO' => 'ETT', 
			'id' => $_GET['id'], 
			'nom' => $nomFichier, 
			'typeD' => $this->htmltosql($_POST['name'])
			));	
	}
}

public function getMotifs($cause){
	$objet=(isset($_GET['subAction'])) ? $_GET['subAction'] : $_GET['type'];
	$sql='SELECT id, cause, dispo FROM motifs_objet WHERE objet=:objet AND motif=:motif';
	$req=$this->appli->dbPdo->prepare($sql);
	$req->execute(array('objet'=>$objet, 'motif'=>$cause));
	return $req;	
}

public function getETT(){
	$tri=(isset($_GET['tri'])) ? $_GET['tri'] : '';
	include_once('./class/ett.class.php');
	$ett = new ETT ($this->appli->dbPdo);
	return $ett->getAllETT($tri);
}

public function getArticles(){
	include_once('./class/articles.class.php');
	$articles=new Articles($this->appli->dbPdo);
	return $articles->getInfosArticles('A');
}

public function getAllArticles(){
	include_once('./class/articles.class.php');
	$articles=new Articles($this->appli->dbPdo);
	return $articles->getInfosArticles('A');	
}

public function getCategories(){
	include_once('./class/articles.class.php');
	$articles=new Articles($this->appli->dbPdo);
	return $articles->getCategories();	
}

public function getMesures(){
	include_once('./class/articles.class.php');
	$articles=new Articles($this->appli->dbPdo);
	return $articles->getMesures();		
}

public function getFournisseurs(){
	include_once('./class/fournisseurs.class.php');
	$fournisseur=new Fournisseurs($this->appli->dbPdo);
	return $fournisseur->getFournisseurs();	
}
public function getMateriel(){
	include_once('./class/articles.class.php');
	$matos=new Articles($this->appli->dbPdo);
	return $matos->getInfosArticles();
}

public function addNewArt(){
	include_once('./class/articles.class.php');
	$matos=new Articles($this->appli->dbPdo);
	return $matos->addNewArticle($_POST);
}

public function addDocToArt(){
	include_once('./class/articles.class.php');
	$matos=new Articles($this->appli->dbPdo);
	return $matos->addDocToArt($_POST, $_GET);
}

public function loadSessionIdPanier(){
	include_once('./class/panier.class.php');
	$panier=new Panier($this->appli->dbPdo);
	return $panier->loadSessionIdPanier();
}

public function getPanierActifByUser(){
	include_once('./class/panier.class.php');
	$panier=new Panier($this->appli->dbPdo);
	return $panier->getPanierActifByUser();	
}

public function validCart(){
	include_once('./class/panier.class.php');
	$panier=new Panier($this->appli->dbPdo);
	return $panier->validCart();	
}

public function getNewOrders(){
	include_once('./class/cart.class.php');
	$cart=new Cart($this->appli->dbPdo);
	return $cart->getNewOrders();	
}

public function getLoc(){
    $sql='SELECT a.id, a.denomination, a.commentaire, a.id_niveau FROM local a ORDER BY a.id_niveau';
    $req=$this->appli->dbPdo->query($sql);
    return $req;
}

public function getCategs(){
    $sql='SELECT id, denomination FROM type_mobilier ORDER BY denomination';
    //$req=$this->appli->dbPdo->prepare($sql);
    return $this->appli->dbPdo->query($sql);
}

public function getBatiments(){
    $sql='SELECT id, denomination FROM local WHERE id_niveau IS NULL';
    return $this->appli->dbPdo->query($sql);
}

public function recordNewMob(){
    if(isset($_FILES['fileToUpload']['name'])){
        $nomFichier=$this->uploadPDF('logistique');
    }
    $urlFichier='/GR/docroom/uploaded_files/logistique/'.$nomFichier;
    $categorie=\filter_input(INPUT_POST, 'newMobSelectCateg');
    $denomination=\filter_input(INPUT_POST, 'denomNewMob');
    $commentaire=\filter_input(INPUT_POST, 'comNewMob');
    $batiment=\filter_input(INPUT_POST, 'selectedBatNewMob');
    $niveau=\filter_input(INPUT_POST, 'selectedNivNewMob');
    $local=\filter_input(INPUT_POST, 'selectedLclNewMob');
    $idMob=md5(uniqid());
    //INSERTION DANS LA TABLE mobilier
    $sql='INSERT INTO mobilier (id, denomination, id_type, commentaire, id_local) VALUES (:idMob, :den, :type, :comm, :local)';
    $req=$this->appli->dbPdo->prepare($sql);
    $req->bindValue('idMob',$idMob,PDO::PARAM_STR);
    $req->bindValue('den',$denomination,PDO::PARAM_STR);
    $req->bindValue('type',$categorie,PDO::PARAM_STR);
    $req->bindValue('comm',$commentaire,PDO::PARAM_STR);
    $req->bindValue('local',$local,PDO::PARAM_STR);
    $req->execute();
    
    //INSERTION DANS LA TABLE documents_gestMob
    $sql='INSERT INTO documents_gestMob (id, url, nom_doc, type_fichier) VALUES (:idDoc, :urlDoc, :nomDoc, :typeFichier)';
    $req=$this->appli->dbPdo->prepare($sql);
    $req->bindValue('idDoc',$nomFichier,PDO::PARAM_STR);
    $req->bindValue('urlDoc',$urlFichier,PDO::PARAM_STR);
    $req->bindValue('nomDoc',$_FILES['fileToUpload']['name'],PDO::PARAM_STR);
    $req->bindValue('typeFichier',$_FILES['fileToUpload']['type'],PDO::PARAM_STR);
    $req->execute();
    
    //INSERTION DANS LA TABLE mob_doc
    $sql='INSERT INTO mob_doc (id_mob, id_doc) VALUES (:mob, :doc)';
    $req=$this->appli->dbPdo->prepare($sql);
    $req->bindValue('mob',$idMob,PDO::PARAM_STR);
    $req->bindValue('doc',$nomFichier,PDO::PARAM_STR);
    $req->execute();
    
    //GENERATION QR CODE
    $urlViewObject='http://'.$_SERVER['HTTP_HOST'].'/GR/index.php?component=logistique&action=viewDetailsMob&Mob='.$idMob;
    include('/var/lib/phpqrcode/qrlib.php');
    QRcode::png($urlViewObject, '/var/www/GR/docroom/QRcodes/'.$idMob.'.png');     
    //FIN GENERATION QR CODE
    
    return $idMob;   
}

public function getInfosMobById($idMob){
    $sql=''
            . 'SELECT '
            . 'a.denomination, a.commentaire, a.id, '
            . 'b.denomination AS denomType, '
            . 'c.denomination AS denomLcl, c.commentaire AS comLcl '
            . 'FROM mobilier a '
            . 'LEFT JOIN type_mobilier b ON b.id=a.id_type '
            . 'LEFT JOIN local c ON c.id=a.id_local '
            . 'WHERE a.id=:idMob';
    $req=$this->appli->dbPdo->prepare($sql);
    $req->bindValue('idMob',$idMob,PDO::PARAM_STR);
    $req->execute();
    return $req;
}

public function getListMob(){
    $sql=''
            . 'SELECT '
            . 'a.id, a.denomination, a.commentaire, '
            . 'b.denomination AS denomType,'
            . 'c. denomination AS denomLcl, c.commentaire AS comLcl '
            . 'FROM mobilier a '
            . 'LEFT JOIN type_mobilier b ON b.id=a.id_type '
            . 'LEFT JOIN local c ON c.id = a.id_local '
            . 'ORDER BY ';
    $sql.='date_in DESC';
    $data=array();
    $i=0;
    $req=$this->appli->dbPdo->query($sql);
    while($row=$req->fetch()){
        $data[$i]['id']=$row['id'];
        $data[$i]['denomination']=$row['denomination'];
        $data[$i]['commentaire']=$row['commentaire'];
        $data[$i]['denomType']=$row['denomType'];
        $data[$i]['denomLcl']=$row['denomLcl'];
        $data[$i]['comLcl']=$row['comLcl'];
        $i++;
    }
    $data['ttl']=$i;
    return $data;
}
}
?>