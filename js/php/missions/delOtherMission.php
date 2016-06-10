<?php

if (isset($_GET['idMission']))
	{
	include ('../connect.php');
	$sql='DELETE FROM z_pat_missions WHERE id_fiche="'.$_GET['idMission'].'"';
	$pdo->exec($sql);
	}

?>