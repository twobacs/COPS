<?php

include ('../connect.php');

if (isset($_GET['agent'])){
	$agent=$_GET['agent'];
	$quartier=$_GET['quartier'];
	$sql='INSERT INTO z_agent_quartier (id_quartier, id_user) VALUES ("'.$quartier.'","'.$agent.'")';
	$pdo->exec($sql);
	$html='L\'enregistrement a été effectué correctement. <br />';
	$html.='Créer une <a href="?component=quartier&action=ajouter&type=agent">autre association</a>.';
	echo $html;
	}
	

?>