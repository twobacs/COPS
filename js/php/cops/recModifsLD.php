<?php

include ('../connect.php');

if ((isset($_GET['idFiche']))&&(isset($_GET['idLD'])))
	{
	$sql='UPDATE z_lieudit SET description="'.$_GET['denomination'].'" WHERE id_lieudit="'.$_GET['idLD'].'"';
	$pdo->exec($sql);
	echo $sql;
	$sql='UPDATE z_fiche_lieudit SET id_liaison="'.$_GET['implication'].'" WHERE id_fiche="'.$_GET['idFiche'].'" AND id_lieudit="'.$_GET['idLD'].'"';
	$pdo->exec($sql);
	}

?>