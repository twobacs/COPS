<?php

if (isset($_GET['idbs']))
	{
	include ('../connect.php');
	$sql='UPDATE z_bs_vv SET plein="'.$_GET['plein'].'" WHERE id_bs="'.$_GET['idbs'].'" AND immatriculation="'.$_GET['vv'].'"';
	$pdo->exec($sql);
	}

?>