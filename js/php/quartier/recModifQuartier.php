<?php

include ('../connect.php');

if (isset($_GET['id'])){
	$id=$_GET['id'];
	$denom=$_GET['denom'];
	$gsm=$_GET['gsm'];
	
	$sql='UPDATE z_quartier SET denomination="'.ucfirst(htmltosql($denom)).'", gsm="'.htmltosql($gsm).'" WHERE id_quartier="'.$id.'"';
	$pdo->exec($sql);
	
	$html='Mise à jour effectuée.<br />';
	$html.='<a href="?component=quartier&action=modifier&type=quartier">Modifier</a> un autre quartier. <br />';
	echo $html;
	}
?>