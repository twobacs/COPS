<?php

class CUsers extends CBase {

    public function __construct($appli) {
        parent::__construct($appli);
    }

    
public function verifCo()  //fonction de vérification de l'existence de la variable de session iduser
	{
	if (isset($_COOKIE["iduser"]) && $_COOKIE["iduser"]>'')
		{
		return true;
		}
	}

public function logForm() //fonction renvoyant vers le formulaire d'identification
	{
	$this->view->affichelogform();
	}
	
	
public function login()
	{
		if($_POST["mode"]=="3"){ /* 29/09/2015 */
		header('location: GR/index.php?component=accueil&action=homepage');	
		}
	else{
		$data=$this->model->login(); //via le model, création de la variable de session iduser, type_user et mode (tablette ou pc)
		$users=$this->model->selectAllUsers();
		$cars=$this->model->selectAllCars();
		$uzi=$this->model->selectUZI();
		$indicatifs=$this->model->selectIndicatifsNow();
		$_SESSION['appli']='users';
		$_SESSION['nivApp']=$this->model->getNivAcces();
		$this->view->afficheResultLogin($data,0,$users,$cars,$uzi,$indicatifs); //renvoie vers la vue avec l'info du process d'identification
		}
	}
	
public function MenuTablette()
	{
	if (!isset($_SESSION['idbs']))
		{
		$uuid=$this->model->doBS();
		}
	$_SESSION['appli']='';
	$this->view->accueilTab2();
	}
	
public function fromMenuTablette(){
	$data=$this->model->getMissionsByIdPat();
	$this->view->showMissionsByIdPatBis($data);
	}

public function logoff() //déconnexion
	{
	$this->model->logoff();
	setcookie("iduser",'');

	// $_COOKIE['iduser'] = '';
	$_SESSION["nivApp"] = '';
	$_SESSION["idbs"] = '';
	$_SESSION["appli"] = '';
	if (isset($_SESSION['idbs']))
		{
		$this->model->closeBS();
		}
	// unset ($_SESSION);
	$this->appli->ctContent.='<center>Déconnexion en cours<br /><br /><img src="../media/icons/ajax-loader.gif" height="5px"></center>';
	$this->appli->bienvenue='';
	session_unset();
	session_destroy();
	header("refresh:3; url=index.php?action=deco", true, 303);
  }
  
 public function modifPassword() //a approfondir
	{
	$ok=$rep=$this->model->modifPassword();
	if ($ok)
		{
		$this->view->menuPrincipal('FromModifPwd');
		}
	}
	
public function mainMenu()
	{
	$co=$this->verifCo();
	if ($co)
		{
		$level=$this->model->checkLevel();
		$this->view->mainMenu($level);
		}
	}
	
public function addUser()
	{
	$co=$this->verifCo();
	if ($co)
		{
		$level=$this->model->checkLevel();
		if ($level<30)
			{
			$this->view->nonDroit();
			}
		else
			{
			$sexe=$this->model->selectSexe();
			$grades=$this->model->selectGrade();
			$services=$this->model->selectService();
			$this->view->formAddUser($sexe,$grades,$services);
			}
		}
	}
	
public function modifUser()
	{
	$co=$this->verifCo();
	if ($co)
		{
		$level=$this->model->checkLevel();
		if ($level<30)
			{
			$this->view->nonDroit();
			}
		else
			{
			$type=$_GET['type'];
			switch ($type)
				{
				case 'OneUser':
					if (isset($_GET['id']))
						{
						$id=$_GET['id'];
						$user=$this->model->selectUserById($id);
						$services=$this->model->selectService();
						$grades=$this->model->selectGrade();
						$this->view->afficheInfosUser($user,$grades,$services);
						}
					else
						{
						$this->view->modifOneUser();
						}
					break;
					
				case 'ListUser':
					$users=$this->model->selectAllUsers();
					$this->view->showAllUsers($users);
					break;
				}
			
			}
		}
	}
}
?>
