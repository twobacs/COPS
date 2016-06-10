<?php

include ('connect.php');

if (isset($_GET['id']))
	{
	$id=$_GET['id'];
	$nom=strtoupper(htmlentities($_GET['nom'],ENT_QUOTES, "UTF-8"));
	$naam=strtoupper(htmlentities($_GET['naam'],ENT_QUOTES, "UTF-8"));
	
	$sql='UPDATE z_rues SET NomRue="'.$nom.'", StraatNaam="'.$naam.'" WHERE IdRue="'.$id.'"';
	$pdo->exec($sql);
	
	$html='Les changements demandés ont été effectués.<br />';
	$html.='<a href="?component=applications&action=showApps">Retour</a> au menu principal. <br />';
	$html.='<a href="?component=rues&action=modifRue&type=OneRue">Modifier</a> une autre rue. <br />';
	
	echo $html;
	}


?>