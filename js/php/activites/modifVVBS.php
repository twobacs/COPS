<?php

if(isset($_GET['idbs'])){
	include('../connect.php');
	$bs=$_GET['idbs'];
	$vv=$_GET['vv'];
	$sql='DELETE FROM z_bs_vv WHERE id_bs=:bs AND immatriculation=:imm';
	$req=$pdo->prepare($sql);
	$req->execute(array(
	'bs' =>$bs,
	'imm' => $vv
	));
	$html='Véhicule supprimé avec succès';
	}	
	echo $html;

?>