<?php

include ('../connect.php');

if ((isset($_GET['idTxt']))&&(isset($_GET['idFiche'])))
	{
	$idTxt=$_GET['idTxt'];
	$idFiche=$_GET['idFiche'];
	$sql='DELETE FROM z_texte_libre WHERE id_textelibre="'.$idTxt.'"';
	$pdo->exec($sql);
	
	$sql='DELETE FROM z_fiche_textelibre WHERE id_fiche="'.$idFiche.'" AND id_textelibre="'.$idTxt.'"';
	$pdo->exec($sql);
	}

?>