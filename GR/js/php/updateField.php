<?php

if(isset($_GET['objet'])){
	include('autoload.php');
	$objet=$_GET['objet'];
	$field=$_GET['field'];
	$id=$_GET['id'];
	$value=$_GET['value'];
	switch($objet){
		case 'ETT':
			$table='z_ETT';
			$fieldId='id_ETT';
			break;
		case 'denCategArticle';
			$table='categories_articles';
			$fieldId='id_categorie';
			$field='denomination';
			break;
		case 'denUMesure';	
			$table='unite_mesure';
			$fieldId='id_uMesure';
			$field='denomination';
			break;
		case 'denFournisseur':
			$table='fournisseurs';
			$fieldId='id_fournisseur';
			$field='nom';
			break;
	}
	$sql='UPDATE '.$table.' SET '.$field.'=:value WHERE '.$fieldId.'=:id';
	$req=$pdo->prepare($sql);
	$req->execute(array('value'=>htmltosql($value),'id'=>$id));
	echo $value;
}

?>