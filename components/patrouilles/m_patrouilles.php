<?php

class MPatrouilles extends MBase {

	private $checkDbPDO = false;

	public function __construct($appli) {
		parent::__construct($appli);
		
	}

public function checkLevel()
	{
	$id=$_COOKIE['iduser'];
	$level=-1;
	$sql='select id_nivAcces FROM z_user_app WHERE id_app="6" AND id_user="'.$id.'"';
	$rep=$this->appli->dbPdo->query($sql);
	while ($row=$rep->fetch())
		{
		$level=$row['id_nivAcces'];
		}
	return $level;
	}
	
private function htm($data)
	{
	$rep=htmlentities($data, ENT_QUOTES, "UTF-8");
	// $rep=htmlentities($rep);
	return $rep;
	}
	
public function recNewPMob()
	{
	include_once('./class/patrouilles.class.php');
	$denom=$this->htm($_POST['denPat']);
	$indic=$this->htm($_POST['indicPat']);
	$dhd=$this->htm($_POST['dhDebut']);
	$dhf=$this->htm($_POST['dhFin']);
	
	$patrouille = new Patrouille($this->appli->dbPdo);
	$data = $patrouille->recNewPat($denom, $indic, $dhd, $dhf);
	return $data;
	}
	
public function recNewAutrePat()
	{
	include_once('./class/patrouilles.class.php');
	$denom=$this->htm($_POST['denPat']);
	$indic=$this->htm($_POST['indicPat']);
	$fonctionnalite=$_POST['fonctionnalite'];
	$prestation=$_POST['prest'];
	$dhd=$this->htm($_POST['dhDebut']);
	$dhf=$this->htm($_POST['dhFin']);
	
	$patrouille = new Patrouille($this->appli->dbPdo);
	$data = $patrouille->recNewPat($denom, $indic, $dhd, $dhf, $prestation); //MODIFIER LA CLASSE POUR INSERTION BDD

	return $data;
	}
	
public function viewPat()
	{
	if (isset($_GET['tri']))
		{
		$tri=$_GET['tri'];
		}
	else
		{
		$tri='';
		}
	include_once('./class/patrouilles.class.php');
	$patrouille = new Patrouille($this->appli->dbPdo);
	$data=$patrouille->listPat($tri);
	return $data;
	}
	
public function searchPat()
	{
	$dhb=(empty($_POST['dhb'])) ? date('Y-m-d').'T'.'00:00:00' : $_POST['dhb'];
	$dhh=(empty($_POST['dhh'])) ? date('Y-m-d').'T'.'23:59:59' : $_POST['dhh'];
	$sql='SELECT date_heure_debut, date_heure_fin, indicatif, denomination FROM z_patrouille WHERE date_heure_debut >= "'.$dhb.'" AND date_heure_fin <= "'.$dhh.'" ORDER BY date_heure_debut';
	$data=$this->appli->dbPdo->query($sql);
	// echo $dhb;
	return $data;
	}
	
public function getFonctionnalites()
	{
	include_once ('./class/patrouilles.class.php');
	$patrouille = new Patrouille($this->appli->dbPdo);
	$data = $patrouille->getFonctionnalites();
	return $data;
	}

public function recNewPMobRecu()
	{
	$denom=$_POST['denPat'];
	$indic=$_POST['indicPat'];
	$dateD=$_POST['dateDebut'];
	$dateF=$_POST['dateFin'];
	$hD=$_POST['hDebut'];
	$hF=$_POST['hFin'];
	$recurrence=$_POST['recurrence'];
	$error=array();
	$i=0;
	while (strtotime($dateD)<=strtotime($dateF))
		{
		if($i!=0){
			$debut=date("Y-m-d", strtotime($dateD. ' + '.$recurrence.' DAY')).' '.$hD.':00';
		}
		else{
			$debut=date("Y-m-d", strtotime($dateD. ' + 0 DAY')).' '.$hD.':00';;
		}
		$dateD=$debut;
		
		if($hF<$hD){
			$fin=date("Y-m-d", strtotime($dateD. ' + '.$recurrence.' DAY')).' '.$hF.':00';
		}
		else{
			$fin=date("Y-m-d", strtotime($dateD. ' + 0 DAY')).' '.$hF.':00';
		}
				
		$sql='SELECT COUNT(*) FROM z_patrouille WHERE indicatif="'.$indic.'" AND date_heure_debut="'.$debut.'"';
		$rep=$this->appli->dbPdo->query($sql);
		while ($row=$rep->fetch())
			{
			$count=$row['COUNT(*)'];
			}
		if ($count==0)
			{
			$sql='INSERT INTO z_patrouille (id_patrouille, date_heure_debut, date_heure_fin, indicatif, denomination, actif, id_prestation) VALUES ("'.md5(uniqid('', true)).'", "'.$debut.'", "'.$fin.'", "'.$indic.'", "'.$denom.'", "O", "0")';
			$this->appli->dbPdo->exec($sql);
			}
		else
			{
			if($i!=0){
				$error[$i]['date']=$debut;
				}
			$i++;
			}
		}
	return $error;	
	}
	
public function recNewAutrePatRecu()
	{
	$denom=$_POST['denPat'];
	$indic=$_POST['indicPat'];
	$dateD=$_POST['dateDebut'];
	$dateF=$_POST['dateFin'];
	$hD=$_POST['hDebut'];
	$hF=$_POST['hFin'];
	$fonctionnalite=$_POST['fonctionnalite'];
	$prestation=$_POST['prest'];
	$recurrence=$_POST['recurrence'];
	$error=array();
	$i=0;
	while (strtotime($dateD)<=strtotime($dateF))
		{
		$dateDTS = strtotime(date("Y-m-d", strtotime($dateD)) . " +".$recurrence." day");
		$dateD=date("Y-m-d",$dateDTS);
		$sql='SELECT COUNT(*) FROM z_patrouille WHERE indicatif="'.$indic.'" AND date_heure_debut="'.$dateD.' '.$hD.':00"';
		$rep=$this->appli->dbPdo->query($sql);
		while ($row=$rep->fetch())
			{
			$count=$row['COUNT(*)'];
			}
		if ($count==0)
			{
			$sql='INSERT INTO z_patrouille (id_patrouille, date_heure_debut, date_heure_fin, indicatif, denomination, actif, id_prestation) VALUES ("'.md5(uniqid('', true)).'", "'.$dateD.' '.$hD.':00", "'.$dateD.' '.$hF.':00", "'.$indic.'", "'.$denom.'", "O", "'.$prestation.'")';
			$this->appli->dbPdo->exec($sql);
			}
		else
			{
			$error[$i]['date']=$dateD;
			$i++;
			}
		}
	return $error;	
	}
}
?>