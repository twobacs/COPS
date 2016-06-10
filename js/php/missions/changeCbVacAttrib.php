<?php

if (isset($_GET['equipe']))
	{
	include ('../connect.php');
	$status=$_GET['status'];
	$idPat=$_GET['equipe'];
	$idFiche=$_GET['mission'];
	
	if ($status=='true')
		{
		$sql='INSERT INTO z_pat_missions (id_patrouille, type_mission, id_fiche) VALUES ("'.$idPat.'", "vacanciers", "'.$idFiche.'")';
		}
		
	else if ($status=='false')
		{
		$sql='DELETE FROM z_pat_missions WHERE id_patrouille="'.$idPat.'" AND id_fiche="'.$idFiche.'"';
		}
	$pdo->exec($sql);
	}
echo $sql;

?>