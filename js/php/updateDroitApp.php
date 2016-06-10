<?php

include ('connect.php');

if (isset($_GET['idUser']))
	{
	$idUser=$_GET['idUser'];
	$idApp=$_GET['idApp'];
	$idNiv=$_GET['idNiv'];
	$sql='UPDATE z_user_app SET id_nivAcces="'.$idNiv.'" WHERE id_app="'.$idApp.'" AND id_user="'.$idUser.'"';
	$pdo->exec($sql);
	}
else return false;

?>