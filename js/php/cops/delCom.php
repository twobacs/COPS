<?php

include('../connect.php');

if ((isset($_GET['idCom']))&&(isset($_GET['idFiche'])))
	{
	$idCom=$_GET['idCom'];
	$idFiche=$_GET['idFiche'];
	$sql='DELETE FROM z_commerce WHERE id_commerce="'.$idCom.'"';
	$pdo->exec($sql);
	$sql='DELETE FROM z_fiche_commerce WHERE id_commerce="'.$idCom.'" AND id_fiche="'.$idFiche.'"';
	$pdo->exec($sql);
	}

?>