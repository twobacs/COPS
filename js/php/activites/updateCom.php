<?php

if (isset($_GET['idPat'])){
	include ('../connect.php');
	$idPat=$_GET['idPat'];
	$idBs=$_GET['idBS'];
	$commentaire=htmltosql($_GET['com']);
	$sql='UPDATE z_bs SET commentaire=:commentaire WHERE id_bs=:idBS AND id_patrouille=:idPat';
	$req=$pdo->prepare($sql);
	$req->execute(array(
	'commentaire' => $commentaire,
	'idBS' => $idBs,
	'idPat' => $idPat
	));
	echo $sql;
}

?>