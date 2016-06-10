<?php

class MChiffres extends MBase {

	private $checkDbPDO = false;

	public function __construct($appli) {
		parent::__construct($appli);
		
	}
	
public function getNivAcces()
	{
	$sql='SELECT id_nivAcces FROM z_user_app WHERE id_user="'.$_COOKIE['iduser'].'" AND id_app="9"';
	$rep=$this->appli->dbPdo->query($sql);
	while ($row=$rep->fetch())
		{
		$nivAcces=$row['id_nivAcces'];
		}
	return $nivAcces;
	}
	
public function getFonctionnalites(){
	$sql='SELECT id_fonctionnalite, denomination FROM z_fonctionnalites';
	$req=$this->appli->dbPdo->prepare($sql);
	$req->execute(array());
	return $req;
}

public function getUsers(){
	
	$sql='SELECT id_user, nom, prenom FROM users ORDER BY nom, prenom ASC';
	$req=$this->appli->dbPdo->prepare($sql);
	$req->execute(array());
	return $req;	
}

public function getsearchDenom($type, $denom, $db, $dh){
	/*RECHERCHE DES IDENTIFIANTS DE PATROUILLES CORRESPONDANT AUX CRITERES INTRODUITS*/
	if($type=='denom'){
		$result=array();
		$sql='SELECT id_patrouille FROM z_patrouille WHERE denomination LIKE :denom AND date_heure_debut >= :DB AND date_heure_fin <= :DH';
		$req=$this->appli->dbPdo->prepare($sql);
		$req->execute(array(
		'denom' => $denom,
		'DB' => $db.' 00:00:00',
		'DH' => $dh.' 23:59:59'
		));
		$i=0;
		while($row=$req->fetch()){
			$rep[$i]['idpat']=$row['id_patrouille'];
			$i++;
		}
	}
	
	if($type=='indic'){
		$result=array();
		$sql='SELECT id_patrouille FROM z_patrouille WHERE id_prestation = :denom AND date_heure_debut >= :DB AND date_heure_fin <= :DH';
		$req=$this->appli->dbPdo->prepare($sql);
		$req->execute(array(
		'denom' => $denom,
		'DB' => $db.' 00:00:00',
		'DH' => $dh.' 23:59:59'
		));
		$i=0;
		while($row=$req->fetch()){
			$rep[$i]['idpat']=$row['id_patrouille'];
			$i++;
		}
		$sql='SELECT a.denomination AS denomPrest, a.descriptif, b.denomination AS denomFonc
		FROM z_prestations a
		LEFT JOIN z_fonctionnalites b ON b.id_fonctionnalite = a.id_fonctionnalite
		WHERE a.id_prestation=:id';
		$req=$this->appli->dbPdo->prepare($sql);
		$req->execute(array(
		'id'=>$denom
		));
		while($row=$req->fetch()){
			$result['fonctionnalite']=$row['denomFonc'];
			$result['prestation']=$row['denomPrest'];
		}
	}
	
	if($type=='user'){
		$result=array();
		$sql='SELECT id_bs FROM z_bs_users WHERE id_user=:id';
		$req=$this->appli->dbPdo->prepare($sql);
		$req->execute(array(
		'id' => $denom
		));
		$i=0;
		$idBS=array();
		while($row=$req->fetch()){
			$idBS[$i]['id']=$row['id_bs'];
			$i++;
		}
		
		$sql2='SELECT id_patrouille FROM z_bs WHERE id_bs = :idBS';
		$req2=$this->appli->dbPdo->prepare($sql2);
		$idPat=array();
		for($i=0;$i<sizeof($idBS);$i++){
			$req2->execute(array(
			'idBS'=>$idBS[$i]['id']
			));
			while($row2=$req2->fetch()){
				$idPat[$i]['id']=$row2['id_patrouille'];
			}
		}
		$i=0;
		for($j=0;$j<sizeof($idPat);$j++){
		$sql3='SELECT id_patrouille FROM z_patrouille WHERE id_patrouille = "'.$idPat[$j]['id'].'" AND date_heure_debut >= "'.$db.'" AND date_heure_fin <= "'.$dh.'"';
		$rep3=$this->appli->dbPdo->query($sql3);

		while($row3=$rep3->fetch()){
				$rep[$i]['idpat']=$row3['id_patrouille'];
				$i++;
			}
		}
		
		$sql='SELECT nom, prenom FROM users WHERE id_user=:id';
		$req=$this->appli->dbPdo->prepare($sql);
		$req->execute(array(
		'id'=>$denom
		));
		while($row=$req->fetch()){
			$result['nom']=$row['nom'];
			$result['prenom']=$row['prenom'];
		}
	}
	
	/*********************************************************************************/
		
	if(isset($rep)){
		$result['nbPatrouilles']=$i;
		$result['ctrlPers']=0;
		$result['ctrlVV']=0;
		$result['vvFouille']=0;
		$result['arrestAdm']=0;
		$result['arrestJud']=0;
		$result['OC']=0;
		$result['BCS']=0;
		$result['fuguesDisp']=0;
		$result['AI']=0;
		$result['pvRgp']=0;
		$result['rebellion']=0;
		$result['pvArmes']=0;
		$result['pvStups']=0;
		$result['pvOutrages']=0;
		$result['pvIvresse']=0;
		$result['pvCoups']=0;
		$result['pvVol']=0;
		$result['pvDiffFamssCoups']=0;
		$result['pvDiffFamAvecCoups']=0;
		$result['pvDegradations']=0;
		$result['pvTapagePart']=0;
		$result['pvTapageEts']=0;
		$result['pvJudAutres']=0;
		$result['pvAccident']=0;
		$result['amiable']=0;
		$result['vitVvCtrl']=0;
		$result['vitPVPI']=0;
		$result['vitRetraits']=0;
		$result['defAss']=0;
		$result['defImm']=0;
		$result['defCT']=0;
		$result['defAssImCT']=0;
		$result['defAssIm']=0;
		$result['defImmCT']=0;
		$result['defPC']=0;
		$result['pvaAssur']=0;
		$result['pvaPC']=0;
		$result['pvaCI']=0;
		$result['pvaExtTrBoite']=0;
		$result['pvaPneus']=0;
		$result['pvaCT']=0;
		$result['pvaIm']=0;
		$result['cycloNbCtrl']=0;
		$result['cycloNonConforme']=0;
		$result['cycloVitNCDefAss']=0;
		$result['cycloDefAss']=0;
		$result['cycloPlaqueJaune']=0;
		$result['cycloAutres']=0;
		$result['cycloEnlSaisies']=0;
		$result['pipvTrottoir']=0;
		$result['pipvZChargt']=0;
		$result['pipvBus']=0;
		$result['pipvPMR']=0;
		$result['pipvPisteCycl']=0;
		$result['pipvPassPietons']=0;
		$result['pipvE1']=0;
		$result['pipvE3']=0;
		$result['pipvGSM']=0;
		$result['pipvCeinture']=0;
		$result['pipvCasque']=0;
		$result['pipvC1']=0;
		$result['pipvStop']=0;
		$result['pipvOrange']=0;
		$result['pipvRouge']=0;
		$result['pipvGenant']=0;
		$result['alcoVVCtrl']=0;
		$result['alcoPersCtrl']=0;
		$result['alcoA']=0;
		$result['alcoP']=0;
		$result['alcoRetraits']=0;
		$result['alcoPds']=0;
		$result['alcoStups']=0;
		$result['alcoSuiteAcc']=0;
		$result['plNbrCtrl']=0;
		$result['plPI']=0;
		$result['plPV']=0;
		$result['plNbrAdr']=0;
		$result['plPIAdr']=0;
		$result['plPVAdr']=0;
		$result['PVRoulAutre']=0;
		$result['PVAAutre']=0;
		$result['pipvAutre']=0;
		
		for($j=0;$j<$i;$j++){
			$sql='SELECT * FROM z_bs WHERE id_patrouille=:id';
			$req=$this->appli->dbPdo->prepare($sql);
			$req->execute(array(
			'id' => $rep[$j]['idpat']
			));
			while($row=$req->fetch()){
				$result['ctrlPers']+=$row['ctrlPers'];
				$result['ctrlVV']+=$row['ctrlVV'];
				$result['vvFouille']+=$row['vvFouille'];
				$result['arrestAdm']+=$row['arrestAdm'];
				$result['arrestJud']+=$row['arrestJud'];
				$result['OC']+=$row['OC'];
				$result['BCS']+=$row['BCS'];
				$result['fuguesDisp']+=$row['fuguesDisp'];
				$result['AI']+=$row['AI'];
				$result['pvRgp']+=$row['pvRgp'];
				$result['rebellion']+=$row['rebellion'];
				$result['pvArmes']+=$row['pvArmes'];
				$result['pvStups']+=$row['pvStups'];
				$result['pvOutrages']+=$row['pvOutrages'];
				$result['pvIvresse']+=$row['pvIvresse'];
				$result['pvCoups']+=$row['pvCoups'];
				$result['pvVol']+=$row['pvVol'];
				$result['pvDiffFamssCoups']+=$row['pvDiffFamssCoups'];
				$result['pvDiffFamAvecCoups']+=$row['pvDiffFamAvecCoups'];
				$result['pvDegradations']+=$row['pvDegradations'];
				$result['pvTapagePart']+=$row['pvTapagePart'];
				$result['pvTapageEts']+=$row['pvTapageEts'];
				$result['pvJudAutres']+=$row['pvJudAutres'];
				$result['pvAccident']+=$row['pvAccident'];
				$result['amiable']+=$row['amiable'];
				$result['vitVvCtrl']+=$row['vitVvCtrl'];
				$result['vitPVPI']+=$row['vitPVPI'];
				$result['vitRetraits']+=$row['vitRetraits'];
				$result['defAss']+=$row['defAss'];
				$result['defImm']+=$row['defImm'];
				$result['defCT']+=$row['defCT'];
				$result['defAssImCT']+=$row['defAssImCT'];
				$result['defAssIm']+=$row['defAssIm'];
				$result['defImmCT']+=$row['defImmCT'];
				$result['defPC']+=$row['defPC'];
				$result['pvaAssur']+=$row['pvaAssur'];
				$result['pvaPC']+=$row['pvaPC'];
				$result['pvaCI']+=$row['pvaCI'];
				$result['pvaExtTrBoite']+=$row['pvaExtTrBoite'];
				$result['pvaPneus']+=$row['pvaPneus'];
				$result['pvaCT']+=$row['pvaCT'];
				$result['pvaIm']+=$row['pvaIm'];
				$result['cycloNbCtrl']+=$row['cycloNbCtrl'];
				$result['cycloNonConforme']+=$row['cycloNonConforme'];
				$result['cycloVitNCDefAss']+=$row['cycloVitNCDefAss'];
				$result['cycloDefAss']+=$row['cycloDefAss'];
				$result['cycloPlaqueJaune']+=$row['cycloPlaqueJaune'];
				$result['cycloAutres']+=$row['cycloAutres'];
				$result['cycloEnlSaisies']+=$row['cycloEnlSaisies'];
				$result['pipvTrottoir']+=$row['pipvTrottoir'];
				$result['pipvZChargt']+=$row['pipvZChargt'];
				$result['pipvBus']+=$row['pipvBus'];
				$result['pipvPMR']+=$row['pipvPMR'];
				$result['pipvPisteCycl']+=$row['pipvPisteCycl'];
				$result['pipvPassPietons']+=$row['pipvPassPietons'];
				$result['pipvE1']+=$row['pipvE1'];
				$result['pipvE3']+=$row['pipvE3'];
				$result['pipvGSM']+=$row['pipvGSM'];
				$result['pipvCeinture']+=$row['pipvCeinture'];
				$result['pipvCasque']+=$row['pipvCasque'];
				$result['pipvC1']+=$row['pipvC1'];
				$result['pipvStop']+=$row['pipvStop'];
				$result['pipvOrange']+=$row['pipvOrange'];
				$result['pipvRouge']+=$row['pipvRouge'];
				$result['pipvGenant']+=$row['pipvGenant'];
				$result['alcoVVCtrl']+=$row['alcoVVCtrl'];
				$result['alcoPersCtrl']+=$row['alcoPersCtrl'];
				$result['alcoA']+=$row['alcoA'];
				$result['alcoP']+=$row['alcoP'];
				$result['alcoRetraits']+=$row['alcoRetraits'];
				$result['alcoPds']+=$row['alcoPds'];
				$result['alcoStups']+=$row['alcoStups'];
				$result['alcoSuiteAcc']+=$row['alcoSuiteAcc'];
				$result['plNbrCtrl']+=$row['plNbrCtrl'];
				$result['plPI']+=$row['plPI'];
				$result['plPV']+=$row['plPV'];
				$result['plNbrAdr']+=$row['plNbrAdr'];
				$result['plPIAdr']+=$row['plPIAdr'];
				$result['plPVAdr']+=$row['plPVAdr'];
				$result['PVRoulAutre']+=$row['PVRoulAutre'];
				$result['PVAAutre']+=$row['PVAAutre'];
				$result['pipvAutre']+=$row['pipvAutre'];
			}
		}
		$result['error']=0;
	}
	else $result['error']=1;
	
	$result['denomination']=$denom;
	$result['debut']=$db;
	$result['fin']=$dh;
	return $result;
}
	
}
?>