<?php

include ('../connect.php');

if (isset($_GET['idvv']) && isset($_GET['idfiche']))
	{
	$idfiche=$_GET['idfiche'];
	$idvv=$_GET['idvv'];
	$sql='DELETE FROM z_vehicule WHERE id_vv="'.$idvv.'"';
	$pdo->exec($sql);
	
	$sql='DELETE FROM z_fiche_vehicule WHERE id_fiche="'.$idfiche.'" AND id_vehicule="'.$idvv.'"';
	$pdo->exec($sql);
	}

?>