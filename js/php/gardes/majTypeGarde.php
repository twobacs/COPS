<?php

if (isset($_GET['id'])){
	$id=$_GET['id'];
	$denom=$_GET['denom'];
	include ('../connect.php');
	$sql='UPDATE z_type_garde SET denomination=:denom WHERE id_typeGarde=:id';
	$req=$pdo->prepare($sql);
	$req->execute(array('denom'=>$denom, 'id'=>$id));
	$html='<td class="green">'.$denom.'</td><td class="green">Modification enregistrée</td>';
	echo $html;
}

?>