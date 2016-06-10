<?php

class Vacancier
{

private $pdo;

public function __construct($dbPdo)
	{
	$this->pdo = $dbPdo;
	}

public function test()
	{
	$sql='SELECT vac_adresse FROM z_vac_habitation WHERE id_vac="1"';
	$rep=$this->pdo->query($sql);
	while ($row=$rep->fetch())
		{
		$adresse=$row['vac_adresse'];
		}
	return $adresse;
	}

public function getVacInfoById($id)
	{
	$bien=array();
	$sql='SELECT a.*, b.NomRue FROM z_vac_habitation a LEFT JOIN z_rues b ON a.IdRue = b.IdRue WHERE id_vac="'.$id.'"';
	$rep=$this->pdo->query($sql);
	while ($row=$rep->fetch())
		{
		// $bien['adresse']=$row['vac_adresse'];
		$bien['adresse']=$row['IdRue'];
		$bien['NomRue']=$row['NomRue'];
		$bien['numero']=$row['vac_numero'];
		$bien['CP']=$row['vac_CP'];
		$bien['ville']=$row['vac_ville'];
		$bien['demande']=$row['vac_dateDemande'];
		$bien['depart']=$row['vac_dateDepart'];
		$bien['retour']=$row['vac_dateRetour'];
	
		$bien['destination']=$row['vac_destination'];
		$bien['contSP']=$row['vac_contSP'];
		
		$bien['GDP']=$row['vac_GDP'];
		
		$bien['nbFacades']=$row['vac_nbFacades'];
		$bien['alarme']=$row['vac_alarme'];
		$bien['eclairageExt']=$row['vac_eclairageExt'];
		$bien['eclairageInt']=$row['vac_eclairageInt'];
		$bien['chien']=$row['vac_chien'];
		$bien['courrier']=$row['vac_courrier'];
		$bien['persCourrier']=$row['vac_persCourrier'];
		$bien['persAuto']=$row['vac_persAuto'];
		$bien['persPers']=$row['vac_persPers'];
		
		$bien['dateTechno']=$row['vac_dateVisiteTechno'];
		
		$bien['remarque']=$row['vac_remarque'];
		
		$bien['gMap']=$row['vac_gmap'];
		}
	return $bien;
	}
	
public function getSelect($id,$type)  //retourne un "select" pour les champs dont les options sont oui / non
	{
	switch ($type)
		{
		case 'chien' :
			$select='vac_chien';
			break;
			
		case 'eclairageInt' :
			$select='vac_eclairageInt';
			break;
			
		case 'eclairageExt':
			$select='vac_eclairageExt';
			break;

		case 'alarme':
			$select='vac_alarme';
			break;
			
		case 'courrier';
			$select='vac_courrier';
			break;
			
		case 'persienne':
			$select='vac_persAuto';
			break;
			
		case 'gdp' :
			$select='vac_GDP';
			break;
			
		}
		
	$sql='SELECT '.$select.' FROM z_vac_habitation WHERE id_vac="'.$id.'"';
	$rep=$this->pdo->query($sql);
	while ($row=$rep->fetch())
		{
		$data=$row[''.$select.''];
		}	
	
	if ($data=="O")
		{
		$html='<select name='.$type.'><option value="O" selected>Oui</option><option value="N">Non</option></select>';
		}
	else if ($data=="N")
		{
		$html='<select name='.$type.'><option value="O">Oui</option><option value="N" selected>Non</option></select>';
		}
	else
		{
		$html='<select name='.$type.'><option></option><option value="O">Oui</option><option value="N">Non</option></select>';
		}	
	return $html;
	}

public function getVVbyIdVac($id)
	{
	$sql='SELECT id_vv FROM z_vac_hab_vv WHERE id_vac="'.$id.'"';
	$rep=$this->pdo->query($sql);
	$i=1;
	while ($row=$rep->fetch())
		{
		$sqla='SELECT marque_vv, imm_vv, lieu_vv FROM z_vac_vv WHERE id_vv="'.$row['id_vv'].'"';
		$repa=$this->pdo->query($sqla);
		while ($rowa=$repa->fetch())
			{
			$vv[$i]['marque']=$rowa['marque_vv'];
			$vv[$i]['imm']=$rowa['imm_vv'];
			$vv[$i]['lieu']=$rowa['lieu_vv'];
			$vv['total']=$i;
			$i++;
			}	
		}
	return $vv;
	}
	
public function getPersByIdVac($id)  //Retourne les informations concernant les personnes de contact sur base de l'identifiant de la surveillance vacances
	{
	$sql='SELECT id_contact FROM z_vac_hab_cont WHERE id_vac="'.$id.'"';
	$rep=$this->pdo->query($sql);
	$i=1;
	while ($row=$rep->fetch())
		{
		$sqla='SELECT id_contact, nom_contact, prenom_contact, adresse_contact, numero_contact, CP_contact, ville_contact, tel_contact, tel2_contact FROM z_vac_contact WHERE id_contact="'.$row['id_contact'].'"';
		$repa=$this->pdo->query($sqla);
		while ($rowa=$repa->fetch())
			{
			$cont[$i]['idCont']=$rowa['id_contact'];
			$cont[$i]['nom']=$rowa['nom_contact'];
			$cont[$i]['prenom']=$rowa['prenom_contact'];
			$cont[$i]['adresse']=$rowa['adresse_contact'];
			$cont[$i]['numero']=$rowa['numero_contact'];
			$cont[$i]['CP']=$rowa['CP_contact'];
			$cont[$i]['ville']=$rowa['ville_contact'];
			$cont[$i]['tel']=$rowa['tel_contact'];
			$cont[$i]['tel2']=$rowa['tel2_contact'];
			$cont['total']=$i;
			$i++;
			}
		}
	return $cont;		
	}
	
public function genCodePersByTel($nom,$prenom,$tel)
	{
	return md5(strtoupper($this->cleanCaracteresSpeciaux($this->wd_remove_accents($nom.$prenom.$tel))));
	}
	
public function cleanCaracteresSpeciaux ($chaine)
	{
	setlocale(LC_ALL, 'fr_FR');
	$chaine = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $chaine);
	$chaine = preg_replace('#[^0-9a-z]+#i', '', $chaine);
	$chaine = preg_replace("#[^a-zA-Z0-9-]#", "", $chaine);
	while(strpos($chaine, '--') !== false)
		{
		$chaine = str_replace('--', '-', $chaine);
		}
	$chaine = trim($chaine, '-');
	return $chaine;
	}
	
