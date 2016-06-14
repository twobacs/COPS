<?php

class MAdmin extends MBase {

	private $checkDbPDO = false;

	public function __construct($appli) {
		parent::__construct($appli);
		
	}

public function getAdminSite(){
	include_once('./class/nivacces.class.php');
	$acces=new NivAcces($this->appli->dbPdo);
	return $acces->getAdminSite('administrateur');
}

public function delAdminSite(){
	include_once('./class/nivacces.class.php');
	$acces=new NivAcces($this->appli->dbPdo);	
	$acces->delAdminSite($_GET['user']);
}

public function addAdminSite(){
	include_once('./class/nivacces.class.php');
	$user=new NivAcces($this->appli->dbPdo);
	$user->addAdminSite($_POST['userToAdd']);
}

public function getUsers(){
	include_once('./class/users.class.php');
	$users=new Users($this->appli->dbPdo);
	return $users->getAllUsers();
}

public function getAdminModules(){
	include_once('./class/nivacces.class.php');
	$acces=new NivAcces($this->appli->dbPdo);
	return $acces->getAdminSite();	
}

public function addAdminModule(){
	include_once('./class/nivacces.class.php');
	$user=new NivAcces($this->appli->dbPdo);
	return $user->addDroitModule($_GET['module'],$_POST['user'],'10');
}

public function delAdminModule(){
	include_once('./class/nivacces.class.php');
	$user=new NivAcces($this->appli->dbPdo);
	return $user->delDroitModule($_GET['idRow'],$_SESSION['idUser']);
}

public function addUserToModule(){
	include_once('./class/nivacces.class.php');
	$user=new NivAcces($this->appli->dbPdo);
	return $user->addDroitModule($_GET['module'],$_POST['selectedUser'],$_POST['nivAcces']);	
}

}
?>