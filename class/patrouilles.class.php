<?php

class Patrouille
{
public $pdo;


public function __construct($dbPdo)
	{
	$this->pdo=$dbPdo;
	}
	
function generate_uuid()
	{
    $md5 = md5(uniqid('', true));
	return $md5;
	}	
	
public function recNewPat($denom, $indic, $dhd, $dhf, $presta=0)
	{
	$var = explode("T",$dhd);
	$date=$var[0];
	$heure=$var[1];
	$dateheure=$date.' '.$heure.':00';
	
	$sql='SELECT COUNT(*) FROM z_patrouille WHERE indicatif="'.$indic.'" AND date_heure_debut LIKE "'.$dateheure.'"';
	$rep=$this->pdo->query($sql);
	while ($row=$rep->fetch())
		{
		$count = $row['COUNT(*)'];
		}
	if ($count==0)
		{	
		$id=$this->generate_uuid();
		$sql='INSERT INTO z_patrouille (id_patrouille, date_heure_debut, date_heure_fin, indicatif, denomination, actif, id_prestation) 
		VALUES ("'.$id.'", "'.$dhd.'", "'.$dhf.'", "'.$indic.'", "'.$denom.'", "O", "'.$presta.'")';
		$this->pdo->exec($sql);
		}
	else
		{
		$id=-1;
		}
	return $id;
	}
	
public function listPat($tri=0)
	{
	$sql='SELECT id_patrouille, date_heure_debut, date_heure_fin, indicatif, denomination, actif FROM z_patrouille WHERE date_heure_fin>NOW() ORDER BY ';
	switch ($tri)
		{
		case '' :
			$sql.='date_heure_debut ASC, indicatif';
			break;
		
		case 'denUp' :
			$sql.='denomination ASC, indicatif';
			break;
			
		case 'denDown' :
			$sql.='denomination DESC, indicatif';
			break;
			
		case 'indUp' :
			$sql.='indicatif ASC, date_heure_debut';
			break;
			
		case 'indDown' :
			$sql.='indicatif DESC, date_heure_debut';
			break;
			
		case 'dhdUp':
			$sql.='date_heure_debut ASC, indicatif';
			break;
			
		case 'dhdDown':
			$sql.='date_heure_debut DESC, indicatif';
			break;
			
		case 'dhfUp':
			$sql.='date_heure_fin ASC, indicatif';
			break;
			
		case 'dhfDown':
			$sql.='date_heure_fin DESC, indicatif';
			break;
						
		}
	$rep=$this->pdo->query($sql);
	return $rep;
	}
	
public function getFonctionnalites()
	{
	$sql='SELECT id_fonctionnalite, denomination FROM z_fonctionnalites ORDER BY denomination';
	$rep=$this->pdo->query($sql);
	return $rep;
	}
	
public function getDateHeureDebut($pat)
	{
	$sql='SELECT date_heure_debut FROM z_patrouille WHERE id_patrouille="'.$pat.'"';
	$rep=$this->pdo->query($sql);
	while ($row=$rep->fetch())
		{
		$hDeb=$row['date_heure_debut'];
		}
	return $hDeb;
	}
	
public function getDateHeureFin($pat)
	{
	$sql='SELECT date_heure_fin FROM z_patrouille WHERE id_patrouille="'.$pat.'"';
	$rep=$this->pdo->query($sql);
	while ($row=$rep->fetch())
		{
		$hFin=$row['date_heure_fin'];
		}
	return $hFin;
	}
	
public function getMissionsByIdPatrouille($pat)
	{
	$i=0;
	$mission=array();
	$repa='';
	$sql='SELECT type_mission, id_fiche FROM z_pat_missions WHERE id_patrouille="'.$pat.'" AND type_mission="cops"';
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
		a.id_fiche,	a.texteInfo, 
		b.denomination AS denomCateg,
		c.denomination AS denomSec 
		FROM z_fiche a 
		LEFT JOIN z_categorie_fiche b ON a.id_categ = b.id_categ 
		LEFT JOIN z_section_fiche c ON b.id_section = c.id_section 
		WHERE a.id_fiche="'.$mission[$j]['id'].'"';
		$repa[$j]['rep']=$this->pdo->query($sqla);
		$repa['total']=$j;
		}
	return $repa;
	}
	
public function getPatInTime($debut,$fin)
	{
	$i=0;
	// $sql='SELECT id_patrouille, indicatif, denomination FROM z_patrouille WHERE 
	// (date_heure_fin>"'.$debut.'" AND date_heure_debut<"'.$fin.'") OR
	// (date_heure_debut>"'.$debut.'" AND date_heure_debut<"'.$fin.'")
	// ORDER BY indicatif';
	$sql='SELECT id_patrouille, indicatif, denomination FROM z_patrouille WHERE date_heure_debut BETWEEN "'.$debut.'" AND "'.$fin.'" ORDER BY indicatif';	
	$rep=$this->pdo->query($sql);
	while ($row=$rep->fetch())
		{
		$data[$i]['id']=$row['id_patrouille'];
		$data[$i]['indicatif']=$row['indicatif'];
		$data[$i]['denomination']=$row['denomination'];
		$i++;
		}
	$data['ttl']=$i;
	return $data;
	}

}
?>