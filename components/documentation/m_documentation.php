<?php

class MDocumentation extends MBase {

	private $checkDbPDO = false;

	public function __construct($appli) {
		parent::__construct($appli);
		
	}
	
public function getNivAcces()
	{
	$sql='SELECT id_nivAcces FROM z_user_app WHERE id_user="'.$_COOKIE['iduser'].'" AND id_app="10"';
	$rep=$this->appli->dbPdo->query($sql);
	while ($row=$rep->fetch())
		{
		$nivAcces=$row['id_nivAcces'];
		}
	return $nivAcces;
	}
	

}
?>
