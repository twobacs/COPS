<?php

class MUsers extends MBase {

	private $checkDbPDO = false;

	public function __construct($appli) {
		parent::__construct($appli);
		
	}

public function login()
	{
	include_once('./class/user.class.php');
	$user = new User($this->appli->dbPdo);
	$login = $_POST['login'];
	$password = $_POST['password'];
	$result=$user->loadViaLogin($login,$password);
	$_SESSION["mode"]=$_POST['mode'];
	return $result;
	}
	
public function logoff()
	{
	if (isset($_COOKIE['iduser']))
		{
		include_once('./class/user.class.php');
		$user = new User($this->appli->dbPdo);
		$id=$_COOKIE['iduser'];
		$user->logoff($id);
		}
	
	}
	
public function getNivAcces()
	{
	if((isset($_COOKIE["iduser"]))&&($_COOKIE!=''))
	{
		$idUser=$_COOKIE["iduser"];
	}
	else $idUser=$_SESSION['idUser'];
	$sql='SELECT id_nivAcces FROM z_user_app WHERE id_user="'.$idUser.'" AND id_app="3"';
	$rep=$this->appli->dbPdo->query($sql);
	while ($row=$rep->fetch())
		{
		$nivAcces=$row['id_nivAcces'];
		}
	if (!isset($nivAcces))
		{
		$nivAcces='0';
		}
	return $nivAcces;
	}

private function htm($data)
	{
	$rep=htmlentities($data, ENT_QUOTES, "UTF-8");
	$rep=htmlentities($rep);
	return $rep;
	}
	
public function modifPassword()
	{
	if ($_POST['pwd1']==$_POST['pwd2'])
		{
		include_once('./class/user.class.php');
		$user = new User($this->appli->dbPdo);
		$user->setNewPassword($_POST['pwd1']);
		return true;
		}
	else {}
	
	}
	
public function checkLevel()
	{
	$id=$_COOKIE['iduser'];
	$level=-1;
	$sql='select id_nivAcces FROM z_user_app WHERE id_app="3" AND id_user="'.$id.'"';
	$rep=$this->appli->dbPdo->query($sql);
	while ($row=$rep->fetch())
		{
		$level=$row['id_nivAcces'];
		}
	return $level;
	}
	
public function selectGrade()
	{
	$sql='SELECT * FROM grades ORDER BY denomination_grade ASC';
	return $this->appli->dbPdo->query($sql);
	}
	
public function selectService()
	{
	$sql='SELECT * FROM services ORDER BY denomination_service ASC';
	return $this->appli->dbPdo->query($sql);
	}
	
public function selectSexe()
	{
	$sql='SELECT * FROM sexe ORDER BY denomination DESC';
	return $this->appli->dbPdo->query($sql);
	}
	
public function selectUserById($id)
	{
	include_once('./class/user.class.php');
	$user = new User($this->appli->dbPdo);
	return $user->selectUserById($id);
	}
	
public function selectAllUsers()
	{
	include_once('./class/user.class.php');
	$user = new User($this->appli->dbPdo);
	return $user->selectAllUsers();
	}
	
public function getDenEquipe()
	{
	include_once('./class/bs.class.php');
	$bs = new BS($this->appli->dbPdo);
	return $bs->getDenEquipe();
	}
	
public function selectAllCars()
	{
	include_once('./class/bs.class.php');
	$bs = new BS($this->appli->dbPdo);
	return $bs->selectAllCars();
	}
	
public function selectUZI()
	{
	include_once('./class/bs.class.php');
	$bs = new BS($this->appli->dbPdo);
	return $bs->selectUZIDispo();
	}
	
public function selectIndicatifsNow()
	{
	include_once('./class/bs.class.php');
	$bs = new BS($this->appli->dbPdo);
	return $bs->selectIndicatifsNow();
	}
	
public function doBS()
	{
	//-------------------------------------------------------------------------------------------//
	// RÉCUPÉRER LES DONNÉES TRANSMISES DU FORMULAIRE COMPLÉTÉ SUITE AU CHOIX DU MENU "TABLETTE" //
	//-------------------------------------------------------------------------------------------//
	// 1. NOMBRE DE COLLABORATEURS
	$nbCol=$_POST['nbCol'];
	
	// 2. NOMBRE DE VÉHICULES
	$nbVV=$_POST['nbVV'];
	
	// 3. ARME COLLECTIVE
	$armeC = (isset($_POST['UZI'])) ? $_POST['UZI'] : false ;
	
	// 4. APPAREIL PHOTO
	$appPh = (isset($_POST['appPhoto'])) ? $_POST['appPhoto'] : false ;
	
	// 5. ETT
	$ett = (isset($_POST['ETT'])) ? $_POST['ETT'] : false ;
	
	// 6. GSM
	$gsm = (isset($_POST['gsm'])) ? $_POST['gsm'] : false ;
	
	// 7. DÉNOMINATION DE L'ÉQUIPE
	$denom = $this->htm($_POST['denomination']);
	
	// 8. IDENTIFIANT DE LA PATROUILLE
	$id = $_POST['idPat'];
	
	// 9. INDICATIF
	$sql='SELECT indicatif FROM z_patrouille WHERE id_patrouille="'.$id.'"';
	$rep=$this->appli->dbPdo->query($sql);
	while ($row=$rep->fetch())
		{
		$indic=$row['indicatif'];
		}
	
	//-----------------------------------------------------//
	// RÉCUPÉRATION DES DONNÉES DES COLLABORATEURS ENGAGÉS //
	//-----------------------------------------------------//
	for ($i=1;$i<=$nbCol;$i++)
		{
		$collabo[$i]=$_POST['colaps'.$i];
		}
	if (!isset($collabo))//(sizeof($collabo)==0)
		{
		$collabo=-1;
		}
	
	//------------------------------------------------//
	// RÉCUPÉRATION DES DONNÉES DES VÉHICULES ENGAGÉS //
	//------------------------------------------------//	
	for ($i=1;$i<=$nbVV;$i++)
		{
		$vv[$i]=$_POST['VV'.$i];
		}	
	if (!isset($vv))//(sizeof($vv)==0)
		{
		$vv=-1;
		}
	
	include_once('./class/bs.class.php');
	$bs = new BS($this->appli->dbPdo);
	$data=$bs->doNewBS($armeC, $appPh, $ett, $gsm, $denom, $indic, $id, $collabo, $vv);
	return $data;	
	}
	
public function getMissionsByIdPat($i=0)
	{
	/* FONCTION RECHERCHANT LES MISSIONS SUR BASE DE l'id_patrouille (variable de session) */
	$idPat = $_SESSION['idpat'];
	// echo $idPat;
	$sql='SELECT type_mission, id_fiche, date_heure_in, date_heure_out, lieu FROM z_pat_missions WHERE id_patrouille="'.$idPat.'" ORDER BY id_fiche';
	$rep=$this->appli->dbPdo->query($sql);
	$i=0;
	$missions=array();
	while ($row=$rep->fetch())
		{
		$type=$row['type_mission'];
		$missions[$i]['dh_in']=$row['date_heure_in'];
		$missions[$i]['dh_out']=$row['date_heure_out'];
		switch ($type)
			{
			case 'cops' :
				$sqla='SELECT a.texteInfo, a.id_categ,
				b.denomination AS denomCateg,
				c.denomination AS denomSection
				FROM z_fiche a
				LEFT JOIN z_categorie_fiche b ON a.id_categ = b.id_categ
				LEFT JOIN z_section_fiche c ON c.id_section = b.id_section
				WHERE a.id_fiche="'.$row['id_fiche'].'"';
				$repa=$this->appli->dbPdo->query($sqla);
				while ($rowa=$repa->fetch())
					{
					$missions[$i]['type']='cops';
					$missions[$i]['idCateg']=$rowa['id_categ'];
					$missions[$i]['section']=$rowa['denomSection'];
					$missions[$i]['categ']=$rowa['denomCateg'];
					$missions[$i]['texte']=$rowa['texteInfo'];
					$missions[$i]['id']=$row['id_fiche'];
					}
				
				break;
				
			case 'vacanciers' :
				include_once('./class/vacancier.class.php');
				$vac = NEW Vacancier($this->appli->dbPdo);
				$repb=$vac->getVacInfoById($row['id_fiche']);
				$missions[$i]['id']=$row['id_fiche'];
				$missions[$i]['type']='vacancier';
				$missions[$i]['rue']=$repb['NomRue'];
				$missions[$i]['numero']=$repb['numero'];
				$missions[$i]['CP']=$repb['CP'];
				$missions[$i]['ville']=$repb['ville'];
				$missions[$i]['depart']=$repb['depart'];
				$missions[$i]['retour']=$repb['retour'];
				$missions[$i]['gMap']=$repb['gMap'];
				// $repc=$vac->getIncidentsById($row['id_fiche']);
				$missions[$i]['nbIncident']=$vac->getNbIncidentsById($row['id_fiche']);
				$missions[$i]['incident']=$vac->getIncidentsById($row['id_fiche']);
				break;
				
			case 'CS' :
				$missions[$i]['type']='CS';
				$missions[$i]['id']=$row['id_fiche'];
				$missions[$i]['lieu']=$row['lieu'];
				$missions[$i]['HDeb']=$row['date_heure_in'];
				$missions[$i]['HFin']=$row['date_heure_out'];
				break;
				
			case 'PP' :
				$missions[$i]['type']='PP';
				$missions[$i]['id']=$row['id_fiche'];
				$missions[$i]['lieu']=$row['lieu'];
				$missions[$i]['HDeb']=$row['date_heure_in'];
				$missions[$i]['HFin']=$row['date_heure_out'];
				break;
			
			case 'PV' :
				$missions[$i]['type']='PV';
				$missions[$i]['id']=$row['id_fiche'];
				$missions[$i]['lieu']=$row['lieu'];
				$missions[$i]['HDeb']=$row['date_heure_in'];
				$missions[$i]['HFin']=$row['date_heure_out'];
				break;
			
			case 'SI' :
				$missions[$i]['type']='SI';
				$missions[$i]['id']=$row['id_fiche'];
				$missions[$i]['lieu']=$row['lieu'];
				break;
			
			case 'INT' :
				$missions[$i]['type']='INT';
				break;
				
			case 'PAT' :
				$missions[$i]['type']='PAT';
				break;	
			}
		$i++;	
		}
	return $missions;
	}
	
public function closeBS()
	{
	$bs=$_SESSION['idbs'];
	$sql = 'UPDATE z_bs SET date_heure_out = NOW() WHERE id_bs="'.$bs.'"';
	$this->appli->dbPdo->exec($sql);
	}

}
?>