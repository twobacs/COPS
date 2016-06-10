<?php

class BS

{
public $pdo;


public function __construct($dbPdo)
	{
	$this->pdo=$dbPdo;
	}
	
public function getDenEquipe()
	{
	$sql='SELECT id_denomination, denomination FROM z_denom_equipe ORDER BY denomination';
	$rep=$this->pdo->query($sql);
	return $rep;
	}
	
public function selectAllCars()
	{
	$sql='SELECT immatriculation, modele FROM z_vv_zp ORDER BY immatriculation';
	return $this->pdo->query($sql);
	}
	
public function selectUZIDispo()
	{
	$sql='SELECT num_arme FROM armes WHERE marque_arme="UZI" AND disponible="O"';
	return $this->pdo->query($sql);
	}
	
public function selectIndicatifsNow()
	{
	$date = date("Y-m-d");
	$heure = date("H:i:s");
	
	$h = explode(":",date("H:i:s"));
	$hm1=($h[0]+1);
	$now=$date.' '.$hm1.':'.$h[1].':00';
	
	$sql='SELECT id_patrouille, denomination, indicatif FROM z_patrouille WHERE "'.$now.'" BETWEEN date_heure_debut AND date_heure_fin AND actif="O" ORDER BY indicatif';
	return $this->pdo->query($sql);
	}
	
function generate_uuid()
	{
    $md5 = md5(uniqid('', true));
	return $md5;
	}
	
public function doNewBS($armeC, $appPh, $ett, $gsm, $denom, $indic, $id, $collabo, $vv)
	{
	/*VERIFICATION DE L'EXISTENCE DU BS EN CAS DE DECONNEXION INVOLONTAIRE*/
	$sql='SELECT COUNT(*) FROM z_bs WHERE id_patrouille=:idPat';
	$req=$this->pdo->prepare($sql);
	$req->execute(array(
	'idPat' => $id
	));
	while ($row=$req->fetch()){
		$count=$row['COUNT(*)'];
	}
	
	if ($count!=0){
	$sql='SELECT id_bs, ctrlPers, ctrlVV FROM z_bs WHERE id_patrouille=:idPat';
	$req=$this->pdo->prepare($sql);
	$req->execute(array(
	'idPat' => $id
	));
	while ($row=$req->fetch()){
		$_SESSION['idbs']=$row['id_bs'];
		$_SESSION['ctVV']=$row['ctrlVV'];
		$_SESSION['ctPers']=$row['ctrlPers'];
		}
	// $_SESSION['idbs']=	
	$_SESSION['indic']=$indic;
	$_SESSION['idpat']=$id;
	}
	
	/*SI PAS, CREATION D'UNE NOUVELLE LIGNE EN BDD, TABLE z_bs*/
	else{
	/* CRÉATION D'UN IDENTIFIANT UNIQUE */
	$uuid=$this->generate_uuid();
	// $_SESSION['idbs']=$this->generate_uuid();
	$_SESSION['idbs']=$uuid;
	$_SESSION['indic']=$indic;
	$_SESSION['idpat']=$id;
	$_SESSION['ctVV']=0;
	$_SESSION['ctPers']=0;
	
		
	// /* INSERTION EN BDD (z_bs) : ID_BS, DATE_HEURE_IN, ID PATROUILLE */
	$sql='INSERT INTO z_bs (id_bs, date_heure_in, id_patrouille, app_photo) VALUES ("'.$_SESSION['idbs'].'", NOW(), "'.$id.'", "'.$appPh.'")';
	$this->pdo->exec($sql);
	
	/* PASSER LE STATUT ACTIF À "N" DE SORTE À CE QUE CETTE PATROUILLE NE PLUISSE PLUS ÊTRE CHOISIE POUR UN AUTRE BS */
	
	//$sql='UPDATE z_patrouille SET actif="N" WHERE id_patrouille="'.$id.'"';
	//$this->pdo->exec($sql);
	
	/* INSERTION EN BDD (z_bs_armecollec) de l'id_bs et du numéro d'arme */
	if ($armeC!=false)
		{
		$sql='INSERT INTO z_bs_armeCollec (id_bs, id_arme) VALUES ("'.$uuid.'", "'.$armeC.'")';
		$this->pdo->exec($sql);
		}
	
	/* INSERTION EN BDD (z_bs_ETT) de l'id_bs et de l'id_ett */
	if ($ett!=false)
		{
		// echo $ett;
		$sql='INSERT INTO z_bs_ETT (id_bs, id_ETT) VALUES ("'.$uuid.'", "'.$ett.'")';
		$this->pdo->exec($sql);
		}
		
	/* INSERTION EN BDD (z_bs_GSM) de l'id_bs et de l'id_gsm */
	if  ($gsm!=false)
		{
		$sql='INSERT INTO z_bs_GSM (id_bs, id_GSM) VALUES ("'.$uuid.'", "'.$gsm.'")';
		$this->pdo->exec($sql);
		}
	
	/* INSERTION EN BDD (z_bs_users) de l'id_bs et de(s) id_user "collaborateurs" */
	if($collabo!=-1)
		{
		for ($i=1;$i<=(sizeof($collabo));$i++)
			{
			$sql='INSERT INTO z_bs_users (id_bs, id_user) VALUES ("'.$uuid.'", "'.$collabo[$i].'")';
			$this->pdo->exec($sql);
			}
		}
	/* INSERTION EN BDD (z_bs_user) de l'id de l'utilisateur s'étant identifié */
	$sql='INSERT INTO z_bs_users (id_bs, id_user) VALUES ("'.$uuid.'", "'.$_COOKIE['iduser'].'")';
	$this->pdo->exec($sql);
			
	/* INSERTION EN BDD (z_bs_vv) des véhicules */
	if($vv!=-1)
		{
		for ($i=1;$i<=(sizeof($vv));$i++)
			{
			$sql='INSERT INTO z_bs_vv (id_bs, immatriculation) VALUES ("'.$uuid.'", "'.$vv[$i].'")';
			$this->pdo->exec($sql);
			}
		}
	}
	return $id;
	}
}
?>