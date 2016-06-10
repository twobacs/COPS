<?php

include ('connect.php');

if (isset($_GET['nom']))
	{
	$nom=strtoupper(wd_remove_accents($_GET['nom']));
	$naam=strtoupper(wd_remove_accents($_GET['naam']));
	
	if (($nom>'') &&($naam>''))
		{
		$sql='SELECT COUNT(*) FROM z_rues WHERE NomRue LIKE "%'.$nom.'%" OR StraatNaam LIKE "%'.$naam.'%"';
		$test='frnl';
		}
		
	else if ($nom=='')
		{
		$sql='SELECT COUNT(*) FROM z_rues WHERE StraatNaam LIKE "%'.$naam.'%"';
		$test='nl';
		}
		
	else if ($naam=='')
		{
		$sql='SELECT COUNT(*) FROM z_rues WHERE NomRue LIKE "%'.$nom.'%"';
		$test='fr';
		}
		
	$rep=$pdo->query($sql);
	
	while ($row=$rep->fetch())
		{
		$count=$row['COUNT(*)'];
		}
	
	if ($count>0)
		{
		$html='<h4>Données déjà existantes :</h4>';
		switch ($test)
			{
			case 'frnl' :
				$sql='SELECT IdRue, NomRue, StraatNaam FROM z_rues WHERE NomRue LIKE "%'.$nom.'%" OR StraatNaam LIKE "%'.$naam.'%"';
				break;
				
			case 'fr' :
				$sql='SELECT IdRue, NomRue, StraatNaam FROM z_rues WHERE NomRue LIKE "%'.$nom.'%"';
				break;
				
			case 'nl' :
				$sql='SELECT IdRue, NomRue, StraatNaam FROM z_rues WHERE StraatNaam LIKE "%'.$naam.'%"';
				break;
			}
		$rep=$pdo->query($sql);
		while ($row=$rep->fetch())
			{
			$html.=$row['NomRue'].' ('.$row['StraatNaam'].') <br />';
			}
		$html.='<br />Veuillez vérifier les champs complétés.';
		}
		
	else
		{
		switch ($test)
			{
			case 'frnl' :
				$sql='INSERT INTO z_rues (NomRue, StraatNaam) VALUES ("'.$nom.'", "'.$naam.'")';
				break;
				
			case 'fr' :
				$sql='INSERT INTO z_rues (NomRue) VALUES ("'.$nom.'")';
				break;
				
			case 'nl' :
				$sql='INSERT INTO z_rues (StraatNaam) VALUES ("'.$naam.'")';
				break;
			}
		$pdo->exec($sql);
		$html='<h4>Données enregistrées</h4>';
		$html.='<a href="?component=applications&action=showApps">Retour</a> au menu principal. <br />';
		$html.='<a href="?component=rues&action=addRue">Ajouter</a> une autre rue. <br />';
		}
	
	echo $html;
	}
	
	
	
function wd_remove_accents($str, $charset='utf-8')
{
    $str = htmlentities($str, ENT_NOQUOTES, $charset);
    
    $str = preg_replace('#&([A-za-z])(?:acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
    $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères
    
    return $str;
}

?>