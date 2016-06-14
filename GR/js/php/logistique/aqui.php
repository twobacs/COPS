<?php

if(isset($_GET['type'])){
	include('../autoload.php');
	$type=$_GET['type'];
	$user=$_GET['user'];
	$sql='SELECT nom, prenom FROM users WHERE id_user="'.$user.'"';
	$rep=$pdo->query($sql);
	while($row=$rep->fetch()){
		$nom=$row['nom'];
		$prenom=$row['prenom'];
		}
	echo 'Matériel attribué à : '.strtoupper($nom).' '.$prenom;
}

?>