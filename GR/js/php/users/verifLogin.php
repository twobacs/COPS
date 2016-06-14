<?php

if(isset($_GET['login'])){
	include('../autoload.php');
	$sql='SELECT COUNT(*) FROM users WHERE login=:login';
	$req=$pdo->prepare($sql);
	$req->execute(array('login'=>$_GET['login']));
	while($row=$req->fetch()){
		$count=$row['COUNT(*)'];
	}
	
	echo $count;
}

?>