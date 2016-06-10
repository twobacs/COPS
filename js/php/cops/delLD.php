<?php

include ('../connect.php');

if ((isset($_GET['idFiche']))&&(isset($_GET['idLD'])))
	{
	$sql='DELETE FROM z_lieudit WHERE id_lieudit="'.$_GET['idLD'].'"';
	$pdo->exec($sql);
	$sql='DELETE FROM z_fiche_lieudit WHERE id_lieudit="'.$_GET['idLD'].'" AND id_fiche="'.$_GET['idFiche'].'"';
	$pdo->exec($sql);
	}

?>