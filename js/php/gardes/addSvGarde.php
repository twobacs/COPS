<?php

if(isset($_GET['idTypeGarde'])){
	include ('../connect.php');
	$selected=$_GET['idTypeGarde'];
	$step=$_GET['step'];
	$denom=$_GET['denom'];
	
	//1ere étape : récupération du nom du type de garde sélectionné
	$sql='SELECT denomination FROM z_type_garde WHERE id_typeGarde=:idTypeGarde';
	$req=$pdo->prepare($sql);
	$req->execute(array('idTypeGarde' => $selected));
	while ($row=$req->fetch()){
		$typeGarde=$row['denomination'];
		}
		
	if ($step=='0'){
	$html='<table>';
	$html.='<tr><th class="sstitre" colspan="2">Ajout d\'un service pour les gardes '.lcfirst($typeGarde).'s</th></tr>';
	$html.='<tr><th>Dénomination nouveau service :</th><td><input type="text" name="denomNewSvGarde" id="denomNewSvGarde"></td></tr>';
	$html.='<tr><td class="noborder" colspan="2"><input type="button" onclick="addSvGarde(\''.$selected.'\',\'1\');" value="Enregistrer nouveau service"></td></tr>';
	$html.='</table>';
	}
		
else if ($step=='1'){
	$sql='INSERT INTO z_sv_garde (denomination_svGarde, id_typeGarde) VALUES (:denom,:id)';
	$req=$pdo->prepare($sql);
	$req->execute(array('denom'=>ucfirst($denom),'id'=>$selected));
	$html='Enregistrement réussi. <a href="?component=garde&action=mainMenu">Rafraîchir page</a><br />';
	}
	echo $html;
	
}

?>