<?php

class VAdmin extends VBase {

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

public function menuAccueil($data=''){
	$html='<div id="menuAccueil">';
	$html.='<h1>Administration du site</h1>';
	$html.='<h2>Fonctionnalit&eacute;s disponibles<h2>';
	$html.='<table class="table" id="fonctAdmin">';
	$html.='<tr><td>Gestion des administrateurs du site</td><td><input type="button" id="bAdminus" onclick="location.href = \'?component=admin&action=gestAdminSite\';" value="Allez !"></tr>';
	$html.='<tr><td>Gestion des administrateurs de modules</td><td><input type="button" id="bAdminus" onclick="location.href = \'?component=admin&action=gestAdminModules\';" value="Allez !"></tr>';
	$html.='<tr><td>Gestion des utilisateurs</td><td><input type="button" id="bAdminus" onclick="location.href = \'?component=user&action=gestUsers\';" value="Allez !"></td></tr>';
	$html.='</table>';
	$html.='</div>';
	$this->appli->content=$html;
}

public function gestAdminSite($data){
	$html='<div id="gestAdminSite">';
	$html.='<h1>Gestion des administrateurs du site</h1>';
	$html.='<table>';
	while($row=$data->fetch()){
		$html.='<tr><td>'.$row['nom'].' '.$row['prenom'].'</td><td><a href="?component=admin&action=delAdminSite&user='.$row['id_user'].'"><input type="button" id="bSupAdmin" value="R&eacute;voquer"></a></td></tr>';
	}
	$html.='</table><hr>';
	$html.='<a href="?component=admin&action=addAdminSite">Ajouter</a> un administrateur du site.';
	$html.='</div>';
	$this->appli->content=$html;
}

public function error($param,$from){
	$html='<div id="error"><h1>Erreur !</h1>';
	switch($param){
		case 1: //Auto-suppression
			$html.='Vous ne pouvez retirer vos propres acc&egrave;s !';
			break;
		case 2: //Ajout personne existante
			$html.='Cet acc&egrave;s est d&eacute;j&agrave exitant.';
			break;
	}
	$html.='<br /><a href="?component='.$_GET['component'].'&action='.$from;
	$html.=(isset($_GET['module'])) ? '&module='.$_GET['module'] : '' ;
	$html.='">Retour</a>';
	$html.='</div>';
	$this->appli->content=$html;
}

public function addAdminSite($data){
	$html='<div id="gestAdminSite">';
	$html.='<h1>Ajout d\'un administrateur du site</h1>';
	$html.='<form method="POST" name="addAdminSite" action="?component=admin&action=addAdminSite&record=true"><table>';
	$html.='<tr><th>Utilisateur &agrave; ajouter :</th><td><select name="userToAdd"><option></option>';
	while($row=$data->fetch()){
		$html.='<option value="'.$row['id_user'].'">'.$row['nom'].' '.$row['prenom'].'</option>';
	}
	$html.='</td></tr><tr><td colspan="2"><input type="submit" id="bSupAdmin" value="Enregistrer"></td></tr>';
	$html.='</table></form>';
	$html.='</div>';
	$this->appli->content=$html;
}

public function gestAdminModules($data,$listUsers){
	$i=0;
	$users=array();
	while($row=$data->fetch()){
		$users[$i]['nom']=$row['nom'];
		$users[$i]['prenom']=$row['prenom'];
		$users[$i]['idUser']=$row['id_user'];
		$users[$i]['app']=$row['app'];
		$users[$i]['id']=$row['id'];
		$i++;
	}
	$options='';
	while($row=$listUsers->fetch()){
		$options.='<option value="'.$row['id_user'].'">'.$row['nom'].' '.$row['prenom'].'</option>';
	}
	$html='<div id="gestAdminSite">';
	$html.='<h1>Gestion des administrateurs des modules</h1>';
	$html.='<h2>Veuillez choisir le module &agrave; g&eacute;rer</h2>';
	$html.='<ul id="adminModules">';
	$html.='<li onclick="slide(\'slide1\',\'0\');">GRH</li><li onclick="slide(\'slide2\',\'0\');">Personnel</li><li onclick="slide(\'slide3\',\'0\');">Logistique</li><li onclick="slide(\'slide4\',\'0\');">Finances</li>';
	$html.='</ul><br />';
	$logistique='<div id="slide3"';
	$logistique.=((isset($_GET['module']))&&($_GET['module']=='logistique')) ? ' style="display:block;"' : '';
	$logistique.='><h3>Module Logistique</h3><table><th>Nom</th><th>Pr&eacute;nom</th><th></th></tr>';
	
	$grh='<div id="slide1"';
	$grh.=((isset($_GET['module']))&&($_GET['module']=='grh')) ? ' style="display:block;"' : '';
	$grh.='><h3>Module GRH</h3><table><th>Nom</th><th>Pr&eacute;nom</th><th></th></tr>';
	
	$personnel='<div id="slide2"';
	$personnel.=((isset($_GET['module']))&&($_GET['module']=='personnel')) ? ' style="display:block;"' : '';
	$personnel.='><h3>Module Personnel</h3><table><th>Nom</th><th>Pr&eacute;nom</th><th></th></tr>';
	
	$finances='<div id="slide4"';
	$finances.=((isset($_GET['module']))&&($_GET['module']=='finances')) ? ' style="display:block;"' : '';
	$finances.='><h3>Module Finances</h3><table><th>Nom</th><th>Pr&eacute;nom</th><th></th></tr>';
	
	
	for($j=0;$j<+$i;$j++){
		switch($users[$j]['app']){
			case 'logistique':
				$logistique.='<tr><td>'.$users[$j]['nom'].'</td><td>'.$users[$j]['prenom'].'</td><td><a href="?component=admin&action=gestAdminModules&subAction=delAdmin&idRow='.$users[$j]['id'].'&module=logistique&user='.$users[$j]['idUser'].'"><input type="button" value="R&eacute;voquer" id="bSupAdmin"></a></td></tr>';
				break;
			case 'grh':
				$grh.='<tr><td>'.$users[$j]['nom'].'</td><td>'.$users[$j]['prenom'].'</td><td><a href="?component=admin&action=gestAdminModules&subAction=delAdmin&idRow='.$users[$j]['id'].'&module=grh&user='.$users[$j]['idUser'].'"><input type="button" value="R&eacute;voquer" id="bSupAdmin"></a></td></tr>';
				break;
			case 'personnel':	
				$personnel.='<tr><td>'.$users[$j]['nom'].'</td><td>'.$users[$j]['prenom'].'</td><td><a href="?component=admin&action=gestAdminModules&subAction=delAdmin&idRow='.$users[$j]['id'].'&module=personnel&user='.$users[$j]['idUser'].'"><input type="button" value="R&eacute;voquer" id="bSupAdmin"></a></td></tr>';
				break;
			case 'finances':
				$finances.='<tr><td>'.$users[$j]['nom'].'</td><td>'.$users[$j]['prenom'].'</td><td><a href="?component=admin&action=gestAdminModules&subAction=delAdmin&idRow='.$users[$j]['id'].'&module=finances&user='.$users[$j]['idUser'].'"><input type="button" value="R&eacute;voquer" id="bSupAdmin"></a></td></tr>';
				break;
		}
	}
	$logistique.='</table><form method="POST" action="?component=admin&action=gestAdminModules&subAction=addAdmin&module=logistique"><table><tr><td><select name="user" required><option></option>'.$options.'</select></td><td><input type="submit" id="bAddAdmin" value="Ajouter"/></td></tr></table></form></div>';
	$grh.='</table><form method="POST" action="?component=admin&action=gestAdminModules&subAction=addAdmin&module=grh"><table><tr><td><select name="user" required><option></option>'.$options.'</select></td><td><input type="submit" id="bAddAdmin" value="Ajouter"/></td></tr></table></form></div>';
	$personnel.='</table><form method="POST" action="?component=admin&action=gestAdminModules&subAction=addAdmin&module=personnel"><table><tr><td><select name="user" required><option></option>'.$options.'</select></td><td><input type="submit" id="bAddAdmin" value="Ajouter"/></td></tr></table></form></div>';
	$finances.='</table><form method="POST" action="?component=admin&action=gestAdminModules&subAction=addAdmin&module=finances"><table><tr><td><select name="user" required><option></option>'.$options.'</select></td><td><input type="submit" id="bAddAdmin" value="Ajouter"/></td></tr></table></form></div>';
	$html.=$logistique.$grh.$personnel.$finances.'</div>';
	$this->appli->content=$html;
}
}
?>