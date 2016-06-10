<?php

include ('../connect.php');

if (isset($_GET['antenne'])){
	$antenne=$_GET['antenne'];
	$quartier=$_GET['quartier'];
	$sql='UPDATE z_quartier SET id_antenne="'.$antenne.'" WHERE id_quartier="'.$quartier.'"';
	$pdo->exec($sql);
	$html='Les modifications ont été correctement enregistrées. <br />';
	$html.='Créer une <a href="?component=quartier&action=assocqa">autre association</a>.';
	echo $html;
	}

?>