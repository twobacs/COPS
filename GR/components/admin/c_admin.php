<?php

class CAdmin extends CBase {

    public function __construct($appli) {
        parent::__construct($appli);
    }


public function home(){
	if(isset($_SESSION['idUser'])){
		$this->view->menuAccueil();
	}
	else $this->view->unconnected();
}

public function gestAdminSite(){
	if(isset($_SESSION['idUser'])){
		$data=$this->model->getAdminSite();
		$this->view->gestAdminSite($data);
	}
	else $this->view->unconnected();
}

public function delAdminSite(){
	if(isset($_SESSION['idUser'])){
		if($_GET['user']!=$_SESSION['idUser']){
			$this->model->delAdminSite();
			$this->gestAdminSite();
		}
		else $this->view->error(1,'gestAdminSite');
	}
	else $this->view->unconnected();
}

public function addAdminSite(){
	if(isset($_SESSION['idUser'])){
		if((isset($_GET['record']))&&($_POST['userToAdd']!='')){
			$this->model->addAdminSite();
			$this->gestAdminSite();
		}
		else{
			$data=$this->model->getUsers();
			$this->view->addAdminSite($data);
		}
	}
	else $this->view->unconnected();
}

public function gestAdminModules(){
	if(isset($_SESSION['idUser'])){
		if((isset($_GET['subAction'])) && (($_GET['subAction']=='delAdmin')||($_GET['subAction']=='addAdmin'))){
			if($_GET['subAction']=='addAdmin'){
				$error=$this->model->addAdminModule();
				if($error==0){
					$data=$this->model->getAdminModules();
					$users=$this->model->getUsers();
					$this->view->gestAdminModules($data,$users);
				}
				else $this->view->error(2,'gestAdminModules');
			}
			else if($_GET['subAction']=='delAdmin'){
				if($_GET['user']!=$_SESSION['idUser']){
					$this->model->delAdminModule();
					$data=$this->model->getAdminModules();
					$users=$this->model->getUsers();
					$this->view->gestAdminModules($data,$users);
				}
				else $this->view->error(1,'gestAdminModules');
			}
		}
		else{
			$data=$this->model->getAdminModules();
			$users=$this->model->getUsers();
			$this->view->gestAdminModules($data,$users);
		}
	}
	else $this->view->unconnected();
}

public function delDroitsById(){
	if(isset($_SESSION['idUser'])){
		$module=$_GET['module'];
		$error=$this->model->delAdminModule();
		header("Location: ?component=".$module."&action=gestUsers&subAction=supp&error=".$error);
	}
	else $this->view->unconnected();
	
}

public function addUserToModule(){
	if(isset($_SESSION['idUser'])){
		$module=$_GET['module'];
		$error=$this->model->addUserToModule();
		header("Location: ?component=".$module."&action=gestUsers&subAction=add&error=".$error);
	}
	else $this->view->unconnected();
}
}
?>