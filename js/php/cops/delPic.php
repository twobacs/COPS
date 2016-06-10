<?php

include ('../connect.php');

if ((isset($_GET['idPic'])) && (isset($_GET['idFiche'])))
	{
	$idPic=$_GET['idPic'];
	$idFiche=$_GET['idFiche'];
	
	$sql='DELETE FROM z_photo WHERE id_photo="'.$idPic.'"';
	$pdo->exec($sql);
	
	$sql='DELETE FROM z_fiche_photo WHERE id_fiche="'.$idFiche.'" AND id_photo="'.$idPic.'"';
	$pdo->exec($sql);
	}

?>