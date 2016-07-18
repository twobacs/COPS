<?php

if ((isset($_GET['level'])) && ($_GET['level']>19))
	{
	include ('../connect.php');
	include ('/var/www/class/patrouilles.class.php');
	include ('/var/www/class/cops.class.php');
	include ('/var/www/class/vacancier.class.php');
	include ('/var/www/class/missions.class.php');
	
	$pat=new Patrouille($pdo);
	$cops=new Cops($pdo);
	$vac=new Vacancier($pdo);
	$mis=new Mission($pdo);
	
	$level = $_GET['level'];
	$dhb = $_GET['dhb'];
	$dhh = $_GET['dhh'];
	
	$debut=splitDateTime($dhb);
	$fin=splitDateTime($dhh);
	
	// echo $debut;
	
	/*
	Informations nécessaire à l'affichage du tableau d'attribution :
	a) Equipes disponible dans le créneau horaire + id respectifs
	b) Missions disponibles dans le créneau horaire
	c) Missions déjà attribuées aux équipes reprises en a)
	*/
	
	
	$html='<table>';
	
	
	//*******************************************************************//
	//Recherche du nombre d'équipes disponibles et de leurs id respectifs//
	//*******************************************************************//
	$equipes=$pat->getPatInTime($debut,$fin);
	// echo $equipes;
	//****//
	//COPS//
	//****//
	
	$html.='<tr><th colspan="'.($equipes['ttl']+2).'" class="sstitre">Missions COPS</th></tr>';
	$html.='<tr><th>Missions</th>';
	//*************************************************************************//
	//Ajout du nombre de colonnes correspondant au nombre d'équipes disponibles//
	//*************************************************************************//
	for ($i=0;$i<$equipes['ttl'];$i++)
		{
		$html.='<th width="10%">'.$equipes[$i]['denomination'].'</th>';
		}
	$html.='<th width="10%">Toutes</th></tr>';
		
	//**********************************************//
	//Recherche des missions dans le créneau horaire//
	//**********************************************//
	$missions=$cops->getMissionsCopsInTime($debut,$fin);
	
	
	for ($i=0;$i<$missions['ttl'];$i++)
		{
		$html.='<tr';
		$html.=($missions[$i]['idCateg']==31) ? ' bgcolor="orange"' : '';
		$html.=($missions[$i]['idCateg']==32) ? ' bgcolor="#99CCFF"' : '';
		$html.=($missions[$i]['idCateg']==33) ? ' bgcolor="#ADFF85"' : '';
		$html.='><td>'.$missions[$i]['texte'].' - <a href="?component=cops&action=moreInfos&idFiche='.$missions[$i]['id'].'" target="_blank">Plus d\'infos</a></td>';
		for ($j=0;$j<($equipes['ttl']);$j++)
			{
			$html.='<td><input type="checkbox" name="cbAttrib'.$i.''.$j.'" id="cbAttrib'.$i.''.$j.'"';
			$sql='SELECT COUNT(*) FROM z_pat_missions WHERE id_patrouille="'.$equipes[$j]['id'].'" AND id_fiche="'.$missions[$i]['id'].'"';
			$rep=$pdo->query($sql);
			while ($row=$rep->fetch())
				{
				$count=$row['COUNT(*)'];
				}
			$html.=($count==0) ? '' : 'checked';
			$html.=' onchange="changeCbAttrib(\''.$equipes[$j]['id'].'\',\''.$missions[$i]['id'].'\',\''.$i.'\',\''.$j.'\');"></td>';
			}
		$html.='<td><input type="checkbox" name="cbAllRow'.$i.'" id="cbAllRow'.$i.'" onchange="changeAllCbAttrib(\''.$missions[$i]['id'].'\',\''.$debut.'\',\''.$fin.'\',\''.$i.'\',\''.$j.'\');"></td></tr>';
		}
	//**********//
	//VACANCIERS//
	//**********//
	$html.='<tr><th colspan="'.($equipes['ttl']+2).'" class="sstitre">Vacanciers</th></tr>';
	$html.='<tr><th>Vacanciers</th>';
	//*************************************************************************//
	//Ajout du nombre de colonnes correspondant au nombre d'équipes disponibles//
	//*************************************************************************//
	for ($i=0;$i<$equipes['ttl'];$i++)
		{
		$html.='<th width="10%">'.$equipes[$i]['indicatif'].'</th>';
		}
	$html.='<th width="10%">Toutes</th></tr>';
	
	//************************************************//
	//Recherche des vacanciers dans le créneau horaire//
	//************************************************//
	$datedebut=getDateFromDateTime($debut);
	$datefin=getDateFromDateTime($fin);
	
	$vacanciers=$vac->getVanciersInTime($datedebut,$datefin);

	for ($i=0;$i<$vacanciers['ttl'];$i++)
		{
		$html.='<tr><td>'.$vacanciers[$i]['rue'].', '.$vacanciers[$i]['num'];
//		à '.$vacanciers[$i]['CP'].' '.$vacanciers[$i]['ville'].' 
		$html.='  ('.$vacanciers[$i]['quartier'].') ';
		$html.=($vacanciers[$i]['nbPass']>'1') ? '('.$vacanciers[$i]['nbPass'].' passages effectués)</td>' : '('.$vacanciers[$i]['nbPass'].' passage effectué)</td>';
		for($j=0;$j<$equipes['ttl'];$j++)
			{
			$html.='<td><input type="checkbox" name="cbVacAttrib'.$i.''.$j.'" id="cbVacAttrib'.$i.''.$j.'"';
			$sql='SELECT COUNT(*) FROM z_pat_missions WHERE id_patrouille="'.$equipes[$j]['id'].'" AND id_fiche="'.$vacanciers[$i]['id'].'"';
			$rep=$pdo->query($sql);
			while ($row=$rep->fetch())
				{
				$count=$row['COUNT(*)'];
				}
			$html.=($count==0) ? '' : 'checked';
			$html.=' onchange="changeCbVacAttrib(\''.$equipes[$j]['id'].'\',\''.$vacanciers[$i]['id'].'\',\''.$i.'\',\''.$j.'\');"></td>';
			}
		$html.='<td><input type="checkbox" name="cbVacAllRow'.$i.'" id="cbVacAllRow'.$i.'" onchange="changeAllCbVacAttrib(\''.$vacanciers[$i]['id'].'\',\''.$debut.'\',\''.$fin.'\',\''.$i.'\',\''.$j.'\');"></td></tr>';	
		}
	//***************//
	//AUTRES MISSIONS//
	//***************//		
	$html.='<tr><th colspan="'.($equipes['ttl']+2).'" class="sstitre">Autres missions</th></tr>';
	// $html.='<tr><td colspan="'.($equipes['ttl']+2).'">Proposer la possibilité de créer des missions autres ainsi que les équipes dispo de manière à pouvoir les attribuer individuellement.</td></tr>';
	$html.='<table>';
	$html.='<tr><th>Mission</th><th>Lieu</th><th>Equipe</th><td class="noborder"></td></tr>';
	$missions=$mis->getMissions();
	$lieux=$mis->getLieux();
	//requête sql reprenant les champs de z_pat_missions pour chaque ligne dont l'id_equipe existe dans le timing déterminé
	$data=array();
	for ($v=0;$v<$equipes['ttl'];$v++)
		{
		$sql='SELECT a.type_mission, a.lieu, a.id_fiche, b.denomination, c.nom_mission
		FROM z_pat_missions a 
		LEFT JOIN z_patrouille b ON a.id_patrouille = b.id_patrouille
		LEFT JOIN z_missions c ON a.type_mission = c.code_mission
		WHERE a.id_patrouille="'.$equipes[$v]['id'].'" AND type_mission != "vacanciers" AND type_mission != "cops" AND type_mission !="SI" AND type_mission!="PAT"';
		// echo $sql;
		$rep=$pdo->query($sql);
		while ($row=$rep->fetch())
			{
			$html.='<tr><td>'.$row['nom_mission'].'</td><td>'.$row['lieu'].'</td><td>'.$row['denomination'].'</td><td><input type="button" value="Supprimer" onclick="delOtherMission(\''.$row['id_fiche'].'\',\''.$level.'\');"></td></tr>';
			}
		}
	$html.='<tr><td>';
	$html.='<select name="mission" id="mission"><option value="-1"></option>';
	for($k=0;$k<$missions['ttl'];$k++)
		{
		$html.='<option value="'.$missions[$k]['id'].'">'.$missions[$k]['nom'].'</option>';
		}
	$html.='</td>';
	$html.='<td><select name="lieu" id="lieu"><option value="-1"></option>';
	for($k=0;$k<$lieux['ttl'];$k++)
		{
		$html.='<option value="'.$lieux[$k]['id'].'">'.$lieux[$k]['nom'].'</option>';
		}
	$html.='</select></td><td>';
	$html.='<select name="equipe" id="equipe"><option value="-1"></option>';
	for($k=0;$k<$equipes['ttl'];$k++)
		{
		$html.='<option value="'.$equipes[$k]['id'].'">'.$equipes[$k]['indicatif'].'</option>';
		}
	$html.='</select></td><td width="15%"><input type="button" onclick="addOtherMission(\''.$level.'\');" value="Attribuer"></td></tr>';
	$html.='<tr><td></td><td><input type="button" onclick="gestLieuxMissions(0,\''.$level.'\');" value="Gérer les lieux de mission"></td><td colspan="2" class="noborder"></td></tr>';
	$html.='</table>';

	$html.='</table>';
	$html.='<div id="Add"></div>';
	// $html.='<div id="newLieu"></div>';
	}
	
echo $html;

?>