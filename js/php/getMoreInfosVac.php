<?php

include ('connect.php');
include ('/var/www/class/vacancier.class.php');

if (isset($_GET['idhab']))
	{
	$level=$_GET['level'];
	$idHab=$_GET['idhab'];
	
	$vac = new Vacancier($pdo);
	$bien=$vac->getVacInfoById($idHab);
	$html='<h4>Informations complémentaires</h4>';
	$html.='Maison '.$bien['nbFacades'].' façades, ';
	$html.=($bien['alarme']=='O') ? 'avec système d\'alarme.' : 'sans système d\'alarme.';
	$html.='<br />';
	$html.=($bien['chien']=='O') ? 'Présence d\'un chien sur place.  ' : 'Aucun chien sur place.  ';
	$html.='<br />';
	$html.=($bien['eclairageExt']=='O') ? 'Système d\'éclairage extérieur automatique.  ' : 'Pas d\'éclairage extérieur automatique.  ';
	$html.='<br />';
	$html.=($bien['eclairageInt']=='O') ? 'Système d\'éclairage intérieur automatique.  ' : 'Pas d\'éclairage intérieur automatique.  ';
	$html.='<br />';
	$html.=($bien['courrier']=='O') ? 'Responsable de relevé du courrier : '.$bien['persCourrier'].'.  ' : 'Personne ne relève le courrier.  ';
	$html.='<br />';
	$html.=($bien['persAuto']=='O') ? 'Persiennes automatiques installées.  ' : (($bien['persPers']>'') ? 'Responsable persiennes : '.$bien['persPers'].'.  ' : 'Pas de chargé de persiennes.  ');
	$html.='<br />';
	$html.=($bien['remarque']=='') ? 'Remarque : néant.  ' : 'Remarque : '.$bien['remarque'];
	$html.='<br />';
	
	$contact=$vac->getPersByIdVac($idHab);
	
	for ($i=1;$i<sizeof($contact);$i++)
		{
		$html.='Personne de contact en cas d\'incident : <a href="#" onclick="showContact(\''.$contact[$i]['adresse'].'\',\''.$contact[$i]['numero'].'\',\''.$contact[$i]['CP'].'\',\''.$contact[$i]['ville'].'\',\''.$contact[$i]['tel'].'\',\''.$contact[$i]['tel2'].'\');">'.$contact[$i]['nom'].' '.$contact[$i]['prenom'].'</a><br />';
	/*	Téléphone : '.$contact[$i]['tel'].'<br />
		Téléphone (2) : '.$contact[$i]['tel2']; */
		}
		
	$html.=($bien['contSP']=='') ? 'Contact sur place : non renseigné.' : 'Contact sur place : '.$bien['contSP'];
	$html.='<br />';	
	// $html.='<br />';
	
	//Affichage des boutons Edition et Suppression selon les droits de l'utilisateur connecté

	// if (($level=='10') || ($level=='20') || ($level=='30') || ($level=='50'))
		// {
		// $html.='   ';
		// $html.='<a href="?component=vacancier&action=editVac&id='.$idHab.'"><img src="../media/icons/edit.png" height=40 title="Editer"></a>';
		// }
	
	// if (($level=='20') || ($level=='30') || ($level=='50'))
		// {
		// $html.='   ';
		// $html.='<a href="#"><img src="../zoom5317/media/icons/remove.png" height=40 title="Supprimer"></a>';
		// }
	// $html.='<hr>';
	$html.='<img src="./media/icons/zoom-out.ico" height=40 title="Moins d\'infos"  onclick=lessInfos(\''.$idHab.'\',\''.$level.'\');>';
	}

echo $html;

?>