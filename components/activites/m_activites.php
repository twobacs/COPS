<?php

class MActivites extends MBase {

	private $checkDbPDO = false;

	public function __construct($appli) {
		parent::__construct($appli);
		
	}
	
public function controleByPm()
	{
	$subAction=$_GET['subaction'];
	$field=$_GET['field'];
	$bs=$_SESSION['idbs'];
	$sql='SELECT '.$field.' FROM z_bs WHERE id_bs="'.$_SESSION['idbs'].'"';
	$rep=$this->appli->dbPdo->query($sql);
	while ($row=$rep->fetch())
		{
		$value=$row[$field];
		}
	switch ($subAction)
		{
		case 'add':
			$value++;
			break;
		case 'rem';
			if ($value!=0)
				{
				$value--;
				}
			break;
		}
	$sql='UPDATE z_bs SET '.$field.'="'.$value.'" WHERE id_bs="'.$_SESSION['idbs'].'"';

	// echo $sql.'<br />';
	$this->appli->dbPdo->exec($sql);
	$_SESSION[$field]=$value;
	}
	
public function recNumFicheByIdPat()
	{
	$idPat=$_SESSION['idpat'];
	$fiche=$_POST['numFiche'];
	echo $fiche;
	}
	
public function getInfosBS()
	{
	$idBS=$_SESSION['idbs'];
	$idpat=$_SESSION['idpat'];
	//INFOS PATROUILLES
	$sql='SELECT * FROM z_patrouille WHERE id_patrouille="'.$idpat.'"';
	$rep=$this->appli->dbPdo->query($sql);
	while ($row=$rep->fetch())
		{
		$data['patrouille']['debut']=$row['date_heure_debut'];
		$data['patrouille']['fin']=$row['date_heure_fin'];
		$data['patrouille']['indicatif']=$row['indicatif'];
		$data['patrouille']['denomination']=$row['denomination'];
		$data['patrouille']['id']=$row['id_patrouille'];
		}
	
	//COLLABORATEURS ENGAGES
	$sql='SELECT 
	a.nom, a.prenom
	FROM users a
	LEFT JOIN z_bs_users b ON a.id_user=b.id_user
	WHERE b.id_bs="'.$idBS.'"
	ORDER BY a.nom, a.prenom';
	$rep=$this->appli->dbPdo->query($sql);
	$i=0;
	while ($row=$rep->fetch())
		{
		$data['collaborateur'][$i]=$row['nom'].' '.$row['prenom'];
		$i++;
		}
	$data['collaborateur']['ttl']=$i;
	
	//ARME COLLECTIVE
	$sql='SELECT id_arme FROM z_bs_armeCollec WHERE id_bs="'.$idBS.'"';
	$rep=$this->appli->dbPdo->query($sql);
	$i=0;
	while ($row=$rep->fetch())
		{
		$data['armeCollec'][$i]=$row['id_arme'];
		$i++;
		}
	$data['armeCollec']['ttl']=$i;
	
	//APPAREIL PHOTO
	$sql='SELECT app_photo FROM z_bs WHERE id_bs="'.$idBS.'"';
	$rep=$this->appli->dbPdo->query($sql);
	while ($row=$rep->fetch())
		{
		$data['app_photo']=$row['app_photo'];
		}
		
	//ETT
	$sql='SELECT id_ETT FROM z_bs_ETT WHERE id_bs="'.$idBS.'"';
	$rep=$this->appli->dbPdo->query($sql);
	while ($row=$rep->fetch())
		{
		$data['ETT']=$row['id_ETT'];
		}
	
	//GSM
	$sql='SELECT id_GSM FROM z_bs_GSM WHERE id_bs="'.$idBS.'"';
	$rep=$this->appli->dbPdo->query($sql);
	while ($row=$rep->fetch())
		{
		$data['GSM']=$row['id_GSM'];
		}

	//VEHICULE
	$sql='SELECT immatriculation FROM z_bs_vv WHERE id_bs="'.$idBS.'"';
	$rep=$this->appli->dbPdo->query($sql);
	$i=0;
	while ($row=$rep->fetch())
		{
		$data['vv'][$i]['immatriculation']=$row['immatriculation'];
		$sql='SELECT plein, degats FROM z_bs_vv WHERE id_bs=:idBS AND immatriculation=:immat';
		$req=$this->appli->dbPdo->prepare($sql);
		if($req->execute(array('idBS'=>$idBS, 'immat'=>$data['vv'][$i]['immatriculation']))){
			while($row=$req->fetch()){
			$data['vv'][$i]['plein']=$row['plein'];
			$data['vv'][$i]['degats']=$row['degats'];
			}
		}
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
	$rep=$this->appli->dbPdo->query($sql);
	// echo $sql;
	while ($row=$rep->fetch())
		{
		if ($row['type_mission']=='vacanciers')
			{
			$data['mission'][$i]['code_prest']='vacanciers';
			$data['mission'][$i]['heure_debut']=$row['date_heure_in'];
			$data['mission'][$i]['heure_fin']=$row['date_heure_out'];
			$data['mission'][$i]['commentaire']=$row['commentaireFiche'];
			$data['mission'][$i]['rue']=$row['NomRue'];
			$data['mission'][$i]['numero']=$row['vac_numero'];
			$data['mission'][$i]['CP']=$row['vac_CP'];
			$data['mission'][$i]['ville']=$row['vac_ville'];
			}
		if ($row['type_mission']=='CS')
			{
			$data['mission'][$i]['code_prest']='Contrôle statique';
			$data['mission'][$i]['heure_debut']=$row['date_heure_in'];
			$data['mission'][$i]['heure_fin']=$row['date_heure_out'];
			$data['mission'][$i]['commentaire']=$row['commentaire'];			
			$data['mission'][$i]['lieu']=$row['lieu'];
			}
		if ($row['type_mission']=='PV')
			{
			$data['mission'][$i]['code_prest']='Patrouille en véhicule';
			$data['mission'][$i]['heure_debut']=$row['date_heure_in'];
			$data['mission'][$i]['heure_fin']=$row['date_heure_out'];
			$data['mission'][$i]['commentaire']=$row['commentaire'];			
			$data['mission'][$i]['lieu']=$row['lieu'];			
			}
		if ($row['type_mission']=='PP')
			{
			$data['mission'][$i]['code_prest']='Patrouille pédestre';
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
			$data['mission'][$i]['code_prest']='Service intérieur';
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
	$rep=$this->appli->dbPdo->query($sql);
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
	// echo $sql;
	
	
	// echo $sql;
	
	return $data;
	}
	
public function StartSvInt()
	{
	$idPat=$_SESSION['idpat'];
	$svint=uniqid();
	$sql='INSERT INTO z_pat_missions (id_patrouille, id_fiche, type_mission, date_heure_in) VALUES ("'.$idPat.'", "'.$svint.'", "SI", NOW())';
	$_SESSION['SvInt']=$svint;
	$this->appli->dbPdo->exec($sql);
	}
	
public function EndSvInt()
	{
	$idPat=$_SESSION['idpat'];
	$sql='UPDATE z_pat_missions SET date_heure_out=NOW(), commentaire="'.$_POST['textSvInt'].'" WHERE id_patrouille="'.$idPat.'" AND date_heure_out="0000-00-00 00:00:00" AND id_fiche="'.$_SESSION['SvInt'].'"';
	$this->appli->dbPdo->exec($sql);
	$_SESSION['SvInt']=0;
	}
	
public function StartPat()
	{
	$idPat=$_SESSION['idpat'];
	$pat=uniqid();
	$sql='INSERT INTO z_pat_missions (id_patrouille, id_fiche, type_mission, date_heure_in) VALUES ("'.$idPat.'", "'.$pat.'", "PAT", NOW())';
	$_SESSION['Pat']=$pat;
	$this->appli->dbPdo->exec($sql);
	}
	
public function EndPat()
	{
	$idPat=$_SESSION['idpat'];
	$sql='UPDATE z_pat_missions SET date_heure_out=NOW(), type_mission="'.$_POST['typPat'].'", commentaire="'.$_POST['textSvInt'].'", lieu=(SELECT nom_lieu FROM z_lieu_mission WHERE id_lieu="'.$_POST['lieu'].'") WHERE id_patrouille="'.$idPat.'" AND date_heure_out="0000-00-00 00:00:00" AND id_fiche="'.$_SESSION['Pat'].'"';
	$this->appli->dbPdo->exec($sql);
	$_SESSION['Pat']=0;
	}	
	
public function getLieuxPat(){
	$sql='SELECT id_lieu, nom_lieu FROM z_lieu_mission ORDER BY nom_lieu';
	$req=$this->appli->dbPdo->query($sql);
	// $req->execute();
	return $req;
}
	
public function checkInterEncours(){
		$data=array();
		$idPat=$_SESSION['idpat'];
		$sql='SELECT COUNT(*) FROM z_pat_missions WHERE id_patrouille="'.$idPat.'" AND type_mission="INT" AND date_heure_in != "0000-00-00 00:00:00" AND date_heure_out = "0000-00-00 00:00:00"';
		$rep=$this->appli->dbPdo->query($sql);
		while ($row=$rep->fetch())
			{
			$count=$row['COUNT(*)'];
			}
		if ($count>0)
			{
			$sql='SELECT date_heure_in FROM z_pat_missions WHERE id_patrouille="'.$idPat.'" AND type_mission="INT" AND date_heure_in != "0000-00-00 00:00:00" AND date_heure_out = "0000-00-00 00:00:00"';
			$rep=$this->appli->dbPdo->query($sql);
			while ($row=$rep->fetch())
				{
				$data['hDeb']=$row['date_heure_in'];
				}
			}
		else {$data['hDeb']=0;}
		return $data;
	}
	
public function getPerso(){
	$data=array();
	$sql='SELECT nom, prenom, id_user FROM users ORDER BY nom, prenom';
	$data['all']=$this->appli->dbPdo->query($sql);
	$sql='SELECT b.nom, b.prenom, b.id_user 
	FROM z_bs_users a LEFT JOIN users b ON b.id_user = a.id_user
	WHERE id_bs="'.$_GET['idBS'].'"
	ORDER BY b.nom, b.prenom';
	$data['logged']=$this->appli->dbPdo->query($sql);
	return $data;
}

public function getVV(){
	$data=array();
	$sql='SELECT immatriculation, modele FROM z_vv_zp ORDER BY immatriculation';
	$data['all']=$this->appli->dbPdo->query($sql);
	$sql='SELECT COUNT(*) FROM z_bs_vv WHERE id_bs="'.$_GET['idBS'].'"';
	$req=$this->appli->dbPdo->query($sql);
	while($row=$req->fetch()){
		$count=$row['COUNT(*)'];
	}
	if($count>0){
		$sql='SELECT immatriculation, plein, degats FROM z_bs_vv WHERE id_bs="'.$_GET['idBS'].'"';
		$data['logged']=$this->appli->dbPdo->query($sql);
	}
	else $data['logged']=0;
	return $data;
}

public function getMatos(){
	$bs=$_GET['idBS'];
	
	$data=array();
	/* ARMES */
	$sql='SELECT num_arme FROM armes WHERE marque_arme="UZI" AND disponible="O" ORDER BY num_arme';
	$data['armes']=$this->appli->dbPdo->query($sql);
	$sql='SELECT COUNT(*) FROM z_bs_armeCollec WHERE id_bs="'.$bs.'"';
	$rep=$this->appli->dbPdo->query($sql);
	while ($row=$rep->fetch()){
		$count=$row['COUNT(*)'];
	}
	if ($count>0){
		$sql='SELECT id_arme FROM z_bs_armeCollec WHERE id_bs="'.$bs.'"';
		$rep=$this->appli->dbPdo->query($sql);
		while($row=$rep->fetch()){
			$data['selectedArme']=$row['id_arme'];
		}
	}
	else $data['selectedArme']=0;
	/* GSM */
	$sql='SELECT app_photo FROM z_bs WHERE id_bs="'.$bs.'"';
	$rep=$this->appli->dbPdo->query($sql);
	while($row=$rep->fetch()){
		$data['photo']=$row['app_photo'];
	}
	/* ETT */
	$sql='SELECT id_ETT FROM z_ETT WHERE date_validite>NOW() ORDER BY id_ETT';
	$data['ETT']=$this->appli->dbPdo->query($sql);
	$sql='SELECT COUNT(*) FROM z_bs_ETT WHERE id_bs="'.$bs.'"';
	$rep=$this->appli->dbPdo->query($sql);
	while($row=$rep->fetch()){
		$count=$row['COUNT(*)'];
	}
	if($count>"0"){
		$sql='SELECT id_ETT FROM z_bs_ETT WHERE id_bs="'.$bs.'"';
		$rep=$this->appli->dbPdo->query($sql);
		while($row=$rep->fetch()){
			$data['selectedETT']=$row['id_ETT'];		
		}
	}
	else $data['selectedETT']=0;
	
	
	/* GSM */
	$sql='SELECT num_GSM FROM z_GSM ORDER BY num_GSM';
	$data['GSM']=$this->appli->dbPdo->query($sql);
	
	$sql='SELECT COUNT(*) FROM z_bs_GSM WHERE id_bs="'.$bs.'"';
	$rep=$this->appli->dbPdo->query($sql);
	while($row=$rep->fetch()){
		$count=$row['COUNT(*)'];
	}
	if($count>"0"){
		$sql='SELECT id_GSM FROM z_bs_GSM WHERE id_bs="'.$bs.'"';
		$rep=$this->appli->dbPdo->query($sql);
		while($row=$rep->fetch()){
			$data['selectedGSM']=$row['id_GSM'];		
		}
	}
	else $data['selectedGSM']=0;
	return $data;
	
}
}
?>
