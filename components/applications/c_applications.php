<?php

class CApplications extends CBase {

    public function __construct($appli) {
        parent::__construct($appli);
    }

public function verifCo()
	{
	if (isset($_COOKIE['iduser']) && ($_COOKIE['iduser']>""))
		{
		return true;
		}
	else 
		{
		$this->view->nonco();
		return false;
		}
	}
	
public function showApps($idUser=0)
	{
	$id=($idUser!=0)?$idUser:0;
	// echo $id;
	$data=$this->model->selectAppByUserId($id);
	$this->view->showApps($data);
	$_SESSION['appli']='';
	}
	
public function gestDroits()
	{
	if (isset($_COOKIE['iduser']))
		{
		if (isset($_GET['appli'])) //ACCES AU MENU DE GESTION DE DROITS D'UNE APPLI SELECTIONNEE
			{
			$data=$this->model->GestDroits($_GET['appli']);
			$this->view->afficheHtml($data);
			}
		else // ACCES AU MENU DE CHOIX DE L'APPLI A ADMINISTRER
			{
			$data=$this->model->selectAppliGestDroits(); 
			$this->view->showMenuGestDroits($data);
			}
		}
	else $this->view->nonCo();
	}
	
public function test()
	{
	$this->appli->ctContent='sdmkgjhsfhsdmkghsjhmk';
	}
	
public function MenuAddUser()  //AFFICHAGE DU MENU AJOUT UTILISATEUR
	{
	if (isset($_COOKIE['iduser']))
		{
		$users=$this->model->MenuAddUser($_GET['appli']);
		$niveau=$this->model->inputTypeSelectNivAcces();
		$this->view->MenuAddUser($users,$niveau,$_GET['appli']);
		}
	else $this->view->nonCo();
	}
	
public function recordNewUser() //ENREGISTREMENT EN BDD DE L'UTILISATEUR CHOISI
	{
	if (isset($_COOKIE['iduser']))
		{
		$appli=$this->model->recordNewUser();
		$users=$this->model->MenuAddUser($_POST['idApp']);
		$niveau=$this->model->inputTypeSelectNivAcces();	
		$this->view->insertNewUserOk($appli,$users,$niveau);
		}
	else $this->view->nonCo();	
	}
	
}
?>