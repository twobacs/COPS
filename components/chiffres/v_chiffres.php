<?php

class VChiffres extends VBase {

    function __construct($appli, $model) {
        parent::__construct($appli, $model);
    }
	
public function nonCo()
		{
		$this->appli->ctContent='Vous n\'êtes pas connecté ou votre session a expiré.  Veuillez vous (re)connecter.';
		}
		
public function afficheHtml($html)
	{
	$this->appli->ctContent='<div id="desChiffres">'.$html.'</div>';
	$this->appli->jScript= '<script type="text/javascript" src="./js/chiffres.js"></script>';
	}
	
private function datefr($date) 
	{
	$split2 = explode("-",$date);	
	$annee = $split2[0];
    $mois = $split2[1];
    $jour = $split2[2];

		return $jour."-".$mois."-".$annee;
	}

private function datehfr($date,$h=0)
	{
    $split = explode(" ",$date);
    $jour = $split[0];
	$heure = ($h==0) ? '' : $split[1];

	$split2 = explode("-",$jour);
	$annee = $split2[0];
    $mois = $split2[1];
    $jour = $split2[2];

	$return = ($h==0) ? $jour."-".$mois."-".$annee : $jour."-".$mois."-".$annee." à ".$heure;

	return $return;
	}

function cleanCaracteresSpeciaux ($chaine)
{
	setlocale(LC_ALL, 'fr_FR');

	$chaine = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $chaine);

	$chaine = preg_replace('#[^0-9a-z]+#i', '-', $chaine);

	while(strpos($chaine, '--') !== false)
	{
		$chaine = str_replace('--', '-', $chaine);
	}

	$chaine = trim($chaine, '-');

	return $chaine;
}	

public function mainMenu(){
	$html='<h1>Un peu de chiffres</h1>';
	$html.='<h2>Veuillez choisir la requête désirée</h2>';
	$html.='<ul>';
	$html.='<li>Chiffres sur base d\'une d&eacute;nomination d\'&eacute;quipe : <a href="?component=chiffres&action=searchDenom"> Go !</a></li>';
	$html.='<li>Chiffres sur base d\'un indicateur : <a href="?component=chiffres&action=searchIndic"> Go !</a></li>';
	$html.='<li>Chiffres sur base d\'un utilisateur : <a href="?component=chiffres&action=searchUser"> Go !</a></li>';
	$html.='<li>Chiffres sur base d\'une mission avec interaction : <a href="?component=chiffres&action=searchMission&tri=date_debut%20DESC"> Go !</a></li>';
	$html.='</ul>';
	$this->afficheHtml($html);
}

public function zefirst(){
	$html='<h2>Obtenir des chiffres sur base d\'une d&eacute;nomination</h2>';
	$html.='<form method="POST" action="?component=chiffres&action=searchDenom">';
	$html.='<table>';
	$html.='<tr><th colspan="2">Veuillez indiquer la d&eacute;nomination &agrave; rechercher :</th><td colspan="2"><input type="text" name="denomination" id="denomination" autofocus required></td></tr>';
	$html.='<tr><th>Du :</th><td><input type="date" name="DB" id="DB" required></td><th>Au :</th><td><input type="date" name="DH" id="DH" required></td></tr>';
	$html.='<tr><td colspan="4"><input type="submit" value="Chercher"></td></tr>';
	$html.='</table>';
	$html.='</form>';
	$this->afficheHtml($html);
}

