<?php

include ('connect.php');

if (isset($_GET['id']))
	{
	$id=$_GET['id'];
	$mdp=md5('azerty');
	$sql='UPDATE users SET mdp_user="'.$mdp.'", log_error="0" WHERE id_user="'.$id.'"';
	$pdo->exec($sql);
	
	$html='Les changements demandés ont été effectués.<br />';
	$html.='<a href="?component=applications&action=showApps">Retour</a> au menu principal. <br />';
	$html.='<a href="?component=users&action=modifUser&type=OneUser">Modifier</a> un autre utilisateur. <br />';	
	
	echo $html;;
	}

?>