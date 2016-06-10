<?php

if(isset($_GET['idbs'])){
	$bs=$_GET['idbs'];
	$vv=$_GET['vv'];
	include ('../connect.php');
	$sql='INSERT INTO z_bs_vv (id_bs, immatriculation)VALUES ("'.$bs.'", "'.$vv.'")';
	$pdo->exec($sql);
}

?>