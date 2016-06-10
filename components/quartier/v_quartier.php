<?php

class VQuartier extends VBase {

    function __construct($appli, $model) {
        parent::__construct($appli, $model);
    }

public function nonco(){
	$this->appli->ctContent.="Vous ne pouvez accéder à cette partie du site, <a href=\"?component=users&action=logForm\">connectez-vous.</a>";
	}
	
	public function nonDroit(){
	$this->appli->ctContent.="Votre profil ne vous permet pas d'accéder à cette partie du site.";
	}
	
public function afficheHtml($data){
	$this->appli->ctContent=$data;
	$this->appli->jScript= '<script type="text/javascript" src="./js/quartier.js"></script>';
	$this->appli->left='<a href="?component=applications&action=showApps">Retour</a> au menu principal.<br />';
	$this->appli->left.='<a href="?component=quartier&action=mainMenu">Menu</a> quartier.<br />';
	}

public function mainMenu($level){
	if ($level<30)
		{
		$this->nonDroit();
		}
	else
		{
		$html='<h2>Gestion quartier</h2>';
		$html.='<h3>Choisissez une action à effectuer</h3>';
		$html.='<ul>';
		$html.='<li>Gestion <a href="#" onclick=moreAntennes();>antennes</a> de quartier</li>';
		$html.='<div id=moreAntennes></div><br />';
		$html.='<li>Gestion <a href="#" onclick=moreQuartiers();>quartier</a></li>';
		$html.='<div id=moreQuartiers></div><br />';
		$html.='<li>Gestion <a href="#" onclick=moreAgents();>agents</a> de quartier</li>';
		$html.='<div id=moreAgents></div><br />';
		$html.='<li>Faire une <a href="#" onclick=moreRecherches();>recherche</a></li>';
		$html.='<div id=moreRecherches></div><br />';		
		$html.='</ul>';
		
		// $html.='Actions à développer : <br />
		// - Gestion antennes de quartier (afficher - ajouter - modifier - supprimer)<br />
		// - Gestion des quartiers (attachement aux rues - "decoupables" par portions - et à l\'antenne) <br />
		// - Gestion des agents de quartier (attachement d\'un AQ à un quartier) <br />';
		
		// $html.='<img src="/zoom5317/templates/mytpl/images/under-construction.png">';
		$this->afficheHtml($html);
		}
	}

//------------------------------------------------------------//
// GENERATION DES FORMULAIRES D'AJOUT DES DIFFERENTES ENTITES //
//------------------------------------------------------------//
	
public function ajouter($type,$rues='',$agent='',$quartier=''){
	$html='<h2>Gestion quartier</h2><h3>Ajout d\'un';
	switch($type)
		{
		case 'antennes':
			$html.='e antenne de quartier</h3>';
			$html.=$this->formAjoutAntenne($rues,$agent);
			break;
			
		case 'quartier':
			$html.=' quartier</h3>';
			$html.=$this->formAjoutQuartier();
			break;
		
		case 'agent':
			$html.='e liaison agent de quartier / quartier</h3>';
			$html.=$this->formAjoutAgent($agent,$quartier);
			break;
		}
	$html.='<div id=rep></div>';
	$this->afficheHtml($html);	
	}
	
private function formAjoutAntenne($rues,$agent){
	$html='<table>';
	$html.='<tr><th>Dénomination :</th><td><input type=text id=denom autofocus></td><th>Adresse :</th><td><select id=adresse><option value=""></option>';
	while ($row=$rues->fetch())
		{
		$html.='<option value="'.$row['IdRue'].'" title="('.$row['StraatNaam'].')">'.$row['NomRue'].'</option>';
		}
	$html.='</select> n° <input type=text size=5 id=num></td></tr>';
	$html.='<tr><th>Téléphone :</th><td><input type=text id=phone value="056 / 863 "></td><th>Fax :</th><td><input type=text id=fax value="056 / 863 "></td></tr>';
	$html.='<tr><th>Responsable antenne :</th><td><select name=resp id=resp><option value=""></option>';
		while ($rowb=$agent->fetch()){
			$html.='<option value="'.$rowb['id_user'].'">'.$rowb['nom'].' '.$rowb['prenom'].'</option>';
			}
		$html.='</td></tr>';
	
	$html.='<tr><td colspan="4" class=noborder><input type=button onclick=addAntenne(); value=Enregistrer></td></tr>';
	$html.='</table>';
	return $html;
	}
	
private function formAjoutQuartier(){
	$html='<table>';
	$html.='<tr><th>Dénomination :</th><td><input type=text id=denom name=denom autofocus></td><th>GSM associé :</th><td><input type=text name=gsm id=gsm value="04xx / xxx xxx"</td></tr>';
	$html.='<tr><td colspan="4" class=noborder><input type=button value="Enregistrer" onclick=addQuartier();></td></tr>';
	$html.='</table>';
	$html.='<div id="rep"></div>';
	return $html;
	}
	
private function formAjoutAgent($agent,$quartier){
	$html='<table>';
	$html.='<tr><th>Agent concerné :</th><td><select id=agent><option value=""></option>';
	while ($row=$agent->fetch())
		{
		$html.='<option value="'.$row['id_user'].'">'.$row['nom'].' '.$row['prenom'].'</option>';
		}
	$html.='</select></td></tr>';
	$html.='<tr><th>Quartier associé :</th><td><select id=quartier onchange=checkAqQuart();><option value=""></option>';
	while ($row=$quartier->fetch())
		{
		$html.='<option value="'.$row['id_quartier'].'">'.$row['denomination'].'</option>';
		}
	$html.='</select></td></tr>';	
	$html.='<tr><td colspan="2" class=noborder><input type=button onclick=addLiaisonAqQuart(); value=Enregistrer></td></tr>';
	$html.='</table>';
	$html.='<div id="rep"></div>';
	return $html;
	}

//-----//
// END //
//-----//

	
//--------------------------------------------------------------------//
// GENERATION DES FORMULAIRES DE MODIFICATION DES DIFFERENTES ENTITES //
//--------------------------------------------------------------------//

public function modifier($type,$data,$rues){
	$html='<h3>Modification d\'un';
	switch($type){
		case 'antennes':
			$html.='e antenne de quartier</h3>';
			$html.='<h4>Sélectionnez l\'antenne à modifier</h4>';
			$html.=$this->ShowAntennes($data,$rues);
			break;
			
		case 'quartier':
			$html.=' quartier</h3>';
			$html.='<h4>Sélectionnez le quartier à modifier</h4>';
			$html.=$this->ShowQuartiers($data,$rues);			
			break;
		
		case 'agent':
			$html.=' agent de quartier</h3>';
			$html.=$this->formModifAgent($data);
			break;
		}
	$this->afficheHtml($html);
	}
	
private function ShowAntennes($data,$rues){
	$html='';
	while ($row=$data->fetch()){
		$html.='<a href="#" onclick=modifAntenne("'.$row['id_antenne'].'");>'.$row['denomination'].'</a><br />';
		$html.='Adresse : '.$row['NomRue'].', '.$row['numero'].'. <br />';
		$html.='Téléphone : '.$row['telephone'].'.<br />';
		$html.='Fax : '.$row['fax'].'.<br />';
		$html.='<br /><div id='.$row['id_antenne'].'></div>';
		$html.='<hr>';
		}
	$html.='<div id="rep"></div><br />';

	return $html;
	}
	
private function ShowQuartiers($data,$rues){
	$html='';
	while ($row=$data->fetch()){
		$html.='<a href="#" onclick=modifQuartier("'.$row['id_quartier'].'");>'.$row['denomination'].'</a><br />';
		$html.='GSM associé : '.$row['gsm'].'<br />';
		$html.='<br /><div id='.$row['id_quartier'].'></div>';
		$html.='<hr>';
		}
	$html.='<div id="rep"></div><br />';	
	return $html;
	}
	
private function formModifAgent($data){
	$html='<table>';
	$html.='<tr><th width=48%>Agent à modifier :</th><td><select name=agent id=agent onChange=infosAgent();><option value=""></option>';
	while ($row=$data->fetch()){
		$html.='<option value='.$row['id_user'].'>'.$row['nom'].' '.$row['prenom'].'</option>';
		}
	$html.='</td></tr>';
	$html.='</table>';
	$html.='<div id="rep"></div><br />';
	return $html;
	}
	
//-----//
// END //
//-----//	
	
public function afficheInfos($type,$data){
	$html='<h2>Antennes de quartier</h2>';
	switch ($type){
		case 'antennes' :
			while ($row=$data->fetch()){
				$html.='<b>'.$row['denomination'].'</b><br />';
				$html.='Adresse : '.$row['NomRue'].', '.$row['numero'].'. <br />';
				$html.='Téléphone : '.$row['telephone'].'.<br />';
				$html.='Fax : '.$row['fax'].'.<br />';
				$html.='<hr>';
				}
		break;
		case 'quartier':
			while ($row=$data->fetch()){
				$html.=$row['denomination'].'<br />';
				$html.='GSM associé : '.$row['gsm'].'<br />';
				$html.='<hr>';
				}
			break;
		case 'agent':
			$html.='<h4>Agents de quartier</h4>';
			while ($row=$data->fetch()){
				$html.=$row['nom'].' '.$row['prenom'];
				if (isset($row['gsm'])){
					$html.=' (Quartier : '.$row['denomination'].'.  GSM de quartier : '.$row['gsm'].').<br />';
					}	
				$html.='<hr>';
				}
			break;	
		}
	$this->afficheHtml($html);	
	}
	
//------------------------//
//ASSOCIATION QUARTIER RUE//
//------------------------//

public function assocrq($rues,$quartiers){
	$html='<h3>Associer une (portion de) rue à un quartier</h3>';
	$html.='<h4>Choix de la rue</h4>';
	$html.='<table>';
	$html.='<tr><th>Rue :</th><td colspan="3"><select id=rue><option value=""></option>';
	while ($row=$rues->fetch())
		{
		$html.='<option value="'.$row['IdRue'].'" title="('.$row['StraatNaam'].')">'.$row['NomRue'].'</option>';
		}
	$html.='</select></td></tr>';	
	$html.='<tr>';
	$html.='<th>Limite paire basse :</th><td><input type=text id=lpb value="0"></td><th>Limite paire haute :</th><td><input type=text id=lph value="998"></td>';
	$html.='</tr>';
	$html.='<tr>';
	$html.='<th>Limite impaire basse :</th><td><input type=text id=lib value="1"></td><th>Limite impaire haute :</th><td><input type=text id=lih value="999"></td>';
	$html.='</tr>';
	$html.='<tr><th>Quartier :</th><td colspan="3"><select id=quartier><option value=""></option>';
	while ($row=$quartiers->fetch())
		{
		$html.='<option value="'.$row['id_quartier'].'">'.$row['denomination'].'</option>';
		}
	$html.='</select></td></tr>';	
	$html.='<tr><td colspan="4" class=noborder><input type=button onclick="assocrq();" value="Enregistrer"></td></tr>';
	$html.='</table>';
	$html.='<div id="rep"></div>';
	$this->afficheHtml($html);
	}
	
	
//----------------------------//
//ASSOCIATION QUARTIER ANTENNE//
//----------------------------//
	
public function assocqa($antennes,$quartiers){
	$html='<h3>Associer un quartier à une antenne de quartier</h3>';
	$html.='<h4>Choix de l\'antenne</h4>';
	$html.='<table>';
	$html.='<tr><th width=48%>Quartier à associer :</th><td><select id=quartier onChange=infosAntenne();><option value=""></option>';
	while ($row=$quartiers->fetch()){
		$html.='<option value='.$row['id_quartier'].'>'.$row['denomination'].'</option>';
		}
	$html.='</td></tr>';
	$html.='<tr><th width=48%>Antenne :</th><td><select id=antenne><option value=""></option>';
	while ($row=$antennes->fetch()){
		$html.='<option value='.$row['id_antenne'].'>'.$row['denomination'].'</option>';
		}
	$html.='</td></tr>';
	$html.='<tr><td colspan="2" class=noborder><input type=button onclick=assocqa(); value="Enregistrer"></td></tr>';
	$html.='</table>';
	$html.='<div id="rep"></div>';
	$this->afficheHtml($html);
	}
	
//------------------------------------------//
// GENERATION DES FORMULAIRES DE RECHERCHES //
//------------------------------------------//	
	
public function formSearch($type,$rues,$agents,$antennes,$quartiers){
	$html='<h3>Rechercher</h3>';
	switch ($type){
		case 'rue':
			$html.=$this->formSearchRue($rues);
			break;
			
		case 'agent':
			$html=$this->formSearchAgent($agents);
			break;
			
		case 'antenne':
			$html=$this->formSearchAntenne($antennes);
			break;
		}
	$html.='<div id=rep></div>';
	$this->afficheHtml($html);
	}
	
private function formSearchRue($rues){
	$html='<h4>Sur base de la rue</h4>';
	$html.='<table>';
	$html.='<tr><th>Rue concernée :</th><td><select name=info id=info onChange=showInfosBy("rue");><option value=""></option>';
	while ($row=$rues->fetch()){
		$html.='<option value='.$row['IdRue'].'>'.$row['NomRue'].'</option>';
		}
	$html.='</td></tr>';
	$html.='</table>';
	return $html;
	}
	
private function formSearchAgent($agents){
	$html='<h4>Sur base de l\'agent de quartier</h4>';
	$html.='<table>';
	$html.='<tr><th>Agent concerné :</th><td><select name=info id=info onChange=showInfosBy("agent");><option value=""></option>';
	while ($row=$agents->fetch()){
		$html.='<option value='.$row['id_user'].'>'.$row['nom'].' '.$row['prenom'].'</option>';
		}
	$html.='</td></tr>';
	$html.='</table>';
	return $html;	
	}
	
private function formSearchAntenne($antennes){
	$html='<h4>Sur base de l\'antenne</h4>';
	$html.='<table>';
	$html.='<tr><th>Antenne concernée :</th><td><select name=info id=info onChange=showInfosBy("antenne");><option value=""></option>';
	while ($row=$antennes->fetch()){
		$html.='<option value='.$row['id_antenne'].'>'.$row['denomination'].'</option>';
		}
	$html.='</td></tr>';
	$html.='</table>';
	return $html;	
	}

//--------------//
//FIN RECHERCHES//
//--------------//
}
?>