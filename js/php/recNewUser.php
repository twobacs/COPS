<?php

include ('connect.php');

if (isset($_GET['nom']) && isset($_GET['prenom']) && isset($_GET['login']) && isset($_GET['matricule']) && isset($_GET['sexe']) && isset($_GET['grade']) && isset($_GET['mail']) && isset($_GET['service'])){
$nom=$_GET['nom'];
$prenom=$_GET['prenom'];
$login=$_GET['login'];
$matricule=$_GET['matricule'];
$sexe=$_GET['sexe'];
$grade=$_GET['grade'];
$mail=$_GET['mail'];
$service=$_GET['service'];
$html='';
$error=0;

$sql='SELECT COUNT(*) FROM users WHERE login="'.$login.'"';
$rep=$pdo->query($sql);
while ($row=$rep->fetch())
	{
	$count=$row['COUNT(*)'];
	}
	
if ($count>0)
	{
	$html.="Ce login existe déjà, veuillez en choisir un autre. <br />";
	$error++;
	}
	
else 
	{
	$sql='SELECT COUNT(*) FROM users WHERE nom="'.htmlentities($nom,ENT_QUOTES, "UTF-8").'" AND prenom="'.htmlentities($prenom,ENT_QUOTES, "UTF-8").'" AND matricule="'.$matricule.'"';
	$rep=$pdo->query($sql);
		while ($row=$rep->fetch())
		{
		$count=$row['COUNT(*)'];
		}
	if ($count>0)
		{
		$html.='Cet utilisateur ('.$nom.' '.$prenom.' - '.$matricule.') existe déjà en base de données.';
		$error++;
		}
	}
	
if ($error==0)
	{
	$sql='INSERT INTO users (login, nom, prenom, matricule, denomination_sexe, denomination_grade, mdp_user, mail, id_service) VALUES 
	("'.htmlentities($login).'", "'.htmlentities($nom).'", "'.htmlentities($prenom,ENT_QUOTES, "UTF-8").'", "'.$matricule.'", "'.$sexe.'", "'.$grade.'", "'.md5('azerty').'", "'.$mail.'", "'.$service.'")';
	$pdo->exec($sql);
	$html.='<h4>Enregistrement effectué</h4>';
	$html.='<ul><li><a href="?component=applications&action=showApps">Accueil</a></li>';
	$html.='<li><a href="?component=users&action=addUser">Ajouter</a> un autre utilisateur</li>';
	$html.='</ul>';
	}
	
echo $html;

}

?>