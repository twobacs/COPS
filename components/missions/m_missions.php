<?php

class MMissions extends MBase {

	private $checkDbPDO = false;

	public function __construct($appli) {
		parent::__construct($appli);

	}
	
private function htm($data)
	{
	$rep=htmlentities($data, ENT_QUOTES, "UTF-8");
	// $rep=htmlentities($rep);
	return $rep;
	}
	
public function getNivAcces()
	{
	$sql='SELECT id_nivAcces FROM z_user_app WHERE id_user="'.$_COOKIE['iduser'].'" AND id_app="8"';
	$rep=$this->appli->dbPdo->query($sql);
	while ($row=$rep->fetch())
		{
		$nivAcces=$row['id_nivAcces'];
		}
	$_SESSION['nivApp']=$nivAcces;
	return $nivAcces;
	}
	
public function getNextTeams()
	{
	return $this->appli->dbPdo->query('SELECT id_patrouille, indicatif, denomination, date_heure_debut, date_heure_fin, id_prestation FROM z_patrouille WHERE date_heure_fin>NOW() ORDER BY date_heure_fin');
	}
	
public function getQMissionByTeam()
	{
	$i=0;
	$pat='';
	$sql='SELECT id_patrouille FROM z_patrouille WHERE date_heure_fin>NOW() ORDER BY date_heure_fin';
	$rep=$this->dbPdo->query($sql);
	while ($row=$rep->fetch())
		{
		$sqla='SELECT COUNT(*) FROM z_pat_missions WHERE id_patrouille="'.$row['id_patrouille'].'"';
		$repa=$this->appli->dbPdo->query($sqla);
		while($rowa=$repa->fetch())
			{
			$count=$rowa['COUNT(*)'];
			}
		$pat[$i]['id']=$row['id_patrouille'];
		$pat[$i]['count']=$count;
		$i++;		
		}
	return $pat;
	}
	
public function getMissionsCopsActifs($pat) //Renvoie les infos COPS actives au moment où existera la patrouille concernée
	{
	//RECUPERER LES INFOS COPS ACTIVES AU MOMENT OU SE FERA LA PATROUILLE CONCERNEE
	include_once ('./class/patrouilles.class.php');
	$patrouille = NEW Patrouille($this->appli->dbPdo);
	$hDeb=$patrouille->getDateHeureDebut($pat);
	$hFin=$patrouille->getDateHeureFin($pat);
	$sql='SELECT a.id_fiche, a.texteInfo,
	b.denomination AS denomCateg,
	c.denomination AS denomSec
	FROM z_fiche a
	LEFT JOIN z_categorie_fiche b ON a.id_categ = b.id_categ
	LEFT JOIN z_section_fiche c ON b.id_section = c.id_section
	WHERE interaction="O" AND date_fin >= "'.$hDeb.'" AND date_debut <= "'.$hFin.'"';
	$rep=$this->appli->dbPdo->query($sql);
	return $rep;
	}
	
public function getVacanciersActifs($pat)//Renvoie les vacanciers actifs au moment où existera la patrouille concernée
	{
	//RECUPERER LES VACANCIERS ACTIfS AU MOMENT OU SE FERA LA PATROUILLE CONCERNEE
	include_once ('./class/vacancier.class.php');
	include_once ('./class/patrouilles.class.php');
	$patrouille = NEW Patrouille($this->appli->dbPdo);
	$vacancier = NEW Vacancier($this->appli->dbPdo);
	$hDeb=$patrouille->getDateHeureDebut($pat);
	$hFin=$patrouille->getDateHeureFin($pat);
	$vacs=$vacancier->getVacanciersActifs($hDeb,$hFin);
	return $vacs;
	}
	
public function recMissionCops() //Procéder à l'enregistrement d'une attribution de mission COPS à une patrouille
	{	
	$pat=$_GET['pat'];
	$mission=$_GET['mCops'];
	$sql='SELECT COUNT(*) FROM z_pat_missions WHERE id_patrouille="'.$pat.'" AND id_fiche="'.$mission.'" AND type_mission="cops"';
	$rep=$this->appli->dbPdo->query($sql);
	while ($row=$rep->fetch())
		{
		$count=$row['COUNT(*)'];
		}
	if ($count==0)
		{
		$sql='INSERT INTO z_pat_missions (id_patrouille, type_mission, id_fiche) VALUES ("'.$pat.'", "cops", "'.$mission.'")';
		$this->appli->dbPdo->exec($sql);
		}
	}
	
public function getMissionsByIdPat($pat)
	{
	include_once ('./class/patrouilles.class.php');
	$patrouille = NEW Patrouille($this->appli->dbPdo);
	$missions = $patrouille->getMissionsByIdPatrouille($pat);
	return $missions;
	}
	
public function recMissionVacanciers() //Procéder à l'enregistrement d'une attribution de mission VACANCIERS à une patrouille
	{
	$pat=$_GET['pat'];
	$vacancier=$_GET['mVac'];
	$sql='SELECT COUNT(*) FROM z_pat_missions WHERE id_patrouille="'.$pat.'" AND id_fiche="'.$vacancier.'" AND type_mission="vacanciers"';
	$rep=$this->appli->dbPdo->query($sql);
	while ($row=$rep->fetch())
		{
		$count=$row['COUNT(*)'];
		}
	if ($count==0)
		{
		$sql='INSERT INTO z_pat_missions (id_patrouille, type_mission, id_fiche) VALUES ("'.$pat.'", "vacanciers", "'.$vacancier.'")';
		$this->appli->dbPdo->exec($sql);
		}
	}
	
public function getVacanciersByIdPat($pat)
	{
	include_once('./class/vacancier.class.php');
	$vacancier = NEW Vacancier($this->appli->dbPdo);
	$vacAttrib=$vacancier->getVacanciersByIdPat($pat);
	return $vacAttrib;
	}
	
public function removeMission()
	{
	$missionToDel=$_GET['mToDel'];
	$pat=$_GET['pat'];
	$sql='DELETE FROM z_pat_missions WHERE id_patrouille="'.$pat.'" AND id_fiche="'.$missionToDel.'"';
	$this->appli->dbPdo->exec($sql);
	}
	
public function SPCops()
	{
	$id=$_GET['id'];
	$idPat=$_SESSION['idpat'];
	//---------------------
	$sql='SELECT COUNT(*) FROM z_pat_missions WHERE id_patrouille="'.$idPat.'" AND id_fiche="'.$id.'" AND type_mission="cops" AND date_heure_in="0000-00-00 00:00:00"';
	$rep=$this->appli->dbPdo->query($sql);
	while ($row=$rep->fetch())
		{
		$count=$row['COUNT(*)'];
		}
	if($count=='0')
		{
		$sql='INSERT INTO z_pat_missions (id_patrouille, type_mission, id_fiche, date_heure_in) VALUES ("'.$idPat.'", "'.$_GET['type'].'","'.$id.'", NOW())';
		$_SESSION['MissionCops'][$id]='Started';
		}
	else
		{
		$sql='UPDATE z_pat_missions SET date_heure_in=NOW() WHERE id_patrouille="'.$idPat.'" AND id_fiche="'.$id.'" AND commentaire="Aucun"';
		$_SESSION['MissionCops'][$id]='Started';
		
		}	
	//---------------------
	/*
	if(isset($_GET['NP']))
		{
		if($_GET['NP']=="O")
			{
			$sql='INSERT INTO z_pat_missions (id_patrouille, type_mission, id_fiche, date_heure_in) VALUES ("'.$idPat.'", "'.$_GET['type'].'","'.$id.'", NOW())';
			}
		}
	else
		{
		$sql='UPDATE z_pat_missions SET date_heure_in=NOW() WHERE id_patrouille="'.$idPat.'" AND id_fiche="'.$id.'" AND commentaire="Aucun"';
		}
	//*/
	// echo $sql;	
	$this->appli->dbPdo->exec($sql);	
	}
	
public function comCops()
	{
	$id=$_GET['id'];
	$idPat=$_SESSION['idpat'];
	$sql='UPDATE z_pat_missions SET date_heure_out=NOW(), commentaire="'.$_POST['text'.$id].'" WHERE id_patrouille="'.$idPat.'" AND id_fiche="'.$id.'" AND date_heure_out="0000-00-00 00:00:00"';
	$this->appli->dbPdo->exec($sql);	
	$_SESSION['MissionCops'][$id]='Finished';
	}
	
public function recMissionAutre() //Procéder à l'enregistrement d'une attribution de mission AUTRE à une patrouille
	{
	$idpat=$_POST['pat'];
	$typeMission=$_POST['TypeMissions'];
	$idFiche=uniqid();
	$lieu=$this->htm($_POST['lieu']);
	$sql='INSERT INTO z_pat_missions (id_patrouille, type_mission, id_fiche, lieu) VALUES ("'.$idpat.'", "'.$typeMission.'","'.$idFiche.'","'.$lieu.'")';
	$this->appli->dbPdo->exec($sql);	
	}

public function getOtherMissionsByIdPat($pat)
	{
	$sql='SELECT type_mission, id_fiche, lieu FROM z_pat_missions WHERE id_patrouille="'.$pat.'" AND type_mission != "vacanciers" AND type_mission != "cops"';
	$rep=$this->dbPdo->query($sql);
	return $rep;
	}
	

}
?>