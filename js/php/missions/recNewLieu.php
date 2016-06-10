<?php

if (isset($_GET['NewLieu']))
	{
	include ('../connect.php');
	$newLieu=$_GET['NewLieu'];
	$sql='INSERT INTO z_lieu_mission (nom_lieu) VALUES ("'.htmltosql($newLieu).'")';
	$pdo->exec($sql);
	}

?>