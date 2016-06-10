<?php

if (isset($_GET['time'])){
	include ('../connect.php');
	// $latitude=$_GET['latitude'];
	// $longitude=$_GET['longitude'];
	
	$latitude='latitude';
	$longitude='longitude';
	$h_avis=$_GET['time'];
	$pat=$_GET['pat'];
	$fiche=$_GET['fiche'];
	$from=$_GET['depuis'];

	if ($from=='SP')
		{
		//verifier si l'intervention existe deja, si non, l'insérer dans les tables z_pat_missions et z_intervention
		$sql='SELECT COUNT(*) FROM z_pat_missions WHERE id_patrouille="'.$pat.'" AND type_mission="INT" AND date_heure_in != "0000-00-00 00:00:00" AND date_heure_out = "0000-00-00 00:00:00"';
		$rep=$pdo->query($sql);
		while ($row=$rep->fetch())
			{
			$count=$row['COUNT(*)'];
			}
		if ($count=='0')
			{
			$sql='INSERT INTO z_pat_missions (id_patrouille, commentaire, type_mission, date_heure_in) VALUES ("'.$pat.'", "'.$fiche.'", "INT", "'.$h_avis.'")';
			$pdo->exec($sql);
		
			$sql='INSERT INTO z_intervention (id_patrouille, num_fiche, dh_avis, dh_surplace) VALUES ("'.$pat.'", "'.$fiche.'", "'.$h_avis.'", NOW()")';
			$pdo->exec($sql); 
			}
		$html='<img src="./media/icons/fin_int.png" width="25%" onclick="intervention(\''.$pat.'\',\''.$h_avis.'\',\'FIN\');">';
		}

	else if ($from=='FIN')
		{
		$sql='UPDATE z_intervention SET dh_fin=NOW(), num_fiche="'.$fiche.'" WHERE id_patrouille="'.$pat.'" AND dh_avis="'.$h_avis.'"';
		$pdo->exec($sql);
		
		$sql='UPDATE z_pat_missions SET date_heure_out=NOW(), commentaire="'.$fiche.'" WHERE id_patrouille="'.$pat.'" AND date_heure_in="'.$h_avis.'"';
		$pdo->exec($sql);
		
		$html='<a href="index.php?mode=m&component=users&action=fromMenuTablette">Retour "Mes missions"</a>';
		
		}
	echo $html;	
}
?>