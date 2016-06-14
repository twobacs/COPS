<?php

class MPersonnel extends MBase {

	private $checkDbPDO = false;

	public function __construct($appli) {
		parent::__construct($appli);
	}


public function nivAcces(){
	include_once('./class/nivacces.class.php');
	$acces=new NivAcces($this->appli->dbPdo);
	return $acces->getNivAcces('personnel', $_SESSION['idUser']);
}

public function getDroitsLog(){
	include_once('./class/nivacces.class.php');
	$acces=new NivAcces($this->appli->dbPdo);
	return $acces->getDtsByApp('personnel');
}

public function getUsers(){
	include_once('./class/users.class.php');
	$users=new Users($this->appli->dbPdo);
	return $users->getAllUsers();
}

}
?>