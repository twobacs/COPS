<?php

if(isset($_GET['idLieu']))
	{
	include ('../connect.php');
	$sql='DELETE FROM z_lieu_mission WHERE id_lieu = "'.$_GET['idLieu'].'"';
	$pdo->exec($sql);
	}

?>