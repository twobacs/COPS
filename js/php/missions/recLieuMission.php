<?php

if (isset($_GET['idLieu']))
	{
	include ('../connect.php');
	$sql='UPDATE z_lieu_mission SET nom_lieu="'.$_GET['newLieu'].'" WHERE id_lieu="'.$_GET['idLieu'].'"';
	$pdo->exec($sql);
	}

?>