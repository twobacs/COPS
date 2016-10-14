<?php
if (isset($_GET['idPat']))
	{
	require_once ('../connect.php');

	$idpat=$_GET['idPat'];
	$idBS=$_GET['idbs'];

	$data=getInfosBS($idpat,$idBS,$pdo);
	$html=genBS($data);
	
$entete='
<!doctype html>
	<head>
		<meta charset="utf-8">
		<link rel="shortcut icon" href="images/favicon.ico" type="image/icon"/>
		<title>COPS - Police Mouscron</title>
		<link rel="stylesheet" href="style.css">                		<script type="text/javascript" src="zoom5317.js"></script>
		<script type="text/javascript" src="jquery-2.1.3.min.js"></script>
		<script type="text/javascript" src="bs.js"></script> 	</head>

	 <body>
		<div id="bloc_page">
			<header>
			</header>

			<div id="Content">';
			
$end='</div></body>';
	
	echo $entete.$html.$end;
	
	}
	
function getInfosBS($idpat,$idBS,$pdo)
	{
	$sql='SELECT * FROM z_patrouille WHERE id_patrouille="'.$idpat.'"';
	$rep=$pdo->query($sql);
	while ($row=$rep->fetch())
		{
		$data['patrouille']['debut']=$row['date_heure_debut'];
		$data['patrouille']['fin']=$row['date_heure_fin'];
		$data['patrouille']['indicatif']=$row['indicatif'];
		$data['patrouille']['denomination']=$row['denomination'];
		}
	
	//COLLABORATEURS ENGAGES
	$sql='SELECT 
	a.nom, a.prenom
	FROM users a
	LEFT JOIN z_bs_users b ON a.id_user=b.id_user
	WHERE b.id_bs="'.$idBS.'"
	ORDER BY a.nom, a.prenom';
	$rep=$pdo->query($sql);
	$i=0;
	while ($row=$rep->fetch())
		{
		$data['collaborateur'][$i]=$row['nom'].' '.$row['prenom'];
		$i++;
		}
	// echo $sql;	
	$data['collaborateur']['ttl']=$i;
	
	//ARME COLLECTIVE
	$sql='SELECT id_arme FROM z_bs_armeCollec WHERE id_bs="'.$idBS.'"';
	$rep=$pdo->query($sql);
	$i=0;
	while ($row=$rep->fetch())
		{
		$data['armeCollec'][$i]=$row['id_arme'];
		$i++;
		}
	$data['armeCollec']['ttl']=$i;
	
	//APPAREIL PHOTO
	$sql='SELECT app_photo FROM z_bs WHERE id_bs="'.$idBS.'"';
	$rep=$pdo->query($sql);
	while ($row=$rep->fetch())
		{
		$data['app_photo']=$row['app_photo'];
		}
		
	//ETT
	$sql='SELECT id_ETT FROM z_bs_ETT WHERE id_bs="'.$idBS.'"';
	$rep=$pdo->query($sql);
	while ($row=$rep->fetch())
		{
		$data['ETT']=$row['id_ETT'];
		}
	
	//GSM
	$sql='SELECT id_GSM FROM z_bs_GSM WHERE id_bs="'.$idBS.'"';
	$rep=$pdo->query($sql);
	while ($row=$rep->fetch())
		{
		$data['GSM']=$row['id_GSM'];
		}

	//VEHICULE
	$sql='SELECT immatriculation, plein, degats FROM z_bs_vv WHERE id_bs="'.$idBS.'"';
	$rep=$pdo->query($sql);
	$i=0;
	while ($row=$rep->fetch())
		{
		$data['vv'][$i]['immatriculation']=$row['immatriculation'];
		$data['vv'][$i]['plein']=$row['plein'];
		$data['vv'][$i]['degats']=$row['degats'];
		$i++;
		}
	$data['vv']['ttl']=$i;
	
	//MISSIONS
	$sql='SELECT 
	a.type_mission, a.id_fiche, a.date_heure_in, a.date_heure_out, a.commentaire, a.lieu,
	b.date_heure, b.resultat, b.commentaire AS commentaireFiche,
	c.vac_numero, c.vac_CP, c.vac_ville,
	d.NomRue,
	e.texteInfo 
	FROM z_pat_missions a
	LEFT JOIN z_vac_hab_controle b ON b.id_vac=a.id_fiche AND b.date_heure=a.date_heure_in
	LEFT JOIN z_vac_habitation c ON c.id_vac=a.id_fiche 
	LEFT JOIN z_rues d ON d.IdRue = c.IdRue	
	LEFT JOIN z_fiche e ON e.id_fiche = a.id_fiche
	WHERE a.id_patrouille="'.$idpat.'" ORDER BY a.date_heure_in';
	$rep=$pdo->query($sql);
	// echo $sql;
	while ($row=$rep->fetch())
		{
		if ($row['type_mission']=='vacanciers')
			{
			$data['mission'][$i]['code_prest']='vacanciers';
			$data['mission'][$i]['heure_debut']=$row['date_heure'];
			$data['mission'][$i]['heure_fin']=$row['date_heure'];
			$data['mission'][$i]['commentaire']=$row['commentaireFiche'];
			$data['mission'][$i]['rue']=$row['NomRue'];
			$data['mission'][$i]['numero']=$row['vac_numero'];
			$data['mission'][$i]['CP']=$row['vac_CP'];
			$data['mission'][$i]['ville']=$row['vac_ville'];			
			}
		if ($row['type_mission']=='CS')
			{
			$data['mission'][$i]['code_prest']='Contr&ocirc;le statique';
			$data['mission'][$i]['heure_debut']=$row['date_heure_in'];
			$data['mission'][$i]['heure_fin']=$row['date_heure_out'];
			$data['mission'][$i]['commentaire']=$row['commentaire'];			
			$data['mission'][$i]['lieu']=htmlspecialchars_decode($row['lieu']);
			}
		if ($row['type_mission']=='PV')
			{
			$data['mission'][$i]['code_prest']='Patrouille en v&eacute;hicule';
			$data['mission'][$i]['heure_debut']=$row['date_heure_in'];
			$data['mission'][$i]['heure_fin']=$row['date_heure_out'];
			$data['mission'][$i]['commentaire']=$row['commentaire'];			
			$data['mission'][$i]['lieu']=$row['lieu'];			
			}
		if ($row['type_mission']=='PP')
			{
			$data['mission'][$i]['code_prest']='Patrouille p&eacute;destre';
			$data['mission'][$i]['heure_debut']=$row['date_heure_in'];
			$data['mission'][$i]['heure_fin']=$row['date_heure_out'];
			$data['mission'][$i]['commentaire']=$row['commentaire'];			
			$data['mission'][$i]['lieu']=$row['lieu'];			
			}			
			
		if ($row['type_mission']=='cops')
			{
			$data['mission'][$i]['code_prest']='Mission COPS';
			$data['mission'][$i]['heure_debut']=$row['date_heure_in'];
			$data['mission'][$i]['heure_fin']=$row['date_heure_out'];
			$data['mission'][$i]['commentaire']=$row['commentaire'];			
			$data['mission'][$i]['lieu']=$row['lieu'];		
			$data['mission'][$i]['texteInfo']=$row['texteInfo'];			
			}
			
		if ($row['type_mission']=='INT')
			{
			$data['mission'][$i]['code_prest']='Intervention';
			$data['mission'][$i]['heure_debut']=$row['date_heure_in'];
			$data['mission'][$i]['heure_fin']=$row['date_heure_out'];
			$data['mission'][$i]['commentaire']=$row['commentaire'];			
			$data['mission'][$i]['lieu']=$row['lieu'];			
			}
		if ($row['type_mission']=='SI')
			{
			$data['mission'][$i]['code_prest']='Service int&eacute;rieur';
			$data['mission'][$i]['heure_debut']=$row['date_heure_in'];
			$data['mission'][$i]['heure_fin']=$row['date_heure_out'];
			$data['mission'][$i]['commentaire']=$row['commentaire'];			
			$data['mission'][$i]['lieu']=$row['lieu'];			
			}	
		if ($row['type_mission']=='PAT')
			{
			$data['mission'][$i]['code_prest']='Patrouille';
			$data['mission'][$i]['heure_debut']=$row['date_heure_in'];
			$data['mission'][$i]['heure_fin']=$row['date_heure_out'];
			$data['mission'][$i]['commentaire']=$row['commentaire'];			
			$data['mission'][$i]['lieu']=$row['lieu'];			
			}	
		$i++;
		}
	$data['mission']['ttl']=$i;	
	$sql='SELECT ctrlPers, ctrlVV, vvFouille, arrestAdm, arrestJud, OC, BCS, fuguesDisp, AI, pvRgp, rebellion, pvArmes, pvStups, pvOutrages, pvIvresse, pvCoups, pvVol, pvDiffFamssCoups, pvDiffFamAvecCoups, pvDegradations, pvTapagePart, pvTapageEts, pvJudAutres, pvAccident, amiable, vitVvCtrl, vitPVPI, vitRetraits, defAss, defImm, defCT, defAssImCT, defAssIm, defImmCT, defPC, pvaAssur, pvaPC, pvaCI, pvaExtTrBoite, pvaPneus, pvaCT, pvaIm, cycloNbCtrl, cycloNonConforme, cycloVitNCDefAss, cycloDefAss, cycloPlaqueJaune, cycloAutres, cycloEnlSaisies, pipvTrottoir, pipvZChargt, pipvBus, pipvPMR, pipvPisteCycl, pipvPassPietons, pipvE1, pipvE3, pipvGSM, pipvCeinture, pipvCasque, pipvC1, pipvStop, pipvOrange, pipvRouge, pipvGenant, alcoVVCtrl, alcoPersCtrl, alcoA, alcoP, alcoRetraits, alcoPds, alcoStups, alcoSuiteAcc, plNbrCtrl, plPI, plPV, plNbrAdr, plPIAdr, plPVAdr, PVRoulAutre, PVAAutre, pipvAutre, commentaire FROM z_bs WHERE id_bs="'.$idBS.'"';
	$rep=$pdo->query($sql);
	$i=0;
	while ($row=$rep->fetch())
		{
		$data['activite']['ctrlPers']=($row['ctrlPers'] != NULL) ? $row['ctrlPers'] : 0 ;
		$data['activite']['ctrlVV']=($row['ctrlVV'] != NULL) ? $row['ctrlVV'] : 0 ;
		$data['activite']['vvFouille']=($row['vvFouille'] != NULL) ? $row['vvFouille'] : 0 ;
		$data['activite']['arrestAdm']=($row['arrestAdm'] != NULL) ? $row['arrestAdm'] : 0 ;
		$data['activite']['arrestJud']=($row['arrestJud'] != NULL) ? $row['arrestJud'] : 0 ;
		$data['activite']['OC']=($row['OC'] != NULL) ? $row['OC'] : 0 ;
		$data['activite']['BCS']=($row['BCS'] != NULL) ? $row['BCS'] : 0 ;
		$data['activite']['fuguesDisp']=($row['fuguesDisp'] != NULL) ? $row['fuguesDisp'] : 0 ;
		$data['activite']['AI']=($row['AI'] != NULL) ? $row['AI'] : 0 ;
		$data['activite']['pvRgp']=($row['pvRgp'] != NULL) ? $row['pvRgp'] : 0 ;
		$data['activite']['rebellion']=($row['rebellion'] != NULL) ? $row['rebellion'] : 0 ;
		$data['activite']['pvArmes']=($row['pvArmes'] != NULL) ? $row['pvArmes'] : 0 ;
		$data['activite']['pvStups']=($row['pvStups'] != NULL) ? $row['pvStups'] : 0 ;
		$data['activite']['pvOutrages']=($row['pvOutrages'] != NULL) ? $row['pvOutrages'] : 0 ;
		$data['activite']['pvIvresse']=($row['pvIvresse'] != NULL) ? $row['pvIvresse'] : 0 ;
		$data['activite']['pvCoups']=($row['pvCoups'] != NULL) ? $row['pvCoups'] : 0 ;
		$data['activite']['pvVol']=($row['pvVol'] != NULL) ? $row['pvVol'] : 0 ;
		$data['activite']['pvDiffFamssCoups']=($row['pvDiffFamssCoups'] != NULL) ? $row['pvDiffFamssCoups'] : 0 ;
		$data['activite']['pvDiffFamAvecCoups']=($row['pvDiffFamAvecCoups'] != NULL) ? $row['pvDiffFamAvecCoups'] : 0 ;
		$data['activite']['pvDegradations']=($row['pvDegradations'] != NULL) ? $row['pvDegradations'] : 0 ;
		$data['activite']['pvTapagePart']=($row['pvTapagePart'] != NULL) ? $row['pvTapagePart'] : 0 ;
		$data['activite']['pvTapageEts']=($row['pvTapageEts'] != NULL) ? $row['pvTapageEts'] : 0 ;
		$data['activite']['pvJudAutres']=($row['pvJudAutres'] != NULL) ? $row['pvJudAutres'] : 0 ;
		$data['activite']['pvAccident']=($row['pvAccident'] != NULL) ? $row['pvAccident'] : 0 ;
		$data['activite']['amiable']=($row['amiable'] != NULL) ? $row['amiable'] : 0 ;
		$data['activite']['vitVvCtrl']=($row['vitVvCtrl'] != NULL) ? $row['vitVvCtrl'] : 0 ;
		$data['activite']['vitPVPI']=($row['vitPVPI'] != NULL) ? $row['vitPVPI'] : 0 ;
		$data['activite']['vitRetraits']=($row['vitRetraits'] != NULL) ? $row['vitRetraits'] : 0 ;
		$data['activite']['defAss']=($row['defAss'] != NULL) ? $row['defAss'] : 0 ;
		$data['activite']['defImm']=($row['defImm'] != NULL) ? $row['defImm'] : 0 ;
		$data['activite']['defCT']=($row['defCT'] != NULL) ? $row['defCT'] : 0 ;
		$data['activite']['defAssImCT']=($row['defAssImCT'] != NULL) ? $row['defAssImCT'] : 0 ;
		$data['activite']['defAssIm']=($row['defAssIm'] != NULL) ? $row['defAssIm'] : 0 ;
		$data['activite']['defImmCT']=($row['defImmCT'] != NULL) ? $row['defImmCT'] : 0 ;
		$data['activite']['defPC']=($row['defPC'] != NULL) ? $row['defPC'] : 0 ;
		$data['activite']['pvaAssur']=($row['pvaAssur'] != NULL) ? $row['pvaAssur'] : 0 ;
		$data['activite']['pvaPC']=($row['pvaPC'] != NULL) ? $row['pvaPC'] : 0 ;
		$data['activite']['pvaCI']=($row['pvaCI'] != NULL) ? $row['pvaCI'] : 0 ;
		$data['activite']['pvaExtTrBoite']=($row['pvaExtTrBoite'] != NULL) ? $row['pvaExtTrBoite'] : 0 ;
		$data['activite']['pvaPneus']=($row['pvaPneus'] != NULL) ? $row['pvaPneus'] : 0 ;
		$data['activite']['pvaCT']=($row['pvaCT'] != NULL) ? $row['pvaCT'] : 0 ;
		$data['activite']['pvaIm']=($row['pvaIm'] != NULL) ? $row['pvaIm'] : 0 ;
		$data['activite']['cycloNbCtrl']=($row['cycloNbCtrl'] != NULL) ? $row['cycloNbCtrl'] : 0 ;
		$data['activite']['cycloNonConforme']=($row['cycloNonConforme'] != NULL) ? $row['cycloNonConforme'] : 0 ;
		$data['activite']['cycloVitNCDefAss']=($row['cycloVitNCDefAss'] != NULL) ? $row['cycloVitNCDefAss'] : 0 ;
		$data['activite']['cycloDefAss']=($row['cycloDefAss'] != NULL) ? $row['cycloDefAss'] : 0 ;
		$data['activite']['cycloPlaqueJaune']=($row['cycloPlaqueJaune'] != NULL) ? $row['cycloPlaqueJaune'] : 0 ;
		$data['activite']['cycloAutres']=($row['cycloAutres'] != NULL) ? $row['cycloAutres'] : 0 ;
		$data['activite']['cycloEnlSaisies']=($row['cycloEnlSaisies'] != NULL) ? $row['cycloEnlSaisies'] : 0 ;
		$data['activite']['pipvTrottoir']=($row['pipvTrottoir'] != NULL) ? $row['pipvTrottoir'] : 0 ;
		$data['activite']['pipvZChargt']=($row['pipvZChargt'] != NULL) ? $row['pipvZChargt'] : 0 ;
		$data['activite']['pipvBus']=($row['pipvBus'] != NULL) ? $row['pipvBus'] : 0 ;
		$data['activite']['pipvPMR']=($row['pipvPMR'] != NULL) ? $row['pipvPMR'] : 0 ;
		$data['activite']['pipvPisteCycl']=($row['pipvPisteCycl'] != NULL) ? $row['pipvPisteCycl'] : 0 ;
		$data['activite']['pipvPassPietons']=($row['pipvPassPietons'] != NULL) ? $row['pipvPassPietons'] : 0 ;
		$data['activite']['pipvE1']=($row['pipvE1'] != NULL) ? $row['pipvE1'] : 0 ;
		$data['activite']['pipvE3']=($row['pipvE3'] != NULL) ? $row['pipvE3'] : 0 ;
		$data['activite']['pipvGSM']=($row['pipvGSM'] != NULL) ? $row['pipvGSM'] : 0 ;
		$data['activite']['pipvCeinture']=($row['pipvCeinture'] != NULL) ? $row['pipvCeinture'] : 0 ;
		$data['activite']['pipvCasque']=($row['pipvCasque'] != NULL) ? $row['pipvCasque'] : 0 ;
		$data['activite']['pipvC1']=($row['pipvC1'] != NULL) ? $row['pipvC1'] : 0 ;
		$data['activite']['pipvStop']=($row['pipvStop'] != NULL) ? $row['pipvStop'] : 0 ;
		$data['activite']['pipvOrange']=($row['pipvOrange'] != NULL) ? $row['pipvOrange'] : 0 ;
		$data['activite']['pipvRouge']=($row['pipvRouge'] != NULL) ? $row['pipvRouge'] : 0 ;
		$data['activite']['pipvGenant']=($row['pipvGenant'] != NULL) ? $row['pipvGenant'] : 0 ;
		$data['activite']['alcoVVCtrl']=($row['alcoVVCtrl'] != NULL) ? $row['alcoVVCtrl'] : 0 ;
		$data['activite']['alcoPersCtrl']=($row['alcoPersCtrl'] != NULL) ? $row['alcoPersCtrl'] : 0 ;
		$data['activite']['alcoA']=($row['alcoA'] != NULL) ? $row['alcoA'] : 0 ;
		$data['activite']['alcoP']=($row['alcoP'] != NULL) ? $row['alcoP'] : 0 ;
		$data['activite']['alcoRetraits']=($row['alcoRetraits'] != NULL) ? $row['alcoRetraits'] : 0 ;
		$data['activite']['alcoPds']=($row['alcoPds'] != NULL) ? $row['alcoPds'] : 0 ;
		$data['activite']['alcoStups']=($row['alcoStups'] != NULL) ? $row['alcoStups'] : 0 ;
		$data['activite']['alcoSuiteAcc']=($row['alcoSuiteAcc'] != NULL) ? $row['alcoSuiteAcc'] : 0 ;
		$data['activite']['plNbrCtrl']=($row['plNbrCtrl'] != NULL) ? $row['plNbrCtrl'] : 0 ;
		$data['activite']['plPI']=($row['plPI'] != NULL) ? $row['plPI'] : 0 ;
		$data['activite']['plPV']=($row['plPV'] != NULL) ? $row['plPV'] : 0 ;
		$data['activite']['plNbrAdr']=($row['plNbrAdr'] != NULL) ? $row['plNbrAdr'] : 0 ;
		$data['activite']['plPIAdr']=($row['plPIAdr'] != NULL) ? $row['plPIAdr'] : 0 ;
		$data['activite']['plPVAdr']=($row['plPVAdr'] != NULL) ? $row['plPVAdr'] : 0 ;
		$data['activite']['PVRoulAutre']=($row['PVRoulAutre'] != NULL) ? $row['PVRoulAutre'] : 0 ;
		$data['activite']['PVAAutre']=($row['PVAAutre'] != NULL) ? $row['PVAAutre'] : 0 ;
		$data['activite']['pipvAutre']=($row['pipvAutre'] != NULL) ? $row['pipvAutre'] : 0 ;
		$data['activite']['commentaire']=($row['commentaire'] != NULL) ? $row['commentaire'] : '' ;
		// $data['activite']['ctrlPers']=($row['ctrlPers'] != NULL) ? $row['ctrlPers'] : 0 ;
		}
	return $data;	
	}
	
