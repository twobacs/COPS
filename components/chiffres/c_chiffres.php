<?php

class CChiffres extends CBase {

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
	
public function mainMenu(){
	$co=$this->verifCo();
	if($co){
		$this->view->mainMenu();
	}	
}

public function searchDenom(){
	$co=$this->verifCo();
	if($co){
		if(!isset($_POST['denomination'])){
		$this->view->zefirst();
		}
	else{
		$this->view->showResult($this->model->getsearchDenom('denom', $_POST['denomination'], $_POST['DB'], $_POST['DH']), 'denom');
		}
	}
}

public function searchIndic(){
	$co=$this->verifCo();
	if($co){
		if(!isset($_POST['indicateur'])){
			$this->view->menuIndicateurs($this->model->getFonctionnalites());
		}
	else{
		$this->view->showResult($this->model->getsearchDenom('indic', $_POST['indicateur'], $_POST['DB'], $_POST['DH']), 'indic');
		}
	}
}

public function searchUser(){
	$co=$this->verifCo();
	if($co){
		if(!isset($_POST['user'])){
			$this->view->menuUsers($this->model->getUsers());
		}
	else{
		$this->view->showResult($this->model->getsearchDenom('user', $_POST['user'], $_POST['DB'], $_POST['DH']), 'user');
		}
	}	
}

public function exportCSV(){
	$co=$this->verifCo();
	if($co){
	$data=$this->model->getsearchDenom($_GET['type'], $_GET['param'],$_GET['DB'], $_GET['DH']);
	$this->view->afficheCSV($data,$_GET['type']);
	}
}

public function getInfoByIdFiche(){
	$data=$this->model->getInfoByIdFiche();
	$this->view->showInfosByIdFiche($data);
}

public function searchMission(){
	$data=$this->model->getMissions();
	$this->view->showMissions($data);
}

}
?>