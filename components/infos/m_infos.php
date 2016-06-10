<?php

class MInfos extends MBase {

	private $checkDbPDO = false;

	public function __construct($appli) {
		parent::__construct($appli);
		
	}

private function convert($data)
{
	$rep=htmlentities($data, ENT_QUOTES, "UTF-8");
	return $rep;
}

public function pushInfo()
{
	if(($_POST['editor1']!='') &&($_POST['title']!=''))
	{
		$sql='INSERT INTO z_info_push (id_user, info, dateIn, titre) VALUES (:user, :info, NOW(), :titre)';
		$req=$this->appli->dbPdo->prepare($sql);
		$req->execute(array(
		'user'=>$_COOKIE["iduser"],
		'info'=>$this->convert($_POST['editor1']),
		'titre'=>$this->convert($_POST['title'])
		));
		$sql='SELECT id_info FROM z_info_push WHERE id_user=:user AND info=:info AND titre=:titre';
		$req=$this->appli->dbPdo->prepare($sql);
		$req->execute(array(
		'user'=>$_COOKIE["iduser"],
		'info'=>$this->convert($_POST['editor1']),
		'titre'=>$this->convert($_POST['title'])
		));
		while($row=$req->fetch())
		{
			$idInfo=$row['id_info'];
		}
		$sql='INSERT INTO z_info_user (id_info, id_user, date_lecture) VALUES (:info, :user, NOW())';
		$req=$this->appli->dbPdo->prepare($sql);
		$req->execute(array(
		'info'=>$idInfo,
		'user'=>$_COOKIE["iduser"]
		));
	}
}

public function getInfosPushed()
{
	$sql='SELECT a.id_info, a.info, a.dateIn, a.titre,
	b.nom, b.prenom
	FROM z_info_push a
	LEFT JOIN users b ON a.id_user=b.id_user
	WHERE a.valid="1"
	ORDER BY dateIn DESC';
	$data=$this->appli->dbPdo->query($sql);
	return $data;
}
	
}
?>