<?php

if (isset($_GET['type'])){
	include ('../connect.php');
	
	switch ($_GET['type']){
		case 'E':
			$table = 'z_pers_garde';
			$user = 'id_pers';
			break;
		case 'I':
			$table = 'users';
			$user = 'id_user';
			break;
		}
	$sql = 'SELECT nom, prenom, fixe, gsm, fax, mail, CP, ville, rue, numero FROM '.$table.' WHERE '.$user.'=:user';
	$req=$pdo->prepare($sql);
	$req->execute(array('user' => $_GET['id']));
	while($row=$req->fetch()){
		$html=$row['nom'].' '.$row['prenom'].'. Téléphone : '.$row['fixe'].'. GSM : '.$row['gsm'].'.';
		$html.='Tel : '.$row['fixe'].'/n';
		}
	echo $html;
	// echo 'cool';
	}
	// echo 'pas cool';

?>