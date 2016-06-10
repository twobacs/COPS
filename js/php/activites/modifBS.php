<?php

if(isset($_GET['idbs'])){
	include('../connect.php');
	$bs=$_GET['idbs'];
	$user=$_GET['user'];
	$sql='SELECT COUNT(*) FROM z_bs_users WHERE id_bs=:bs';
	$req=$pdo->prepare($sql);
	$req->execute(array(
	'bs' => $bs
	));
	while($row=$req->fetch()){
		$count=$row['COUNT(*)'];
	}
	if ($count==1){
		$html="Il s'agit du dernier utilisateur pour ce BS ! Vous ne pouvez le supprimer sans en ajouter un autre préalablement !";
	}
	else{
		$sql='DELETE FROM z_bs_users WHERE id_bs=:bs AND id_user=:user';
		$req=$pdo->prepare($sql);
		$req->execute(array(
		'bs' =>$bs,
		'user' => $user
		));
		$html='Utilisateur supprimé avec succès';
	}
	
	echo $html;
}

?>