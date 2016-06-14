<?php

class MGestpers extends MBase {

	private $checkDbPDO = false;

	public function __construct($appli) {
		parent::__construct($appli);
		
	}

public function nivAcces(){
	include_once('./class/nivacces.class.php');
	$niv=new NivAcces($this->appli->dbPdo);
	$niv->getNivAcces($_GET['component'], $_SESSION['idUser']);
	// return $niv->login($login, $password);
}
}
?>