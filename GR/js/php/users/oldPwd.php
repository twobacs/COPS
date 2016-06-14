<?php

if(isset($_GET['oldPwd'])){
	include('../autoload.php');
	$oldPwd=$_GET['oldPwd'];
	$user=$_GET['user'];
	$sql='SELECT mdp_user FROM users WHERE id_user=:user';
	$req=$pdo->prepare($sql);
	$req->execute(array('user'=>$user));
	while($row=$req->fetch()){
		$mdpbdd=$row['mdp_user'];
	}
	if($mdpbdd==md5($oldPwd)){
		echo '1';
	}
	else echo '0';
}

?>