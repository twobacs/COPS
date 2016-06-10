<?php

include ('../connect.php');

if ((isset($_GET['idTxt']))&&(isset($_GET['idFiche'])))
	{
	$idTxt=$_GET['idTxt'];
	$idFiche=$_GET['idFiche'];
	$titre=$_GET['titre'];
	$texte=$_GET['texte'];
	
	$sql='UPDATE z_texte_libre SET texte="'.$texte.'", titre="'.$titre.'" WHERE id_textelibre="'.$idTxt.'"';
	$pdo->exec($sql);
	}

?>