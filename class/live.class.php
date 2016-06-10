<?php

class Live
{
public $pdo;
public $test='testeur';

	public function __construct($dbPdo)
	{
		$this->pdo=$dbPdo;
	}

public function getTest()
{
	$date = strftime("%Y-%m-%d", mktime(0, 0, 0, date('m'), date('d')-1, date('Y'))); 
	return $date;
}

public function selectUnreadByIdUser($user)
{
	$date = strftime("%Y-%m-%d", mktime(0, 0, 0, date('m'), date('d')-1, date('Y'))); 
	
	//SELECTIONNER TOUTES LES INFOS ENCODEES COMME VUES PAR L'UTILISATEUR
	$sql='SELECT id_info FROM z_info_user WHERE id_user="'.$user.'"';
	$read=array();
	$i=0;
	$rep=$this->pdo->query($sql);
	while ($row=$rep->fetch())
	{
		$read[$i]=$row['id_info'];
		$i++;
	}
	
	//SELECTIONNER TOUTES LES INFOS DU JOUR ET DE LA VEILLE
	$i=0;
	$infos=array();
	$sql='SELECT id_info FROM z_info_push WHERE dateIn >= "'.$date.'"';
	$rep=$this->pdo->query($sql);
	while($row=$rep->fetch())
	{
		$infos[$i]['exists']=$row['id_info'];
		$i++;
	}	
	
	//CREER UN TABLEAU DANS LEQUEL SONT REPRIS LES ID DES INFOS NON LUES
	
	//SI id_info N'EST PAS DANS LE TABLEAU $read, REPRENDRE CET ID DANS UN NOUVEAU TABLEAU $unread
	$i=0;
	$j=0;
	$unread=array();
	while($i<sizeof($infos))
	{
		if (!in_array($infos[$i]['exists'],$read))
		{
			$unread[$j]['id']=$infos[$i]['exists'];
			$j++;
		}
	$i++;
	}
	return $unread;
}

public function getMessagesInfoByArray($toRead)
{	
	for($i=0;$i<sizeof($toRead);$i++)
	{
		$sql='SELECT id_info, titre, info, dateIn FROM z_info_push WHERE id_info="'.$toRead[$i]['id'].'"';
		$rep=$this->pdo->query($sql);
		while ($row=$rep->fetch())
		{
			$data[$i]['id']=$row['id_info'];
			$data[$i]['titre']=$row['titre'];
			$data[$i]['info']=$row['info'];
			$data[$i]['dateIn']=$row['dateIn'];
		}
	}
	if (isset($data))
	{
		return $data;
	}
	else return false;
}

public function setReadById($idInfo, $idUser)
{
	$sql='INSERT INTO z_info_user (id_info, id_user, date_lecture) VALUES (:info, :user, NOW())';
	$req=$this->pdo->prepare($sql);
	$req->execute(array(
	'info' => $idInfo,
	'user' => $idUser
	));
}
	
}

?>