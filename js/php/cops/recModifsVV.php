<?php

include ('../connect.php');

if (isset($_GET['idvv']) && isset($_GET['idfiche']))
	{
	$idvv=$_GET['idvv'];
	$idfiche=$_GET['idfiche'];
	$marque=$_GET['marque'];
	$modele=$_GET['modele'];
	$couleur=$_GET['couleur'];
	$immat=$_GET['immat'];
	$chassis=$_GET['chassis'];
	$implication=$_GET['implication'];
	$info=$_GET['info'];
	
	$sql='UPDATE z_vehicule SET marque="'.$marque.'", modele="'.$modele.'", immatriculation="'.$immat.'", chassis="'.$chassis.'", couleur="'.$couleur.'", descriptif="'.$info.'" WHERE id_vv="'.$idvv.'"';
	$pdo->exec($sql);
	
	$sql='UPDATE z_fiche_vehicule SET id_liaison="'.$implication.'" WHERE id_vehicule="'.$idvv.'" AND id_fiche="'.$idfiche.'"';
	$pdo->exec($sql);
	}

?>