<?php

include ('../connect.php');

if (isset($_GET['agent'])){
	$agent=$_GET['agent'];
	$quartier=$_GET['quartier'];
	$sql='UPDATE z_agent_quartier SET id_quartier="'.$quartier.'" WHERE id_user="'.$agent.'"';
	$pdo->exec($sql);
	$html='La modification a été effectuée correctement. <br />';
	$html.='Autre <a href="?component=quartier&action=modifier&type=agent">modification</a>.';
	echo $html;
	}

?>