public function menuIndicateurs($fonct){
	$html='<h2>Obtenir des chiffres sur base d\'un indicateur</h2>';
	$html.='<form method="POST" action="?component=chiffres&action=searchIndic">';
	$html.='<table>';
	$html.='<tr><th colspan="2">Veuillez choisir une fonctionnalit&eacute; :</th><td colspan="2"><select name="fonctionnalite" id="fonctionnalite" onchange="selectPrest();"><option></option>';
	while($row=$fonct->fetch()){
		$html.='<option value="'.$row['id_fonctionnalite'].'">'.$row['denomination'].'</option>';
	}
	$html.='</select></td></tr>';
	$html.='<tr><th colspan="2">Veuillez choisir un type de prestation :</th><td name="prestation" id="prestation" colspan="2"></td></tr>';
	$html.='<tr><th>Du :</th><td><input type="date" name="DB" id="DB" required></td><th>Au :</th><td><input type="date" name="DH" id="DH" required></td></tr>';
	$html.='<tr><td colspan="4"><input type="submit" value="Chercher"></td></tr>';
	$html.='</table>';
	$html.='</form>';	
	$this->afficheHtml($html);
}

public function menuUsers($users){
	$html='<h2>Obtenir des chiffres sur base d\'un utilisateur</h2>';
	$html.='<form method="POST" action="?component=chiffres&action=searchUser">';
	$html.='<table>';
	$html.='<tr><th colspan="2">Veuillez choisir un utilisateur :</th><td colspan="2"><select name="user" id="user"><option></option>';
	while($row=$users->fetch()){
		$html.='<option value="'.$row['id_user'].'">'.strtoupper($row['nom']).' '.$row['prenom'].'</option>';
	}
	$html.='</td></tr>';
	$html.='<tr><th>Du :</th><td><input type="date" name="DB" id="DB" required></td><th>Au :</th><td><input type="date" name="DH" id="DH" required></td></tr>';
	$html.='<tr><td colspan="4"><input type="submit" value="Chercher"></td></tr>';	
	$html.='</table>';
	$html.='</form>';	
	$this->afficheHtml($html);	
}

