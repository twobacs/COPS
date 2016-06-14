<?php

if(isset($_GET['idFourn'])){
	include ('../autoload.php');
	$id=$_GET['idFourn'];
	$fourn=NEW Fournisseurs($pdo);
	$data = $fourn->getFournisseurById($id);
	$html='<table class="table">';
	while($row=$data->fetch()){
		$html.='<tr><td><input type="text" name="newNomFourn'.$id.'" id="newNomFourn'.$id.'" value="'.$row['nom'].'" autofocus   placeHolder="Nom fournisseur"></td><td colspan="2"><input type="text" name="newNumEntreprise'.$id.'" id="newNumEntreprise'.$id.'" value="'.$row['num_entreprise'].'"   placeHolder="Num&eacute;ro d\'entreprise"></td></tr>';
		$html.='<tr><td><input type="text" name="newDescFourn'.$id.'" id="newDescFourn'.$id.'" value="'.$row['description'].'" placeHolder="Description"></td><td><input type="text" name="newRueFourn'.$id.'" id="newRueFourn'.$id.'" value="'.$row['adresse'].'" placeHolder="Rue"></td><td><input type="text" name=newNumFourn'.$id.'" id="newNumFourn'.$id.'" value="'.$row['numero'].'" placeHolder="Num&eacute;ro"></td></tr>';
		$html.='<tr><td><input type="text" name="newVilleFourn'.$id.'" id="newVilleFourn'.$id.'" value="'.$row['ville'].'" placeHolder="Ville"></td><td><input type="text" name="newCPFourn'.$id.'" id="newCPFourn'.$id.'" value="'.$row['CP'].'" placeHolder="Code postal"></td><td><input type="text" name="newPaysFourn'.$id.'" id="newPaysFourn'.$id.'" value="'.$row['pays'].'" placeHolder="Pays"></td></tr>';
		$html.='<tr><td><input type="mail" name="newMailFourn'.$id.'" id="newMailFourn'.$id.'" value="'.$row['mail'].'" placeHolder="Mail"></td><td><input type="text" name="newTelFourn'.$id.'" id="newTelFourn'.$id.'" value="'.$row['tel'].'" placeHolder="T&eacute;l&eacute;phone"></td><td><input type="text" name="newFaxFourn'.$id.'" id="newFaxFourn'.$id.'" value="'.$row['fax'].'" placeHolder="Fax"></td></tr>';
		$html.='<tr><td colspan="4"><input type="button" value="Enregistrer modifications" onclick="updateFourn(\''.$id.'\');"></td></tr>';
	}
	$html.='</table>';
	echo $html;
}

?>