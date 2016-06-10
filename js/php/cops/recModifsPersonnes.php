<?php

include ('../connect.php');

if ((isset($_GET['idPers']))&&(isset($_GET['idPers'])))
	{
	$nom=$_GET['nom'];
	$prenom=$_GET['prenom'];
	$naissance=$_GET['naissance'];
	$pays=$_GET['pays'];
	$CP=$_GET['CP'];
	$ville=$_GET['ville'];
	$rue=$_GET['rue'];
	$num=$_GET['num'];
	$desc=$_GET['desc'];
	$implication=$_GET['implication'];
	$idPers=$_GET['idPers'];
	$idFiche=$_GET['idFiche'];
	
	//UPDATE DE LA PERSONNE CONSIDEREE
	$sql='UPDATE z_personne SET nom="'.strtoupper($nom).'", prenom="'.$prenom.'", date_naissance="'.$naissance.'", pays="'.$pays.'", ville="'.$ville.'", CP="'.$CP.'", adresse="'.$rue.'", numero="'.$num.'", descriptif="'.$desc.'" WHERE id_personne="'.$idPers.'"';
	$pdo->exec($sql);
	
	$sql='UPDATE z_fiche_personne SET id_liaison="'.$implication.'" WHERE id_personne="'.$idPers.'" AND id_fiche="'.$idFiche.'"';
	$pdo->exec($sql);
	}

function htm($data)
	{
	$text = htmlentities($data, ENT_NOQUOTES, "UTF-8");
	$text = htmlspecialchars_decode($text);
	return $text;
	}
?>