<?php

if (isset($_GET['select'])){
	include ('../connect.php');
	switch($_GET['select']){
		case -2:
			$html=suppType($pdo);
			break;
		case -1:
			$html=ajoutType();
			break;
		case 0:
			$html='';
			break;
		default:
			$html=selectServiceFromType($_GET['select'],$pdo);
	}
}
else $html='Une erreur s\'est produite';
echo $html;

function suppType($pdo){
	$sql='SELECT id_typeGarde, denomination FROM z_type_garde';
	$rep=$pdo->query($sql);
	$html='<h4>Modifier ou supprimer un type</h4><table id="tableauTypesGarde">';
	$html.='<tr><th width="50%">Dénomination</th><th>Action</th></tr>';
	while($row=$rep->fetch()){
		$html.='<tr id="trGarde_'.$row['id_typeGarde'].'"><td id="tdGarde_'.$row['id_typeGarde'].'">'.$row['denomination'].'</td><td><input type="button" name="bModifTypeGarde" onclick="modifTypeGarde(\''.$row['id_typeGarde'].'\',\'1\');" value="Modifier"> ou <input type="button" name="bSuppTypeGarde" onclick="modifTypeGarde(\''.$row['id_typeGarde'].'\',\'-1\');" value="Supprimer"></td></tr>';
	}
	$html.='</table>';
	return $html;
}

function ajoutType(){
	$html='<h4>Ajout d\'un type de garde</h4>';
	$html.='<table id="tabNewTypeGarde">';
	$html.='<tr><th>Dénomination :</th><td><input type="text" id="newTypeGarde" name="newTypeGarde" required autofocus></td></tr>';
	$html.='<tr><td colspan="2" class="noborder"><input type="button" onclick="addNewType();" value="Ajouter"></td></tr>';
	$html.='</table>';
	return $html;
}

function selectServiceFromType($selected,$pdo){
	//1ere étape : récupération du nom du type de garde sélectionné
	$html='<table>';
	$sql='SELECT denomination FROM z_type_garde WHERE id_typeGarde=:idTypeGarde';
	$req=$pdo->prepare($sql);
	$req->execute(array('idTypeGarde' => $selected));
	while ($row=$req->fetch()){
		$typeGarde=$row['denomination'];
		}
	$html.='<tr><th class="titre" colspan="3">Gestion des gardes '.lcfirst($typeGarde).'s</th></tr>';
	
	//2eme étape : recherche des services repris selon le type choisi
	$sql='SELECT id_svGarde, denomination_svGarde FROM z_sv_garde WHERE id_typeGarde=:idTypeGarde';
	$req=$pdo->prepare($sql);
	$req->execute(array('idTypeGarde' => $selected));
	while ($row=$req->fetch()){
		$html.='<tr id="trSV_'.$row['id_svGarde'].'" width="25%"><th>'.$row['denomination_svGarde'].'</th><td id="tdSV_'.$row['id_svGarde'].'" width="50%"><input type="button" value="Gérer les membres" onclick="gestMembresSvGarde(\''.$row['id_svGarde'].'\');"></td><td class="orange" width="25%"><input type="button" value="Supprimer ce service de garde" onclick="modifSvGarde(\''.$row['id_svGarde'].'\',\'delete\');"></td></tr>';
		//<input type="button" value="Modifier" onclick="modifSvGarde(\''.$row['id_svGarde'].'\',\'modif\');">
		}
	$html.='<tr><td class="noborder" colspan="3"><input type="button" value="Ajouter un service" onclick="addSvGarde(\''.$selected.'\',\'0\');"></td></tr>';
	$html.='</table>';
	return $html;
}
?>