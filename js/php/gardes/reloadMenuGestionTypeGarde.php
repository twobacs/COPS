<?php

include('../connect.php');
	$sql='SELECT id_typeGarde, denomination FROM z_type_garde ORDER BY denomination';
	$req=$pdo->query($sql);
	$html='Type de garde à gérer : <select name="sv_garde" id="sv_garde" onchange="garde_to_step1();">';
	$html.='<option value="0"></option>';
	while($row=$req->fetch()){
		$html.='<option value="'.$row['id_typeGarde'].'">'.$row['denomination'].'</option>';
		}
	// $html.='<option value="0">---------------</option>';	
	// $html.='<option value="-1">AJOUTER UN TYPE</option>';	
	// $html.='<option value="-2">MODIFIER / SUPPRIMER UN TYPE</option>';	
	$html.='</select>';	
	echo $html;

?>