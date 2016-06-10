<?php

class VRues extends VBase {

    function __construct($appli, $model) {
        parent::__construct($appli, $model);
    }

public function nonco()
	{
	$this->appli->ctContent.="Vous ne pouvez accéder à cette partie du site, <a href=\"?component=users&action=logForm\">connectez-vous.</a>";
	}
	
	public function nonDroit()
	{
	$this->appli->ctContent.="Votre profil ne vous permet pas d'accéder à cette partie du site.";
	}
	
public function afficheHtml($data)	
	{
	$this->appli->ctContent=$data;
	$this->appli->jScript= '<script type="text/javascript" src="./js/rues.js"></script>';
	}

public function mainMenu($level)
	{
	if ($level<30)
		{
		$this->nonDroit();
		}
	else
		{
		$html='<h2>Gestion des rues</h2>';
		$html.='<h3>Choisissez une action à effectuer</h3>';
		$html.='<ul>';
		$html.='<li><a href=?component=rues&action=addRue>Ajouter</a> une rue</li>';
		// $html.='<li><a href=?component=ruers&action=modifRue?Modifier</a> une rue</li>';
		$html.='<li><a href="#" onclick=moreOptions();>Modifier</a> une rue</li>';
		$html.='<div id=rep></div>';
		$html.='</ul>';		
		$this->afficheHtml($html);
		}
	}
	
public function modifOneRue()
	{
	$html='<h2>Modifier une rue</h2>';
	$html.='Veuillez introduire le nom de la rue à modifier : <input type=text id=nomRue onkeyup=searchRue(this); autofocus><br />';
	$html.='<div id=rep></div>';
	$this->afficheHtml($html);
	}
	
public function showAllRues($data)
	{
	$html='<h2>Modifier une rue</h2>';
	$html.='<h3>Veuillez sélectionner la rue à modifier</h3>';
	
	while ($row=$data->fetch())
		{
		$html.=$row['NomRue'].' ('.$row['StraatNaam'].') - <a href=?component=rues&action=modifRue&type=OneRue&id='.$row['IdRue'].'>Modifier</a><br />';
		}
	$this->afficheHtml($html);
	}
	
public function afficheInfosRue($data)
	{
	$html='<h2>Modification d\'une rue</h2>';
	$html.='<table>';
	while ($row=$data->fetch())
		{
		$html.='<tr><th>Nom :</th><td><input type=text name=nom id=nom value="'.$row['NomRue'].'" autofocus></td><th>Nom NL :</th><td><input type=text name=naam id=naam value="'.$row['StraatNaam'].'"></td></tr>';
		$html.='<tr><td class="noborder" colspan="4"><input type=button value="Enregistrer" onclick=updateRue(\''.$row['IdRue'].'\');></td></tr>';
		}
	$html.='</table>';
	$html.='<div id=rep></div>';
	$this->afficheHtml($html);
	}
	
public function formAddRue()
	{
	$html='<h2>Ajout d\'une rue</h2>';
	$html.='<h3>Veuillez noter la dénomination complète de la nouvelle rue. (ex : Rue de la Station, pas "Station" uniquement) Merci.</h3>';
	$html.='<form method=POST nom=formAddRue action=?component=rues&action=addRue><table>';
	$html.='<tr><th>Dénomination (fr) :</th><td><input type=text name=newNom id=newNom autofocus></td><th>Dénomination (nl) :</th><td><input type=text name=newNaam id=newNaam></td></tr>';
	$html.='<tr><td colspan=4 class=noborder><input type=button value="Enregistrer" onclick=addRue();></td></tr>';
	$html.='</table></form>';
	$html.='<div id=rep></div>';
	$this->afficheHtml($html);
	}
	
	
}
?>