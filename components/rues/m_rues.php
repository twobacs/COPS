<?php

class MRues extends MBase {

	private $checkDbPDO = false;

	public function __construct($appli) {
		parent::__construct($appli);
		
	}

public function checkLevel()
	{
	$id=$_COOKIE['iduser'];
	$level=-1;
	$sql='select id_nivAcces FROM z_user_app WHERE id_app="4" AND id_user="'.$id.'"';
	$rep=$this->appli->dbPdo->query($sql);
	while ($row=$rep->fetch())
		{
		$level=$row['id_nivAcces'];
		}
	return $level;
	}
	
	
public function selectRues()
	{
	include_once('./class/rues.class.php');
	$rue = new Rue($this->appli->dbPdo);
	$data=$rue->selectRues();
	return $data;
	}
	
public function selectRueById($id)
	{
	include_once('./class/rues.class.php');
	$rue = new Rue($this->appli->dbPdo);
	$data=$rue->selectRueById($id);
	return $data;
	}
	
public function insertNewRue()
	{
	$nom=strtoupper(htmlentities($_POST['newNom'],ENT_QUOTES, "UTF-8"));
	$naam=strtoupper(htmlentities($_POST['newNaam'],ENT_QUOTES, "UTF-8"));
	
	$sql='SELECT COUNT(*) FROM z_rues WHERE NomRue LIKE "%'.$nom.'%" OR StraatNaam LIKE "%'.$naam.'%"';
	echo $sql;
	}


}
?>