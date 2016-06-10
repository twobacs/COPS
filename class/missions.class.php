<?php

class Mission
{
private $pdo;

public function __construct($dbPdo)
	{
	$this->pdo=$dbPdo;
	}
	
public function getMissionsInTimeNotVacNotCops($debut,$fin)
	{
	$i=0;
	$sql='SELECT id_fiche, texteInfo, type_mission FROM z_pat_missions WHERE 
	(date_fin>"'.$debut.'" AND date_debut<"'.$fin.'" AND type_mission!="cops") OR
	(date_debut>"'.$debut.'" AND date_debut<"'.$fin.'" AND type_mission!="cops") OR
	ORDER BY date_debut';
	$rep=$this->pdo->query($sql);
	while ($row=$rep->fetch())
		{
		$data[$i]['id']=$row['id_fiche'];
		$data[$i]['texte']=$row['texteInfo'];
		$i++;
		}
	$data['ttl']=$i;
	return $data;	
	}

public function getMissions()
	{
	$i=0;
	$sql='SELECT id_mission, nom_mission, code_mission FROM z_missions';
	$rep=$this->pdo->query($sql);
	while ($row=$rep->fetch())
		{
		$data[$i]['id']=$row['id_mission'];
		$data[$i]['nom']=$row['nom_mission'];
		$data[$i]['code']=$row['code_mission'];
		$i++;
		}
	$data['ttl']=$i;
	return $data;
	}
	
public function getLieux()
	{
	$i=0;
	$sql='SELECT id_lieu, nom_lieu FROM z_lieu_mission ORDER BY nom_lieu';
	$rep=$this->pdo->query($sql);
	while ($row=$rep->fetch())
		{
		$data[$i]['id']=$row['id_lieu'];
		$data[$i]['nom']=$row['nom_lieu'];
		$i++;
		}
	$data['ttl']=$i;
	return $data;
	}
}

?>