public function showResult($result,$type){
	$html='<h2>R&eacute;sultat de votre recherche</h2>';
	$html.='<div id="resultzefirst">';
	$html.='Vous avez recherch&eacute; ';
	if($type=='denom'){
		$html.='"'.$result['denomination'].'" (d&eacute;nomination &eacute;quipe)';
	}
	else if($type=='indic'){
		$html.='"'.$result['fonctionnalite'].' - '.$result['prestation'].'" (indicateurs de prestations)';
	}
	else if($type=='user'){
		$html.='les activit&eacute;s de '.strtoupper($result['nom']).' '.$result['prenom'];
	}
	$html.=', entre le '.$this->datefr($result['debut']).' et le '.$this->datefr($result['fin'],1).'.<br />';
	if($result['error']==0){
	$html.='Cette recherche a retourné <b>'.$result['nbPatrouilles'].'</b> r&eacute;sultats r&eacute;partis comme suit : <br />';
	$html.='<table>';
	$html.='<tr><th colspan="3" id="titrezefirst">ACTIVITE GENERALE</th></tr>';
	$html.='<tr><th id="intituleZefirst">Intitul&eacute;</th><th id="intituleZefirst">Nombre total</th><th id="intituleZefirst">Moyenne par &eacute;quipe</th></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de personne contr&ocirc;l&eacute;es :</th><td>'.$result['ctrlPers'].'</td><td>'.round($result['ctrlPers']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de  v&eacute;hicules contr&ocirc;l&eacute;s:</th><td>'.$result['ctrlVV'].'</td><td>'.round($result['ctrlVV']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de v&eacute;hicules fouill&eacute;s :</th><td>'.$result['vvFouille'].'</td><td>'.round($result['vvFouille']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre d\'arrestations administratives :</th><td>'.$result['arrestAdm'].'</td><td>'.round($result['arrestAdm']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre d\'arrestations judiciaires :</th><td>'.$result['arrestJud'].'</td><td>'.round($result['arrestJud']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="titrezefirst" colspan="3">ACTIVITE JUDICIAIRE</th></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre d\'ordonnances de capture :</th><td>'.$result['OC'].'</td><td>'.round($result['OC']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre d\'avis d\'identification :</th><td>'.$result['AI'].'</td><td>'.round($result['AI']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de PV armes</th><td>'.$result['pvArmes'].'</td><td>'.round($result['pvArmes']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de PV ivresse</th><td>'.$result['pvIvresse'].'</td><td>'.round($result['pvIvresse']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de PV diff&eacute;rends familiaux sans coups :</th><td>'.$result['pvDiffFamssCoups'].'</td><td>'.round($result['pvDiffFamssCoups']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de PV tapage particulier :</th><td>'.$result['pvTapagePart'].'</td><td>'.round($result['pvTapagePart']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de BCS :</th><td>'.$result['BCS'].'</td><td>'.round($result['BCS']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de PV RGP :</th><td>'.$result['pvRgp'].'</td><td>'.round($result['pvRgp']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de PV stups :</th><td>'.$result['pvStups'].'</td><td>'.round($result['pvStups']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de PV coups :</th><td>'.$result['pvCoups'].'</td><td>'.round($result['pvCoups']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de PV diff&eacute;rrends familiaux avec coups :</th><td>'.$result['pvDiffFamAvecCoups'].'</td><td>'.round($result['pvDiffFamAvecCoups']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de PV tapage &eacute;tablissement :</th><td>'.$result['pvTapageEts'].'</td><td>'.round($result['pvTapageEts']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de PV fugues / disparitions :</th><td>'.$result['fuguesDisp'].'</td><td>'.round($result['fuguesDisp']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de PV r&eacute;bellion :</th><td>'.$result['rebellion'].'</td><td>'.round($result['rebellion']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de PV outrages :</th><td>'.$result['pvOutrages'].'</td><td>'.round($result['pvOutrages']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de PV vol :</th><td>'.$result['pvVol'].'</td><td>'.round($result['pvVol']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de PV d&eacute;gradations :</th><td>'.$result['pvDegradations'].'</td><td>'.round($result['pvDegradations']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de PV autres :</th><td>'.$result['pvJudAutres'].'</td><td>'.round($result['pvJudAutres']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th colspan="3" id="titrezefirst">ACTIVITE ROULAGE</th></tr>';
	$html.='<tr><th colspan="3" id="sstitrezefirst">ACCIDENTS</th></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de PV accident :</th><td>'.$result['pvAccident'].'</td><td>'.round($result['pvAccident']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de constats amiables :</th><td>'.$result['amiable'].'</td><td>'.round($result['amiable']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th colspan="3" id="sstitrezefirst">VITESSE</th></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de v&eacute;hicules contr&ocirc;l&eacute;s :</th><td>'.$result['vitVvCtrl'].'</td><td>'.round($result['vitVvCtrl']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de :</th><td>'.$result['vitVvCtrl'].'</td><td>'.round($result['vitVvCtrl']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de PV / PI :</th><td>'.$result['vitPVPI'].'</td><td>'.round($result['vitPVPI']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de retraits :</th><td>'.$result['vitRetraits'].'</td><td>'.round($result['vitRetraits']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th colspan="3" id="sstitrezefirst">PV roulage</th></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de d&eacute;faut d\'assurance :</th><td>'.$result['defAss'].'</td><td>'.round($result['defAss']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de d&eacute;faut d\'immatriculation :</th><td>'.$result['defImm'].'</td><td>'.round($result['defImm']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de d&eacute;faut CT :</th><td>'.$result['defCT'].'</td><td>'.round($result['defCT']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de d&eacute;faut d\'ass + imm + CT :</th><td>'.$result['defAssImCT'].'</td><td>'.round($result['defAssImCT']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de d&eacute;faut ass + imm :</th><td>'.$result['defAssIm'].'</td><td>'.round($result['defAssIm']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de d&eacute;faut imm + CT :</th><td>'.$result['defImmCT'].'</td><td>'.round($result['defImmCT']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de d&eacute;faut de PC :</th><td>'.$result['defPC'].'</td><td>'.round($result['defPC']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de PV roulage autre :</th><td>'.$result['PVRoulAutre'].'</td><td>'.round($result['PVRoulAutre']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th colspan="3" id="sstitrezefirst">PVA</th></tr>';
	$html.='<tr><th id="intituleZefirst">Assurance :</th><td>'.$result['pvaAssur'].'</td><td>'.round($result['pvaAssur']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">PC :</th><td>'.$result['pvaPC'].'</td><td>'.round($result['pvaPC']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">CI :</th><td>'.$result['pvaCI'].'</td><td>'.round($result['pvaCI']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Extincteur - Triangle - Bo&icirc;te S :</th><td>'.$result['pvaExtTrBoite'].'</td><td>'.round($result['pvaExtTrBoite']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Pneus :</th><td>'.$result['pvaPneus'].'</td><td>'.round($result['pvaPneus']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">CT :</th><td>'.$result['pvaCT'].'</td><td>'.round($result['pvaCT']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Immatriculation :</th><td>'.$result['pvaIm'].'</td><td>'.round($result['pvaIm']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">PVA autre :</th><td>'.$result['PVAAutre'].'</td><td>'.round($result['PVAAutre']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th colspan="3" id="sstitrezefirst">PV CYCLO</th></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de cyclos contr&ocirc;l&eacute;s :</th><td>'.$result['cycloNbCtrl'].'</td><td>'.round($result['cycloNbCtrl']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">PVA cyclos non conformes :</th><td>'.$result['cycloNonConforme'].'</td><td>'.round($result['cycloNonConforme']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">PV vit non conformes + d&eacute;faut d\'assurance :</th><td>'.$result['cycloVitNCDefAss'].'</td><td>'.round($result['cycloVitNCDefAss']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">PV simple d&eacute;faut assurance :</th><td>'.$result['cycloDefAss'].'</td><td>'.round($result['cycloDefAss']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Plaque jaune :</th><td>'.$result['cycloPlaqueJaune'].'</td><td>'.round($result['cycloPlaqueJaune']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">PV autres :</th><td>'.$result['cycloAutres'].'</td><td>'.round($result['cycloAutres']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Enl&egrave;vements - saisies :</th><td>'.$result['cycloEnlSaisies'].'</td><td>'.round($result['cycloEnlSaisies']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th colspan="3" id="sstitrezefirst">PI / PV</th></tr>';
	$html.='<tr><th id="intituleZefirst">Trottoir :</th><td>'.$result['pipvTrottoir'].'</td><td>'.round($result['pipvTrottoir']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Zone chargement :</th><td>'.$result['pipvZChargt'].'</td><td>'.round($result['pipvZChargt']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Bande : Arr&ecirc;t bus :</th><td>'.$result['pipvBus'].'</td><td>'.round($result['pipvBus']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">PMR :</th><td>'.$result['pipvPMR'].'</td><td>'.round($result['pipvPMR']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Piste cyclable :</th><td>'.$result['pipvPisteCycl'].'</td><td>'.round($result['pipvPisteCycl']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Passage pi&eacute;tons :</th><td>'.$result['pipvPassPietons'].'</td><td>'.round($result['pipvPassPietons']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">E1 :</th><td>'.$result['pipvE1'].'</td><td>'.round($result['pipvE1']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">E3 :</th><td>'.$result['pipvE3'].'</td><td>'.round($result['pipvE3']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">GSM :</th><td>'.$result['pipvGSM'].'</td><td>'.round($result['pipvGSM']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Ceinture :</th><td>'.$result['pipvCeinture'].'</td><td>'.round($result['pipvCeinture']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Casque :</th><td>'.$result['pipvCasque'].'</td><td>'.round($result['pipvCasque']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">C1 :</th><td>'.$result['pipvC1'].'</td><td>'.round($result['pipvC1']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Stop :</th><td>'.$result['pipvStop'].'</td><td>'.round($result['pipvStop']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Feu orange :</th><td>'.$result['pipvOrange'].'</td><td>'.round($result['pipvOrange']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Feu rouge :</th><td>'.$result['pipvRouge'].'</td><td>'.round($result['pipvRouge']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">G&ecirc;nant :</th><td>'.$result['pipvGenant'].'</td><td>'.round($result['pipvGenant']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">PI / PV autre :</th><td>'.$result['pipvAutre'].'</td><td>'.round($result['pipvAutre']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th colspan="3" id="sstitrezefirst">ALCO</th></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de v&eacute;hicules contr&ocirc;l&eacute;s :</th><td>'.$result['alcoVVCtrl'].'</td><td>'.round($result['alcoVVCtrl']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de personnes contr&ocirc;l&eacute;es :</th><td>'.$result['alcoPersCtrl'].'</td><td>'.round($result['alcoPersCtrl']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de "A" :</th><td>'.$result['alcoA'].'</td><td>'.round($result['alcoA']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de "P" :</th><td>'.$result['alcoP'].'</td><td>'.round($result['alcoP']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de retraits :</th><td>'.$result['alcoRetraits'].'</td><td>'.round($result['alcoRetraits']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de prise de sang :</th><td>'.$result['alcoPds'].'</td><td>'.round($result['alcoPds']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de PV stups :</th><td>'.$result['alcoStups'].'</td><td>'.round($result['alcoStups']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Suite accident :</th><td>'.$result['alcoSuiteAcc'].'</td><td>'.round($result['alcoSuiteAcc']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th colspan="3" id="sstitrezefirst">PI POIDS LOURDS</th></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de PL contr&ocirc;l&eacute;s :</th><td>'.$result['plNbrCtrl'].'</td><td>'.round($result['plNbrCtrl']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de :</th><td>'.$result['plNbrCtrl'].'</td><td>'.round($result['plNbrCtrl']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de PI :</th><td>'.$result['plPI'].'</td><td>'.round($result['plPI']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de PV :</th><td>'.$result['plPV'].'</td><td>'.round($result['plPV']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre d\' ADR contr&ocirc;l&eacute;s :</th><td>'.$result['plNbrAdr'].'</td><td>'.round($result['plNbrAdr']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de PI ADR :</th><td>'.$result['plPIAdr'].'</td><td>'.round($result['plPIAdr']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='<tr><th id="intituleZefirst">Nombre de PV ADR :</th><td>'.$result['plPVAdr'].'</td><td>'.round($result['plPVAdr']/$result['nbPatrouilles'], 2).'</td></tr>';
	$html.='</table>';
	}
	else $html.='Cette recherche n\'a retourné aucun r&eacute;sultat.';
	$html.='<br /><a href="?component=chiffres&action=mainMenu">Retour</a> &agrave; l\'&eacute;cran de choix de requ&ecirc;te.<br />';
	$html.='<a href="?component=chiffres&action=exportCSV&type='.$type.'&param='.$result['denomination'].'&DB='.$result['debut'].'&DH='.$result['fin'].'">Exporter ce r&eacute;sultat en CSV</a>';
	$html.='</div>';
	
	$this->afficheHtml($html);
}

public function afficheCSV($data,$type){
	$this->appli->tplIndex = 'indexCSV.html';
	
	if($type=='denom'){
		$fichier=$data['denomination'];
	}
	else if($type=='indic'){
		$fichier=$data['fonctionnalite'].'-'.$data['prestation'];
	}
	else if($type=='user'){
		$fichier=$this->cleanCaracteresSpeciaux($data['nom']).'-'.$this->cleanCaracteresSpeciaux($data['prenom']);
	}
	$fichier.='_'.$data['debut'].'_'.$data['fin'];
	
	header("Content-Type: text/csv; charset=UTF-8");
	header("Content-disposition: filename=".$fichier.".csv");
	// Création de la ligne d'en-tête
	$entete = array("Intitule", "Nombre total", "Moyenne");
	// Création du contenu du tableau
	$separateur = ";";
	// Affichage de la ligne de titre, terminée par un retour chariot
	echo implode($separateur, $entete)."\r\n";
	// Affichage du contenu du tableau
	foreach ($data as $v1) {
		echo key($data).';'."$v1".';';
		if (is_numeric($v1)){
			echo round($v1/$data['nbPatrouilles'],2)."\n";
		}
		else echo "\n";
		next($data);
	}
}

public function showInfosByIdFiche($data){
	$mm=0;
	$html='<h2>R&eacute;sultat de votre recherche</h2>';
	$html.='<h3>Texte informatif de la fiche</h3>';
	$html.='<b>'.$data['texteInfo'].'</b>';
	$html.='<h3>D&eacute;tails des r&eacute;sultats</h3>';
	$html.='<table border="1"><tr><th>Indicatif</th><th>De</th><th>&Agrave;</th><th>Membre(s)</th><th>R&eacute;sultat</th></tr>';
	for($i=0;$i<$data['total'];$i++){
		if(isset($data[$i]['indicatif'])){
			$html.='<td>'.$data[$i]['indicatif'].'</td><td>'.$this->datehfr($data[$i]['dhb'],1).'</td><td>'.$this->datehfr($data[$i]['dhf'],1).'</td><td>';
				for($j=0;$j<$data[$i]['equipiers'];$j++){
					if($j!=0){
						$html.=' - ';
					}
					$html.=$data[$i]['nom'][$j].' '.$data[$i]['prenom'][$j].' ';
				}
				$html.='</td>';
				$html.='<td>';
				if($data[$i]['date_heure_in']=='0000-00-00 00:00:00'){
					$html.='Non effectu&eacute;';
				}
				else{
					$html.=$data[$i]['commentaire'];
				}
				$html.='</td>';
			$html.='</tr>';
			$mm++;
			}
		}
	$html.='</table>';
	$html.='<h3>Soit un total de ';
	$html.=$mm.' PM </h3>';
	// $html.=$mm;
	// echo '<pre>';
	// var_dump($data);
	// echo '</pre>';
	$html.='<a href="?component=chiffres&action=searchMission">Retour s&eacute;lection fiche</a>';
	$this->afficheHtml($html);
}

public function showMissions($data){
	$tri=$_GET['tri'];
	$zetri='<a href=?component=chiffres&action=searchMission&tri=';
	$html='<h2>Veuillez choisir une mission</h2>';
	$html.='<table border="1"><tr><th>';
	$zetri.=($tri=='dateHr_encodage DESC') ? 'dateHr_encodage%20ASC>' : 'dateHr_encodage%20DESC>';
	$html.=$zetri;
	$zetri='<a href=?component=chiffres&action=searchMission&tri=';
	$html.='Date d\'encodage</a></th><th>';
	$zetri.=($tri=='date_debut DESC') ? 'date_debut%20ASC>' : 'date_debut%20DESC>';
	$html.=$zetri;
	$html.='Date d&eacute;but</a></th><th>';
	$zetri='<a href=?component=chiffres&action=searchMission&tri=';
	$zetri.=($tri=='date_fin DESC') ? 'date_fin%20ASC>' : 'date_fin%20DESC>';
	$html.=$zetri;
	$html.='Date Fin</a></th><th>Texte</th></tr>';
	foreach($data as $key => $row){
		$html.='<td width="15%">'.$row['dateHr_encodage'].'</td><td width="15%">'.$this->datehfr($row['date_debut'],1).'</td><td width="15%">'.$this->datehfr($row['date_fin'],1).'</td><td><a href="?component=chiffres&action=getInfoByIdFiche&key='.$row['id_fiche'].'">'.$row['texteInfo'].'</a></td></tr>';
	}
	$html.='</table>';
	$html.=sizeof($data);
	$this->afficheHtml($html);
}
}
?>