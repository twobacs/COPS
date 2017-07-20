<?php

if(isset($_GET['id'])){
	include('../autoload.php');
	$id=$_GET['id'];
	$sql='UPDATE users SET mdp_user=:mdp, log_error=:error WHERE id_user=:id';
	$req=$pdo->prepare($sql);
	$req->bindValue('mdp',md5('azerty'),PDO::PARAM_STR);
	$req->bindValue('error',0,PDO::PARAM_INT);
	$req->bindValue('id',0,$id::PARAM_INT);
	$req->execute();
}

?>