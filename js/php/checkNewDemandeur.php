<?php

include ('connect.php');

if (isset($_GET['nom']))
	{
	$nom=$_GET['nom'];
	$prenom=$_GET['prenom'];
	$dn=$_GET['dn'];
	
	$id_dem=md5(strtoupper(cleanCaracteresSpeciaux(wd_remove_accents($nom.$prenom.$dn))));
	
	$sql='SELECT COUNT(*) FROM z_vac_demandeur WHERE id_dem="'.$id_dem.'"';
	$rep=$pdo->query($sql);
	while ($row=$rep->fetch())
		{
		$count=$row['COUNT(*)'];
		switch ($count)
			{
			case 0 :
			$html=formVierge($id_dem);
			break;
			
			case 1 :
			$html=formComplet($id_dem,$pdo);
			break;
			}
		}
	echo $html;
	}

//******************************************************//
//******************************************************//
//******************************************************//

function wd_remove_accents($str, $charset='utf-8')
	{
    $str = htmlentities($str, ENT_NOQUOTES, $charset);
    $str = preg_replace('#&([A-za-z])(?:acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
    $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères
    return $str;
	}	
	
function cleanCaracteresSpeciaux ($chaine)
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
	
function formVierge($id)
	{
	$html='<tr><th>Téléphone : </th><td><input type=text name=tel></td></tr>';
	$html.='<tr><th>GSM : </th><td><input type=text name=gsm></td></tr>';
	$html.='<tr><th>Mail : </th><td><input type=text name=mail></td></tr>';
	$html.='<tr><td colspan="2"><input type=submit value="Enregistrer"><input type=hidden name=newIdDem value='.$id.'></td></tr>';
	return $html;
	}
	
function formComplet($id,$pdo)
	{
	$sql='SELECT tel_dem, gsm_dem, mail_dem FROM z_vac_demandeur WHERE id_dem="'.$id.'"';
	$rep=$pdo->query($sql);
	while ($row=$rep->fetch())
		{
		$html='<tr><th>Téléphone : </th><td><input type=text name=tel value="'.$row['tel_dem'].'"></td></tr>';
		$html.='<tr><th>GSM : </th><td><input type=text name=gsm value="'.$row['gsm_dem'].'"></td></tr>';
		$html.='<tr><th>Mail : </th><td><input type=text name=mail value="'.$row['mail_dem'].'"><input type=hidden name=idDem value='.$id.'></td></tr>'; // ajouter champs caché avec id demandeur
		$html.='<tr><td colspan="2"><input type=submit value="Valider"></td></tr>';
		}
	return $html;
	}
?>