<?php

class VUser extends VBase {

    function __construct($appli, $model) {
        parent::__construct($appli, $model);
    }

public function datefr($date){
	$a=explode("-", $date);
	$annee=$a[0];
	$mois=$a[1];
	$jour=$a[2];
	$rep=$jour.'-'.$mois.'-'.$annee;
	return $rep;
}

public function formConnect(){
		$html='<form role="form" action="index.php?component=user&action=login" method="POST" autocomplete="off">';
		$html.='<div class="login">';
			$html.='<h1>Connexion</h1>';
			$html.='<input type="text" name="login" id="identifiant" placeholder="Identifiant"/>';
			$html.='<input type="password" name="password"  id="password" placeholder="Mot de passe">';		
			$html.='<button type="submit" class="btn btn-primary btn-block btn-large">Entrer</button>';			
		$html.='</div>';
	$html.='</form>';
	$menu='<h3>Site optimis&eacute; pour <a href="https://www.google.com/chrome/browser/desktop/index.html" target="_blank">Chrome</a></h3>';
	$this->appli->content=$html;
	$this->appli->menu_perso=$menu;
}


public function unconnected(){
	$html='<div id="unconnected">';
	$html.='Vous n\'&ecirc;tes pas connect&eacute;(e) ou votre session a expir&eacute;, veuillez vous connecter.';
	$html.='</div>';
	$this->appli->content=$html;
}	

public function error(){
	$html='<div id="error">';
	$html.='<h1>Erreur !</h1>';
	$html.='Vous n\'avez pas les droits requis pour arriver ici !';
	$html.='</div>';
	$this->appli->content=$html;
}

public function menuAccueil($data=1,$from=''){
	$this->appli->menu_perso='';
	$html='<div id=menuAccueil>';
	if($data==1){
	$html.='<h1>Bienvenue sur la plateforme "Gestion et Ressources", '.$_SESSION['Prenom'].'.</h1>';
	if($from=='modifpwd'){
		$html.='Votre mot de passe a &eacute;t&eacute; correctement modifi&eacute;.<br />';
	}
	$html.='<div class="bGRH" id="bGRH" onclick="location.href=\'index.php?component=grh&action=home\'">GRH</div>';
	$html.='<div class="bPers" id="bPers" onclick="location.href=\'index.php?component=personnel&action=home\'">Personnel</div><br />';
	$html.='<div class="bLogi" id="bLogi" onclick="location.href=\'index.php?component=logistique&action=home\'">Logistique</div>';
	$html.='<div class="bFinances" id="bFinances" onclick="location.href=\'index.php?component=finances&action=home\'">Finances</div><br />';
	$html.=(isset($_SESSION['admin'])) ? '<div class="bAdmin" id="bAdmin" onclick="location.href=\'index.php?component=admin&action=home\'">Admin</div>' : '';
	}
	else if ($data==2){
		$html.='<h2>Ce login a &eacute;t&eacute; bloqu&eacute; suite &agrave; un trop grand nombre d\'erreurs</h2>';
		$html.='Veuillez contacter un administrateur pour le d&eacute;bloquer';
	}
	
	else if($data==0){
		$html.='<h2>Le login introduit n\'existe pas ou vous avez effectu&eacute; une erreur dans votre mot de passe</h2>';
	}
	
	else if($data==3){
		$html.='<h2>Changez votre mot de passe</h2>';
		$html.='Votre mot de passe est celui par d&eacute;faut, veuillez <a href="?component=user&action=modifpassword">le modifier</a>.';
	}
	// f4ca39c3befdab49e7a690e48e7aa947
	$html.='</div>';
	$this->appli->content=$html;
}

public function formModifPassword($data){
	$html='<div id=gestAdminSite>';
	$html.='<h2>Modification du mot de passe</h2>';
	if(isset($_GET['error'])){
		$html.='Une erreur s\'est produite lors du changement de mot de passe, veuillez v&eacute;rifier l\'ensemble des donn&eacute;es introduites et respecter les contraintes minimales pour le nouveau mot de passe.<br />';
	}
	$html.='Votre nouveau mot de passe doit comporter au moins une minuscule, une majuscule, un chiffre et doit contenir au minimum 8 caract&egrave;res.';
	$html.='<form method="POST" action="?component=user&action=modifpassword"><table class="table">';
	$html.='<tr><td><input placeholder="Ancien mot de passe"type="password" name="oldPwd" id="oldPwd" onkeyup="verifOldPwd(\''.$_SESSION['idUser'].'\');" onkeyup="showTrSubmit();"></td></tr>';
	$html.='<tr><td><input placeholder="Nouveau mot de passe"type="password" name="newPwd1" id="newPwd1" onfocusout="validNewPwd();" onchange="verifNewPwd();" onkeyup="resetmdp2()"></td></tr>';
	$html.='<tr id="trPwd2"><td><input placeholder="Nouveau mot de passe (confirmation)"type="password" name="newPwd2" id="newPwd2" onkeyup="verifNewPwd();"></td></tr></table>';
	$html.='<table class="table"><tr id="trSubmit" style="display:none;"><td><input type="submit" value="Valider" id="bAddAdmin"></td></tr></table>';
	$html.='</table></form>';
	$html.='</div>';
	$this->appli->content=$html;
	$this->appli->jscript='<script type="text/javascript" src="/GR/js/users.js"></script>';
}

public function infosUser($data){
	$html='<div id="infoUser">';
	$html.='<h1>Mes données administratives</h1>';
	$html.='<form id="formMesDonnees" id="formMesDonnees" method="POST" action="?component=user&action=infosUser&subAction=updateDonnees&user='.$_SESSION['idUser'].'">';
	$html.='<table class="table">';
	while($row=$data->fetch()){
		$html.='<tr><th>Nom :</th><td>'.$row['nom'].'</td><th>Pr&eacute;nom :</th><td>'.$row['prenom'].'</td></tr>';
		$html.='<tr><th>Matricule :</th><td>'.$row['matricule'].'</td><th>Lat&eacute;ralit&eacute; :</th><td>'.ucfirst($row['lateralite']).'</td></tr>';
		$html.='<tr><th>Date de naissance :</th><td>'.$this->datefr($row['naissance']).'</td><th>Num&eacute;ro national :</th><td>'.$row['rrn'].'</td></tr>';
		$html.='<tr><th>Sexe :</th><td>'.$row['denomination_sexe'].'</td><th>Grade :</th><td>'.$row['denomination_grade'].'</td></tr>';
		$html.='<tr><th>Mail :</th><td><input placeholder="Mail" type="mail" name="mailUser" id="mailUser" value="'.$row['mail'].'"></td><th>T&eacute;l&eacute;phone fixe :</th><td><input placeholder="Téléphone fixe"type="tel" name="TelFixeUser" id="TelFixeUser" value="'.$row['fixe'].'"></td></tr>';
		$html.='<tr><th>GSM :</th><td><input placeholder="GSM"type="tel" name="GSMUser" id="GSMUser" value="'.$row['gsm'].'"></td><th>Fax :</th><td><input placeholder="Fax"type="tel" name="faxUser" id="faxUser" value="'.$row['fax'].'"></td></tr>';
		$html.='<tr><th>Adresse :</th><td colspan="3">'.$row['CP'].' '.$row['ville'].', '.$row['rue'].' '.$row['numero'].'</td></tr>';
		$html.='<tr><td class="noborder" colspan="4"><input type="submit" id="bEnregistrer" value="Enregistrer les modifications"></td></tr>';
	}
	$html.='</table>';
	$html.='</form>';
	$html.='<a id="linkPersContact" href="?component=user&action=infosPersUrg&id_user='.$_SESSION['idUser'].'">Personnes &agrave; pr&eacute;venir en cas d\'incident</a>';
	$html.='</div>';
	$this->appli->content=$html;
	return $html;
}

public function ShowInfosPersUrg($data){
	$html='<div id="infoUser">';
	$html.='<h1>Personnes &agrave; pr&eacute;venir en cas d\'incident</h1><h2>';
	$i=0;
	while ($row=$data->fetch()){
		$idcontact[$i]=$row['id_contact'];
		$nom[$i]=$row['nom'];
		$prenom[$i]=$row['prenom'];
		$CodePost[$i]=$row['CodePost'];
		$Commune[$i]=$row['Commune'];
		$Rue[$i]=$row['Rue'];
		$Numero[$i]=$row['Numero'];
		$Tf[$i]=$row['Tf'];
		$GSM[$i]=$row['GSM'];
		$Parente[$i]=$row['parente'];
		$Prior[$i]=$row['Prior'];
		$i++;
	}
	if ($i>0){
		$html.=($i==1) ? 'Une personne figure ' : $i.' personnes figurent ';
		// $html.=$contacts;
	}
	else $html.='Aucune personne ne figure ';
	$html.='dans votre liste de contacts</h2>';
	for($j=0;$j<$i;$j++){
			$html.='<form method="POST" action="index.php?component=user&action=updatePersCont&id_user='.$_GET['id_user'].'&id_contact='.$idcontact[$j].'"><table class="table">';
			$html.='<tr><th>Nom :</th><td><input type="text" name="nomCont" id="nomCont" required  value="'.html_entity_decode($nom[$j]).'"></td><th>Pr&eacute;nom :</th><td><input type="text" name="prenomCont" id="prenomCont" required  value="'.html_entity_decode($prenom[$j]).'"></td></tr>';
			$html.='<tr><th>Code postal :</th><td><input type="text" name="CPCont" id="CPCont"  value="'.$CodePost[$j].'"></td><th>Commune :</th><td><input type="text" name="villeCont" id="villeCont"  value="'.$Commune[$j].'"></td></tr>';
			$html.='<tr><th>Rue :</th><td><input type="text" name="rueCont" id="rueCont"  value="'.$Rue[$j].'"></td><th>Num&eacute;ro :</th><td><input type="text" name="numCont" id="numCont"  value="'.$Numero[$j].'"></td></tr>';
			$html.='<tr><th>T&eacute;l&eacute;phone :</th><td><input type="text" name="telCont" id="telCont"  value="'.$Tf[$j].'"></td><th>GSM :</th><td><input type="text" name="gsmCont" id="gsmCont"  value="'.$GSM[$j].'"></td></tr>';
			$html.='<tr><th>Parent&eacute; :</th><td><input type="text" name="parenteCont" id="parenteCont"  value="'.$Parente[$j].'"></td><th>Priorit&eacute; :</th><td><input type="text" name="prioCont" id="prioCont"  value="'.$Prior[$j].'"></td></tr>';
			$html.='<tr><td class="noborder" colspan="4"><input id="bEnregistrer" type="submit" value="Enregistrer les modifications"><input type="button" id="bSupp" value="Supprimer ce contact" onclick="location.href=\'index.php?component=user&action=DelPersUrg&id_user='.$_SESSION['idUser'].'&idContact='.$idcontact[$j].'\'" /></td></tr>';
			$html.='</table></form>';
			$html.='<hr>';
		}
	$html.='<a id="linkPersContact" href="?component=user&action=addPersIncident&id_user='.$_SESSION['idUser'].'">Ajouter</a>';
	$html.='</div>';
	$this->appli->content=$html;
}

public function formAjoutPersContact($user){
	$html='<div id="infoUser">';
	$html.='<h1>Ajout d\'une personne de contact en cas d\'incident</h1>';
	$html.='<form method="POST" action="?component=user&action=addPersIncident&subAction=addNewContact&user='.$_SESSION['idUser'].'"><table class="table">';
	$html.='<tr><th>Nom :</th><td><input placeholder="Nom"type="text" name="nomCont" id="nomCont" autofocus required></td><th>Pr&eacute;nom :</th><td><input placeholder="Prénom" type="text" name="prenomCont" id="prenomCont" required></td></tr>';
	$html.='<tr><th>Code postal :</th><td><input placeholder="Code postal" type="text" name="CPCont" id="CPCont"></td><th>Commune :</th><td><input placeholder="Commune" type="text" name="villeCont" id="villeCont"></td></tr>';
	$html.='<tr><th>Rue :</th><td><input placeholder="Rue" type="text" name="rueCont" id="rueCont"></td><th>Num&eacute;ro :</th><td><input placeholder="Numéro" type="text" name="numCont" id="numCont"></td></tr>';
	$html.='<tr><th>T&eacute;l&eacute;phone :</th><td><input placeholder="Téléphone" type="text" name="telCont" id="telCont"></td><th>GSM :</th><td><input placeholder="GSM" type="text" name="gsmCont" id="gsmCont"></td></tr>';
	$html.='<tr><th>Parent&eacute; :</th><td><input placeholder="Parenté" type="text" name="parenteCont" id="parenteCont"></td><th>Priorit&eacute; :</th><td><input placeholder="Priorité" type="text" name="prioCont" id="prioCont"></td></tr>';
	$html.='<tr><td class="noborder" colspan="4"><input id="bEnregistrer" type="submit" value="Enregistrer"><input type="reset"><input type="button" id="bAnnuler" value="Annuler" onclick="location.href=\'index.php?component=user&action=infosPersUrg&id_user='.$_SESSION['idUser'].'\'" /></td></tr>';
	$html.='</table></form>';
	$html.='</div>';
	$this->appli->content=$html;
}

public function menuGestUsers(){
	$html='<div id="gestAdminSite">';
	$html.='<h2>Gestion des utilisateurs</h2>';
	$html.='<table class="table"id="gestUsers">';
	$html.='<tr><td>Chercher sur le nom : <input placeholder="Chercher sur le nom" type="text" id="nom" onkeyup="searchUser(\'nom\');"></td><td>Chercher sur le matricule : <input placeholder="Chercher sur le matricule" type="text" id="matricule" onkeyup="searchUser(\'matricule\');"></td></tr>';
	$html.='<tr><th>Nom</th><th>Pr&eacute;nom</th></tr>';
	$html.='</table>';
	$html.='<table id="resultSearch"></table>';
	$html.='<a href="?component=user&action=addUser">Ajouter un utilisateur</a>';
	$html.='</div>';
	$this->appli->content=$html;
}

public function showInfosUserById($data){
	while ($row=$data['user']->fetch()){
		$login=$row['login'];
		$nom=$row['nom'];
		$prenom=$row['prenom'];
		$matricule=$row['matricule'];
		$lateralite=$row['lateralite'];
		$uniforme=$row['uniformise'];
		$sexe=$row['denomination_sexe'];
		$grade=$row['denomination_grade'];
		$mail=$row['mail'];
		$fixe=$row['fixe'];
		$gsm=$row['gsm'];
		$fax=$row['fax'];
		$CP=$row['CP'];
		$ville=$row['ville'];
		$rue=$row['rue'];
		$numero=$row['numero'];
		$DN=$row['naissance'];
		$RRN=$row['rrn'];
		$service=$row['id_service'];		
	}
	$html='<div id="gestAdminSite">';
	$html.='<h2>Fiche d&eacute;taill&eacute;e de '.$nom.' '.$prenom.'</h2>';
	$html.='<form method="POST" action="?component=user&action=recModifsById&id='.$_GET['id'].'"><table>';
	
	$html.='<tr><th colspan="4" class="titreTH">Donn&eacute;es administratives</th></tr>';
	$html.='<tr><th>Nom</th><td>'.$nom.'</td><th>Pr&eacute;nom</th><td>'.$prenom.'</td></tr>';
	$html.='<tr><th>Matricule</th><td>'.$matricule.'</td><th>Num&eacute;ro RRN</th><td>'.$RRN.'</td></th></tr>';
	$html.='<tr><th>Sexe</th><td>'.$sexe.'</td><th>Lat&eacute;ralit&eacute;</th><td><select name="lateralite" id="lateralite"><option value="droitier"';
	$html.=($lateralite=="droitier") ? ' selected' : '';
	$html.='>Droitier</option><option value="gaucher"';
	$html.=($lateralite=="gaucher") ? ' selected' : '';
	$html.='>Gaucher</option></select></td></tr>';
		$html.='<tr><th>Uniformis&eacute;</th><td><select name="uniforme" id="uniforme"><option value="U"';
	$html.=($uniforme=="U") ? ' selected' : '';
	$html.='>Oui</option><option value="N"';
	$html.=($uniforme!="U") ? ' selected' : '';
	$html.='>Non</option></select></td><th>Grade</th><td><select name="grade" id="grade">';
	while($row=$data['grades']->fetch()){
		$html.='<option value="'.$row['denomination_grade'].'"';
		$html.=($grade==$row['denomination_grade']) ? ' selected' : '';
		$html.='>'.$row['denomination_grade'].'</option>';
	}
	$html.='</select></td></tr>';
	$html.='<tr><th>Service</th><td><select name="service" id="service>';
	while($row=$data['services']->fetch()){
		$html.='<option value="'.$row['id_service'].'"';
		$html.=($service==$row['id_service']) ? ' selected' : '';
		$html.='>'.$row['denomination_service'].'</option>';
	}
	$html.='</select></td><th>Mail</th><td><input type="mail" id="mail" name="mail" value="'.$mail.'"></td></tr>';
	
	$html.='<tr><th colspan="4" class="titreTH">Donn&eacute;es de contact</th></tr>';
	$html.='<tr><th>T&eacute;l&eacute;phone</th><td><input type="text" name="tel" id="tel" value="'.$fixe.'"></td><th>GSM</th><td><input type="text" name="gsm" id="gsm" value="'.$gsm.'"></td></tr>';
	$html.='<tr><th>Fax</th><td><input type="text" name="fax" id="fax" value="'.$fax.'"></td></tr>';
	
	$html.='<tr><th colspan="4" class="titreTH">Domicile</th></tr>';
	$html.='<tr><th>Code postal</th><td><input type="text" name="CP" id="CP" value="'.$CP.'"></td><th>Commune</th><td><input type="text" name="ville" id="ville" value="'.$ville.'"></td></tr>';
	$html.='<tr><th>Rue</th><td><input type="text" name="rue" id="rue" value="'.$rue.'"></td><th>Num&eacute;ro</th><td><input type="text" name="num" id="num" value="'.$numero.'"></td></tr>';
	
	$html.='<tr><th colspan="4" class="titreTH">Donn&eacute;es de connexion</th></tr>';
	$html.='<tr><th>Identifiant</th><td><input type="text" name="login" id="login" value="'.$login.'"></td><td colspan="2"><input type="button" value="R&eacute;initialiser mot de passe" onclick="resetMDP(\''.$_GET['id'].'\');"></td></tr>';
	$html.='<tr><td colspan="2"><input id="bAddAdmin" type="submit" value="Enregistrer les modifications"></td><td colspan="2"><input type="button" id="bSupAdmin" value="Annuler et revenir &agrave la s&eacute;lection de personne" onclick="location.href=\'index.php?component=user&action=gestUsers\'" ></td></tr>';
	$html.='</table></form>';
	$html.='<hr>';
	$html.='</div>';
	$this->appli->content=$html;
}

public function formAddUser($data){
	$html='<div id="gestAdminSite">';
	$html.='<h2>Ajout d\'un utilisateur</h2>';
	$html.='<form method="POST" action="?component=user&action=addUser&record"><table class="table">';
	//
	$html.='<tr><th colspan="4" class="titreTH">Donn&eacute;es administratives</th></tr>';
	$html.='<tr><th>Nom</th><td><input placeholder="Nom" type="text" name="nom" id="nom" required autofocus></td><th>Pr&eacute;nom</th><td><input placeholder="Prénom" type="text" name="prenom" id="prenom" required></td></tr>';
	$html.='<tr><th>Date de naissance</th><td><input type="date" name="naissance" id="naissance" onfocusout="autocompleteRRN();"></td><th>Num&eacute;ro RRN</th><td id="tdRRN"><input placeholder="Numéro RRN" type="text" name="rrn" id="rrn" onfocusout="verifRRN()";></td></th></tr>';
	$html.='<tr><th>Sexe</th><td><select class="form-control" name="sexe" required><option></option><option value="Feminin">F&eacute;minin</option><option value="Masculin">Masculin</option></td><th>Lat&eacute;ralit&eacute;</th><td><select class="form-control" name="lateralite" id="lateralite" required><option></option><option value="droitier">Droitier</option><option value="gaucher">Gaucher</option></select></td></tr>';
	$html.='<tr><th>Uniformis&eacute;</th><td><select class="form-control" name="uniforme" id="uniforme" required><option></option><option value="U">Oui</option><option value="N">Non</option></select></td><th>Grade</th><td><select class="form-control" name="grade" id="grade" required><option></option>';
	while($row=$data['grades']->fetch()){
		$html.='<option value="'.$row['denomination_grade'].'">'.$row['denomination_grade'].'</option>';
	}
	$html.='</select></td></tr>';
	$html.='<tr><th>Service</th><td><select class="form-control" name="service" id="service required><option></option>';
	while($row=$data['services']->fetch()){
		$html.='<option value="'.$row['id_service'].'">'.$row['denomination_service'].'</option>';
	}
	$html.='</select></td><th>Mail</th><td><input placeholder="Mail" type="mail" id="mail" name="mail"></td></tr>';
	$html.='<tr><th>Matricule</th><td id="tdMat"><input placeholder="Matricule"type="text" name="matricule" id="matricule" onfocusout="verifMat();"></td></tr>';
	
	$html.='<tr><th colspan="4" class="titreTH">Donn&eacute;es de contact</th></tr>';
	$html.='<tr><th>T&eacute;l&eacute;phone</th><td><input placeholder="Téléphone" type="text" name="tel" id="tel"></td><th>GSM</th><td><input placeholder="GSM" type="text" name="gsm" id="gsm"></td></tr>';
	$html.='<tr><th>Fax</th><td><input placeholder="Fax"type="text" name="fax" id="fax"></td></tr>';
	
	$html.='<tr><th colspan="4" class="titreTH">Domicile</th></tr>';
	$html.='<tr><th>Code postal</th><td><input placeholder="Code postal" type="text" name="CP" id="CP"></td><th>Commune</th><td><input placeholder="Commune" type="text" name="ville" id="ville"></td></tr>';
	$html.='<tr><th>Rue</th><td><input placeholder="Rue" type="text" name="rue" id="rue"</td><th>Num&eacute;ro</th><td><input placeholder="Numéro" type="text" name="num" id="num"></td></tr>';
	
	$html.='<tr><th colspan="4" class="titreTH">Donn&eacute;es de connexion</th></tr>';
	$html.='<tr><th colspan="2">Identifiant</th><td colspan="2"><input placeholder="Identifiant" type="text" name="login" id="login" onfocusout="verifLogin();" required></td></tr>';
	$html.='<tr><td colspan="2"><input id="bAddAdmin" type="submit" value="Enregistrer les modifications"></td><td colspan="2"><input type="button" id="bSupAdmin" value="Annuler et revenir &agrave la s&eacute;lection de personne" onclick="location.href=\'index.php?component=user&action=gestUsers\'" ></td></tr>';
	$html.='</table></form>';
	$html.='<hr>';
	
	
	//
	$html.='</table></form>';
	$html.='</div>';
	$this->appli->content=$html;
}
}
?>