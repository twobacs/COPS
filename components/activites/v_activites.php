<?php

class VActivites extends VBase {

    function __construct($appli, $model) {
        parent::__construct($appli, $model);
    }
	
public function nonCo()
		{
		$this->appli->ctContent='Vous n\'êtes pas connecté ou votre session a expiré.  Veuillez vous (re)connecter.';
		}
		
public function afficheHtml($html)
	{
	$this->appli->ctContent=$html;
	// $this->appli->jScript= '<script type="text/javascript" src="./js/missions.js"></script>';
	$this->appli->jScript='<script type="text/javascript" src="./js/activites.js"></script>';
	}
	
private function datefr($date,$dateOnly=0) 
	{
    $split = explode(" ",$date);
    $jour = $split[0];
	if(isset($split[1]))
		{
		$heure = $split[1];
		}
	
	$split2 = explode("-",$jour);	
	$annee = $split2[0];
    $mois = $split2[1];
    $jour = $split2[2];
	
	if($dateOnly==1)
		{
		return $jour."-".$mois."-".$annee;
		}
		
	else if($dateOnly==2)
		{
		return $heure;
		}
	else
		{
		return $jour."-".$mois."-".$annee.' à '.$heure;
		}
	}	
		
public function mainMenu()
	{
	$plus='<img src="./media/icons/ajouter.png" height="25%">';
	$moins='<img src="./media/icons/retirer.png" height="25%">';
	$html='<div  id="tabActivites">';	
	$html.='<h2>Mes activités</h2>';
	
	$html.='<a href="#act_gen">Générale</a> - <a href="#act_judi">Judiciaire</a> - <a href="#act_roulage">Roulage</a> - <a href="#accidents">Accident</a> - <a href="#vitesse">Vitesse</a> - <a href="#pv_roul">PV roulage</a> - <a href="#pva">PVA</a> - <a href="#pv_cyclo">PV cyclo</a> - <a href="#PIPV">PI / PV</a> - <a href="#alco">Alco</a> - <a href="#PIPL">PI poids lourds</a>';
	

	$plusJs='<img src="./media/icons/ajouter.png" height="25%" onclick="modifyEntity(\''.$_SESSION['idbs'].'\',';
	$moinsJs='<img src="./media/icons/retirer.png" height="25%" onclick="modifyEntity(\''.$_SESSION['idbs'].'\',';


	//ACTIVITE GENERALE
	$html.='<table>';
	$html.='<tr><th colspan="4" class="titre" id="act_gen">ACTIVITE GENERALE</th></tr>'; 	
	
	$value=(isset($_SESSION['ctrlPers'])) ? $_SESSION['ctrlPers'] : '0';
	$html.='<tr>
	<th width="30%">Personnes contrôlées</th>
	<td width="30%">'.$plusJs.'\'ctrlPers\',\'add\');"></td>
	<td id="ctrlPers">'.$value.'</td>
	<td width="30%">'.$moinsJs.'\'ctrlPers\',\'rem\');"></td>
	</tr>';
	
	$value=(isset($_SESSION['ctrlVV'])) ? $_SESSION['ctrlVV'] : '0';
	$html.='<tr>
	<th width="30%">Véhicules contrôlés</th>
	<td width="30%">'.$plusJs.'\'ctrlVV\',\'add\');"></td>
	<td id="ctrlVV"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'ctrlVV\',\'rem\');"></td>
	</tr>';
	
	$value=(isset($_SESSION['vvFouille'])) ? $_SESSION['vvFouille'] : '0';
	$html.='<tr>
	<th width="30%">Véhicules fouillés</th>
	<td width="30%">'.$plusJs.'\'vvFouille\',\'add\');"></td>
	<td id="vvFouille"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'vvFouille\',\'rem\');"></td>
	</tr>';		

	$value=(isset($_SESSION['arrestAdm'])) ? $_SESSION['arrestAdm'] : '0';
	$html.='<tr>
	<th width="30%">Arrestations administratives</th>
	<td width="30%">'.$plusJs.'\'arrestAdm\',\'add\');"></td>
	<td id="arrestAdm"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'arrestAdm\',\'rem\');"></td>
	</tr>';	
	
	$value=(isset($_SESSION['arrestJud'])) ? $_SESSION['arrestJud'] : '0';
	$html.='<tr>
	<th width="30%">Arrestations judiciaires</th>
	<td width="30%">'.$plusJs.'\'arrestJud\',\'add\');"></td>
	<td id="arrestJud"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'arrestJud\',\'rem\');"></td>
	</tr>';	
	
	$html.='</table>';
	
	//ACTIVITE JUDICIAIRE
	$html.='<table>';
	$html.='<tr><th colspan="4" class="titre" id="act_judi">ACTIVITE JUDICIAIRE</th></tr>';
	
	$value=(isset($_SESSION['OC'])) ? $_SESSION['OC'] : '0';
	$html.='<tr>
	<th width="30%">Nbr d\'ordonnances de capture</th>
	<td width="30%">'.$plusJs.'\'OC\',\'add\');"></td>
	<td id="OC"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'OC\',\'rem\');"></td>
	</tr>';
	
	$value=(isset($_SESSION['AI'])) ? $_SESSION['AI'] : '0';
	$html.='<tr>
	<th width="30%">Nbr d\'avis d\'identification</th>
	<td width="30%">'.$plusJs.'\'AI\',\'add\');"></td>
	<td id="AI"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'AI\',\'rem\');"></td>
	</tr>';
	
	$value=(isset($_SESSION['pvArmes'])) ? $_SESSION['pvArmes'] : '0';
	$html.='<tr>
	<th width="30%">Nbr PV Armes</th>
	<td width="30%">'.$plusJs.'\'pvArmes\',\'add\');"></td>
	<td id="pvArmes"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'pvArmes\',\'rem\');"></td>
	</tr>';	
	
	$value=(isset($_SESSION['pvIvresse'])) ? $_SESSION['pvIvresse'] : '0';
	$html.='<tr>
	<th width="30%">Nbr PV ivresse</th>
	<td width="30%">'.$plusJs.'\'pvIvresse\',\'add\');"></td>
	<td id="pvIvresse"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'pvIvresse\',\'rem\');"></td>
	</tr>';		

	$value=(isset($_SESSION['pvDiffFamssCoups'])) ? $_SESSION['pvDiffFamssCoups'] : '0';
	$html.='<tr>
	<th width="30%">Nbr PV Différends familiaux sans coups</th>
	<td width="30%">'.$plusJs.'\'pvDiffFamssCoups\',\'add\');"></td>
	<td id="pvDiffFamssCoups"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'pvDiffFamssCoups\',\'rem\');"></td>
	</tr>';		

	$value=(isset($_SESSION['pvTapagePart'])) ? $_SESSION['pvTapagePart'] : '0';
	$html.='<tr>
	<th width="30%">Nbr PV tapage part.</th>
	<td width="30%">'.$plusJs.'\'pvTapagePart\',\'add\');"></td>
	<td id="pvTapagePart"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'pvTapagePart\',\'rem\');"></td>
	</tr>';	
	
	$value=(isset($_SESSION['BCS'])) ? $_SESSION['BCS'] : '0';
	$html.='<tr>
	<th width="30%">Nbr BCS</th>
	<td width="30%">'.$plusJs.'\'BCS\',\'add\');"></td>
	<td id="BCS"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'BCS\',\'rem\');"></td>
	</tr>';

	$value=(isset($_SESSION['pvRgp'])) ? $_SESSION['pvRgp'] : '0';
	$html.='<tr>
	<th width="30%">Nbr PV RGP</th>
	<td width="30%">'.$plusJs.'\'pvRgp\',\'add\');"></td>
	<td id="pvRgp"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'pvRgp\',\'rem\');"></td>
	</tr>';
	
	$value=(isset($_SESSION['pvStups'])) ? $_SESSION['pvStups'] : '0';
	$html.='<tr>
	<th width="30%">Nbr PV Stups</th>
	<td width="30%">'.$plusJs.'\'pvStups\',\'add\');"></td>
	<td id="pvStups"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'pvStups\',\'rem\');"></td>
	</tr>';
	
	$value=(isset($_SESSION['pvCoups'])) ? $_SESSION['pvCoups'] : '0';
	$html.='<tr>
	<th width="30%">Nbr PV Coups</th>
	<td width="30%">'.$plusJs.'\'pvCoups\',\'add\');"></td>
	<td id="pvCoups"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'pvCoups\',\'rem\');"></td>
	</tr>';
	
	$value=(isset($_SESSION['pvDiffFamAvecCoups'])) ? $_SESSION['pvDiffFamAvecCoups'] : '0';
	$html.='<tr>
	<th width="30%">Nbr PV Différends familiaux avec coups</th>
	<td width="30%">'.$plusJs.'\'pvDiffFamAvecCoups\',\'add\');"></td>
	<td id="pvDiffFamAvecCoups"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'pvDiffFamAvecCoups\',\'rem\');"></td>
	</tr>';
	
	$value=(isset($_SESSION['pvTapageEts'])) ? $_SESSION['pvTapageEts'] : '0';
	$html.='<tr>
	<th width="30%">Nbr PV tapage établissement</th>
	<td width="30%">'.$plusJs.'\'pvTapageEts\',\'add\');"></td>
	<td id="pvTapageEts"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'pvTapageEts\',\'rem\');"></td>
	</tr>';	
	
	$value=(isset($_SESSION['fuguesDisp'])) ? $_SESSION['fuguesDisp'] : '0';
	$html.='<tr>
	<th width="30%">Nbr PV Fugues / Disparition</th>
	<td width="30%">'.$plusJs.'\'fuguesDisp\',\'add\');"></td>
	<td id="fuguesDisp"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'fuguesDisp\',\'rem\');"></td>
	</tr>';	
	
	$value=(isset($_SESSION['rebellion'])) ? $_SESSION['rebellion'] : '0';
	$html.='<tr>
	<th width="30%">Nbr PV Rébellion</th>
	<td width="30%">'.$plusJs.'\'rebellion\',\'add\');"></td>
	<td id="rebellion"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'rebellion\',\'rem\');"></td>
	</tr>';	
	
	$value=(isset($_SESSION['pvOutrages'])) ? $_SESSION['pvOutrages'] : '0';
	$html.='<tr>
	<th width="30%">Nbr PV Outrages</th>
	<td width="30%">'.$plusJs.'\'pvOutrages\',\'add\');"></td>
	<td id="pvOutrages"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'pvOutrages\',\'rem\');"></td>
	</tr>';

	$value=(isset($_SESSION['pvVol'])) ? $_SESSION['pvVol'] : '0';
	$html.='<tr>
	<th width="30%">Nbr PV Vol</th>
	<td width="30%">'.$plusJs.'\'pvVol\',\'add\');"></td>
	<td id="pvVol"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'pvVol\',\'rem\');"></td>
	</tr>';	

	$value=(isset($_SESSION['pvDegradations'])) ? $_SESSION['pvDegradations'] : '0';
	$html.='<tr>
	<th width="30%">Nbr PV Dégradations</th>
	<td width="30%">'.$plusJs.'\'pvDegradations\',\'add\');"></td>
	<td id="pvDegradations"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'pvDegradations\',\'rem\');"></td>
	</tr>';	

	$value=(isset($_SESSION['pvJudAutres'])) ? $_SESSION['pvJudAutres'] : '0';
	$html.='<tr>
	<th width="30%">Nbr PV autres</th>
	<td width="30%">'.$plusJs.'\'pvJudAutres\',\'add\');"></td>
	<td id="pvJudAutres"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'pvJudAutres\',\'rem\');"></td>
	</tr>';			
	
	$html.='</table>';

	//ACTIVITE ROULAGE
	$html.='<table>';
	$html.='<tr><th colspan="4" class="titre" id="act_roulage">ACTIVITE ROULAGE</th></tr>';
	
	$html.='<tr><th colspan="4" class="sstitre" id="accidents">ACCIDENTS</th></tr>';
	
	$value=(isset($_SESSION['pvAccident'])) ? $_SESSION['pvAccident'] : '0';
	$html.='<tr>
	<th width="30%">Nbr PV accident</th>
	<td width="30%">'.$plusJs.'\'pvAccident\',\'add\');"></td>
	<td id="pvAccident"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'pvAccident\',\'rem\');"></td>
	</tr>';

	$value=(isset($_SESSION['amiable'])) ? $_SESSION['amiable'] : '0';
	$html.='<tr>
	<th width="30%">Nbr Constats Amiables</th>
	<td width="30%">'.$plusJs.'\'amiable\',\'add\');"></td>
	<td id="amiable"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'amiable\',\'rem\');"></td>
	</tr>';	
	
	$html.='<tr><th colspan="4" class="sstitre" id="vitesse">VITESSE</th></tr>';
	
	$value=(isset($_SESSION['vitVvCtrl'])) ? $_SESSION['vitVvCtrl'] : '0';
	$html.='<tr>
	<th width="30%">Nbr véhicules contrôlés</th>
	<td width="30%">'.$plusJs.'\'vitVvCtrl\',\'add\');"></td>
	<td id="vitVvCtrl"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'vitVvCtrl\',\'rem\');"></td>
	</tr>';
	
	$value=(isset($_SESSION['vitPVPI'])) ? $_SESSION['vitPVPI'] : '0';
	$html.='<tr>
	<th width="30%">Nbr PV / PI</th>
	<td width="30%">'.$plusJs.'\'vitPVPI\',\'add\');"></td>
	<td id="vitPVPI"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'vitPVPI\',\'rem\');"></td>
	</tr>';
	
	$value=(isset($_SESSION['vitRetraits'])) ? $_SESSION['vitRetraits'] : '0';
	$html.='<tr>
	<th width="30%">Nbr retraits</th>
	<td width="30%">'.$plusJs.'\'vitRetraits\',\'add\');"></td>
	<td id="vitRetraits"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'vitRetraits\',\'rem\');"></td>
	</tr>';	
	
	$html.='<tr><th colspan="4" class="sstitre" id="pv_roul">PV roulage</th></tr>';
	
	$value=(isset($_SESSION['defAss'])) ? $_SESSION['defAss'] : '0';
	$html.='<tr>
	<th width="30%">Défaut d\'assurance</th>
	<td width="30%">'.$plusJs.'\'defAss\',\'add\');"></td>
	<td id="defAss"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'defAss\',\'rem\');"></td>
	</tr>';	
	
	$value=(isset($_SESSION['defImm'])) ? $_SESSION['defImm'] : '0';
	$html.='<tr>
	<th width="30%">Défaut d\'immatriculation</th>
	<td width="30%">'.$plusJs.'\'defImm\',\'add\');"></td>
	<td id="defImm"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'defImm\',\'rem\');"></td>
	</tr>';	
	
	$value=(isset($_SESSION['defCT'])) ? $_SESSION['defCT'] : '0';
	$html.='<tr>
	<th width="30%">Défaut CT</th>
	<td width="30%">'.$plusJs.'\'defCT\',\'add\');"></td>
	<td id="defCT"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'defCT\',\'rem\');"></td>
	</tr>';	
	
	$value=(isset($_SESSION['defAssImCT'])) ? $_SESSION['defAssImCT'] : '0';
	$html.='<tr>
	<th width="30%">Défaut Ass + im + CT</th>
	<td width="30%">'.$plusJs.'\'defAssImCT\',\'add\');"></td>
	<td id="defAssImCT"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'defAssImCT\',\'rem\');"></td>
	</tr>';	
	
	$value=(isset($_SESSION['defAssIm'])) ? $_SESSION['defAssIm'] : '0';
	$html.='<tr>
	<th width="30%">Défaut Ass + imm</th>
	<td width="30%">'.$plusJs.'\'defAssIm\',\'add\');"></td>
	<td id="defAssIm"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'defAssIm\',\'rem\');"></td>
	</tr>';
	
	$value=(isset($_SESSION['defImmCT'])) ? $_SESSION['defImmCT'] : '0';
	$html.='<tr>
	<th width="30%">Imm + CT</th>
	<td width="30%">'.$plusJs.'\'defImmCT\',\'add\');"></td>
	<td id="defImmCT"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'defImmCT\',\'rem\');"></td>
	</tr>';
	
	$value=(isset($_SESSION['defPC'])) ? $_SESSION['defPC'] : '0';
	$html.='<tr>
	<th width="30%">Défaut PC</th>
	<td width="30%">'.$plusJs.'\'defPC\',\'add\');"></td>
	<td id="defPC"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'defPC\',\'rem\');"></td>
	</tr>';
	
	$value=(isset($_SESSION['PVRoulAutre'])) ? $_SESSION['PVRoulAutre'] : '0';
	$html.='<tr>
	<th width="30%">PV roulage Autre</th>
	<td width="30%">'.$plusJs.'\'PVRoulAutre\',\'add\');"></td>
	<td id="PVRoulAutre"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'PVRoulAutre\',\'rem\');"></td>
	</tr>';
	
	$html.='<tr><th colspan="4" class="sstitre" id="pva">PVA</th></tr>';
	
	$value=(isset($_SESSION['pvaAssur'])) ? $_SESSION['pvaAssur'] : '0';
	$html.='<tr>
	<th width="30%">Assurance</th>
	<td width="30%">'.$plusJs.'\'pvaAssur\',\'add\');"></td>
	<td id="pvaAssur"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'pvaAssur\',\'rem\');"></td>
	</tr>';
	
	$value=(isset($_SESSION['pvaPC'])) ? $_SESSION['pvaPC'] : '0';
	$html.='<tr>
	<th width="30%">PC</th>
	<td width="30%">'.$plusJs.'\'pvaPC\',\'add\');"></td>
	<td id="pvaPC"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'pvaPC\',\'rem\');"></td>
	</tr>';
	
	$value=(isset($_SESSION['pvaCI'])) ? $_SESSION['pvaCI'] : '0';
	$html.='<tr>
	<th width="30%">CI</th>
	<td width="30%">'.$plusJs.'\'pvaCI\',\'add\');"></td>
	<td id="pvaCI"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'pvaCI\',\'rem\');"></td>
	</tr>';	
	
	$value=(isset($_SESSION['pvaExtTrBoite'])) ? $_SESSION['pvaExtTrBoite'] : '0';
	$html.='<tr>
	<th width="30%">Ext - Tr - Boîte s</th>
	<td width="30%">'.$plusJs.'\'pvaExtTrBoite\',\'add\');"></td>
	<td id="pvaExtTrBoite"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'pvaExtTrBoite\',\'rem\');"></td>
	</tr>';	
	
	$value=(isset($_SESSION['pvaPneus'])) ? $_SESSION['pvaPneus'] : '0';
	$html.='<tr>
	<th width="30%">Pneus</th>
	<td width="30%">'.$plusJs.'\'pvaPneus\',\'add\');"></td>
	<td id="pvaPneus"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'pvaPneus\',\'rem\');"></td>
	</tr>';	

	$value=(isset($_SESSION['pvaCT'])) ? $_SESSION['pvaCT'] : '0';
	$html.='<tr>
	<th width="30%">CT</th>
	<td width="30%">'.$plusJs.'\'pvaCT\',\'add\');"></td>
	<td id="pvaCT"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'pvaCT\',\'rem\');"></td>
	</tr>';	
	
	$value=(isset($_SESSION['pvaIm'])) ? $_SESSION['pvaIm'] : '0';
	$html.='<tr>
	<th width="30%">Immat.</th>
	<td width="30%">'.$plusJs.'\'pvaIm\',\'add\');"></td>
	<td id="pvaIm"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'pvaIm\',\'rem\');"></td>
	</tr>';	

	$value=(isset($_SESSION['PVAAutre'])) ? $_SESSION['PVAAutre'] : '0';
	$html.='<tr>
	<th width="30%">PVA Autre</th>
	<td width="30%">'.$plusJs.'\'PVAAutre\',\'add\');"></td>
	<td id="PVAAutre"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'PVAAutre\',\'rem\');"></td>
	</tr>';		
	
	$html.='<tr><th colspan="4" class="sstitre" id="pv_cyclo">PV CYCLO</th></tr>';	
	
	$value=(isset($_SESSION['cycloNbCtrl'])) ? $_SESSION['cycloNbCtrl'] : '0';
	$html.='<tr>
	<th width="30%">Nbr Cyclos contrôlés</th>
	<td width="30%">'.$plusJs.'\'cycloNbCtrl\',\'add\');"></td>
	<td id="cycloNbCtrl"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'cycloNbCtrl\',\'rem\');"></td>
	</tr>';	

	$value=(isset($_SESSION['cycloNonConforme'])) ? $_SESSION['cycloNonConforme'] : '0';
	$html.='<tr>
	<th width="30%">PVA cyclos non conformes</th>
	<td width="30%">'.$plusJs.'\'cycloNonConforme\',\'add\');"></td>
	<td id="cycloNonConforme"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'cycloNonConforme\',\'rem\');"></td>
	</tr>';		

	$value=(isset($_SESSION['cycloVitNCDefAss'])) ? $_SESSION['cycloVitNCDefAss'] : '0';
	$html.='<tr>
	<th width="30%">PV vit non conformes + déf assurance</th>
	<td width="30%">'.$plusJs.'\'cycloVitNCDefAss\',\'add\');"></td>
	<td id="cycloVitNCDefAss"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'cycloVitNCDefAss\',\'rem\');"></td>
	</tr>';	

	$value=(isset($_SESSION['cycloDefAss'])) ? $_SESSION['cycloDefAss'] : '0';
	$html.='<tr>
	<th width="30%">PV simple défaut assurance</th>
	<td width="30%">'.$plusJs.'\'cycloDefAss\',\'add\');"></td>
	<td id="cycloDefAss"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'cycloDefAss\',\'rem\');"></td>
	</tr>';		
	
	$value=(isset($_SESSION['cycloPlaqueJaune'])) ? $_SESSION['cycloPlaqueJaune'] : '0';
	$html.='<tr>
	<th width="30%">Plaque jaune</th>
	<td width="30%">'.$plusJs.'\'cycloPlaqueJaune\',\'add\');"></td>
	<td id="cycloPlaqueJaune"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'cycloPlaqueJaune\',\'rem\');"></td>
	</tr>';	

	$value=(isset($_SESSION['cycloAutres'])) ? $_SESSION['cycloAutres'] : '0';
	$html.='<tr>
	<th width="30%">PV autres</th>
	<td width="30%">'.$plusJs.'\'cycloAutres\',\'add\');"></td>
	<td id="cycloAutres"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'cycloAutres\',\'rem\');"></td>
	</tr>';	

	$value=(isset($_SESSION['cycloEnlSaisies'])) ? $_SESSION['cycloEnlSaisies'] : '0';
	$html.='<tr>
	<th width="30%">Enlèvements - saisies</th>
	<td width="30%">'.$plusJs.'\'cycloEnlSaisies\',\'add\');"></td>
	<td id="cycloEnlSaisies"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'cycloEnlSaisies\',\'rem\');"></td>
	</tr>';	
	
	$html.='<tr><th colspan="4" class="sstitre" id="PIPV">PI / PV</th></tr>';

	$value=(isset($_SESSION['pipvTrottoir'])) ? $_SESSION['pipvTrottoir'] : '0';
	$html.='<tr>
	<th width="30%">Trottoir</th>
	<td width="30%">'.$plusJs.'\'pipvTrottoir\',\'add\');"></td>
	<td id="pipvTrottoir"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'pipvTrottoir\',\'rem\');"></td>
	</tr>';	

	$value=(isset($_SESSION['pipvZChargt'])) ? $_SESSION['pipvZChargt'] : '0';
	$html.='<tr>
	<th width="30%">Zone chargement</th>
	<td width="30%">'.$plusJs.'\'pipvZChargt\',\'add\');"></td>
	<td id="pipvZChargt"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'pipvZChargt\',\'rem\');"></td>
	</tr>';	

	$value=(isset($_SESSION['pipvBus'])) ? $_SESSION['pipvBus'] : '0';
	$html.='<tr>
	<th width="30%">Bande / Arrêt bus</th>
	<td width="30%">'.$plusJs.'\'pipvBus\',\'add\');"></td>
	<td id="pipvBus"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'pipvBus\',\'rem\');"></td>
	</tr>';	

	$value=(isset($_SESSION['pipvPMR'])) ? $_SESSION['pipvPMR'] : '0';
	$html.='<tr>
	<th width="30%">PMR</th>
	<td width="30%">'.$plusJs.'\'pipvPMR\',\'add\');"></td>
	<td id="pipvPMR"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'pipvPMR\',\'rem\');"></td>
	</tr>';	
	
	$value=(isset($_SESSION['pipvPisteCycl'])) ? $_SESSION['pipvPisteCycl'] : '0';
	$html.='<tr>
	<th width="30%">Piste cyclable</th>
	<td width="30%">'.$plusJs.'\'pipvPisteCycl\',\'add\');"></td>
	<td id="pipvPisteCycl"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'pipvPisteCycl\',\'rem\');"></td>
	</tr>';		
	
	$value=(isset($_SESSION['pipvPassPietons'])) ? $_SESSION['pipvPassPietons'] : '0';
	$html.='<tr>
	<th width="30%">Passage piétons</th>
	<td width="30%">'.$plusJs.'\'pipvPassPietons\',\'add\');"></td>
	<td id="pipvPassPietons"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'pipvPassPietons\',\'rem\');"></td>
	</tr>';	

	$value=(isset($_SESSION['pipvE1'])) ? $_SESSION['pipvE1'] : '0';
	$html.='<tr>
	<th width="30%">E1</th>
	<td width="30%">'.$plusJs.'\'pipvE1\',\'add\');"></td>
	<td id="pipvE1"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'pipvE1\',\'rem\');"></td>
	</tr>';		
	
	$value=(isset($_SESSION['pipvE3'])) ? $_SESSION['pipvE3'] : '0';
	$html.='<tr>
	<th width="30%">E3</th>
	<td width="30%">'.$plusJs.'\'pipvE3\',\'add\');"></td>
	<td id="pipvE3"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'pipvE3\',\'rem\');"></td>
	</tr>';		
	
	$value=(isset($_SESSION['pipvGSM'])) ? $_SESSION['pipvGSM'] : '0';
	$html.='<tr>
	<th width="30%">GSM</th>
	<td width="30%">'.$plusJs.'\'pipvGSM\',\'add\');"></td>
	<td id="pipvGSM"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'pipvGSM\',\'rem\');"></td>
	</tr>';	

	$value=(isset($_SESSION['pipvCeinture'])) ? $_SESSION['pipvCeinture'] : '0';
	$html.='<tr>
	<th width="30%">Ceinture</th>
	<td width="30%">'.$plusJs.'\'pipvCeinture\',\'add\');"></td>
	<td id="pipvCeinture"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'pipvCeinture\',\'rem\');"></td>
	</tr>';	

	$value=(isset($_SESSION['pipvCasque'])) ? $_SESSION['pipvCasque'] : '0';
	$html.='<tr>
	<th width="30%">Casque</th>
	<td width="30%">'.$plusJs.'\'pipvCasque\',\'add\');"></td>
	<td id="pipvCasque"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'pipvCasque\',\'rem\');"></td>
	</tr>';	
	
	$value=(isset($_SESSION['pipvC1'])) ? $_SESSION['pipvC1'] : '0';
	$html.='<tr>
	<th width="30%">C1</th>
	<td width="30%">'.$plusJs.'\'pipvC1\',\'add\');"></td>
	<td id="pipvC1"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'pipvC1\',\'rem\');"></td>
	</tr>';		
	
	$value=(isset($_SESSION['pipvStop'])) ? $_SESSION['pipvStop'] : '0';
	$html.='<tr>
	<th width="30%">Stop</th>
	<td width="30%">'.$plusJs.'\'pipvStop\',\'add\');"></td>
	<td id="pipvStop"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'pipvStop\',\'rem\');"></td>
	</tr>';		
	
	$value=(isset($_SESSION['pipvOrange'])) ? $_SESSION['pipvOrange'] : '0';
	$html.='<tr>
	<th width="30%">Feu orange</th>
	<td width="30%">'.$plusJs.'\'pipvOrange\',\'add\');"></td>
	<td id="pipvOrange"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'pipvOrange\',\'rem\');"></td>
	</tr>';		
	
	$value=(isset($_SESSION['pipvRouge'])) ? $_SESSION['pipvRouge'] : '0';
	$html.='<tr>
	<th width="30%">Feu rouge</th>
	<td width="30%">'.$plusJs.'\'pipvRouge\',\'add\');"></td>
	<td id="pipvRouge"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'pipvRouge\',\'rem\');"></td>
	</tr>';	
	
	$value=(isset($_SESSION['pipvGenant'])) ? $_SESSION['pipvGenant'] : '0';
	$html.='<tr>
	<th width="30%">Gênant</th>
	<td width="30%">'.$plusJs.'\'pipvGenant\',\'add\');"></td>
	<td id="pipvGenant"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'pipvGenant\',\'rem\');"></td>
	</tr>';
	
	$value=(isset($_SESSION['pipvAutre'])) ? $_SESSION['pipvAutre'] : '0';
	$html.='<tr>
	<th width="30%">PI / PV autre</th>
	<td width="30%">'.$plusJs.'\'pipvAutre\',\'add\');"></td>
	<td id="pipvAutre"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'pipvAutre\',\'rem\');"></td>
	</tr>';	
	
	$html.='<tr><th colspan="4" class="sstitre" id="alco">ALCO</th></tr>';

	$value=(isset($_SESSION['alcoVVCtrl'])) ? $_SESSION['alcoVVCtrl'] : '0';
	$html.='<tr>
	<th width="30%">Nbre véhicules contrôlés</th>
	<td width="30%">'.$plusJs.'\'alcoVVCtrl\',\'add\');"></td>
	<td id="alcoVVCtrl"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'alcoVVCtrl\',\'rem\');"></td>
	</tr>';	
	
	$value=(isset($_SESSION['alcoPersCtrl'])) ? $_SESSION['alcoPersCtrl'] : '0';
	$html.='<tr>
	<th width="30%">Nbre personnes contrôlées</th>
	<td width="30%">'.$plusJs.'\'alcoPersCtrl\',\'add\');"></td>
	<td id="alcoPersCtrl"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'alcoPersCtrl\',\'rem\');"></td>
	</tr>';		
	
	$value=(isset($_SESSION['alcoA'])) ? $_SESSION['alcoA'] : '0';
	$html.='<tr>
	<th width="30%">Nbre A</th>
	<td width="30%">'.$plusJs.'\'alcoA\',\'add\');"></td>
	<td id="alcoA"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'alcoA\',\'rem\');"></td>
	</tr>';		
	
	$value=(isset($_SESSION['alcoP'])) ? $_SESSION['alcoP'] : '0';
	$html.='<tr>
	<th width="30%">Nbre P</th>
	<td width="30%">'.$plusJs.'\'alcoP\',\'add\');"></td>
	<td id="alcoP"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'alcoP\',\'rem\');"></td>
	</tr>';	

	$value=(isset($_SESSION['alcoRetraits'])) ? $_SESSION['alcoRetraits'] : '0';
	$html.='<tr>
	<th width="30%">Nbre retraits</th>
	<td width="30%">'.$plusJs.'\'alcoRetraits\',\'add\');"></td>
	<td id="alcoRetraits"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'alcoRetraits\',\'rem\');"></td>
	</tr>';
	
	$value=(isset($_SESSION['alcoPds'])) ? $_SESSION['alcoPds'] : '0';
	$html.='<tr>
	<th width="30%">Nbre prise de sang</th>
	<td width="30%">'.$plusJs.'\'alcoPds\',\'add\');"></td>
	<td id="alcoPds"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'alcoPds\',\'rem\');"></td>
	</tr>';	
	
	$value=(isset($_SESSION['alcoStups'])) ? $_SESSION['alcoStups'] : '0';
	$html.='<tr>
	<th width="30%">PV stups</th>
	<td width="30%">'.$plusJs.'\'alcoStups\',\'add\');"></td>
	<td id="alcoStups"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'alcoStups\',\'rem\');"></td>
	</tr>';
	
	$value=(isset($_SESSION['alcoSuiteAcc'])) ? $_SESSION['alcoSuiteAcc'] : '0';
	$html.='<tr>
	<th width="30%">Suite accident</th>
	<td width="30%">'.$plusJs.'\'alcoSuiteAcc\',\'add\');"></td>
	<td id="alcoSuiteAcc"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'alcoSuiteAcc\',\'rem\');"></td>
	</tr>';
	
	$html.='<tr><th colspan="4" class="sstitre" id="PIPL">PI POIDS LOURDS</th></tr>';
	
	$value=(isset($_SESSION['plNbrCtrl'])) ? $_SESSION['plNbrCtrl'] : '0';
	$html.='<tr>
	<th width="30%">Nbr PL contrôlés</th>
	<td width="30%">'.$plusJs.'\'plNbrCtrl\',\'add\');"></td>
	<td id="plNbrCtrl"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'plNbrCtrl\',\'rem\');"></td>
	</tr>';	
	
	$value=(isset($_SESSION['plPI'])) ? $_SESSION['plPI'] : '0';
	$html.='<tr>
	<th width="30%">PI</th>
	<td width="30%">'.$plusJs.'\'plPI\',\'add\');"></td>
	<td id="plPI"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'plPI\',\'rem\');"></td>
	</tr>';	

	$value=(isset($_SESSION['plPV'])) ? $_SESSION['plPV'] : '0';
	$html.='<tr>
	<th width="30%">PV</th>
	<td width="30%">'.$plusJs.'\'plPV\',\'add\');"></td>
	<td id="plPV"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'plPV\',\'rem\');"></td>
	</tr>';	

	$value=(isset($_SESSION['plNbrAdr'])) ? $_SESSION['plNbrAdr'] : '0';
	$html.='<tr>
	<th width="30%">Nbr ADR contrôlés</th>
	<td width="30%">'.$plusJs.'\'plNbrAdr\',\'add\');"></td>
	<td id="plNbrAdr"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'plNbrAdr\',\'rem\');"></td>
	</tr>';	

	$value=(isset($_SESSION['plPIAdr'])) ? $_SESSION['plPIAdr'] : '0';
	$html.='<tr>
	<th width="30%">PI ADR</th>
	<td width="30%">'.$plusJs.'\'plPIAdr\',\'add\');"></td>
	<td id="plPIAdr"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'plPIAdr\',\'rem\');"></td>
	</tr>';	

	$value=(isset($_SESSION['plPVAdr'])) ? $_SESSION['plPVAdr'] : '0';
	$html.='<tr>
	<th width="30%">PV ADR</th>
	<td width="30%">'.$plusJs.'\'plPVAdr\',\'add\');"></td>
	<td id="plPVAdr"><font size="5">'.$value.'</font></td>
	<td width="30%">'.$moinsJs.'\'plPVAdr\',\'rem\');"></td>
	</tr>';		
	
	
	$html.='</table>';
	$html.='</div>';
	
	$this->afficheHtml($html);
	}
	
public function newIntervention($time,$data)
	{
	$html='<div id="intervention"><h2>Nouvelle intervention</h2>';
	if ($data['hDeb']==0){
		$html.='<form method="POST" name=newInter action="index.php?component=activites&action=intervention">';
		$html.='<table>';
		$html.='<tr><th>Numéro de fiche ou brève description :</th><td id="num_fiche"><textarea rows="4" cols="80" id="numFiche" name="numFiche" autofocus required></textarea></td></tr>';
		$html.='<tr><td class="noborder" colspan="1">Exemple : PI stationnement, ... </td></tr>';
		$html.='<tr><td colspan="2" id="button"><img src="./media/icons/sur_place.png" width="25%" onclick="intervention(\''.$_SESSION['idpat'].'\',\''.$time.'\',\'SP\');"></td></tr>';
		$html.='</table>';
		$html.='<div id="rep"></div>';
	}
	else{
		$html.='<form method="POST" name=newInter action="index.php?component=activites&action=intervention">';
		$html.='<table>';
		$html.='<tr><th>Numéro de fiche ou brève description :</th><td id="num_fiche"><input type="text" id="numFiche" name="numFiche" autofocus required></td></tr>';	
		$html.='<tr><td class="noborder" colspan="1">Exemple : PI stationnement, ... </td></tr>';
		
		$html.='<tr><td colspan="2" id="button"><img src="./media/icons/fin_int.png" width="25%" onclick="intervention(\''.$_SESSION['idpat'].'\',\''.$data['hDeb'].'\',\'FIN\');"></td></tr>';
		$html.='</table>';
		$html.='<div id="rep"></div>';
		$html.='</div>';
	}
	$this->afficheHtml($html);
	}
	
public function newPatrouille($time)	{
	$html='<h2>Nouvelle patrouille</h2>';
	$html.='<form method="POST" name=newPatrouille action="index.php?component=activites&action=patrouille">';
	$html.='<table>';
	$html.='<tr><th>Description :</th><td id="num_fiche"><input type="text" id="comPat" name="comPat" autofocus required></td></tr>';
	$html.='<tr><td class="noborder" colspan="1">Exemple : Patrouille VV frontière MàL, ... </td></tr>';
	$html.='<tr><td colspan="2" id="button"><img src="./media/icons/sur_place.png" width="25%" onclick="patrouille(\''.$_SESSION['idpat'].'\',\''.$time.'\',\'SP\');"></td></tr>';
	$html.='</table>';
	$html.='<div id="rep"></div>';
	$this->afficheHtml($html);	
}

public function genBS($data)
	{
	
	$html='<div id="genBS">';
	$html.='<h2>Bulletin de service <a href="?mode=m&component=activites&action=GenBS"><img src="./media/icons/reload.png" height="32px"></a></h2>';
	$html.='<table>';
	$html.='<tr><th>Date :</th><td>'.substr($this->datefr($data['patrouille']['debut']),0,10).'</td><th>Horaire :</th><td>'.substr($this->datefr($data['patrouille']['debut']),14,5).' - '.substr($this->datefr($data['patrouille']['fin']),14,5).'</td></tr>';
	$html.='</table>';
	$html.='<table>';
	$html.='<tr><th>Dénomination :</th><td>'.$data['patrouille']['denomination'].'</td><th>Indicatif :</th><td>'.$data['patrouille']['indicatif'].'</td></tr>';
	$html.='<tr><th colspan="2">Collaborateur(s) engagé(s)</th><th colspan="2">Matériel</th></tr>';
	$html.='<tr><td colspan="2">';
	for($i=0;$i<$data['collaborateur']['ttl'];$i++)
		{
		$html.=$data['collaborateur'][$i].'<br />';
		}
	$html.='<br /><a href="?mode=pop&component=activites&action=modifBS&idBS='.$_SESSION['idbs'].'&part=collabos" target="_blank"><input type=button value="Modifier ces donn&eacute;es"></td><td colspan="2">';
	if ($data['armeCollec']['ttl']=='0')
		{
		$html.='Arme collective : Aucune <br />';
		}
	else
		{
		$html.=($data['armeCollec']['ttl']>'1') ? 'Armes collectives : ' : 'Arme collective : ';
		for($i=0;$i<$data['armeCollec']['ttl'];$i++)
			{
			$html.=$data['armeCollec'][$i].'<br />';
			}
		}
	$html.=($data['app_photo']=='') ? 'Appareil photo : Aucun. <br />' : 'Appareil photo : '.$data['app_photo'].'<br />';
	$html.=(!isset($data['ETT'])) ? 'ETT : Aucun <br />' : 'ETT n° : '.$data['ETT'].'<br />';
	$html.=(!isset($data['GSM'])) ? 'GSM : Aucun <br />' : 'GSM n° : '.$data['GSM'].'<br /><hr>';
	$html.='<a href="?mode=pop&component=activites&action=modifBS&idBS='.$_SESSION['idbs'].'&part=matos" target="_blank"><input type=button value="Modifier ces donn&eacute;es">';
	$html.='</td></tr>';
	$html.='<tr><th colspan="2">Immatriculation v&eacute;hicule de service :</th><td colspan="2">';
	if($data['vv']['ttl']==0)
		{
		$html.='Aucun véhicule utilisé.';
		}
	else
		{	
		for($i=0;$i<$data['vv']['ttl'];$i++)
			{
			$html.='<b>'.$data['vv'][$i]['immatriculation'].'</b><br /><hr>';
			$html.='
			Plein effectué : 
			<div id="plO'.$i.'">';
			$pleinO=($data['vv'][$i]['plein']=='O') ? 'checked' : '';
			$pleinN=($data['vv'][$i]['plein']=='N') ? 'checked' : '';

			$html.='<input type="radio" id="pleinO'.$i.'" value="Oui" name="plein'.$i.'" onclick="checkPlein(\''.$i.'\',\''.$_SESSION['idbs'].'\',\''.$data['vv'][$i]['immatriculation'].'\');" '.$pleinO.'>Oui</div>
			<div id="plN'.$i.'">
			<input type="radio" id="pleinN'.$i.'" value="Non" name="plein'.$i.'" onclick="checkPlein(\''.$i.'\',\''.$_SESSION['idbs'].'\',\''.$data['vv'][$i]['immatriculation'].'\');" '.$pleinN.'>Non</div>';
			
			$html.='Dégâts éventuels constatés :<br />';
			$html.='<div id="degats'.$i.'"><input type="text" size="90%" name="DegatsVV'.$i.'" id="DegatsVV'.$i.'" value="'.$data['vv'][$i]['degats'].'" style="text-align:center;" onfocusout="DegatsVV(\''.$i.'\',\''.$_SESSION['idbs'].'\',\''.$data['vv'][$i]['immatriculation'].'\');"></div>';
			
			$html.=($data['vv']['ttl']>1) ? '<hr>' : '';
			}
			
		}
	$html.='<a href="?mode=pop&component=activites&action=modifBS&idBS='.$_SESSION['idbs'].'&part=VV" target="_blank"><input type=button value="Modifier ces donn&eacute;es"></td></tr>';
	$html.='</table>';
	//MISSIONS ACCOMPLIES PAR ORDRE CHRONOLOGIQUE
	$html.='<table>';
	$html.='<tr><th colspan="4" class="titre">ACTIVITES</th></tr>';
	$html.='<tr><th>Hr Début</th><th>Hr Fin</th><th>CODE Prestation</th><th>Description / lieu</th></tr>';
	
	
	
	for ($i=0;$i<$data['mission']['ttl'];$i++)
		{
		if (isset($data['mission'][$i]['code_prest']))
			{
			if ($data['mission'][$i]['heure_debut']!='0000-00-00 00:00:00')
				{
				$html.='<tr><td>'.$this->datefr($data['mission'][$i]['heure_debut'],2).'</td>';
				$html.='<td>'.$this->datefr($data['mission'][$i]['heure_fin'],2).'</td>';
				}
			else	
				{
				$html.='<tr><td colspan="2">Non réalisée</td>';
				}
			$html.='<td>'.ucfirst($data['mission'][$i]['code_prest']);
			if($data['mission'][$i]['code_prest']=='vacanciers')
				{
				$html.=' - ('.$data['mission'][$i]['rue'].', '.$data['mission'][$i]['numero'].')';
				}
			if($data['mission'][$i]['code_prest']=='Mission COPS')
				{
				$html.=' ('.$data['mission'][$i]['texteInfo'].')';
				}
			$html.='</td>';
			$html.='<td>'.$data['mission'][$i]['commentaire'];
			if ((isset($data['mission'][$i]['lieu'])) AND ($data['mission'][$i]['lieu']!=''))
				{
				$html.=' / '.$data['mission'][$i]['lieu'];
				}
			$html.='</td></tr>';
			}
		}
	
	$html.='</table><br />';
	$html.='<table>';

	$html.='<tr><th colspan="2" class="titre">RÉSULTATS ACTIVITÉS</th></tr>';
	$html.='<tr><th colspan="2" class="sstitre">ACTIVITÉ GÉNÉRALE</th></tr>';
	if(($data['activite']['ctrlPers']!=0)OR($data['activite']['ctrlVV']!=0)OR($data['activite']['vvFouille']!=0)OR($data['activite']['arrestAdm']!=0)OR($data['activite']['arrestJud']!=0))
		{
		$html.=($data['activite']['ctrlPers']!=0) ? '<tr><th>Personnes contrôlées :</th><td align="center">'.$data['activite']['ctrlPers'].'</td></tr>' : '';
		$html.=($data['activite']['ctrlVV']!=0) ? '<tr><th>Véhicules contrôlés :</th><td align="center">'.$data['activite']['ctrlVV'].'</td></tr>' : '';
		$html.=($data['activite']['vvFouille']!=0) ? '<tr><th>Véhicules fouillés :</th><td align="center">'.$data['activite']['vvFouille'].'</td></tr>' : '';
		$html.=($data['activite']['arrestAdm']!=0) ? '<tr><th>Arrestation administratives :</th><td align="center">'.$data['activite']['arrestAdm'].'</td></tr>' : '';
		$html.=($data['activite']['arrestJud']!=0) ? '<tr><th>Arrestations judiciaires :</th><td align="center">'.$data['activite']['arrestJud'].'</td></tr>' : '';
		}
	
	$html.='<tr><th colspan="2" class="sstitre">ACTIVITÉ JUDICIAIRE</th></tr>';
	if(($data['activite']['OC']!=0)OR($data['activite']['AI']!=0)OR($data['activite']['pvArmes']!=0)OR($data['activite']['pvIvresse']!=0)OR($data['activite']['pvDiffFamssCoups']!=0)OR($data['activite']['pvTapagePart']!=0)OR($data['activite']['BCS']!=0)OR($data['activite']['pvRgp']!=0)OR($data['activite']['pvStups']!=0)OR($data['activite']['pvCoups']!=0)OR($data['activite']['pvDiffFamAvecCoups']!=0)OR($data['activite']['pvTapageEts']!=0)OR($data['activite']['fuguesDisp']!=0)OR($data['activite']['rebellion']!=0)OR($data['activite']['pvOutrages']!=0)OR($data['activite']['pvVol']!=0)OR($data['activite']['pvDegradations']!=0)OR($data['activite']['pvJudAutres']!=0))
		{		
		$html.=($data['activite']['OC']!=0) ? '<tr><th>Nbr d\'ordonnances de capture :</th><td align="center">'.$data['activite']['OC'].'</td></tr>' : '';
		$html.=($data['activite']['AI']!=0) ? '<tr><thNbr d\'avis d\'identifiaction :</th><td align="center">'.$data['activite']['AI'].'</td></tr>' : '';
		$html.=($data['activite']['pvArmes']!=0) ? '<tr><th>Nbr PV Armes :</th><td align="center">'.$data['activite']['pvArmes'].'</td></tr>' : '';
		$html.=($data['activite']['pvIvresse']!=0) ? '<tr><th>Nbr PV ivresse :</th><td align="center">'.$data['activite']['pvIvresse'].'</td></tr>' : '';
		$html.=($data['activite']['pvDiffFamssCoups']!=0) ? '<tr><th>Nbr PV Différends familiaux sans coups :</th><td align="center">'.$data['activite']['pvDiffFamssCoups'].'</td></tr>' : '';
		$html.=($data['activite']['pvTapagePart']!=0) ? '<tr><th>Nbr PV tapage part. :</th><td align="center">'.$data['activite']['pvTapagePart'].'</td></tr>' : '';
		$html.=($data['activite']['BCS']!=0) ? '<tr><th>Nbr BCS :</th><td align="center">'.$data['activite']['BCS'].'</td></tr>' : '';
		$html.=($data['activite']['pvRgp']!=0) ? '<tr><th>Nbr PV RGP :</th><td align="center">'.$data['activite']['pvRgp'].'</td></tr>' : '';
		$html.=($data['activite']['pvStups']!=0) ? '<tr><th>Nbr PV Stups :</th><td align="center">'.$data['activite']['pvStups'].'</td></tr>' : '';
		$html.=($data['activite']['pvCoups']!=0) ? '<tr><th>Nbr PV Coups :</th><td align="center">'.$data['activite']['pvCoups'].'</td></tr>' : '';
		$html.=($data['activite']['pvDiffFamAvecCoups']!=0) ? '<tr><th>Nbr PV Différends familiaux avec coups :</th><td align="center">'.$data['activite']['pvDiffFamAvecCoups'].'</td></tr>' : '';
		$html.=($data['activite']['pvTapageEts']!=0) ? '<tr><th>Nbr PV tapage établissement :</th><td align="center">'.$data['activite']['pvTapageEts'].'</td></tr>' : '';
		$html.=($data['activite']['fuguesDisp']!=0) ? '<tr><th>Nbr PV Fugues / Disparition :</th><td align="center">'.$data['activite']['fuguesDisp'].'</td></tr>' : '';
		$html.=($data['activite']['rebellion']!=0) ? '<tr><th>Nbr PV Rébellion :</th><td align="center">'.$data['activite']['rebellion'].'</td></tr>' : '';
		$html.=($data['activite']['pvOutrages']!=0) ? '<tr><th>Nbr PV Outrages :</th><td align="center">'.$data['activite']['pvOutrages'].'</td></tr>' : '';
		$html.=($data['activite']['pvVol']!=0) ? '<tr><th>Nbr PV Vol :</th><td align="center">'.$data['activite']['pvVol'].'</td></tr>' : '';
		$html.=($data['activite']['pvDegradations']!=0) ? '<tr><th>Nbr PV Dégradations :</th><td align="center">'.$data['activite']['pvDegradations'].'</td></tr>' : '';
		$html.=($data['activite']['pvJudAutres']!=0) ? '<tr><th>Nbr PV autres :</th><td align="center">'.$data['activite']['pvJudAutres'].'</td></tr>' : '';
		}
	
	$html.='<tr><th class="sstitre" colspan="2">ACTIVITE ROULAGE</th></tr>';
	
	if (($data['activite']['pvAccident']!=0)OR($data['activite']['amiable']!=0))
		{
		$html.='<tr><th class="sstitre" colspan="2">ACCIDENTS</th></tr>';
		$html.=($data['activite']['pvAccident']!=0) ? '<tr><th>Nbr PV accident :</th><td align="center">'.$data['activite']['pvAccident'].'</td></tr>' : '';
		$html.=($data['activite']['amiable']!=0) ? '<tr><th>Nbr Constats Amiables :</th><td align="center">'.$data['activite']['amiable'].'</td></tr>' : '';
		}
	
	if(($data['activite']['vitVvCtrl']!=0)OR($data['activite']['vitPVPI']!=0)OR($data['activite']['vitRetraits']!=0))
		{
		$html.='<tr><th class="sstitre" colspan="2">VITESSE</th></tr>';
		$html.=($data['activite']['vitVvCtrl']!=0) ? '<tr><th>Nbr véhicules contrôlés :</th><td align="center">'.$data['activite']['vitVvCtrl'].'</td></tr>' : '';
		$html.=($data['activite']['vitPVPI']!=0) ? '<tr><th>Nbr PV / PI :</th><td align="center">'.$data['activite']['vitPVPI'].'</td></tr>' : '';
		$html.=($data['activite']['vitRetraits']!=0) ? '<tr><th>Nbr retraits :</th><td align="center">'.$data['activite']['vitRetraits'].'</td></tr>' : '';
		}
		
	if (($data['activite']['defAss']!=0)OR($data['activite']['defImm']!=0)OR($data['activite']['defCT']!=0)OR($data['activite']['defAssImCT']!=0)OR($data['activite']['defAssIm']!=0)OR($data['activite']['defImmCT']!=0)OR($data['activite']['defPC']!=0)OR($data['activite']['PVRoulAutre']!=0))
		{
		$html.='<tr><th class="sstitre" colspan="2">PV ROULAGE</th></tr>';
		$html.=($data['activite']['defAss']!=0) ? '<tr><th>Défaut d\'assurance :</th><td align="center">'.$data['activite']['defAss'].'</td></tr>' : '';
		$html.=($data['activite']['defImm']!=0) ? '<tr><th>Défaut d\'immatriculation :</th><td align="center">'.$data['activite']['defImm'].'</td></tr>' : '';
		$html.=($data['activite']['defCT']!=0) ? '<tr><th>Défaut CT :</th><td align="center">'.$data['activite']['defCT'].'</td></tr>' : '';
		$html.=($data['activite']['defAssImCT']!=0) ? '<tr><th>Défaut Ass + im + CT :</th><td align="center">'.$data['activite']['defAssImCT'].'</td></tr>' : '';
		$html.=($data['activite']['defAssIm']!=0) ? '<tr><th>Défaut Ass + imm :</th><td align="center">'.$data['activite']['defAssIm'].'</td></tr>' : '';
		$html.=($data['activite']['defImmCT']!=0) ? '<tr><th>Imm + CT :</th><td align="center">'.$data['activite']['defImmCT'].'</td></tr>' : '';
		$html.=($data['activite']['defPC']!=0) ? '<tr><th>Défaut PC :</th><td align="center">'.$data['activite']['defPC'].'</td></tr>' : '';
		$html.=($data['activite']['PVRoulAutre']!=0) ? '<tr><th>PV Roulage autre :</th><td align="center">'.$data['activite']['PVRoulAutre'].'</td></tr>' : '';
		}
		
	if(($data['activite']['pvaAssur']!=0)OR($data['activite']['pvaPC']!=0)OR($data['activite']['pvaCI']!=0)OR($data['activite']['pvaExtTrBoite']!=0)OR($data['activite']['pvaPneus']!=0)OR($data['activite']['pvaCT']!=0)OR($data['activite']['pvaIm']!=0)OR($data['activite']['PVAAutre']!=0))	
		{
		$html.='<tr><th class="sstitre" colspan="2">PVA</th></tr>';
		$html.=($data['activite']['pvaAssur']!=0) ? '<tr><th>Assurance :</th><td align="center">'.$data['activite']['pvaAssur'].'</td></tr>' : '';
		$html.=($data['activite']['pvaPC']!=0) ? '<tr><th>PC :</th><td align="center">'.$data['activite']['pvaPC'].'</td></tr>' : '';
		$html.=($data['activite']['pvaCI']!=0) ? '<tr><th>CI :</th><td align="center">'.$data['activite']['pvaCI'].'</td></tr>' : '';
		$html.=($data['activite']['pvaExtTrBoite']!=0) ? '<tr><th>Ext - Tr - Boîte s :</th><td align="center">'.$data['activite']['pvaExtTrBoite'].'</td></tr>' : '';
		$html.=($data['activite']['pvaPneus']!=0) ? '<tr><th>Pneus :</th><td align="center">'.$data['activite']['pvaPneus'].'</td></tr>' : '';
		$html.=($data['activite']['pvaCT']!=0) ? '<tr><th>CT :</th><td align="center">'.$data['activite']['pvaCT'].'</td></tr>' : '';
		$html.=($data['activite']['pvaIm']!=0) ? '<tr><th>Immat. :</th><td align="center">'.$data['activite']['pvaIm'].'</td></tr>' : '';
		$html.=($data['activite']['PVAAutre']!=0) ? '<tr><th>PVA Autre :</th><td align="center">'.$data['activite']['PVAAutre'].'</td></tr>' : '';
		}
	
	if(($data['activite']['cycloNbCtrl']!=0)OR($data['activite']['cycloNonConforme']!=0)OR($data['activite']['cycloVitNCDefAss']!=0)OR($data['activite']['cycloDefAss']!=0)OR($data['activite']['cycloPlaqueJaune']!=0)OR($data['activite']['cycloAutres']!=0)OR($data['activite']['cycloEnlSaisies']!=0))
		{
		$html.='<tr><th class="sstitre" colspan="2">PV CYCLO</th></tr>';
		$html.=($data['activite']['cycloNbCtrl']!=0) ? '<tr><th>Nbr Cyclos contrôlés :</th><td align="center">'.$data['activite']['cycloNbCtrl'].'</td></tr>' : '';
		$html.=($data['activite']['cycloNonConforme']!=0) ? '<tr><th>PVA cyclos non conformes :</th><td align="center">'.$data['activite']['cycloNonConforme'].'</td></tr>' : '';
		$html.=($data['activite']['cycloVitNCDefAss']!=0) ? '<tr><th>PV vit non conformes + déf assurance :</th><td align="center">'.$data['activite']['cycloVitNCDefAss'].'</td></tr>' : '';
		$html.=($data['activite']['cycloDefAss']!=0) ? '<tr><th>PV simple défaut assurance :</th><td align="center">'.$data['activite']['cycloDefAss'].'</td></tr>' : '';
		$html.=($data['activite']['cycloPlaqueJaune']!=0) ? '<tr><th>Plaque jaune :</th><td align="center">'.$data['activite']['cycloPlaqueJaune'].'</td></tr>' : '';
		$html.=($data['activite']['cycloAutres']!=0) ? '<tr><th>PV autres :</th><td align="center">'.$data['activite']['cycloAutres'].'</td></tr>' : '';
		$html.=($data['activite']['cycloEnlSaisies']!=0) ? '<tr><th>Enlèvements - saisies :</th><td align="center">'.$data['activite']['cycloEnlSaisies'].'</td></tr>' : '';
		}
	
	if(($data['activite']['pipvTrottoir']!=0)OR($data['activite']['pipvZChargt']!=0)OR($data['activite']['pipvBus']!=0)OR($data['activite']['pipvPMR']!=0)OR($data['activite']['pipvPisteCycl']!=0)OR($data['activite']['pipvPassPietons']!=0)OR($data['activite']['pipvE1']!=0)OR($data['activite']['pipvE3']!=0)OR($data['activite']['pipvGSM']!=0)OR($data['activite']['pipvCeinture']!=0)OR($data['activite']['pipvCasque']!=0)OR($data['activite']['pipvC1']!=0)OR($data['activite']['pipvStop']!=0)OR($data['activite']['pipvOrange']!=0)OR($data['activite']['pipvRouge']!=0)OR($data['activite']['pipvAutre']!=0)OR($data['activite']['pipvAutre']!=0))
		{
		$html.='<tr><th class="sstitre" colspan="2">PI / PV</th></tr>';
		$html.=($data['activite']['pipvTrottoir']!=0) ? '<tr><th>Trottoir :</th><td align="center">'.$data['activite']['pipvTrottoir'].'</td></tr>' : '';
		$html.=($data['activite']['pipvZChargt']!=0) ? '<tr><th>Zone chargement :</th><td align="center">'.$data['activite']['pipvZChargt'].'</td></tr>' : '';
		$html.=($data['activite']['pipvBus']!=0) ? '<tr><th>Bande / Arrêt bus :</th><td align="center">'.$data['activite']['pipvBus'].'</td></tr>' : '';
		$html.=($data['activite']['pipvPMR']!=0) ? '<tr><th>PMR :</th><td align="center">'.$data['activite']['pipvPMR'].'</td></tr>' : '';
		$html.=($data['activite']['pipvPisteCycl']!=0) ? '<tr><th>Piste cyclable :</th><td align="center">'.$data['activite']['pipvPisteCycl'].'</td></tr>' : '';
		$html.=($data['activite']['pipvPassPietons']!=0) ? '<tr><th>Passage piétons :</th><td align="center">'.$data['activite']['pipvPassPietons'].'</td></tr>' : '';
		$html.=($data['activite']['pipvE1']!=0) ? '<tr><th>E1 :</th><td align="center">'.$data['activite']['pipvE1'].'</td></tr>' : '';
		$html.=($data['activite']['pipvE3']!=0) ? '<tr><th>E3 :</th><td align="center">'.$data['activite']['pipvE3'].'</td></tr>' : '';
		$html.=($data['activite']['pipvGSM']!=0) ? '<tr><th>GSM :</th><td align="center">'.$data['activite']['pipvGSM'].'</td></tr>' : '';
		$html.=($data['activite']['pipvCeinture']!=0) ? '<tr><th>Ceinture :</th><td align="center">'.$data['activite']['pipvCeinture'].'</td></tr>' : '';
		$html.=($data['activite']['pipvCasque']!=0) ? '<tr><th>Casque :</th><td align="center">'.$data['activite']['pipvCasque'].'</td></tr>' : '';
		$html.=($data['activite']['pipvC1']!=0) ? '<tr><th>C1 :</th><td align="center">'.$data['activite']['pipvC1'].'</td></tr>' : '';
		$html.=($data['activite']['pipvStop']!=0) ? '<tr><th>Stop :</th><td align="center">'.$data['activite']['pipvStop'].'</td></tr>' : '';
		$html.=($data['activite']['pipvOrange']!=0) ? '<tr><th>Feu orange :</th><td align="center">'.$data['activite']['pipvOrange'].'</td></tr>' : '';
		$html.=($data['activite']['pipvRouge']!=0) ? '<tr><th>Feu rouge :</th><td align="center">'.$data['activite']['pipvRouge'].'</td></tr>' : '';
		$html.=($data['activite']['pipvGenant']!=0) ? '<tr><th>Gênant :</th><td align="center">'.$data['activite']['pipvGenant'].'</td></tr>' : '';
		$html.=($data['activite']['pipvAutre']!=0) ? '<tr><th>PI / PV Autre</th><td align="center">'.$data['activite']['pipvAutre'].'</td></tr>' : '';
		}
	
	if(($data['activite']['alcoVVCtrl']!=0)OR($data['activite']['alcoPersCtrl']!=0)OR($data['activite']['alcoA']!=0)OR($data['activite']['alcoP']!=0)OR($data['activite']['alcoRetraits']!=0)OR($data['activite']['alcoPds']!=0)OR($data['activite']['alcoStups']!=0)OR($data['activite']['alcoSuiteAcc']!=0))
		{
		$html.='<tr><th class="sstitre" colspan="2">ALCO</th></tr>';
		$html.=($data['activite']['alcoVVCtrl']!=0) ? '<tr><th>Nbre véhicules contrôlés :</th><td align="center">'.$data['activite']['alcoVVCtrl'].'</td></tr>' : '';
		$html.=($data['activite']['alcoPersCtrl']!=0) ? '<tr><th>Nbre personnes contrôlées :</th><td align="center">'.$data['activite']['alcoPersCtrl'].'</td></tr>' : '';
		$html.=($data['activite']['alcoA']!=0) ? '<tr><th>Nbre A :</th><td align="center">'.$data['activite']['alcoA'].'</td></tr>' : '';
		$html.=($data['activite']['alcoP']!=0) ? '<tr><th>Nbre P :</th><td align="center">'.$data['activite']['alcoP'].'</td></tr>' : '';
		$html.=($data['activite']['alcoRetraits']!=0) ? '<tr><th>Nbre retraits :</th><td align="center">'.$data['activite']['alcoRetraits'].'</td></tr>' : '';
		$html.=($data['activite']['alcoPds']!=0) ? '<tr><th>Nbre prise de sang :</th><td align="center">'.$data['activite']['alcoPds'].'</td></tr>' : '';
		$html.=($data['activite']['alcoStups']!=0) ? '<tr><th>PV stups :</th><td align="center">'.$data['activite']['alcoStups'].'</td></tr>' : '';
		$html.=($data['activite']['alcoSuiteAcc']!=0) ? '<tr><th>Suite accident :</th><td align="center">'.$data['activite']['alcoSuiteAcc'].'</td></tr>' : '';
		}
		
	if(($data['activite']['plNbrCtrl']!=0)OR($data['activite']['plPI']!=0)OR($data['activite']['plPV']!=0)OR($data['activite']['plNbrAdr']!=0)OR($data['activite']['plPIAdr']!=0)OR($data['activite']['plPVAdr']!=0))
		{
		$html.='<tr><th class="sstitre" colspan="2">PI POIDS LOURDS</th></tr>';
		$html.=($data['activite']['plNbrCtrl']!=0) ? '<tr><th>Nbr PL contrôlés :</th><td align="center">'.$data['activite']['plNbrCtrl'].'</td></tr>' : '';
		$html.=($data['activite']['plPI']!=0) ? '<tr><th>PI :</th><td align="center">'.$data['activite']['plPI'].'</td></tr>' : '';
		$html.=($data['activite']['plPV']!=0) ? '<tr><th>PV :</th><td align="center">'.$data['activite']['plPV'].'</td></tr>' : '';
		$html.=($data['activite']['plNbrAdr']!=0) ? '<tr><th>Nbr ADR contrôlés :</th><td align="center">'.$data['activite']['plNbrAdr'].'</td></tr>' : '';
		$html.=($data['activite']['plPIAdr']!=0) ? '<tr><th>PI ADR :</th><td align="center">'.$data['activite']['plPIAdr'].'</td></tr>' : '';
		$html.=($data['activite']['plPVAdr']!=0) ? '<tr><th>PV ADR :</th><td align="center">'.$data['activite']['plPVAdr'].'</td></tr>' : '';
		}
	$html.='<tr><th colspan="2" class="titre">COMMENTAIRES SERVICE</th></tr>';
	$html.='<tr><td colspan="2"><textarea id="commentaire" rows="4" cols="80" onkeyup="updateCom(\''.$_SESSION['idpat'].'\',\''.$_SESSION['idbs'].'\');">'.$data['activite']['commentaire'].'</textarea></td></tr>';
	$html.='<tr><th colspan="2"><input name="b_send" type="button" onclick="sendBS(\''.$_SESSION['idpat'].'\',\''.$_SESSION['idbs'].'\');" value="Valider BS"></th></tr>';
	$html.='</table>';
	$html.='</div>';
	
	$this->afficheHtml($html);
	}
	
public function SvPat($data)
	{
	$html='<form method="post" action="?mode=m&component=activites&action=patrouille&end=O"><table>';
	$html.='<tr><th class="titre" colspan="4">Initiative</th></tr>';
	$html.='<tr><th width="25%">Commentaire : </th><td colspan="3"><textarea rows="4" cols="80" name="textSvInt"></textarea></td></tr>';
	$html.='<tr><th>Type :</th><td><input type="radio" name="typPat" id="PP" value="PP"><label for="PP">Patrouille p&eacute;destre</label><br><input type="radio" name="typPat" value="PV"><label>Patrouille v&eacute;hicule</label><br><input type="radio" name="typPat" value="CS"><label>Statique</label></td><th>Lieu :</th><td><select name="lieu" id="lieu"><option>AUTRE (Mentionner dans le commentaire)</option>';
	while($row=$data->fetch()){
		$html.='<option value="'.$row['id_lieu'].'">'.$row['nom_lieu'].'</option>';
	}
	$html.='</select></td></tr>';
	$html.='<tr><td colspan="4" class="centerTD"><input type="submit" value="Fin de patrouille" class="boutonFinInt"></td></tr>';
	$html.='</table></form>';
	$this->afficheHtml($html);
	}
	
public function SvInt()
	{
	$html='<form method="post" action="?mode=m&component=activites&action=SvInt&end=O"><table>';
	$html.='<tr><th class="titre" colspan="2">Service intérieur</th></tr>';
	$html.='<tr><th width="25%">Commentaire : </th><td><textarea rows="4" cols="80" name="textSvInt"></textarea></td></tr>';
	$html.='<tr><td colspan="2"><input type="submit" value="Fin de service intérieur" class="boutonFinInt"></td></tr>';
	$html.='</table></form>';
	$this->afficheHtml($html);
	}
	
public function formModifBS($from, $data, $idBS){
	$html='<h2>Modifer les infos BS</h2>';
	switch($from){
		case 'collabos':
			$html.='<h3>Modification des collaborateurs engag&eacute;s</h3>';
			$html.='<table>';
			$html.='<tr><th>Collaborateur(s) engag&eacute;(s)</th><th>Action</th></tr>';
			while($row=$data['logged']->fetch()){
				$html.='<tr><th>'.strtoupper($row['nom']).' '.$row['prenom'].'</th><td align="center"><input type="button" onclick="delUserOfBS(\''.$row['id_user'].'\', \''.$_GET['idBS'].'\');" value="Supprimer"></td></tr>';
			}
			$html.='<tr><td colspan="2" align="center"><input type="button" onclick="addUserToBS(\''.$_GET['idBS'].'\');" value="Ajouter un collaborateur"></td></tr>';
			$html.='<tr id="trAddUser"></tr>';
			$html.='<tr id="trbAdd"></tr>';
			$html.='</table>';
			break;
		case 'matos':
			$html.='<h3>Modification du mat&eacute;riel emport&eacute;</h3>';
			$html.='<table><tr><th>Type mat&eacute;riel</th><th>Actuellement</th><th>Nouveau</th></tr>';
		
			$html.='<tr><th>Arme collective</th><td>';
			if($data['selectedArme']!=0){
				$html.=$data['selectedArme'];
				}				
			else $html.='Aucune arme s&eacute;lectionn&eacute;e';
			$html.='</td><td><select name="newArmeCo" id="newArmeCo" onchange="updateBS(\''.$_GET['idBS'].'\', \'arme\');"><option></option><option value="0">Aucune arme</option>';
			while($row=$data['armes']->fetch()){
				$html.='<option value="'.$row['num_arme'].'">'.$row['num_arme'].'</option>';
			}
			$html.='</select></td></tr>';
			
			$html.='<tr><th>Appareil photo</th><td>';
			if($data['photo']!=''){
				$html.=$data['photo'];
			}
			else $html.='Aucun appareil s&eacute;lectionn&eacute;';
			$html.='</td><td><select name="newAppPhoto" id="newAppPhoto" onchange="updateBS(\''.$_GET['idBS'].'\', \'photo\');"><option></option><option value="0">Aucun appareil</option>';
			for($i=1;$i<5;$i++){
				$html.='<option value="'.$i.'">Appareil n°'.$i.'</option>';
			}
			$html.='</select></td></tr>';
			
			$html.='<tr><th>ETT</th><td>';
			if($data['selectedETT']!="0"){
				$html.=$data['selectedETT'];
			}
			else $html.='Aucun ETT s&eacute;lectionn&eacute;';
			$html.='</td><td><select name="newETT" id="newETT" onchange="updateBS(\''.$_GET['idBS'].'\', \'ett\');"><option></option><option value="0">Aucun ETT</option>';			
			while($row=$data['ETT']->fetch()){
				$html.='<option value="'.$row['id_ETT'].'">'.$row['id_ETT'].'</option>';
			}
			$html.='</select></td></tr>';
			$html.='<tr><th>GSM</th><td>';
			if($data['selectedGSM']!=0){
				$html.=$data['selectedGSM'];
				}
			else $html.='Aucun GSM s&eacute;lectionn&eacute;';
			$html.='</td><td><select name="newGSM" id="newGSM" onchange="updateBS(\''.$_GET['idBS'].'\', \'gsm\');"><option></option><option value="0">Aucun GSM</option>';
			while($row=$data['GSM']->fetch()){
				$html.='<option value="'.$row['num_GSM'].'">'.$row['num_GSM'].'</option>';
			}
			$html.='</select></td></tr>';
			$html.='</table>';
			break;
		case 'VV':
			$html.='<h3>Modification des v&eacute;hicules utilis&eacute;s</h3>';
			$html.='<table>';
			$html.='<tr><th>V&eacute;hicule(s) engag&eacute;(s)</th><th>Action</th></tr>';
			if($data['logged']===0){
				$html.='<tr><td colspan="2">Aucun v&eacute;hicule</td></tr>';
			}
			else{
				while($row=$data['logged']->fetch()){
					$html.='<tr><th>'.strtoupper($row['immatriculation']).'</th><td align="center"><input type="button" onclick="delVVOfBS(\''.$row['immatriculation'].'\', \''.$_GET['idBS'].'\');" value="Supprimer"></td></tr>';
				}
			}
			$html.='<tr><td colspan="2" align="center"><input type="button" onclick="addVVToBS(\''.$_GET['idBS'].'\');" value="Ajouter un v&eacute;hicule"></td></tr>';
			$html.='<tr id="trAddVV"></tr>';
			$html.='<tr id="trbAdd"></tr>';
			$html.='</table>';			
			break;
	}
	$this->afficheHtml($html);
}
	
}
?>
