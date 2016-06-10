<?php

class MQuartier extends MBase {

	private $checkDbPDO = false;

	public function __construct($appli) {
		parent::__construct($appli);
		
	}

public function checkLevel()
	{
	$id=$_COOKIE['iduser'];
	$level=-1;
	$sql='select id_nivAcces FROM z_user_app WHERE id_app="5" AND id_user="'.$id.'"';
	$rep=$this->appli->dbPdo->query($sql);
	while ($row=$rep->fetch())
		{
		$level=$row['id_nivAcces'];
		}
	return $level;
	}
	
public function getRues()
	{
	include_once('./class/quartier.class.php');
	$quartier=new Quartier($this->appli->dbPdo);
	$data=$quartier->getRues();
	return $data;	
	}
	
public function getInfos($type)
	{
	include_once('./class/quartier.class.php');
	$quartier=new Quartier($this->appli->dbPdo);
	
	switch ($type)
		{
		case 'antennes':
			$data=$quartier->getInfosAntennes();
			break;
			
		case 'quartier':
			$data=$quartier->getInfosQuartiers();
			break;
			
		case 'agent':
			$data=$quartier->getInfosAgents();
			break;
		}
	return $data;
	}

	
}
?>