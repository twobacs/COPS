<?php

class MVacancier extends MBase {

	private $checkDbPDO = false;

	public function __construct($appli) {
		parent::__construct($appli);

	}

private function htm($data)
	{
	$rep=htmlentities($data, ENT_QUOTES, "UTF-8");
	//$rep=htmlentities($rep);
	return $rep;
	}

public function getNivAcces()
	{
	$sql='SELECT id_nivAcces FROM z_user_app WHERE id_user="'.$_COOKIE['iduser'].'" AND id_app="2"';
	$rep=$this->appli->dbPdo->query($sql);
	while ($row=$rep->fetch())
		{
		$nivAcces=$row['id_nivAcces'];
		}
	return $nivAcces;
	}

//Ajouter un demandeur de surveillance habitation
public function addDemandeur()
	{
	//Réception des informations du demandeur depuis v->formAddHabS1
	$nom=$this->htm($_POST['nom']);
	$prenom=$this->htm($_POST['prenom']);
	$dn=$_POST['dn'];
	$newId=$_POST['newIdDem'];
	$tel=$_POST['tel'];
	$gsm=$_POST['gsm'];
	$mail=$_POST['mail'];

	$sql='INSERT INTO z_vac_demandeur (id_dem, nom_dem, prenom_dem, dn_dem, tel_dem, gsm_dem, mail_dem) VALUES ("'.$newId.'", "'.$nom.'", "'.$prenom.'", "'.$dn.'", "'.$tel.'", "'.$gsm.'", "'.$mail.'")';
	$this->appli->dbPdo->exec($sql);
	return $newId;
	}

public function updateDem($id)
	{
	$tel=$_POST['tel'];
	$gsm=$_POST['gsm'];
	$mail=$_POST['mail'];
	$sql='UPDATE z_vac_demandeur SET tel_dem="'.$tel.'", gsm_dem="'.$gsm.'", mail_dem="'.$mail.'" WHERE id_dem="'.$id.'"';
	$this->appli->dbPdo->exec($sql);
	}

public function updateDemFull($id)
	{
	$sql='UPDATE z_vac_demandeur SET
	nom_dem="'.$_POST['nomDem'].'",
	prenom_dem="'.$_POST['prenomDem'].'",
	dn_dem="'.$_POST['dnDem'].'",
	tel_dem="'.$_POST['telDem'].'",
	gsm_dem="'.$_POST['gsmDem'].'",
	mail_dem="'.$_POST['mailDem'].'"
	WHERE id_dem="'.$id.'"
	';
	$this->appli->dbPdo->exec($sql);
	}

public function getInfoDemById($idDem)
	{
	$sql='SELECT nom_dem, prenom_dem FROM z_vac_demandeur WHERE id_dem="'.$idDem.'"';
	$rep=$this->appli->dbPdo->query($sql);
	while ($row=$rep->fetch())
		{
		$dem['nom']=$row['nom_dem'];
		$dem['prenom']=$row['prenom_dem'];
		}
	return $dem;
	}

public function getInfoVacByIdDem($idDem)
	{
	$i=0;
	$sql='SELECT COUNT(*) FROM z_vac_habitation WHERE id_dem="'.$idDem.'"';
	$rep=$this->appli->dbPdo->query($sql);
	while ($row=$rep->fetch())
		{
		$count=$row['COUNT(*)'];
		}
	switch ($count)
		{
		case 0 :
			$hab['quantite']=0;
			break;

		default :
			$hab['quantite']=$count;
			$sqla='SELECT a.*, b.NomRue FROM z_vac_habitation a
			LEFT JOIN z_rues b ON a.IdRue = b.IdRue

			WHERE id_dem="'.$idDem.'" ORDER BY vac_dateDepart DESC';
			$repa=$this->appli->dbPdo->query($sqla);
			while ($rowa=$repa->fetch())
				{
				$hab[$i]['id_vac']=$rowa['id_vac'];
				$hab[$i]['adresse']=$rowa['NomRue'];
				$hab[$i]['numero']=$rowa['vac_numero'];
				$hab[$i]['CP']=$rowa['vac_CP'];
				$hab[$i]['ville']=$rowa['vac_ville'];
				$hab[$i]['demande']=$rowa['vac_dateDemande'];
				$hab[$i]['depart']=$rowa['vac_dateDepart'];
				$hab[$i]['retour']=$rowa['vac_dateRetour'];
				$i++;
				}
			break;
		}
	return $hab;
	}

public function getInfoVacByIdVac($id)
	{
	$rep=array();
	include_once ('/var/www/class/vacancier.class.php');
	include_once ('/var/www/class/rues.class.php');
	$vac = new Vacancier($this->appli->dbPdo);
	$rep['house']=$vac->getVacInfoById($id);
	$rep['vv']=$vac->getInfoCarsByIdVac($id);
	$rep['contact']=$vac->getPersByIdVac($id);
	$rep['demandeur']=$vac->getInfosDemandeurByIdVac($id);
	$rue = new Rue($this->appli->dbPdo);
	$rep['rues']=$rue->selectRues();

	return $rep;
	}

public function insertNewVac()
	{
	//RECUPERATION DES DONNEES DEMANDEUR
	$demandeur=$_POST['idDem'];

	//RECUPERATION DES DONNEES HABITATION
	$IdRue=$_POST['adresse'];
	$numero=$_POST['numero'];
	$CP=$_POST['CP'];
	$ville=$_POST['ville'];
	$depart=$_POST['depart'];
	$retour=$_POST['retour'];
	$destination=$_POST['destination'];
	$contSP=$_POST['contSP'];
	$facades=$_POST['nbFacades'];
	$gdp=$_POST['gdp'];
	$alarme=$_POST['alarme'];
	$eclExt=$_POST['eclairageExt'];
	$eclInt=$_POST['eclairageInt'];
	$chien=$_POST['chien'];
	$courrier=$_POST['courrier'];
	$persCourrier=$_POST['persCourrier'];
	$persienne=$_POST['persienne'];
	$persPers=$_POST['persPers'];
	$techno=$_POST['techno'];
	$remarque=$_POST['remarque'];

	$sql='SELECT NomRue FROM z_rues WHERE IdRue="'.$IdRue.'"';
	$rep=$this->appli->dbPdo->query($sql);
	while ($row=$rep->fetch())
		{
		$adresse=$row['NomRue'];
		}

	$link=str_replace(' ','+', $adresse);
	$linkGMaps='https://maps.google.be/maps?q='.$link.'+'.$numero.',+'.$ville;


	//RECUPERATION DES DONNEES VEHICULE(S)
	$nbVV=$_POST['ttvv'];
	for ($i=1;$i<=$nbVV;$i++)
		{
		$vv[$i]['immat']=$_POST['immVV'.$i.''];
		$vv[$i]['marque']=$_POST['marqueVV'.$i.''];
		$vv[$i]['lieu']=$_POST['lieuVV'.$i.''];
		}

	//RECUPERATION DES DONNEES DES PERSONNES DE CONTACT
	$nbCont=$_POST['ttCont'];
	for ($i=1;$i<=$nbCont;$i++)
		{
		$contact[$i]['nom']=$_POST['nomCont'.$i.''];
		$contact[$i]['prenom']=$_POST['prenomCont'.$i.''];
		$contact[$i]['adresse']=$_POST['adresseCont'.$i.''];
		$contact[$i]['numero']=$_POST['numCont'.$i.''];
		$contact[$i]['CP']=$_POST['CPCont'.$i.''];
		$contact[$i]['ville']=$_POST['villeCont'.$i.''];
		$contact[$i]['tel']=$_POST['telCont'.$i.''];
		$contact[$i]['tel2']=$_POST['tel2Cont'.$i.''];
		}

	//INSERTION EN BDD DE L'HABITATION
	$sql='INSERT INTO z_vac_habitation
	(id_dem, IdRue, vac_numero, vac_CP, vac_ville, vac_dateDemande, vac_dateDepart, vac_dateRetour, vac_destination, vac_contSP, vac_GDP, vac_nbFacades,
	vac_alarme, vac_eclairageExt, vac_eclairageInt, vac_chien, vac_courrier, vac_persCourrier, vac_persAuto, vac_persPers, vac_dateVisiteTechno, vac_remarque, vac_gmap)
	VALUES
	("'.$demandeur.'", "'.$IdRue.'", "'.$numero.'", "'.$CP.'", "'.$ville.'", NOW(), "'.$depart.'", "'.$retour.'", "'.$destination.'", "'.$contSP.'", "'.$gdp.'", "'.$facades.'",
	"'.$alarme.'", "'.$eclExt.'", "'.$eclInt.'", "'.$chien.'", "'.$courrier.'", "'.$persCourrier.'", "'.$persienne.'", "'.$persPers.'", "'.$techno.'", "'.$remarque.'", "'.$linkGMaps.'")
	';
	$this->appli->dbPdo->exec($sql);

	//RECUPERATION DE L'ID DE L'HABITATION CREEE
	$sql='SELECT id_vac FROM z_vac_habitation WHERE id_dem="'.$demandeur.'" AND vac_dateDepart="'.$depart.'" AND vac_dateRetour="'.$retour.'" AND vac_dateDemande=CURRENT_DATE()';
	$rep=$this->appli->dbPdo->query($sql);
	while ($row=$rep->fetch())
		{
		$idvac=$row['id_vac'];
		}
	//INSERTION EN BDD DES VEHICULES + JOINTURE
	for ($i=1;$i<=$nbVV;$i++)
		{
		$sql='INSERT INTO z_vac_vv (marque_vv, imm_vv, lieu_vv) VALUES ("'.$vv[$i]['marque'].'", "'.$vv[$i]['immat'].'", "'.$vv[$i]['lieu'].'")';
		$this->appli->dbPdo->exec($sql);
		$sql='SELECT id_vv FROM z_vac_vv WHERE marque_vv="'.$vv[$i]['marque'].'" AND imm_vv="'.$vv[$i]['immat'].'" AND lieu_vv="'.$vv[$i]['lieu'].'"';
		$rep=$this->appli->dbPdo->query($sql);
		while ($row=$rep->fetch())
			{
			$idvv[$i]=$row['id_vv'];
			}
		$sql='INSERT INTO z_vac_hab_vv (id_vac, id_vv) VALUES ("'.$idvac.'", "'.$idvv[$i].'")';
		$this->appli->dbPdo->exec($sql);
		}
	//INSERTION EN BDD DES CONTACTS + JOINTURE
	for ($i=1;$i<=$nbCont;$i++)
		{
		include_once ('/var/www/class/vacancier.class.php');
		$vac = new Vacancier($this->appli->dbPdo);
		$idCont=$vac->genCodePersByTel($contact[$i]['nom'],$contact[$i]['prenom'],$contact[$i]['tel']);
		// echo $idCont;
		$sql='SELECT COUNT(*) FROM z_vac_contact WHERE id_contact="'.$idCont.'"';
		$rep=$this->appli->dbPdo->query($sql);
		while ($row=$rep->fetch())
			{
			$count=$row['COUNT(*)'];
			}
		if ($count==0)
			{
			$sql='INSERT INTO z_vac_contact (id_contact, nom_contact, prenom_contact, adresse_contact, numero_contact, CP_contact, ville_contact, tel_contact, tel2_contact)
			VALUES ("'.$idCont.'", "'.$contact[$i]['nom'].'", "'.$contact[$i]['prenom'].'", "'.$contact[$i]['adresse'].'", "'.$contact[$i]['numero'].'", "'.$contact[$i]['CP'].'",
			"'.$contact[$i]['ville'].'", "'.$contact[$i]['tel'].'", "'.$contact[$i]['tel2'].'")';
			}
		else
			{
			$sql='UPDATE z_vac_contact SET
			nom_contact="'.$contact[$i]['nom'].'",
			prenom_contact="'.$contact[$i]['prenom'].'",
			adresse_contact="'.$contact[$i]['adresse'].'",
			numero_contact="'.$contact[$i]['numero'].'",
			CP_contact="'.$contact[$i]['CP'].'",
			ville_contact="'.$contact[$i]['ville'].'",
			tel_contact="'.$contact[$i]['tel'].'",
			tel2_contact="'.$contact[$i]['tel2'].'"
			WHERE id_contact="'.$idCont.'"';
			}
		// echo $sql.'<br />';
		$this->appli->dbPdo->exec($sql);

		$sql='INSERT INTO z_vac_hab_cont (id_vac, id_contact) VALUES ("'.$idvac.'", "'.$idCont.'")';
		$this->appli->dbPdo->exec($sql);
		}

	//MISE A JOUR DE L'INDICATEUR DEMANDES_HABITATION
	$year=date("Y");
	$sql='SELECT demandes_habitation FROM z_indic WHERE demandes_habitation LIKE "'.$year.'_%" ORDER BY demandes_habitation DESC LIMIT 1';
	$rep=$this->appli->dbPdo->query($sql);
	while ($row=$rep->fetch())
		{
		$last=$row['demandes_habitation'];
		}
	list($annee, $id, $indexH) = preg_split('[_]', $last);
	$indexH++;
	$newIndexH=$year.'_'.$idvac.'_'.$indexH;
	$sql='INSERT INTO z_indic (demandes_habitation) VALUES ("'.$newIndexH.'")';
	 //echo $newIndexH;
	$this->appli->dbPdo->exec($sql);

	// MISE A JOUR DE L'INDICATEUR DEMANDEURS_HABITATION
	$sql='SELECT demandeurs_habitation FROM z_indic WHERE demandeurs_habitation = "'.$year.'_'.$demandeur.'" ORDER BY demandeurs_habitation';
	$rep=$this->appli->dbPdo->query($sql);
	while ($row=$rep->fetch())
		{
		$last=$row['demandeurs_habitation'];
		}
	if (!isset($last))
		{
		$user='a';
		}
	else
		{
		list($annee, $user) = preg_split('[_]', $last);
		}
	if ($user!=$demandeur)
		{
		$newIndexD=$year.'_'.$demandeur;
		$sql='INSERT INTO z_indic (demandeurs_habitation) VALUES ("'.$newIndexD.'")';
		$this->appli->dbPdo->exec($sql);
		}
	}

public function getListCurrentVac()
	{
	$sql='SELECT a.id_vac, a.IdRue, a.vac_numero, a.vac_CP, a.vac_ville, a.vac_dateDepart, a.vac_dateRetour, a.vac_gmap, a.vac_GDP,
	b.NomRue,
	c.nom_dem, c.prenom_dem,
	e.denomination
	FROM z_vac_habitation a
	LEFT JOIN z_rues b ON a.IdRue = b.IdRue
	LEFT JOIN z_vac_demandeur c ON a.id_dem = c.id_dem
	LEFT JOIN z_quartier_rue d ON d.IdRue = a.IdRue
	LEFT JOIN z_quartier e ON e.id_quartier = d.id_quartier
	WHERE vac_dateDepart<NOW() AND vac_dateRetour>NOW()
	GROUP BY a.id_vac
	ORDER BY e.denomination ASC ,a.vac_dateDepart ASC';
	$rep=$this->appli->dbPdo->query($sql);
	return $rep;
	}

public function getListAllVac()
	{
	$sql='SELECT
	a.id_vac, a.vac_numero, a.vac_CP, a.vac_ville, a.vac_dateDemande, a.vac_dateDepart, a.vac_dateRetour, a.vac_gmap,
	b.NomRue,
	c.nom_dem, c.prenom_dem
	FROM z_vac_habitation a
	LEFT JOIN z_rues b ON a.IdRue = b.IdRue
	LEFT JOIN z_vac_demandeur c on a.id_dem = c.id_dem
	ORDER BY a.vac_dateDepart ASC';
	return $this->appli->dbPdo->query($sql);
	}

public function searchVac($type)
	{
	include_once ('/var/www/class/vacancier.class.php');
	$vac = new Vacancier ($this->appli->dbPdo);
	switch ($type)
		{
		case 'adress':
			$rep=$vac->getVacByAdress($_POST['search'], $_POST['num']);
			break;

		case 'demandeur':
			$rep=$vac->getVacByDemandeur($_POST['search']);
			break;

		case 'dd':
			$rep=$vac->getVacByDateDepart($_POST['dateBasse'], $_POST['dateHaute']);
			break;
		}
	return $rep;
	}

public function updateHouse($id)
	{
	$sql='UPDATE z_vac_habitation SET
	IdRue="'.$_POST['rue'].'",
	vac_numero="'.$_POST['numHab'].'",
	vac_CP="'.$_POST['CP'].'",
	vac_ville="'.$_POST['ville'].'",
	vac_dateDepart="'.$_POST['dateDepart'].'",
	vac_dateRetour="'.$_POST['dateRetour'].'",
	vac_nbFacades="'.$_POST['nbFacades'].'",
	vac_alarme="'.$_POST['alarme'].'",
	vac_eclairageExt="'.$_POST['eclairageExt'].'",
	vac_eclairageInt="'.$_POST['eclairageInt'].'",
	vac_chien="'.$_POST['chien'].'",
	vac_courrier="'.$_POST['courrier'].'",
	vac_persCourrier="'.$_POST['persCourrier'].'",
	vac_persAuto="'.$_POST['persAuto'].'",
	vac_persPers="'.$_POST['persPers'].'",
	vac_remarque="'.$_POST['remarque'].'",
	vac_dateVisiteTechno="'.$_POST['dateTechno'].'"
	 WHERE id_vac="'.$id.'"';
	$this->appli->dbPdo->exec($sql);
	}

public function updateVV($id)
	{
	for ($i=0;$i<$_POST['totVV'];$i++)
		{
		$sql='UPDATE z_vac_vv SET
		marque_vv="'.$_POST['modele'.$i].'",
		imm_vv="'.$_POST['immat'.$i].'",
		lieu_vv="'.$_POST['lieu'.$i].'"
		WHERE id_vv="'.$_POST['idVV'.$i].'"';
		$this->appli->dbPdo->exec($sql);
		}
	}

public function updateContact($id)
	{
	for ($i=1;$i<=$_POST['totCont'];$i++)
		{
		$sql='UPDATE z_vac_contact SET
		nom_contact="'.$_POST['nom'.$i].'",
		prenom_contact="'.$_POST['prenom'.$i].'",
		adresse_contact="'.$_POST['addCont'.$i].'",
		numero_contact="'.$_POST['numCont'.$i].'",
		CP_contact="'.$_POST['CPCont'.$i].'",
		ville_contact="'.$_POST['villeCont'.$i].'",
		tel_contact="'.$_POST['telCont1'.$i].'",
		tel2_contact="'.$_POST['telCont2'.$i].'"
		WHERE id_contact="'.$_POST['idCont'.$i].'"
		';
		$this->appli->dbPdo->exec($sql);
		}
	}

public function delVacById($id)
	{
	$sql='
	DELETE FROM z_vac_contact WHERE id_contact=(SELECT id_contact FROM z_vac_hab_cont WHERE id_vac="'.$id.'")
	';
	//$this->appli->dbPdo->exec($sql);
	$sql='
	DELETE FROM z_vac_vv WHERE id_vv=(SELECT id_vv FROM z_vac_hab_vv WHERE id_vac="'.$id.'")
	';
	$this->appli->dbPdo->exec($sql);
	$sql='
	DELETE FROM z_vac_hab_cont WHERE id_vac="'.$id.'"
	';
	$this->appli->dbPdo->exec($sql);
	$sql='
	DELETE FROM z_vac_hab_vv WHERE id_vac="'.$id.'"
	';
	$this->appli->dbPdo->exec($sql);
	$sql='
	DELETE FROM z_vac_habitation WHERE id_vac="'.$id.'"
	';
	$this->appli->dbPdo->exec($sql);
	$sql='
	DELETE FROM z_indic WHERE demandes_habitation LIKE "%_'.$id.'_%"
	';
	$this->appli->dbPdo->exec($sql);
	}

public function getIndic($annee)
	{
	$data=array();
	$sql='
	SELECT COUNT(*) FROM z_indic
	WHERE demandes_habitation LIKE "'.$annee.'_%"
	';
	$rep=$this->appli->dbPdo->query($sql);
	while ($row=$rep->fetch())
		{
		$data['biens']=$row['COUNT(*)'];
		}

	$sql='
	SELECT COUNT(*) FROM z_indic
	WHERE demandeurs_habitation LIKE "'.$annee.'_%"
	';
	$rep=$this->appli->dbPdo->query($sql);
	while ($row=$rep->fetch())
		{
		$data['demandeurs']=$row['COUNT(*)'];
		}
	return $data;
	}

public function getVacFinished()
	{
	include_once ('/var/www/class/vacancier.class.php');
	$vac = new Vacancier($this->appli->dbPdo);
	$data=$vac->getVacFinished();
	return $data;
	}

public function getInfoCRById($id)
	{
	include_once ('/var/www/class/vacancier.class.php');
	$vac = new Vacancier($this->appli->dbPdo);
	$data['demandeur']=$vac->getInfosDemandeurByIdVac($id);
	$data['bien']=$vac->getVacInfoById($id);
	$data['surveillances']=$vac->getInfosControlesByIdVac($id);
	return $data;
	}

public function getInfoVacByIdRue($id)
	{
	include_once ('/var/www/class/vacancier.class.php');
	$vac = new Vacancier($this->appli->dbPdo);
	$data=$vac->getInfoVacByIdRue($id);
	return $data;
	}

public function getInfoVacByIdDemandeur($id)
	{
	include_once ('/var/www/class/vacancier.class.php');
	$vac = new Vacancier($this->appli->dbPdo);
	$data=$vac->getInfoVacByIdDem($id);
	return $data;
	}

public function addVVById()
	{
	$idvac=$_GET['id'];
	$marque=$_POST['newVV'];
	$immat=$_POST['newImmat'];
	$lieu=$_POST['newLieu'];
	$sql='INSERT INTO z_vac_vv (marque_vv, imm_vv, lieu_vv) VALUES ("'.$marque.'", "'.$immat.'", "'.$lieu.'")';
		$this->appli->dbPdo->exec($sql);
		$sql='SELECT id_vv FROM z_vac_vv WHERE marque_vv="'.$marque.'" AND imm_vv="'.$immat.'" AND lieu_vv="'.$lieu.'"';
		$rep=$this->appli->dbPdo->query($sql);
		while ($row=$rep->fetch())
			{
			$idvv=$row['id_vv'];
			}
		$sql='INSERT INTO z_vac_hab_vv (id_vac, id_vv) VALUES ("'.$idvac.'", "'.$idvv.'")';
		$this->appli->dbPdo->exec($sql);
	}

public function addPersById()
	{
	include_once ('/var/www/class/vacancier.class.php');
	$vac = NEW Vacancier($this->appli->dbPdo);
	$idvac=$_GET['id'];
	$nom=$_POST['newNom'];
	$prenom=$_POST['newPrenom'];
	$adresse=$_POST['newAdress'];
	$num=$_POST['newNum'];
	$CP=$_POST['newCP'];
	$ville=$_POST['newCity'];
	$tel1=$_POST['newTel1'];
	$tel2=$_POST['newTel2'];

	$idCont=$vac->genCodePersByTel($nom,$prenom,$tel1);

	$sql='INSERT INTO z_vac_contact (id_contact, nom_contact, prenom_contact, adresse_contact, numero_contact, CP_contact, ville_contact, tel_contact, tel2_contact) VALUES ("'.$idCont.'", "'.$nom.'", "'.$prenom.'", "'.$adresse.'", "'.$num.'", "'.$CP.'", "'.$ville.'", "'.$tel1.'", "'.$tel2.'")';
	$this->appli->dbPdo->exec($sql);

	$sql='INSERT INTO z_vac_hab_cont (id_vac, id_contact) VALUES ("'.$idvac.'", "'.$idCont.'")';
	$this->appli->dbPdo->exec($sql);

	}

public function vacSend(){
	$idVac=$_GET['idVac'];
	$sql='UPDATE z_vac_habitation SET vac_CR="O", vac_date_CR=(SELECT DATE(NOW())) WHERE id_vac=:idVac';
	$req=$this->appli->dbPdo->prepare($sql);
	$req->execute(array(
	'idVac'=>$idVac
	));
	}

public function verifVac($id=0){
	$idVac=$_GET['idVac'];
	$sql='SELECT a.id_vac, a.date_heure, a.commentaire,
	b.vac_dateRetour
	FROM z_vac_hab_controle a 
	LEFT JOIN z_vac_habitation b ON b.id_vac=a.id_vac
	WHERE a.id_vac=:idVac 
	ORDER BY a.date_heure';
	$req=$this->appli->dbPdo->prepare($sql);
	$req->execute(array(
	'idVac'=>$idVac
	));
	$sql='SELECT b.nom_dem, b.prenom_dem
	FROM z_vac_habitation a
	LEFT JOIN z_vac_demandeur b ON b.id_dem = a.id_dem
	WHERE a.id_vac=:idVac';
	$pers=$this->appli->dbPdo->prepare($sql);
	$pers->execute(array(
	'idVac'=>$idVac
	));

	$data['idVac']=$idVac;
	$data['sql']=$req;
	$data['pers']=$pers;
	return $data;
}

public function delRowById(){
	$idVac=$_GET['idVac'];
	$time=$_GET['time'];
	$sql='DELETE FROM z_vac_hab_controle WHERE id_vac=:idVac AND date_heure=:time';
	$req=$this->appli->dbPdo->prepare($sql);
	$req->execute(array(
	'idVac'=>$idVac,
	'time'=>$time
	));
}

public function addRowToCr(){
	$idVac=$_GET['idVac'];
	$idUser=$_COOKIE['iduser'];
	$sql='INSERT INTO z_vac_hab_controle (id_vac, id_user, date_heure, commentaire) VALUES (:idVac, :idUser, :dateheure, :commentaire)';
	$req=$this->appli->dbPdo->prepare($sql);
	$req->execute(array(
	'idVac'=>$idVac,
	'idUser'=>$idUser,
	'dateheure'=>$_POST['DateNewRow'].' '.$_POST['TimeNewRow'],
	'commentaire'=>$this->htm($_POST['ComNewRow'])
	));
}

}

?>
