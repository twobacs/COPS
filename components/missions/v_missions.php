<?php

class VMissions extends VBase {

    function __construct($appli, $model) {
        parent::__construct($appli, $model);
    }
	
public function nonco()
	{
	$this->appli->ctContent.="Vous ne pouvez accéder à cette partie du site.";
	}


public function afficheHtml($data)	
	{
	$this->appli->ctContent=$data;
	$this->appli->jScript= '<script type="text/javascript" src="./js/missions.js"></script>';
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
	else
		{
		return $jour."-".$mois."-".$annee.' à '.$heure;
		}
	}
	
// public function showMenu($level,$teams,$missionsByTeam)
	// {
	// $html='';
	// if ($level>='29')
		// {
		// $html.='Choisissez une équipe à laquelle attribuer une mission : <br /><br />';
		// $html.='<table><tr><th>Dénomination</th><th>Du</th><th>Au</th><th>Missions attribuées</th></tr>';
		// while ($row=$teams->fetch())
			// {
			// $html.='<tr><td><a href=index.php?component=missions&action=addMission&step=1&idprest='.$row['id_prestation'].'&pat='.$row['id_patrouille'].'>'.$row['indicatif'].'</a></td><td>'.$this->datefr($row['date_heure_debut']).'</td><td>'.$this->datefr($row['date_heure_fin']).'</td><td>';
			// for ($i=0;$i<sizeof($missionsByTeam);$i++)
				// {
				// if ($missionsByTeam[$i]['id']==$row['id_patrouille'])
					// {
					// switch ($missionsByTeam[$i]['count'])
						// {
						// case 0:
							// $html.='Aucune mission attribuée';
							// break;
							
						// case 1:
							// $html.='1 mission attribuée';
							// break;
							
						// default:
							// $html.=$missionsByTeam[$i]['count'].' missions attribuées';
						// }
					// }
				// }
			// $html.='</td></tr>';
			// }
		// $html.='</table>';
		// }
	// else
		// {
		// $html.='Vous n\'avez pas accès à cette partie du site';
		// }
		
