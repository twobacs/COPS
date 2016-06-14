<?php

if(isset($_GET['type'])){
	include('../autoload.php');
	$html='';
	$sql='SELECT id_user, nom, prenom FROM users WHERE '.$_GET['type'].' LIKE :entry AND actif="O" ORDER BY nom, prenom';
	$req=$pdo->prepare($sql);
	$req->execute(array('entry'=>$_GET['entry'].'%'));
	// print_r($req);
	while($row=$req->fetch()){
		$html.='<tr style="cursor : pointer;" onclick="location.href=\'?component=user&action=getUserById&id='.$row['id_user'].'\'"><td>'.$row['nom'].'</td><td>'.$row['prenom'].'</td></tr>';;
	}
	echo $html;	
}
?>