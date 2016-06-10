<?php

if (isset($_GET['idPersInt'])){
	include ('../connect.php');
	$id=$_GET['idPersInt'];
	$sql='SELECT id_user, nom, prenom, mail, fixe, gsm, fax, rue, numero, CP, ville FROM users WHERE id_user=:idUser';
	$req=$pdo->prepare($sql);
	$req->execute(array('idUser'=>$id));
	$html='<table>';
	while ($row=$req->fetch()){
		$html.='<tr><th width="25%">Nom :</th><td width="25%"><input type="text" name="nomPersExt" id="nomPersExt" autofocus required value="'.$row['nom'].'"></td><th width="25%">Prénom :</th><td width="25%"><input type="text" name="prenomPersExt" id="prenomPersExt" value="'.$row['prenom'].'"></td></tr>';
		$html.='<tr><th>Tel fixe :</th><td><input type="tel" name="telPersExt" id="telPersExt" value="'.$row['fixe'].'"></td><th>GSM :</th><td><input type="tel" name="gsmPersExt" id="gsmPersExt" value="'.$row['gsm'].'"></td></tr>';
		$html.='<tr><th>Fax :</th><td><input type="tel" name="faxPersExt" id="faxPersExt" value="'.$row['fax'].'"></td><th>Mail :</th><td><input type="email" name="emailPersExt" id="emailPersExt" value="'.$row['mail'].'"></td></tr>';
		$html.='<tr><th>Rue :</th><td><input type="text" name="ruePersExt" id="ruePersExt" value="'.$row['rue'].'"></td><th>Numéro :</th><td><input type="text" name="numPersExt" id="numPersExt" value="'.$row['numero'].'"></td></tr>';
		$html.='<tr><th>Code postal :</th><td><input type="text" name="CPPersExt" id="CPPersExt" value="'.$row['CP'].'"><input type="hidden" name="idPersE" id="idPersE" value="undefined"></td><th>Ville :</th><td><input type="text" name="villePersExt" id="villePersExt" value="'.$row['ville'].'"></td></tr>';
	}
	$html.='</table>';
	echo $html;
}

?>