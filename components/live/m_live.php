<?php

class MLive extends MBase {

	private $checkDbPDO = false;

	public function __construct($appli) {
		parent::__construct($appli);
		
	}

private function convert($data)
{
	$rep=htmlentities($data, ENT_QUOTES, "UTF-8");
	return $rep;
}

public function getMessagesUnread()
{
	include_once ('./class/live.class.php');
	$live = NEW Live($this->appli->dbPdo);
	$toRead = $live->selectUnreadByIdUser($_COOKIE['iduser']);
	return $live->getMessagesInfoByArray($toRead);	
}

public function setRead($id)
{
	include_once ('./class/live.class.php');
	$live = NEW Live($this->appli->dbPdo);
	$live->setReadById($id,$_COOKIE['iduser']);
}
	
}
?>