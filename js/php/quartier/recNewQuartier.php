<?php

include ('../connect.php');

if (isset($_GET['denom'])){
	$denom=$_GET['denom'];
	$gsm=$_GET['gsm'];
	
	$sql='SELECT COUNT(*) FROM z_quartier WHERE denomination="'.ucfirst(htmltosql($denom)).'"';
	$rep=$pdo->query($sql);
	
	while ($row=$rep->fetch()){
		$count=$row['COUNT(*)'];
		}
		
	if ($count==0){
		$sql='INSERT INTO z_quartier (denomination, gsm) VALUES ("'.ucfirst(htmltosql($denom)).'","'.htmltosql($gsm).'")';
		$pdo->exec($sql);
		$html='<h4>Enregistrement effectué</h4>';
		}
		
	else{
		$html='<h3>Il existe déjà un quartier avec cette dénomination, veuillez vérifier.</h3>';
		}
	$html.='<a href=?component=quartier&action=ajouter&type=quartier>Ajouter</a> un autre quartier.<br />';
	echo $html;
	}

?>