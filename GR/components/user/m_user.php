<?php

class MUser extends MBase {

	private $checkDbPDO = false;

	public function __construct($appli) {
		parent::__construct($appli);
		
	}
	
public function nivAcces(){
	include_once('./class/nivacces.class.php');
	$acces=new NivAcces($this->appli->dbPdo);
	return $acces->getNivAcces($_GET['component'], $_SESSION['idUser']);
}

public function login($login, $password){
	include_once('./class/users.class.php');
	$user=new Users($this->appli->dbPdo);
	return $user->login($login, $password);
}

public function getInfosUserById(){
	include_once('./class/users.class.php');
	$user=new Users($this->appli->dbPdo);
	return $user->getInfosUserById($_SESSION['idUser']);
}

public function updateDonneesUser(){
	include_once('./class/users.class.php');
	$user=new Users($this->appli->dbPdo);
	$user->updateDonneesUser($_GET['user'], $_POST['TelFixeUser'], $_POST['GSMUser'], $_POST['faxUser'], $_POST['mailUser']);
}

public function getInfosPersUrg(){
	include_once('./class/pers_contact.class.php');
	$contact=new Pers_contact($this->appli->dbPdo);
	return $contact->getInfosPersUrg($_GET['id_user']);	
}

public function addNewContact(){
	include_once('./class/pers_contact.class.php');
	$contact=new Pers_contact($this->appli->dbPdo);
	$contact->addNewContact($_REQUEST);
}

public function updatePersCont(){
	include_once('./class/pers_contact.class.php');
	$contact=new Pers_contact($this->appli->dbPdo);
	$contact->updateNewContact($_REQUEST);
}

public function DelPersUrg(){
	include_once('./class/pers_contact.class.php');
	$contact=new Pers_contact($this->appli->dbPdo);
	$contact->DelPersUrg($_REQUEST);
}

public function getAllUsers(){
	include_once('./class/users.class.php');
	$users=new Users($this->appli->dbPdo);
	return $users->getAllUsers();
}

public function getUserById(){
	include_once('./class/users.class.php');
	$users=new Users($this->appli->dbPdo);
	return $users->getInfosUserById($_GET['id']);
}

public function getServices(){
	include_once('./class/services.class.php');
	$services=new Services($this->appli->dbPdo);
	return $services->getServices();
}

public function getGrades(){
	include_once('./class/grades.class.php');
	$grades=new Grades($this->appli->dbPdo);
	return $grades->getGrades();
}

public function recModifsById(){
	include_once('./class/users.class.php');
	$users=new Users($this->appli->dbPdo);
	return $users->update($_GET['id'], $_POST);	
}

public function addUser(){
	include_once('./class/users.class.php');
	$users=new Users($this->appli->dbPdo);
	return $users->addUser($_POST);
}

public function modifpassword(){
	include_once('./class/users.class.php');
	$users=new Users($this->appli->dbPdo);
	return $users->modifPassword($_POST,$_SESSION['idUser']);	
}

public function getLoginById(){
	include_once('./class/users.class.php');
	$users=new Users($this->appli->dbPdo);
	return $users->getLoginById($_SESSION['idUser']);	
}

}
?>