<?php

if (isset($_GET['idMission']))
	{
	include ('../connect.php');
	
	$idMission=strtoupper($_GET['idMission']);
	$idLieu=$_GET['idLieu'];
	$equipe=$_GET['idEquipe'];
	
	$idFiche=uniqid();
	
	$sql='SELECT nom_lieu FROM z_lieu_mission WHERE id_lieu="'.$idLieu.'"';
	$rep=$pdo->query($sql);
	while ($row=$rep->fetch())
		{
		$nomLieu=$row['nom_lieu'];
		}
	
	$sql='SELECT code_mission FROM z_missions WHERE id_mission="'.$idMission.'"';
	$rep=$pdo->query($sql);
	while ($row=$rep->fetch())
		{
		$codeMission=$row['code_mission'];
		}
		
	$sql='INSERT INTO z_pat_missions (id_patrouille, type_mission, id_fiche, lieu) VALUES ("'.$equipe.'", "'.$codeMission.'", "'.$idFiche.'", "'.$nomLieu.'")';
	$pdo->exec($sql);
	}

?>