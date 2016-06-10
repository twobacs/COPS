<?php

include ('../connect.php');

if (isset($_GET['denom']))
	{
	$denom=$_GET['denom'];
	$adresse=$_GET['adresse'];
	$tel=$_GET['tel'];
	$fax=$_GET['fax'];
	$num=$_GET['num'];
	$resp=$_GET['resp'];
	
	$sql='SELECT COUNT(*) FROM z_antenne_quartier WHERE denomination="'.$denom.'"';
	$rep=$pdo->query($sql);
	while ($row=$rep->fetch())
		{
		$count=$row['COUNT(*)'];
		}
	
	if ($count==0)
		{
		$sql='INSERT INTO z_antenne_quartier (denomination, IdRue, numero, telephone, fax, id_resp) VALUES ("'.ucfirst(htmltosql($denom)).'","'.$adresse.'","'.ucfirst(htmltosql($num)).'","'.ucfirst(htmltosql($tel)).'","'.ucfirst(htmltosql($fax)).'", "'.$resp.'")';
		$pdo->exec($sql);
		$html='<h4>Données enregistrées</h4> ('.ucfirst(htmltosql($denom)).')';
		}
		
	else
		{
		$html='Une antenne de quartier avec cette dénomination existe déjà en base de données, veuillez vérifier.<br />Aucun enregistrement n\'a été effectué.';
		}
	
	$html.='<a href="?component=quartier&action=ajouter&type=antennes">Ajouter</a> une autre antenne de quartier. <br />';
	
	echo $html;
	}

?>