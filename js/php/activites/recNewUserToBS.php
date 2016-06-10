<?php

if(isset($_GET['idbs'])){
	$bs=$_GET['idbs'];
	$user=$_GET['user'];
	include ('../connect.php');
	$sql='INSERT INTO z_bs_users (id_bs, id_user)VALUES ("'.$bs.'", "'.$user.'")';
	$pdo->exec($sql);
}

?>