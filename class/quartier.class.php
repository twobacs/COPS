<?php

class Quartier
{
private $pdo;


public function __construct($dbPdo){
	$this->pdo=$dbPdo;
	}
	
public function getRues(){
	$sql='SELECT IdRue, NomRue, StraatNaam FROM z_rues ORDER BY NomRue ASC';
	$data=$this->pdo->query($sql);
	return $data;
	}


public function getInfosAntennes($id=''){
	$sql=
	'SELECT a.id_antenne, a.denomination, a.IdRue, a.numero, a.telephone, a.fax, b.NomRue 
	FROM z_antenne_quartier a
	LEFT JOIN z_rues b ON a.IdRue = b.IdRue';
	if ($id>''){
		$sql.=' WHERE id_antenne="'.$id.'"';
		}
	$sql.=' ORDER BY a.denomination DESC';
	$data=$this->pdo->query($sql);
	return $data;	
	}
	
public function getInfosQuartiers($id=''){
	$sql='
	SELECT a.id_quartier, a.denomination, a.id_antenne, a.gsm 
	FROM z_quartier a
	LEFT JOIN z_antenne_quartier b ON a.id_antenne = b.id_antenne';
	if ($id>''){
		$sql.=' WHERE id_quartier="'.$id.'"';
		}
	$sql.=' ORDER BY a.denomination ASC';
	$data=$this->pdo->query($sql);
	return $data;
	}
	
public function getInfosAgents($id='')
	{
	$sql='
	SELECT a.nom, a.prenom, a.id_user, c.denomination, c.gsm
	FROM users a 
	LEFT JOIN z_agent_quartier b ON a.id_user=b.id_user
	LEFT JOIN z_quartier c ON b.id_quartier=c.id_quartier
	WHERE a.id_service=(SELECT id_service FROM services WHERE denomination_service="Quartier")';
	
	if ($id>''){	
		$sql.=' AND a.id_user="'.$id.'" ';
		}
	
	$sql.='
	ORDER BY a.nom, a.prenom
	';
	$data=$this->pdo->query($sql);
	return $data;
	}
}
?>