<?php

class CUser extends CBase {

    public function __construct($appli) {
        parent::__construct($appli);
    }


public function login(){
	$data=$this->model->login($_POST['login'], $_POST['password']);
	$this->view->menuAccueil($data);
}

public function home(){
	if(isset($_SESSION['idUser'])){
		$this->view->menuAccueil();
	}
	else $this->view->formConnect();
}

public function infosUser(){
	if(isset($_SESSION['idUser'])){
		if(isset($_GET['subAction']) && ($_GET['subAction']=='updateDonnees')){
			$this->model->updateDonneesUser();
		}
		$data=$this->model->getInfosUserById();
		$this->view->infosUser($data);
		return $data;
	}
	else $this->view->unconnected();
}

public function infosPersUrg(){
	if(isset($_SESSION['idUser'])){
		$data=$this->model->getInfosPersUrg();
		$this->view->ShowInfosPersUrg($data);
	}
	else $this->view->unconnected();
}

public function addPersIncident(){
	if(isset($_SESSION['idUser'])){
		if(isset($_GET['subAction']) && ($_GET['subAction']=='addNewContact')){
			$this->model->addNewContact();
			$this->infosPersUrg();
		}
		else $this->view->formAjoutPersContact($_GET['id_user']);
	}
	
}

public function updatePersCont(){
	if(isset($_SESSION['idUser'])){
		$this->model->updatePersCont();
		$this->infosPersUrg();
	}
}

public function DelPersUrg(){
	$this->model->DelPersUrg();
	$this->infosPersUrg();
}

public function changeColor(){
	$_SESSION['background-color']=$_POST['textcolor'];
	header('location: index.php?component='.$_GET['fromC'].'&action='.$_GET['fromA'].'');
}

public function logoff(){
	echo 'reerrreee';
	session_unset();
	session_destroy();
	header('location: index.php?component=user&action=disconnect');
}

public function gestUsers(){
	if(isset($_SESSION['idUser'])){
		$niv=$this->model->nivAcces();
		if(($_SESSION['appli']=='user')&&($_SESSION['acces']>4)){
			$this->view->menuGestUsers();
		}
		else $this->view->error();
	}
	else $this->view->unconnected();
}

public function getUserById(){
	if((isset($_SESSION['idUser']))&&($_SESSION['appli']=='user')&&($_SESSION['acces']>'4')){
		$data=array();
		$data['user']=$this->model->getUserById();
		$data['services']=$this->model->getServices();
		$data['grades']=$this->model->getGrades();
		$this->view->showInfosUserById($data);
	}
	else if(!isset($_SESSION['idUser'])){
		$this->view->unconnected();
	}
	else $this->view->error();		
}

public function recModifsById(){
	if((isset($_SESSION['idUser']))&&($_SESSION['appli']=='user')&&($_SESSION['acces']>'4')){
		$this->model->recModifsById();
		header('location: index.php?component=user&action=getUserById&id='.$_GET['id'].'&record');
	}
	else if(!isset($_SESSION['idUser'])){
		$this->view->unconnected();
	}
	else $this->view->error();	
}

public function addUser(){
	if((isset($_SESSION['idUser']))&&($_SESSION['appli']=='user')&&($_SESSION['acces']>'4')){
		if(isset($_GET['record'])){
			$id=$this->model->addUser();
			header('location: index.php?component=user&action=getUserById&id='.$id.'&record');
		}
		else{
			$data['services']=$this->model->getServices();
			$data['grades']=$this->model->getGrades();
			$this->view->formAddUser($data);
		}
	}
	else if(!isset($_SESSION['idUser'])){
		$this->view->unconnected();
	}
	else $this->view->error();	
}

public function modifpassword(){
	if(isset($_POST['newPwd1'])){
		$data=$this->model->modifpassword();
		switch($data){
			case '0':
				// $login=$this->model->getLoginById();
				// $data=$this->model->login($login, $_POST['newPwd1']);
				// $this->view->menuAccueil($data,'modifpwd');
				header('location: index.php?component=user&action=home');
				break;
			case '1':
				header('location: index.php?component=user&action=modifpassword&error');
				break;				
		}
	}
	else{
		$data=$this->model->getInfosUserById();
		$this->view->formModifPassword($data);
	}
}

}
?>
