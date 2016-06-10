<?php

if(isset($_GET['idSv'])){
	include ('../connect.php');
	$idSv=$_GET['idSv'];
	$step=$_GET['step'];	
	$typeOfPers=$_GET['typeOfPersSelected'];
	$idOfPersSelected=(isset($_GET['idOfPersSelected']))?$_GET['idOfPersSelected']:'0';
	
	//Récupération du nom de la garde concernée
	$sql='SELECT denomination_svGarde FROM z_sv_garde WHERE id_svGarde=:idSv';
	$req=$pdo->prepare($sql);
	$req->execute(array('idSv'=>$idSv));
	while($row=$req->fetch()){
		$denomination=$row['denomination_svGarde'];
		}
	//****************************************************
	
	if($step=='1'){
		$html='<table>';
		$html.='<tr><th class="sstitre" colspan="2">Ajout d\'un contact pour la garde '.lcfirst($denomination).'</th></tr>';
		$html.='<tr><th width="50%">Type de contact :</th><td><select name="selectTypeContact" id="selectTypeContact" onChange="addPersToSvGarde(\''.$idSv.'\',\'2\',\'\');"><option></option><option value="E">Externe</option><option value="I">Interne</option></select></td></tr>';
		$html.='<div id="tableAddContactSv"></div>';
		$html.='</table>';
		}
	else if($step=='2'){
		// echo $idSv.'-'.$step;
		if ($typeOfPers=='E'){
			$html='<table><tr><th width="50%">Choisir dans la liste :</th><td><select name="selectedPersExt" id="SelectedPersExt" onchange="addPersToSvGarde(\''.$idSv.'\',\'2\',\'\');"><option value="-1"></option>';
			$sql='SELECT id_pers, nom, prenom FROM z_pers_garde ORDER BY nom, prenom';
			$req=$pdo->prepare($sql);
			$req->execute();
			while ($row=$req->fetch()){
				$html.='<option value="'.$row['id_pers'].'"';
				$html.=($row['id_pers']==$idOfPersSelected)?' selected':'';
				$html.='>'.$row['nom'].' '.$row['prenom'].'</option>';
			}
			$html.='</select></td></tr>';
			$html.='<tr><th colspan="2"> OU </th></table>';
			$html.='<table>';
			//Si une personne externe est préselectionnée -> recherche des données en bdd
				$idPers=' ';
				$nom=' ';
				$prenom=' ';
				$fixe=' ';
				$gsm=' ';
				$fax=' ';
				$mail=' ';
				$CP=' ';
				$ville=' ';
				$rue=' ';
				$numero=' ';
			if ($idOfPersSelected!="-1"){
				$sql='SELECT id_pers, nom, prenom, fixe, gsm, fax, mail, CP, ville, rue, numero FROM z_pers_garde WHERE id_pers=:id';
				$req=$pdo->prepare($sql);
				$req->execute(array('id'=>$idOfPersSelected));
				while ($row=$req->fetch()){
					$idPers=$row['id_pers'];
					$nom=$row['nom'];
					$prenom=$row['prenom'];
					$fixe=$row['fixe'];
					$gsm=$row['gsm'];
					$fax=$row['fax'];
					$mail=$row['mail'];
					$CP=$row['CP'];
					$ville=$row['ville'];
					$rue=$row['rue'];
					$numero=$row['numero'];
					}
					$html.='<tr><th class="sstitre" colspan="4">Ajout d\'un contact extérieur</th></tr>';
					$html.='<tr><th width="25%">Nom :</th><td width="25%"><input type="text" name="nomPersExt" id="nomPersExt" autofocus required value="'.$nom.'"></td><th width="25%">Prénom :</th><td width="25%"><input type="text" name="prenomPersExt" id="prenomPersExt" value="'.$prenom.'"></td></tr>';
					$html.='<tr><th>Tel fixe :</th><td><input type="tel" name="telPersExt" id="telPersExt" value="'.$fixe.'"></td><th>GSM :</th><td><input type="tel" name="gsmPersExt" id="gsmPersExt" value="'.$gsm.'"></td></tr>';
					$html.='<tr><th>Fax :</th><td><input type="tel" name="faxPersExt" id="faxPersExt" value="'.$fax.'"></td><th>Mail :</th><td><input type="email" name="emailPersExt" id="emailPersExt" value="'.$mail.'"></td></tr>';
					$html.='<tr><th>Rue :</th><td><input type="text" name="ruePersExt" id="ruePersExt" value="'.$rue.'"></td><th>Numéro :</th><td><input type="text" name="numPersExt" id="numPersExt" value="'.$numero.'"></td></tr>';
					$html.='<tr><th>Code postal :</th><td><input type="text" name="CPPersExt" id="CPPersExt" value="'.$CP.'"></td><th>Ville :</th><td><input type="text" name="villePersExt" id="villePersExt" value="'.$ville.'"></td></tr>';
					$html.='<tr><td colspan="4" class="noborder"><input type="button" value="Enregistrer" onclick="recNewPersGarde(\''.$idSv.'\',\'E\');"><input type="hidden" name="idPersE" id="idPersE" value="'.$idOfPersSelected.'"></td></tr>';
					$html.='</table>';
			}
			else{
			$html.='<tr><th class="sstitre" colspan="4">Ajout d\'un contact extérieur</th></tr>';
			$html.='<tr><th width="25%">Nom :</th><td width="25%"><input type="text" name="nomPersExt" id="nomPersExt" autofocus required></td><th width="25%">Prénom :</th><td width="25%"><input type="text" name="prenomPersExt" id="prenomPersExt"></td></tr>';
			$html.='<tr><th>Tel fixe :</th><td><input type="tel" name="telPersExt" id="telPersExt"></td><th>GSM :</th><td><input type="tel" name="gsmPersExt" id="gsmPersExt"></td></tr>';
			$html.='<tr><th>Fax :</th><td><input type="tel" name="faxPersExt" id="faxPersExt"></td><th>Mail :</th><td><input type="email" name="emailPersExt" id="emailPersExt"></td></tr>';
			$html.='<tr><th>Rue :</th><td><input type="text" name="ruePersExt" id="ruePersExt"></td><th>Numéro :</th><td><input type="text" name="numPersExt" id="numPersExt"></td></tr>';
			$html.='<tr><th>Code postal :</th><td><input type="text" name="CPPersExt" id="CPPersExt"></td><th>Ville :</th><td><input type="text" name="villePersExt" id="villePersExt"></td></tr>';
			$html.='<tr><td colspan="4" class="noborder"><input type="button" value="Enregistrer" onclick="recNewPersGarde(\''.$idSv.'\',\'E\');"><input type="hidden" name="idPersE" id="idPersE" value="'.$idOfPersSelected.'"></td></tr>';
			$html.='</table>';
			}
			}
		else if ($typeOfPers=='I'){
			$sql='SELECT id_user, nom, prenom FROM users WHERE actif="O" ORDER BY nom';
			$req=$pdo->query($sql);
			$html='<table>';
			$html.='<tr><th class="sstitre" colspan="4">Ajout d\'un contact interne</th></tr>';
			$html.='<tr><th colspan="2">Choisissez un nom :</th><td colspan="2"><select name="selectPersInt" id="selectPersInt" onchange="showMoreFromUserInt();"><option value="-1"></option>';
			while ($row=$req->fetch()){
				$html.='<option value="'.$row['id_user'].'">'.$row['nom'].' '.$row['prenom'].'</option>';
				}
			$html.='</select></td></tr>';
			$html.='</table>';
			$html.='<div id="moreInfoUserInt"></div>';
			$html.='<table>';
			$html.='<tr><td colspan="4" class="noborder"><input type="button" value="Enregistrer" onclick="recNewPersGarde(\''.$idSv.'\',\'I\');"></td></tr>';
			$html.='</table>';
			// $html.='</table>';
			}
		
		}
echo $html;	
}

?>