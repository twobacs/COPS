<?php

include ('connect.php');

if (isset($_GET['id']))
	{
	$id=$_GET['id'];
	$nom=$_GET['nom'];
	$prenom=$_GET['prenom'];
	$login=$_GET['login'];
	$mail=$_GET['mail'];
	$grade=$_GET['grade'];
	$service=$_GET['service'];
	
	$sql='UPDATE users SET 
	nom="'.ucfirst(htmlentities($nom,ENT_QUOTES, "UTF-8")).'", 
	prenom="'.ucfirst(htmlentities($prenom,ENT_QUOTES, "UTF-8")).'", 
	login="'.htmlentities($login,ENT_QUOTES, "UTF-8").'", 
	mail="'.htmlentities($mail,ENT_QUOTES, "UTF-8").'", 
	denomination_grade="'.$grade.'", 
	id_service="'.$service.'"
	WHERE id_user="'.$id.'"';
	
	$pdo->exec($sql);
		
	$html='Les changements demandés ont été effectués.<br />';
	$html.='<a href="?component=applications&action=showApps">Retour</a> au menu principal. <br />';
	$html.='<a href="?component=users&action=modifUser&type=OneUser">Modifier</a> un autre utilisateur. <br />';
	
	echo $html;
	
	}

?>