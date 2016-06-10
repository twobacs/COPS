<?php

if(isset($_GET['id'])){
	include ('../connect.php');
	$typePers=$_GET['typePers'];
	$idPers=$_GET['id'];
	$action=$_GET['action'];
	$sv=$_GET['sv'];

	switch ($typePers){
		case 'E':
			$table = 'z_pers_garde';
			$id='id_pers';
			break;
		case 'I':
			$table = 'users';
			$id='id_user';
			break;
	}
	
	if($action=='delete'){
	//contrôler si des gardes ne sont pas déjà attribuées à la personne concernée pour le service concerné
	$sql='SELECT id FROM z_garde_sv_pers WHERE '.$id.'="'.$idPers.'" AND id_svGarde="'.$sv.'"';
	$rep=$pdo->query($sql);
	while ($row=$rep->fetch()){
		$idGardeSvPers=$row['id'];
	}
	$sql='SELECT COUNT(*) FROM z_garde WHERE id_garde_sv_pers="'.$idGardeSvPers.'" AND dateHr_fin > NOW()';
	$rep=$pdo->query($sql);
	while ($row=$rep->fetch()){
		$count=$row['COUNT(*)'];
	}
	if ($count=='0'){
		$sql='DELETE FROM z_garde_sv_pers WHERE '.$id.'="'.$idPers.'" AND id_svGarde="'.$sv.'"';
		$pdo->exec($sql);
		$html='Enregistrement supprimé';
		}
	else $html='Des gardes sont encore enregistrées pour la personne que vous désirez supprimer, veuillez les supprimer préalablement.';
	}
	
	else if($action=='modif'){
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
		
		$sql='SELECT nom, prenom, fixe, gsm, fax, mail, CP, ville, rue, numero FROM '.$table.' WHERE '.$id.'="'.$idPers.'"';
		$req=$pdo->prepare($sql);
		$req->execute();
		while ($row=$req->fetch()){
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
		$nom=($nom=='')?' ':$nom;
		$prenom=($prenom=='')?' ':$prenom;
		$fixe=($fixe=='')?' ':$fixe;
		$gsm=($gsm=='')?' ':$gsm;
		$fax=($fax=='')?' ':$fax;
		$mail=($mail=='')?' ':$mail;		
		$CP=($CP=='')?' ':$CP;
		$ville=($ville=='')?' ':$ville;
		$rue=($rue=='')?' ':$rue;
		$numero=($numero=='')?' ':$numero;
		
		$html='<br /><table>';
		$html.='<tr><th colspan="4" class="sstitre">Modification d\'une personne</th></tr>';
		$html.='<tr><th width="25%">Nom :</th><td width="25%"><input type="text" name="nomPers" id="nomPers" value="'.$nom.'" autofocus></td><th width="25%">Prénom :</th><td width="25%"><input type="text" name="prenomPers" id="prenomPers" value="'.$prenom.'"></td></tr>';
		$html.='<tr><th>Tel fixe :</th><td><input type="tel" name="telPers" id="telPers" value="'.$fixe.'"</td><th>GSM :</th><td><input type="tel" name="gsmPers" id="gsmPers" value="'.$gsm.'"></td></tr>';
		$html.='<tr><th>Fax :</th><td><input type="tel" name="faxPers" id="faxPers" value="'.$fax.'"></td><th>Mail :</th><td><input type="email" name="mailPers" id="mailPers" value="'.$mail.'"></td></tr>';
		$html.='<tr><th>Rue :</th><td><input type="text" name="ruePers" id="ruePers" value="'.$rue.'"></td><th>Numéro :</th><td><input type="text" name="numPers" id="numPers" value="'.$numero.'"></td></tr>';
		$html.='<tr><th>Code postal :</th><td><input type="text" name="CPPers" id="CPPers" value="'.$CP.'"></td><th>Ville :</th><td><input type="text" name="villePers" id="villePers" value="'.$ville.'"></td></tr>';
		$html.='<tr><td colspan="4" class="noborder"><input type="button" value="Enregistrer" onclick="modifPersGarde(\''.$typePers.'\',\''.$idPers.'\',\'Record\',\''.$sv.'\');"></td></tr>';
		$html.='</table>';
		}
		
		else if($action=='Record'){
			$html = 'pouet';
			$sql='UPDATE '.$table.' SET nom=:nom, prenom=:prenom, fixe=:fixe, gsm=:gsm, fax=:fax, mail=:mail, rue=:rue, numero=:numero, CP=:CP, ville=:ville WHERE '.$id.'=:idPers';
			$req=$pdo->prepare($sql);
			$req->execute(array(
			'nom' => $_GET['nom'],
			'prenom' => $_GET['prenom'],
			'fixe' => $_GET['fixe'],
			'gsm' => $_GET['gsm'],
			'fax' => $_GET['fax'],
			'mail' => $_GET['mail'],
			'rue' => $_GET['rue'],
			'numero' => $_GET['numero'],
			'CP' => $_GET['CP'],
			'ville' => $_GET['ville'],
			'idPers' => $idPers
			));
			$html='Modifications enregistrées avec succès.';
		}
}
echo $html;


?>
