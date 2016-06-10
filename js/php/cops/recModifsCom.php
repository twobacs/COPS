<?php

include ('../connect.php');

if ((isset($_GET['idCom'])) && (isset($_GET['idFiche'])))
	{
	$denomination=$_GET['denomination'];
	$description=$_GET['description'];
	$CP=$_GET['CP'];
	$ville=$_GET['ville'];
	$idRue=$_GET['idRue'];
	$num=$_GET['num'];
	$idImplication=$_GET['idImplication'];
	$idCom=$_GET['idCom'];
	$idFiche=$_GET['idFiche'];
	
	$sql='UPDATE z_commerce SET nom="'.$denomination.'", ville="'.$ville.'", CP="'.$CP.'", idRue="'.$idRue.'", numero="'.$num.'", descriptif="'.$description.'" WHERE id_commerce="'.$idCom.'"';
	$pdo->exec($sql);
	
	$sql='UPDATE z_fiche_commerce SET id_liaison="'.$idImplication.'" WHERE id_commerce="'.$idCom.'" AND id_fiche="'.$idFiche.'"';
	$pdo->exec($sql);
	}

?>