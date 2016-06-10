<?php

include ('../connect.php');

if (isset($_GET['id']))
	{
	$id=$_GET['id'];
	$denom=$_GET['denom'];
	$adresse=$_GET['adresse'];
	$tel=$_GET['tel'];
	$fax=$_GET['fax'];
	$num=$_GET['num'];
	$resp=$_GET['resp'];
	
	$sql='UPDATE z_antenne_quartier SET denomination="'.ucfirst(htmltosql($denom)).'", IdRue="'.$adresse.'", numero="'.$num.'", telephone="'.$tel.'", fax="'.$fax.'", id_resp="'.$resp.'" WHERE id_antenne="'.$id.'"';
	$pdo->exec($sql);
	
	$html='Mise à jour effectuée.<br />';
	$html.='<a href="?component=quartier&action=modifier&type=antennes">Modifier</a> une autre antenne de quartier. <br />';
	echo $html;
	}
?>