public function wd_remove_accents($str, $charset='utf-8')
	{
    $str = htmlentities($str, ENT_NOQUOTES, $charset);
    $str = preg_replace('#&([A-za-z])(?:acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
    $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères
    return $str;
	}
	
public function getInfoCarsByIdVac($idVac)
	{
	$vv=array();
	$sql='SELECT id_vv FROM z_vac_hab_vv WHERE id_vac="'.$idVac.'"';
	$rep=$this->pdo->query($sql);
	$i=0;
	while ($row=$rep->fetch())
		{
		$sqla='SELECT id_vv, marque_vv, imm_vv, lieu_vv FROM z_vac_vv WHERE id_vv="'.$row['id_vv'].'"';
		$repa=$this->pdo->query($sqla);
		while ($rowa=$repa->fetch())
			{
			$vv[$i]['marque']=$rowa['marque_vv'];
			$vv[$i]['imm']=$rowa['imm_vv'];
			$vv[$i]['lieu']=$rowa['lieu_vv'];
			$vv[$i]['id']=$rowa['id_vv'];
			$i++;
			}
		$vv['total']=$i;
		}
	return $vv;
	}
	
function getVacByAdress($rue, $num)
	{
	$sql='SELECT id_vac, id_dem, vac_adresse, vac_numero, vac_CP, vac_ville, vac_dateDepart, vac_dateRetour FROM z_vac_habitation WHERE vac_adresse LIKE "%'.$rue.'%"';
	$sql.=($num=='') ? '' : 'AND vac_numero="'.$num.'" ';
	$sql.='ORDER BY vac_dateDepart ASC';
	$rep=$this->pdo->query($sql);
	return $rep;
	}
	
// function getVacByDemandeur($dem)
	// {
	// $str=
	// $sql='SELECT id_dem FROM z_vac_demandeur WHERE nom_dem LIKE "'.htmlentities($dem, ENT_QUOTES, "UTF-8").'"';
	
	// return $sql;
	// }
	
function getVacByDateDepart($db,$dh)
	{
	$sql='SELECT id_vac, id_dem, vac_adresse, vac_numero, vac_CP, vac_ville FROM z_vac_habitation 
	WHERE vac_dateDepart>="'.$db.'" 
	AND vac_dateDepart<="'.$dh.'"';
	// echo $sql;
	$rep=$this->pdo->query($sql);
	return $rep;
	}
	
function getInfosDemandeurByIdVac($id) //Renvoie les informations du demandeur sur base de l'id vacancier
	{
	$sql='SELECT id_dem, nom_dem, prenom_dem, dn_dem, tel_dem, gsm_dem, mail_dem FROM z_vac_demandeur WHERE id_dem=(SELECT id_dem FROM z_vac_habitation WHERE id_vac="'.$id.'")';
	return $this->pdo->query($sql);
	}
	
function getVacFinished()
	{
	$sql='
	SELECT a.id_vac, a.vac_numero, a.vac_CP, a.vac_ville, a.vac_dateDepart, a.vac_dateRetour,
	b.NomRue,
	c.nom_dem, c.prenom_dem
	FROM z_vac_habitation a
	LEFT JOIN z_rues b ON a.IdRue=b.IdRue
	LEFT JOIN z_vac_demandeur c ON a.id_dem = c.id_dem
	WHERE vac_dateDepart < NOW() AND vac_CR = "N"
	ORDER BY vac_dateRetour
	';
	$rep=$this->pdo->query($sql);
	return $rep;
	}
	
function getInfosControlesByIdVac($id)
	{
	$sql='
	SELECT date_heure, commentaire 
	FROM z_vac_hab_controle
	WHERE id_vac="'.$id.'"
	ORDER BY date_heure
	';	
	return $this->pdo->query($sql);
	}
	
function getInfoVacByIdRue($id)
	{
	$sql='
	SELECT a.id_vac, a.vac_numero, a.vac_CP, a.vac_ville, a.vac_dateDepart, a.vac_dateRetour,
	b.NomRue,
	c.nom_dem, c.prenom_dem
	FROM z_vac_habitation a
	LEFT JOIN z_rues b ON a.IdRue=b.IdRue
	LEFT JOIN z_vac_demandeur c ON a.id_dem = c.id_dem
	WHERE a.IdRue="'.$id.'"
	ORDER BY a.vac_dateDepart DESC
	';
	$rep=$this->pdo->query($sql);
	return $rep;	
	}
	
function getInfoVacByIdDem($id)
	{
	$sql='
	SELECT a.id_vac, a.vac_numero, a.vac_CP, a.vac_ville, a.vac_dateDepart, a.vac_dateRetour,
	b.NomRue,
	c.nom_dem, c.prenom_dem
	FROM z_vac_habitation a
	LEFT JOIN z_rues b ON a.IdRue=b.IdRue
	LEFT JOIN z_vac_demandeur c ON a.id_dem = c.id_dem
	WHERE c.id_dem="'.$id.'"
	ORDER BY a.vac_dateDepart DESC
	';
	$rep=$this->pdo->query($sql);
	return $rep;			
	}
	
function getVacanciersActifs($hDeb,$hFin)
	{
	$sql='
	SELECT 
	a.id_vac, a.idRue, a.vac_numero, a.vac_CP, a.vac_ville, a.vac_dateDepart, a.vac_dateRetour, 
	b.NomRue 
	FROM z_vac_habitation a
	LEFT JOIN z_rues b ON b.IdRue = a.IdRue
	WHERE vac_dateRetour >= "'.$hDeb.'" AND vac_dateDepart <= "'.$hFin.'"
	';
	$rep=$this->pdo->query($sql);
	return $rep;
	}
	
function getVacanciersByIdPat($pat)
	{
	$i=0;
	$mission=array();
	$repa='';
	$sql='SELECT type_mission, id_fiche FROM z_pat_missions WHERE id_patrouille="'.$pat.'" AND type_mission="vacanciers"';
	$rep=$this->pdo->query($sql);
	while ($row=$rep->fetch())
		{
		$mission[$i]['type']=$row['type_mission'];
		$mission[$i]['id']=$row['id_fiche'];
		$i++;
		}
		
	for ($j=0;$j<$i;$j++)
		{
		$sqla='
		SELECT 
		a.id_vac, a.idRue, a.vac_numero, a.vac_CP, a.vac_ville, a.vac_dateDepart, a.vac_dateRetour, 
		b.NomRue 
		FROM z_vac_habitation a
		LEFT JOIN z_rues b ON b.IdRue = a.IdRue
		WHERE a.id_vac="'.$mission[$j]['id'].'"';
		$repa[$j]['rep']=$this->pdo->query($sqla);
		$repa['total']=$j;
		}
	return $repa;
	}
	
function getIncidentsById($id)
	{
	$sql='SELECT date_heure, commentaire FROM z_vac_hab_controle WHERE id_vac="'.$id.'" AND resultat="Incident"';
	return $this->pdo->query($sql);
	}
	
function getNbIncidentsById($id)
	{
	$sql='SELECT COUNT(*) FROM z_vac_hab_controle WHERE id_vac="'.$id.'" AND resultat="Incident"';
	$rep=$this->pdo->query($sql);
	while ($row=$rep->fetch())
		{
		$count=$row['COUNT(*)'];
		}
	return $count;
	}

function getVanciersInTime($debut,$fin)	
	{
	$i=0;
	$sql='SELECT a.id_vac, a.vac_CP, a.vac_ville, a.vac_numero, a.IdRue, b.NomRue
	FROM z_vac_habitation a
	LEFT JOIN z_rues b ON a.IdRue=b.IdRue
	WHERE
	((a.vac_dateRetour>"'.$debut.'" AND a.vac_dateDepart<"'.$fin.'") OR
	(a.vac_dateDepart>"'.$debut.'" AND a.vac_dateDepart<"'.$fin.'"))
	ORDER BY a.vac_dateDepart';//d.id_quartier';
	
	$rep=$this->pdo->query($sql);
	while ($row=$rep->fetch())
		{
		$data[$i]['id']=$row['id_vac'];
		$data[$i]['CP']=$row['vac_CP'];
		$data[$i]['ville']=$row['vac_ville'];
		$data[$i]['num']=$row['vac_numero'];
		$data[$i]['rue']=$row['NomRue'];
		//RECHERCHE DU QUARTIER DONT FAIT PARTIE L'HABITATION
		$cote=($data[$i]['num'] % 2 == 0) ? 'P' : 'I';
		// echo $cote.'<br />';
		// Une petite regex fait ça très simplement et proprement : 
		$row['vac_numero'] = preg_replace('`[^0-9]`', '', $row['vac_numero']);
		
		$sqlb='SELECT a.id_quartier, b.denomination 
		FROM z_quartier_rue a
		LEFT JOIN z_quartier b ON b.id_quartier=a.id_quartier
		WHERE IdRue="'.$row['IdRue'].'" AND cote="'.$cote.'" AND limiteBas<='.$row['vac_numero'].' AND limiteHaut>='.$row['vac_numero'].'';
		// echo '<b>'.$sqlb.'<b><br />';
		$repb=$this->pdo->query($sqlb);
		while ($rowb=$repb->fetch()){
		$data[$i]['quartier']=$rowb['denomination'];	
		// echo $rowb['denomination'].'<br />';
		}
		//FIN RECHERCHE QUARTIER		
		$sqla='SELECT COUNT(*) FROM z_vac_hab_controle WHERE id_vac="'.$row['id_vac'].'"';
		$repa=$this->pdo->query($sqla);
		while($rowa=$repa->fetch())
			{
			$data[$i]['nbPass']=$rowa['COUNT(*)'];
			}
		$i++;
		}
	$data['ttl']=$i;
	$data['sql']=$sql;
	
	return $data;	
	}
	
/*
, d.denomination
LEFT JOIN z_quartier d ON c.id_quartier=d.id_quartier
LEFT JOIN z_quartier_rue c ON a.IdRue=c.IdRue
	IF ((a.vac_numero % 2)=0,c.cote="P",c.cote="I")
	AND
*/	
}
?>
