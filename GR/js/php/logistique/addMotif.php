<?php

if(isset($_GET['objet'])){
	include('../autoload.php');
	$dispo=((isset($_GET['motif']))&&($_GET['motif']=='attrib')) ? 'N' : $_GET['dispo'];
	$sql='INSERT INTO motifs_objet (objet, motif, cause, dispo) VALUES (:objet, :motif, :cause, :dispo)';
	$req=$pdo->prepare($sql);
	$req->execute(array(
	'objet'=>htmltosql($_GET['objet']),
	'motif'=>htmltosql($_GET['motif']),
	'cause'=>htmltosql($_GET['cause']),
	'dispo'=>$dispo
	));
}

?>