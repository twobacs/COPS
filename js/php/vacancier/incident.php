<?php

include ('../connect.php');

if (isset($_GET['vac']))
	{
	$vac=$_GET['vac'];	
	$user=$_GET['user'];	
	// $longitude=$_GET['longi'];	
	// $latitude=$_GET['lat'];	
	$commentaire=$_GET['commentaire'];
	$idPat=$_GET['pat'];
	//echo 'idVac : '.$vac.'.<br />idUser : '.$user.'.<br />Latitude : '.$latitude.'.<br />Longitude : '.$longitude.'.';
	
	$req=$pdo->prepare('INSERT INTO z_vac_hab_controle (id_vac, id_user, date_heure, resultat, commentaire) VALUES
	(:idvac, :iduser, :date, :resultat, :commentaire)');
	$req->execute(array(
	'idvac' => $vac,
	'iduser' => $user,
	'date' => date("Y-m-d H:i:s"),
	// 'latitude' => $latitude,
	// 'longitude' => $longitude,
	'resultat' => 'Incident',
	'commentaire' => htmlentities($commentaire, ENT_QUOTES, "UTF-8")
	));
	if ($idPat!='')
		{
		$sql='UPDATE z_pat_missions SET date_heure_in=NOW(), date_heure_out=NOW() WHERE id_patrouille="'.$idPat.'" AND id_fiche="'.$vac.'"';
		$pdo->exec($sql);
		}
	echo '<br /><font color="red">Enregistrement OK : Incident</font><br />';
	}
?>
