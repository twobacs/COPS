<?php

if(isset($_GET['id'])){
	include ('../autoload.php');
	$sql='SELECT COUNT(*) FROM articles WHERE id_mesure=:id';
	$req=$pdo->prepare($sql);
	$req->execute(array('id'=>$_GET['id']));
	while ($row=$req->fetch()){
		$count=$row['COUNT(*)'];
	}
	if($count==0){
		$sql='DELETE FROM categories_articles WHERE id_mesure=:id';
		$req=$pdo->prepare($sql);
		$req->execute(array('id'=>$_GET['id']));
	}
	echo $count;
}


?>