	// $this->afficheHtml($html);
	// }
	
public function showMenu($level)
	{
	$html='<h2>Menu missions</h2>';
	$html.='Gérer les attributions de missions : <br />';
	$html.='<table>';
	$html.='<tr><th>Date / heure basse :</th><td><input type="datetime-local" name="dhb" id="dhb" value="'.date('Y-m-d').'T00:00"></td></tr>';
	$html.='<tr><th>Date / heure haute :</th><td><input type="datetime-local" name="dhh" id="dhh" value="'.date('Y-m-d').'T00:00"></td></tr>';
	$html.='<tr><td colspan="2" class="noborder"><input type="button" onclick="attriMissions(\''.$level.'\');" value="Continuer"></td></tr>';
	$html.='</table>';
	$html.='<div id="attriMissions">';
	$html.='<h3>Missions en cours</h3>';
	$html.='Ici s\'afficheront les missions en cours et leur état d\'avancement';
	$html.='</div>';
	$this->afficheHtml($html);
	}
	
public function addMission($level,$missions,$prest,$cops,$vacanciers,$pat,$vacAttrib,$otherMissions)
	{
	$html='';
	if ($level>29)
		{
		$i=0;
		$fiche[0]='';
		$vac[0]='';
		$html.='<h3>Missions déjà attribuées à cette patrouille :</h3>';
		$html.='Missions COPS :<br />';
		$html.='<ul>';
		if ($missions!='')
			{
			for ($j=0;$j<=$missions['total'];$j++)
			{
			while ($row=$missions[$j]['rep']->fetch())
				{
				$html.='<li>'.$row['denomSec'].' - '.$row['denomCateg'].' : '.$row['texteInfo'].' - <a href="index.php?component=missions&action=removeMission&mToDel='.$row['id_fiche'].'&idprest='.$prest.'&step=2&pat='.$pat.'&mission=cops&mCops='.$row['id_fiche'].'">Retirer</a></li>';
				$fiche['id'][$i]=$row['id_fiche'];
				$i++;
				}
			}
			}
		$html.='</ul>';
		$html.='Vacanciers :<br />';
		$html.='<ul>';
		$i=0;
		if ($vacAttrib!='')
			{
			for ($j=0;$j<=$vacAttrib['total'];$j++)
				{
				while ($row=$vacAttrib[$j]['rep']->fetch())
					{
					$html.='<li>'.$row['NomRue'].', '.$row['vac_numero'].' à '.$row['vac_CP'].' '.$row['vac_ville'].' - <a href="index.php?component=missions&action=removeMission&mToDel='.$row['id_vac'].'&idprest='.$prest.'&step=2&pat='.$pat.'&mission=cops&mCops='.$row['id_vac'].'">Retirer</a></li>';
					$vac['id'][$i]=$row['id_vac'];
					$i++;
					}
				}
			}	
		$html.='</ul>';
		$html.='Autre missions :<br /><ul>';
		// $i=0;
		if ($otherMissions!='')
			{
			
			while($row=$otherMissions->fetch())
				{
				$html.='<li><b>';
				$type=$row['type_mission'];
				switch($type)
					{
					case 'CS':
						$html.='Contrôle statique.';
						break;
					case 'PP':
						$html.='Patrouille pédestre.';
						break;
					case 'PV':
						$html.='Patrouille en véhicule.';
						break;
					case 'SI':
						$html.='Service intérieur.';
						break;
					}
				$html.='</b>  Lieu : '.$row['lieu'].'</li>';
				}
			}
		$html.='<hr>';
		$html.='<h3>Missions COPS attribuables :</h3><table>';
		$qMissions=0;
		while ($row=$cops->fetch())
			{
			if ((!isset($fiche['id'])) OR (! in_array($row['id_fiche'],$fiche['id'])))
				{
				$html.='<tr><td>'.$row['denomSec'].' - '.$row['denomCateg'].' : '.$row['texteInfo'].'</td><td><a href="index.php?component=missions&action=addMission&idprest='.$prest.'&step=2&pat='.$pat.'&mission=cops&mCops='.$row['id_fiche'].'">Attribuer</a></td></tr>';
				$qMissions++;
				}
			}
		$html.=($qMissions==0) ? '<b>Information</b> : Pas de mission COPS disponible.' : '';
		$html.='</table>';
		$html.='<h3>Vacanciers attribuables :</h3><table>';
		$qVacanciers=0;
		while ($row=$vacanciers->fetch())
			{
			if ((!isset($vac['id'])) OR (! in_array($row['id_vac'],$vac['id'])))
				{			
				$html.='<tr><td>'.$row['NomRue'].', '.$row['vac_numero'].' à '.$row['vac_CP'].' '.$row['vac_ville'].' (Du '.$this->datefr($row['vac_dateDepart'],1).' au '.$this->datefr($row['vac_dateRetour'],1).')</td><td><a href="index.php?component=missions&action=addMission&idprest='.$prest.'&step=2&pat='.$pat.'&mission=vacanciers&mVac='.$row['id_vac'].'">Attribuer</a></td></tr>';
				$qVacanciers++;
				}
			}
		$html.=($qVacanciers==0) ? '<b>Information</b> : Pas de vacanciers disponibles.' : '';	
		$html.='</table>';
		$html.='<hr>';
		$html.='<h3>Autres types de missions</h3>';
		$html.='<form name="OtherMissions" method="POST" action="index.php?component=missions&action=addMission&idprest=0&step=2&pat='.$pat.'&mission=autre">';
	$html.='<table>';
		$html.='<tr><th>Type</th><th>Lieu</th><td class="noborder"></td></tr>';
		$html.='<tr><td><select name="TypeMissions">';
		$html.='<option value="CS">Contrôle statique</option>';
		$html.='<option value="PP">Patrouille pédestre</option>';
		$html.='<option value="PV">Patrouille en véhicule</option>';
		$html.='<option value="SI">Service intérieur</option>';
		$html.='</select></td>';
		$html.='<td><input type="text" name="lieu" required></td>';
		$html.='<td><input type="submit" value="Attribuer"><input type="hidden" name="pat" value="'.$pat.'"></td>';
		//http://192.168.254.52/index.php?component=missions&action=addMission&idprest=0&step=2&pat=fedec36aff3357f7d67892e7ae88cf5b&mission=vacanciers&mVac=162
		$html.='</table></form>';
		}
	else
		{
		$html.='Vous n\'avez pas accès à cette partie du site';
		}
	$this->afficheHtml($html);
	}
}
?>