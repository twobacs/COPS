<?php

include ('../connect.php');

if ((isset($_GET['idPic']))&&(isset($_GET['idFiche'])))
	{
	$idPic=$_GET['idPic'];
	$idFiche=$_GET['idFiche'];
	$commentaire=$_GET['commentaire'];
	
	$sql='UPDATE z_photo SET commentaire="'.$commentaire.'" WHERE id_photo="'.$idPic.'"';
	$pdo->exec($sql);
	}

?>