function genBS($data)
	{
	
	$html='<div id="BS">';
	$html.='<h2 align="center">Bulletin de service</h2>';
	$html.='<input type="button" onclick="imprimer();" value="Imprimer">';
	$html.='<table align="center">';
	$html.='<tr><td width="50%"><b>Date</b> : '.substr(datefr($data['patrouille']['debut']),0,10).'</td><td><b>Horaire</b> : '.substr(datefr($data['patrouille']['debut']),13,5).' - '.substr(datefr($data['patrouille']['fin']),13,5).'</td></tr>';
	$html.='</table>';
	$html.='<table align="center">';
	$html.='<tr><td width="50%">D&eacute;nomination : '.$data['patrouille']['denomination'].'</td><td>Indicatif : '.$data['patrouille']['indicatif'].'</td></tr>';
	
	$html.='<tr><th>Collaborateur(s) engag&eacute(s)</th><th>Mat&eacute;riel</th></tr>';
	$html.='<tr><td>';
	for($i=0;$i<$data['collaborateur']['ttl'];$i++)
		{
		$html.=$data['collaborateur'][$i].'<br />';
		}
	$html.='</td><td>';
	if ($data['armeCollec']['ttl']=='0')
		{
		$html.='Arme collective : aucune. <br />';
		}
	else
		{
		$html.=($data['armeCollec']['ttl']>'1') ? 'Armes collectives : ' : 'Arme collective : ';
		for($i=0;$i<$data['armeCollec']['ttl'];$i++)
			{
			$html.=$data['armeCollec'][$i].'<br />';
			}
		}
	$html.=($data['app_photo']=='') ? 'Appareil photo : aucun. <br />' : 'Appareil photo : '.$data['app_photo'].'<br />';
	$html.=($data['ETT']=='Non') ? 'ETT : aucun. <br />' : 'ETT n&ordm; : '.$data['ETT'].'<br />';
	$html.=(!isset($data['GSM'])) ? 'GSM : aucun. <br />' : 'GSM n&ordm; : '.$data['GSM'].'<br />';
	$html.='</td></tr>';
	$html.='<tr><td colspan="2">VH Immatriculation : <br/>';
	if($data['vv']['ttl']==0)
		{
		$html.='Aucun v&eacute;hicule utilis&eacute;.';
		}
	else
		{	
		for($i=0;$i<$data['vv']['ttl'];$i++)
			{
			$html.=$data['vv'][$i]['immatriculation'].'<br /><hr>';
			$html.='Plein effectu&eacute; : ';
			$html.=($data['vv'][$i]['plein']=='O') ? 'Oui.' : 'Non.';
			$html.='<br />D&eacute;g&acirc;ts &eacute;ventuels constat&eacute;s : '.$data['vv'][$i]['degats'].'.';
			$html.=($data['vv']['ttl']>1) ? '<hr>' : '';
			}
		}
	$html.='</td></tr>';
	$html.='</table>';
	//MISSIONS ACCOMPLIES PAR ORDRE CHRONOLOGIQUE
	$html.='<table>';
	$html.='<tr><th colspan="4" class="titre">ACTIVITES</th></tr>';
	$html.='<tr><th>Hr D&eacute;but</th><th>Hr Fin</th><th>CODE Prestation</th><th>Description / lieu</th></tr>';
	
	
	
	for ($i=0;$i<$data['mission']['ttl'];$i++)
		{
		if (isset($data['mission'][$i]['code_prest']))
			{
			if ($data['mission'][$i]['heure_debut']!='0000-00-00 00:00:00')
				{
				$html.='<tr><td>'.datefr($data['mission'][$i]['heure_debut'],2).'</td>';
				$html.='<td>'.datefr($data['mission'][$i]['heure_fin'],2).'</td>';
				}
			else	
				{
				$html.='<tr><td colspan="2">Non r&eacute;alis&eacute;e</td>';
				}

			$html.='<td>'.ucfirst($data['mission'][$i]['code_prest']);
			if($data['mission'][$i]['code_prest']=='vacanciers')
				{
				$html.=' ('.$data['mission'][$i]['rue'].', '.$data['mission'][$i]['numero'].')';
				}
			if($data['mission'][$i]['code_prest']=='Mission COPS')
				{
				$html.=' - ('.$data['mission'][$i]['texteInfo'].')';
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

	$html.='<tr><th colspan="2" class="titre">R&Eacute;SULTATS ACTIVIT&Eacute;S</th></tr>';
	$html.='<tr><th colspan="2" class="sstitre">ACTIVIT&Eacute; G&Eacute;N&Eacute;RALE</th></tr>';
	if(($data['activite']['ctrlPers']!=0)OR($data['activite']['ctrlVV']!=0)OR($data['activite']['vvFouille']!=0)OR($data['activite']['arrestAdm']!=0)OR($data['activite']['arrestJud']!=0))
		{
		$html.=($data['activite']['ctrlPers']!=0) ? '<tr><th width="50%">Personnes contr&ocirc;l&eacute;es :</th><td align="center">'.$data['activite']['ctrlPers'].'</td></tr>' : '';
		$html.=($data['activite']['ctrlVV']!=0) ? '<tr><th width="50%">V&eacute;hicules contr&ocirc;l&eacute;s :</th><td align="center">'.$data['activite']['ctrlVV'].'</td></tr>' : '';
		$html.=($data['activite']['vvFouille']!=0) ? '<tr><th width="50%">V&eacute;hicules fouill&eacute;s :</th><td align="center">'.$data['activite']['vvFouille'].'</td></tr>' : '';
		$html.=($data['activite']['arrestAdm']!=0) ? '<tr><th width="50%">Arrestation administratives :</th><td align="center">'.$data['activite']['arrestAdm'].'</td></tr>' : '';
		$html.=($data['activite']['arrestJud']!=0) ? '<tr><th width="50%">Arrestations judiciaires :</th><td align="center">'.$data['activite']['arrestJud'].'</td></tr>' : '';
		}
	
	$html.='<tr><th colspan="2" class="sstitre">ACTIVIT&Eacute; JUDICIAIRE</th></tr>';
	if(($data['activite']['OC']!=0)OR($data['activite']['AI']!=0)OR($data['activite']['pvArmes']!=0)OR($data['activite']['pvIvresse']!=0)OR($data['activite']['pvDiffFamssCoups']!=0)OR($data['activite']['pvTapagePart']!=0)OR($data['activite']['BCS']!=0)OR($data['activite']['pvRgp']!=0)OR($data['activite']['pvStups']!=0)OR($data['activite']['pvCoups']!=0)OR($data['activite']['pvDiffFamAvecCoups']!=0)OR($data['activite']['pvTapageEts']!=0)OR($data['activite']['fuguesDisp']!=0)OR($data['activite']['rebellion']!=0)OR($data['activite']['pvOutrages']!=0)OR($data['activite']['pvVol']!=0)OR($data['activite']['pvDegradations']!=0)OR($data['activite']['pvJudAutres']!=0))
		{		
		$html.=($data['activite']['OC']!=0) ? '<tr><th width="50%">Nbr d\'ordonnances de capture :</th><td align="center">'.$data['activite']['OC'].'</td></tr>' : '';
		$html.=($data['activite']['AI']!=0) ? '<tr><th width="50%"Nbr d\'avis d\'identifiaction :</th><td align="center">'.$data['activite']['AI'].'</td></tr>' : '';
		$html.=($data['activite']['pvArmes']!=0) ? '<tr><th width="50%">Nbr PV Armes :</th><td align="center">'.$data['activite']['pvArmes'].'</td></tr>' : '';
		$html.=($data['activite']['pvIvresse']!=0) ? '<tr><th width="50%">Nbr PV ivresse :</th><td align="center">'.$data['activite']['pvIvresse'].'</td></tr>' : '';
		$html.=($data['activite']['pvDiffFamssCoups']!=0) ? '<tr><th width="50%">Nbr PV Diff&eacute;rends familiaux sans coups :</th><td align="center">'.$data['activite']['pvDiffFamssCoups'].'</td></tr>' : '';
		$html.=($data['activite']['pvTapagePart']!=0) ? '<tr><th width="50%">Nbr PV tapage part. :</th><td align="center">'.$data['activite']['pvTapagePart'].'</td></tr>' : '';
		$html.=($data['activite']['BCS']!=0) ? '<tr><th width="50%">Nbr BCS :</th><td align="center">'.$data['activite']['BCS'].'</td></tr>' : '';
		$html.=($data['activite']['pvRgp']!=0) ? '<tr><th width="50%">Nbr PV RGP :</th><td align="center">'.$data['activite']['pvRgp'].'</td></tr>' : '';
		$html.=($data['activite']['pvStups']!=0) ? '<tr><th width="50%">Nbr PV Stups :</th><td align="center">'.$data['activite']['pvStups'].'</td></tr>' : '';
		$html.=($data['activite']['pvCoups']!=0) ? '<tr><th width="50%">Nbr PV Coups :</th><td align="center">'.$data['activite']['pvCoups'].'</td></tr>' : '';
		$html.=($data['activite']['pvDiffFamAvecCoups']!=0) ? '<tr><th width="50%">Nbr PV Diff&eacute;rends familiaux avec coups :</th><td align="center">'.$data['activite']['pvDiffFamAvecCoups'].'</td></tr>' : '';
		$html.=($data['activite']['pvTapageEts']!=0) ? '<tr><th width="50%">Nbr PV tapage &eacute;tablissement :</th><td align="center">'.$data['activite']['pvTapageEts'].'</td></tr>' : '';
		$html.=($data['activite']['fuguesDisp']!=0) ? '<tr><th width="50%">Nbr PV Fugues / Disparition :</th><td align="center">'.$data['activite']['fuguesDisp'].'</td></tr>' : '';
		$html.=($data['activite']['rebellion']!=0) ? '<tr><th width="50%">Nbr PV R&eacute;bellion :</th><td align="center">'.$data['activite']['rebellion'].'</td></tr>' : '';
		$html.=($data['activite']['pvOutrages']!=0) ? '<tr><th width="50%">Nbr PV Outrages :</th><td align="center">'.$data['activite']['pvOutrages'].'</td></tr>' : '';
		$html.=($data['activite']['pvVol']!=0) ? '<tr><th width="50%">Nbr PV Vol :</th><td align="center">'.$data['activite']['pvVol'].'</td></tr>' : '';
		$html.=($data['activite']['pvDegradations']!=0) ? '<tr><th width="50%">Nbr PV D&eacute;gradations :</th><td align="center">'.$data['activite']['pvDegradations'].'</td></tr>' : '';
		$html.=($data['activite']['pvJudAutres']!=0) ? '<tr><th width="50%">Nbr PV autres :</th><td align="center">'.$data['activite']['pvJudAutres'].'</td></tr>' : '';
		}
	
	$html.='<tr><th class="sstitre" colspan="2">ACTIVIT&Eacute; ROULAGE</th></tr>';
	
	if (($data['activite']['pvAccident']!=0)OR($data['activite']['amiable']!=0))
		{
		$html.='<tr><th class="sstitre" colspan="2">ACCIDENTS</th></tr>';
		$html.=($data['activite']['pvAccident']!=0) ? '<tr><th width="50%">Nbr PV accident :</th><td align="center">'.$data['activite']['pvAccident'].'</td></tr>' : '';
		$html.=($data['activite']['amiable']!=0) ? '<tr><th width="50%">Nbr Constats Amiables :</th><td align="center">'.$data['activite']['amiable'].'</td></tr>' : '';
		}
	
	if(($data['activite']['vitVvCtrl']!=0)OR($data['activite']['vitPVPI']!=0)OR($data['activite']['vitRetraits']!=0))
		{
		$html.='<tr><th class="sstitre" colspan="2">VITESSE</th></tr>';
		$html.=($data['activite']['vitVvCtrl']!=0) ? '<tr><th width="50%">Nbr v&eacute;hicules contr&ocirc;l&eacute;s :</th><td align="center">'.$data['activite']['vitVvCtrl'].'</td></tr>' : '';
		$html.=($data['activite']['vitPVPI']!=0) ? '<tr><th width="50%">Nbr PV / PI :</th><td align="center">'.$data['activite']['vitPVPI'].'</td></tr>' : '';
		$html.=($data['activite']['vitRetraits']!=0) ? '<tr><th width="50%">Nbr retraits :</th><td align="center">'.$data['activite']['vitRetraits'].'</td></tr>' : '';
		}
		
	if (($data['activite']['defAss']!=0)OR($data['activite']['defImm']!=0)OR($data['activite']['defCT']!=0)OR($data['activite']['defAssImCT']!=0)OR($data['activite']['defAssIm']!=0)OR($data['activite']['defImmCT']!=0)OR($data['activite']['defPC']!=0)OR($data['activite']['PVRoulAutre']!=0))
		{
		$html.='<tr><th class="sstitre" colspan="2">PV ROULAGE</th></tr>';
		$html.=($data['activite']['defAss']!=0) ? '<tr><th width="50%">D&eacute;faut d\'assurance :</th><td align="center">'.$data['activite']['defAss'].'</td></tr>' : '';
		$html.=($data['activite']['defImm']!=0) ? '<tr><th width="50%">D&eacute;faut d\'immatriculation :</th><td align="center">'.$data['activite']['defImm'].'</td></tr>' : '';
		$html.=($data['activite']['defCT']!=0) ? '<tr><th width="50%">D&eacute;faut CT :</th><td align="center">'.$data['activite']['defCT'].'</td></tr>' : '';
		$html.=($data['activite']['defAssImCT']!=0) ? '<tr><th width="50%">D&eacute;faut Ass + im + CT :</th><td align="center">'.$data['activite']['defAssImCT'].'</td></tr>' : '';
		$html.=($data['activite']['defAssIm']!=0) ? '<tr><th width="50%">D&eacute;faut Ass + imm :</th><td align="center">'.$data['activite']['defAssIm'].'</td></tr>' : '';
		$html.=($data['activite']['defImmCT']!=0) ? '<tr><th width="50%">Imm + CT :</th><td align="center">'.$data['activite']['defImmCT'].'</td></tr>' : '';
		$html.=($data['activite']['defPC']!=0) ? '<tr><th width="50%">D&eacute;faut PC :</th><td align="center">'.$data['activite']['defPC'].'</td></tr>' : '';
		$html.=($data['activite']['PVRoulAutre']!=0) ? '<tr><th width="50%">PV roulage Autre :</th><td align="center">'.$data['activite']['PVRoulAutre'].'</td></tr>' : '';
		}
		
	if(($data['activite']['pvaAssur']!=0)OR($data['activite']['pvaPC']!=0)OR($data['activite']['pvaCI']!=0)OR($data['activite']['pvaExtTrBoite']!=0)OR($data['activite']['pvaPneus']!=0)OR($data['activite']['pvaCT']!=0)OR($data['activite']['pvaIm']!=0)OR($data['activite']['PVAAutre']!=0))	
		{
		$html.='<tr><th class="sstitre" colspan="2">PVA</th></tr>';
		$html.=($data['activite']['pvaAssur']!=0) ? '<tr><th width="50%">Assurance :</th><td align="center">'.$data['activite']['pvaAssur'].'</td></tr>' : '';
		$html.=($data['activite']['pvaPC']!=0) ? '<tr><th width="50%">PC :</th><td align="center">'.$data['activite']['pvaPC'].'</td></tr>' : '';
		$html.=($data['activite']['pvaCI']!=0) ? '<tr><th width="50%">CI :</th><td align="center">'.$data['activite']['pvaCI'].'</td></tr>' : '';
		$html.=($data['activite']['pvaExtTrBoite']!=0) ? '<tr><th width="50%">Ext - Tr - Bo&icirc;te s :</th><td align="center">'.$data['activite']['pvaExtTrBoite'].'</td></tr>' : '';
		$html.=($data['activite']['pvaPneus']!=0) ? '<tr><th width="50%">Pneus :</th><td align="center">'.$data['activite']['pvaPneus'].'</td></tr>' : '';
		$html.=($data['activite']['pvaCT']!=0) ? '<tr><th width="50%">CT :</th><td align="center">'.$data['activite']['pvaCT'].'</td></tr>' : '';
		$html.=($data['activite']['pvaIm']!=0) ? '<tr><th width="50%">Immat. :</th><td align="center">'.$data['activite']['pvaIm'].'</td></tr>' : '';
		$html.=($data['activite']['PVAAutre']!=0) ? '<tr><th width="50%">Immat. :</th><td align="center">'.$data['activite']['PVAAutre'].'</td></tr>' : '';
		}
	
	if(($data['activite']['cycloNbCtrl']!=0)OR($data['activite']['cycloNonConforme']!=0)OR($data['activite']['cycloVitNCDefAss']!=0)OR($data['activite']['cycloDefAss']!=0)OR($data['activite']['cycloPlaqueJaune']!=0)OR($data['activite']['cycloAutres']!=0)OR($data['activite']['cycloEnlSaisies']!=0))
		{
		$html.='<tr><th class="sstitre" colspan="2">PV CYCLO</th></tr>';
		$html.=($data['activite']['cycloNbCtrl']!=0) ? '<tr><th width="50%">Nbr Cyclos contr&ocirc;l&eacute;s :</th><td align="center">'.$data['activite']['cycloNbCtrl'].'</td></tr>' : '';
		$html.=($data['activite']['cycloNonConforme']!=0) ? '<tr><th width="50%">PVA cyclos non conformes :</th><td align="center">'.$data['activite']['cycloNonConforme'].'</td></tr>' : '';
		$html.=($data['activite']['cycloVitNCDefAss']!=0) ? '<tr><th width="50%">PV vit non conformes + d&eacute;f assurance :</th><td align="center">'.$data['activite']['cycloVitNCDefAss'].'</td></tr>' : '';
		$html.=($data['activite']['cycloDefAss']!=0) ? '<tr><th width="50%">PV simple d&eacute;faut assurance :</th><td align="center">'.$data['activite']['cycloDefAss'].'</td></tr>' : '';
		$html.=($data['activite']['cycloPlaqueJaune']!=0) ? '<tr><th width="50%">Plaque jaune :</th><td align="center">'.$data['activite']['cycloPlaqueJaune'].'</td></tr>' : '';
		$html.=($data['activite']['cycloAutres']!=0) ? '<tr><th width="50%">PV autres :</th><td align="center">'.$data['activite']['cycloAutres'].'</td></tr>' : '';
		$html.=($data['activite']['cycloEnlSaisies']!=0) ? '<tr><th width="50%">Enl&egrave;vements - saisies :</th><td align="center">'.$data['activite']['cycloEnlSaisies'].'</td></tr>' : '';
		}
	
	if(($data['activite']['pipvTrottoir']!=0)OR($data['activite']['pipvZChargt']!=0)OR($data['activite']['pipvBus']!=0)OR($data['activite']['pipvPMR']!=0)OR($data['activite']['pipvPisteCycl']!=0)OR($data['activite']['pipvPassPietons']!=0)OR($data['activite']['pipvE1']!=0)OR($data['activite']['pipvE3']!=0)OR($data['activite']['pipvGSM']!=0)OR($data['activite']['pipvCeinture']!=0)OR($data['activite']['pipvCasque']!=0)OR($data['activite']['pipvC1']!=0)OR($data['activite']['pipvStop']!=0)OR($data['activite']['pipvOrange']!=0)OR($data['activite']['pipvRouge']!=0)OR($data['activite']['pipvGenant']!=0)OR($data['activite']['pipvAutre']!=0))
		{
		$html.='<tr><th class="sstitre" colspan="2">PI / PV</th></tr>';
		$html.=($data['activite']['pipvTrottoir']!=0) ? '<tr><th width="50%">Trottoir :</th><td align="center">'.$data['activite']['pipvTrottoir'].'</td></tr>' : '';
		$html.=($data['activite']['pipvZChargt']!=0) ? '<tr><th width="50%">Zone chargement :</th><td align="center">'.$data['activite']['pipvZChargt'].'</td></tr>' : '';
		$html.=($data['activite']['pipvBus']!=0) ? '<tr><th width="50%">Bande / Arr&ecirc;t bus :</th><td align="center">'.$data['activite']['pipvBus'].'</td></tr>' : '';
		$html.=($data['activite']['pipvPMR']!=0) ? '<tr><th width="50%">PMR :</th><td align="center">'.$data['activite']['pipvPMR'].'</td></tr>' : '';
		$html.=($data['activite']['pipvPisteCycl']!=0) ? '<tr><th width="50%">Piste cyclable :</th><td align="center">'.$data['activite']['pipvPisteCycl'].'</td></tr>' : '';
		$html.=($data['activite']['pipvPassPietons']!=0) ? '<tr><th width="50%">Passage pi&eacute;tons :</th><td align="center">'.$data['activite']['pipvPassPietons'].'</td></tr>' : '';
		$html.=($data['activite']['pipvE1']!=0) ? '<tr><th width="50%">E1 :</th><td align="center">'.$data['activite']['pipvE1'].'</td></tr>' : '';
		$html.=($data['activite']['pipvE3']!=0) ? '<tr><th width="50%">E3 :</th><td align="center">'.$data['activite']['pipvE3'].'</td></tr>' : '';
		$html.=($data['activite']['pipvGSM']!=0) ? '<tr><th width="50%">GSM :</th><td align="center">'.$data['activite']['pipvGSM'].'</td></tr>' : '';
		$html.=($data['activite']['pipvCeinture']!=0) ? '<tr><th width="50%">Ceinture :</th><td align="center">'.$data['activite']['pipvCeinture'].'</td></tr>' : '';
		$html.=($data['activite']['pipvCasque']!=0) ? '<tr><th width="50%">Casque :</th><td align="center">'.$data['activite']['pipvCasque'].'</td></tr>' : '';
		$html.=($data['activite']['pipvC1']!=0) ? '<tr><th width="50%">C1 :</th><td align="center">'.$data['activite']['pipvC1'].'</td></tr>' : '';
		$html.=($data['activite']['pipvStop']!=0) ? '<tr><th width="50%">Stop :</th><td align="center">'.$data['activite']['pipvStop'].'</td></tr>' : '';
		$html.=($data['activite']['pipvOrange']!=0) ? '<tr><th width="50%">Feu orange :</th><td align="center">'.$data['activite']['pipvOrange'].'</td></tr>' : '';
		$html.=($data['activite']['pipvRouge']!=0) ? '<tr><th width="50%">Feu rouge :</th><td align="center">'.$data['activite']['pipvRouge'].'</td></tr>' : '';
		$html.=($data['activite']['pipvGenant']!=0) ? '<tr><th width="50%">G&ecirc;nant :</th><td align="center">'.$data['activite']['pipvGenant'].'</td></tr>' : '';
		$html.=($data['activite']['pipvAutre']!=0) ? '<tr><th width="50%">PI / PV Autre :</th><td align="center">'.$data['activite']['pipvAutre'].'</td></tr>' : '';
		
		}
	
	if(($data['activite']['alcoVVCtrl']!=0)OR($data['activite']['alcoPersCtrl']!=0)OR($data['activite']['alcoA']!=0)OR($data['activite']['alcoP']!=0)OR($data['activite']['alcoRetraits']!=0)OR($data['activite']['alcoPds']!=0)OR($data['activite']['alcoStups']!=0)OR($data['activite']['alcoSuiteAcc']!=0))
		{
		$html.='<tr><th class="sstitre" colspan="2">ALCO</th></tr>';
		$html.=($data['activite']['alcoVVCtrl']!=0) ? '<tr><th width="50%">Nbre v&eacute;hicules contr&ocirc;l&eacute;s :</th><td align="center">'.$data['activite']['alcoVVCtrl'].'</td></tr>' : '';
		$html.=($data['activite']['alcoPersCtrl']!=0) ? '<tr><th width="50%">Nbre personnes contr&ocirc;l&eacute;es :</th><td align="center">'.$data['activite']['alcoPersCtrl'].'</td></tr>' : '';
		$html.=($data['activite']['alcoA']!=0) ? '<tr><th width="50%">Nbre A :</th><td align="center">'.$data['activite']['alcoA'].'</td></tr>' : '';
		$html.=($data['activite']['alcoP']!=0) ? '<tr><th width="50%">Nbre P :</th><td align="center">'.$data['activite']['alcoP'].'</td></tr>' : '';
		$html.=($data['activite']['alcoRetraits']!=0) ? '<tr><th width="50%">Nbre retraits :</th><td align="center">'.$data['activite']['alcoRetraits'].'</td></tr>' : '';
		$html.=($data['activite']['alcoPds']!=0) ? '<tr><th width="50%">Nbre prise de sang :</th><td align="center">'.$data['activite']['alcoPds'].'</td></tr>' : '';
		$html.=($data['activite']['alcoStups']!=0) ? '<tr><th width="50%">PV stups :</th><td align="center">'.$data['activite']['alcoStups'].'</td></tr>' : '';
		$html.=($data['activite']['alcoSuiteAcc']!=0) ? '<tr><th width="50%">Suite accident :</th><td align="center">'.$data['activite']['alcoSuiteAcc'].'</td></tr>' : '';
		}
		
	if(($data['activite']['plNbrCtrl']!=0)OR($data['activite']['plPI']!=0)OR($data['activite']['plPV']!=0)OR($data['activite']['plNbrAdr']!=0)OR($data['activite']['plPIAdr']!=0)OR($data['activite']['plPVAdr']!=0))
		{
		$html.='<tr><th class="sstitre" colspan="2">PI POIDS LOURDS</th></tr>';
		$html.=($data['activite']['plNbrCtrl']!=0) ? '<tr><th width="50%">Nbr PL contr&ocirc;l&eacute;s :</th><td align="center">'.$data['activite']['plNbrCtrl'].'</td></tr>' : '';
		$html.=($data['activite']['plPI']!=0) ? '<tr><th width="50%">PI :</th><td align="center">'.$data['activite']['plPI'].'</td></tr>' : '';
		$html.=($data['activite']['plPV']!=0) ? '<tr><th width="50%">PV :</th><td align="center">'.$data['activite']['plPV'].'</td></tr>' : '';
		$html.=($data['activite']['plNbrAdr']!=0) ? '<tr><th width="50%">Nbr ADR contr&ocirc;l&eacute;s :</th><td align="center">'.$data['activite']['plNbrAdr'].'</td></tr>' : '';
		$html.=($data['activite']['plPIAdr']!=0) ? '<tr><th width="50%">PI ADR :</th><td align="center">'.$data['activite']['plPIAdr'].'</td></tr>' : '';
		$html.=($data['activite']['plPVAdr']!=0) ? '<tr><th width="50%">PV ADR :</th><td align="center">'.$data['activite']['plPVAdr'].'</td></tr>' : '';
		}
	$html.='<tr><th colspan="2" class="titre">COMMENTAIRES SERVICE</th></tr>';
	$html.='<tr><td colspan="2"><textarea id="commentaire" rows="4" cols="80">'.$data['activite']['commentaire'].'</textarea></td></tr>';	
	$html.='</table>';
	$html.='</div>';
	
	return $html;
	}
	
function datefr($date,$dateOnly=0) 
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
	
function getSubject($idpat,$pdo)
	{
	require_once ('../connect.php');
	$sql='SELECT date_heure_debut, date_heure_fin, indicatif FROM z_patrouille WHERE id_patrouille="'.$idpat.'"';
	$rep=$pdo->query($sql);
	while ($row=$rep->fetch())
		{
		$indicatif = $row['indicatif'];
		$debut=dateHrfr($row['date_heure_debut'],1);
		$fin=dateHrfr($row['date_heure_fin'],1);
		}
	$html='BS de '.$indicatif.' du '.$debut.'.';
	
	return $html;
	}
?>