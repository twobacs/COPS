<?php

class VPersonnel extends VBase {

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


public function unconnected(){
	$html='<div id="unconnected">';
	$html.='Vous n\'&ecirc;tes pas connect&eacute;(e) ou votre session a expir&eacute;, veuillez vous connecter.';
	$html.='</div>';
	$this->appli->content=$html;
}

public function accueil($niv){
	$html='<div id="menuAccueil">';
	$html.='<h1>Gestion du personnel</h1>';
	switch($niv){
		case '0':
			$html.='Vous n\'avez aucun acc&egrave;s &agrave cette application, veuillez vous adresser au service du personnel afin d\'en obtenir un.';
			break;
		case '10':
			$html.=$this->menuAdminPers();
			$html.=$this->menuSvPers();
			$html.=$this->menuUser();
			break;
		case '8':
			$html.=$this->menuSvPers();
			$html.=$this->menuUser();
			break;
		case '1':
			$html.=$this->menuUser();
			break;
	}
	$html.='</div>';
	$this->appli->content=$html;
}

public function menuAdminPers(){
	$html='<div id="MenuAdmin">';
	$html.='<h2 onclick="slide(\'slide1\',\'0\');">Menu administrateur</h2>';
	$html.='<div id="slide1"><a href="?component=personnel&action=gestUsers">Gestion des utilisateurs du module</a><br /><a href="?component=user&action=gestUsers">Gestion des utilisateurs de la plateforme</a></div>';
	$html.='</div>';
	return $html;
}

public function menuSvPers(){
	$html='<div id="menuSvPers">';
	$html.='<h2 onclick="slide(\'slide2\',\'0\');">Menu personnel du service</h2>';
	$html.='<div id="slide2">';
	$html.='<a href="?component=personnel&action=gestPers">Gestion des personnes</a><br />';
	$html.='</div>';
	$html.='</div>';
	return $html;
}

public function menuUser(){
	$html='<div id="MenuUser">';
	$html.='<h2 onclick="slide(\'slide4\',\'0\');">Menu utilisateur</h2>';
	$html.='<div id="slide4"><a href="?component=logistique&action=gestUsers">Exemple lien utilisateurs</a></div>';
	$html.='</div>';
	return $html;
}

public function gestDroits($droits,$users){
	$html='<div id="gestAdminSite">';
	$html.='<h2>Gestion des droits pour le module Service du Personnel</h2>';
	$html.='<h3>Utilisateurs actuels</h3>';
	$options='';
	while($row=$users->fetch()){
		$options.='<option value="'.$row['id_user'].'">'.$row['nom'].' '.$row['prenom'].'</option>';
	}
	$html.='<table class="table">';
	$html.='<tr><th>Nom</th><th>Pr&eacute;nom</th><th>Niveau actuel</th><th></th></tr>';
	while($row=$droits->fetch()){
		$html.='<tr><td>'.$row['nom'].'</td><td>'.$row['prenom'].'</td><td>';
		switch($row['niv_acces']){
			case '10':
				$html.='Administrateur';
				break;
			case '8':
				$html.='Personnel du service';
				break;
			case '1':
				$html.='Utilisateur enregistr&eacute;';
				break;				
		}
		$html.='</td><td><a href="?component=admin&action=delDroitsById&module=personnel&idRow='.$row['id'].'"><input type="button" id="bSupAdmin" value="R&eacute;voquer" /></a></td></tr>';
	}
	$html.='</table><hr><h3>Ajouter un utilisateur</h3>';
	$html.='<form method="POST" action="?component=admin&action=addUserToModule&module=personnel"><table class="table">';
	$html.='<tr><th>Utilisateur :</th><td><select class="form-control" name="selectedUser" required><option></option>'.$options.'</select></td><th>Niveau d\'acc&egrave;s :</th><td><select class="form-control" name="nivAcces"><option value="1">Utilisateur enregistr&eacute;</option><option value="8">Personnel du service</option><option value="10">Administrateur</option></select></td><td><input type="submit" value="Enregistrer" id="bAddAdmin" /></td></tr>';
	$html.='</table></form>';
	$html.='</div>';
	$this->appli->content=$html;
}
}
?>