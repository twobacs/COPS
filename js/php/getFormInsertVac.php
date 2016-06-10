<?php

include ('connect.php');
include ('/var/www/class/vacancier.class.php');
include ('/var/www/class/rues.class.php');

$vac = new Vacancier($pdo);
$rue = new Rue($pdo);


if (isset($_GET['id']))
	{
	$id=$_GET['id'];

	$bien=$vac->getVacInfoById($id);
	$rues=$rue->selectRues();

	
	//DONNES RELATIVES A L'HABITATION
	$adresse=$bien['adresse'];
	$numero=$bien['numero'];
	$CP=$bien['CP'];
	$ville=$bien['ville'];
	$demande=$bien['demande'];
	$depart=$bien['depart'];
	$retour=$bien['retour'];

	$nbFacades=$bien['nbFacades'];
	$alarme=$bien['alarme'];
	$eclairageExt=$bien['eclairageExt'];
	$eclairageInt=$bien['eclairageInt'];
	$chien=$bien['chien'];
	$courrier=$bien['courrier'];
	$persCourrier=$bien['persCourrier'];
	$persAuto=$bien['persAuto'];
	$persPers=$bien['persPers'];
	
	$destination=$bien['destination'];
	$contSP=$bien['contSP'];
	
	$dateTechno=$bien['dateTechno'];
	
	$GDP=$bien['GDP'];
	
	$remarque=$bien['remarque'];

	$selectAlarm=$vac->getSelect($id,'alarme');
	$selectEclE=$vac->getSelect($id,'eclairageExt');
	$selectEclI=$vac->getSelect($id,'eclairageInt');
	$selectChien=$vac->getSelect($id,'chien');
	$selectCourrier=$vac->getSelect($id,'courrier');
	$selectPersienne=$vac->getSelect($id,'persienne');
	$selectGDP=$vac->getSelect($id,'gdp');

	//CONSTRUCTION FORMULAIRE	
	
	$html='<form name="goToStep2" action="?component=vacancier&action=addHab&etape=2" method=POST><table class=noborder>';
	$html.='<tr><th class=titre colspan="4"><input type=hidden name=idDem value='.$_GET['dem'].'><titre>Habitation concernée</titre></th></tr>';
	
	//Adresse en champs texte, à remplacer par un select
	// $html.='<tr><th>Adresse :</th><td><input type=text name=adresse value="'.$adresse.'" autofocus></td>';

	//Remplacement par le select :
	$html.='<tr><th>Adresse :</th><td><select name=adresse><option></option>';
	while ($row=$rues->fetch())
		{
		$html.='<option value="'.$row['IdRue'].'"';
		if ($adresse==$row['IdRue'])
			{
			$html.=' SELECTED';
			}
		$html.='>'.$row['NomRue'].'</option>';
		}

	$html.='</select></td>';
	// fin select

	$html.='<th>Numéro :</th><td><input type=text name=numero value="'.$numero.'"></td></tr>';
	$html.='<tr><th>Code Postal :</th><td><input type=text name=CP value="'.$CP.'"></td><th>Ville :</th><td><input type=text name=ville value="'.$ville.'"></td></tr>';
	$html.='<tr><th class=titre colspan="4">Absence</th></tr>';
	$html.='<tr><th>Du :</th><td><input type=date name=depart value="'.$depart.'"></td><th>Au :</th><td><input type=date name=retour value="'.$retour.'"></td></tr>';
	$html.='<tr><th>Destination :</th><td><input type=text name=destination value="'.$destination.'"></td><th>Moyen de contact sur place :</th><td><input type="text" name="contSP" value="'.$contSP.'"></td></tr>';
	$html.='<tr><th class=titre colspan="4">Informations relatives à l\'habitation</th></tr>';
	$html.='<tr><th>Nombre de façades :</th><td><input type=text name=nbFacades value="'.$nbFacades.'"</td><th>Alarme :</th><td>'.$selectAlarm.'</td></tr>';
	$html.='<tr><th>Elairage extérieur :</th><td>'.$selectEclE.'</td><th>Eclairage intérieur :</th><td>'.$selectEclI.'</td></tr>';
	$html.='<tr><th>Présence d\'un chien :</th><td>'.$selectChien.'</td><th>GDP :</th><td>'.$selectGDP.'</td></tr>';
	$html.='<th>Relevé de courrier :</th><td>'.$selectCourrier.'</td><th>Personne en charge :</th><td><input type=text name=persCourrier value="'.$persCourrier.'"</td></tr>';
	$html.='<tr><th>Chargé de persiennes ?</th><td>'.$selectPersienne.'</td><th>Personne en charge :</th><td><input type=text name=persPers value="'.$persPers.'"></td></tr>';
	$html.='<tr><th>Visite technoprévention :</th><td><input type=date name=techno value='.$dateTechno.'></td><th>Remarque : </th><td><textarea name=remarque rows="2"></textarea></td></tr>';
	$html.='<tr><th class=titre colspan="4">Informations relatives aux véhicules restant sur place</th></tr>';
	$html.='<tr><th colspan="2">Nombre de véhicule(s) à encoder :</th><td colspan="2"><input id=nbVV	type=text onkeyup="addFormVV(\''.$id.'\');"></td></tr>';
	$html.='</table><table id=vv class=noborder></table>';
	$html.='<table id=contact class=noborder></table><table id=boutonEnregistrer class=noborder>';
	$html.='</table></form>';
	
	echo $html;
	}

?>
