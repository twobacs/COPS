<?php

include ('../connect.php');

// echo $_GET['quartier'];

if (isset($_GET['rue']))
	{
	$rue=$_GET['rue'];
	$lpb=$_GET['lpb'];
	$lph=$_GET['lph'];
	$lib=$_GET['lib'];
	$lih=$_GET['lih'];
	$quartier=$_GET['quartier'];
	
	$sql='SELECT COUNT(*) FROM z_quartier_rue WHERE IdRue="'.$rue.'"'; //Vérifie l'existence de la rue dans la table z_quartier_rue
	$rep=$pdo->query($sql);
	while ($row=$rep->fetch()){
		$count=$row['COUNT(*)'];
		}
	// echo $count;
	if ($count==0){ //Si le compteur est à zéro, enregistrement des données sans contrôle
		$html=recDonnees($rue,$lpb,$lph,$lib,$lih,$quartier,$pdo);
		}

		/*
		Une rue peut exister à de multiples reprises dans la table z_quartier_rue mais les valeurs de limites pair/impair - basse/haute
		ne peuvent pas exister dans deux entrées différentes
		Il faut donc vérifier pour chaque nouvelle entrée si lbp, lhp, lib, lih ne sont pas déjà compris individuellement dans un autre enregistrement.
		*/	

	else{
		$html='';
		$erreur=0;
		$sql='SELECT a.limiteBas, a.limiteHaut, b.denomination FROM z_quartier_rue a
		LEFT JOIN z_quartier b 
		ON a.id_quartier=b.id_quartier
		WHERE IdRue="'.$rue.'" AND cote="P"'; //sélection des valeurs haute et basse encodée pour la rue considérée cote pair
		$rep=$pdo->query($sql);
		while ($row=$rep->fetch()){
			$bas=$row['limiteBas'];
			$haut=$row['limiteHaut'];
			if (($lpb>=$bas) AND ($lpb<=$haut)){
				$html.='La limite paire basse encodée est déjà reprise comme faisant partie d\'un quartier ('.$row['denomination'].').<br />';
				$erreur++;
				}
			if (($lph<=$haut) AND ($lph>=$bas)){
				$html.='La limite paire haute encodée est déjà reprise comme faisant partie d\'un quartier ('.$row['denomination'].').<br />';
				$erreur++;
				}
			}
	
		$sql='SELECT a.limiteBas, a.limiteHaut, b.denomination FROM z_quartier_rue a
		LEFT JOIN z_quartier b 
		ON a.id_quartier=b.id_quartier
		WHERE IdRue="'.$rue.'" AND cote="I"'; //sélection des valeurs haute et basse encodée pour la rue considérée cote impair
		$rep=$pdo->query($sql);
		while ($row=$rep->fetch()){
			$bas=$row['limiteBas'];
			$haut=$row['limiteHaut'];
			if (($lib>=$bas) AND ($lib<=$haut)){
				$html.='La limite impaire basse encodée est déjà reprise comme faisant partie d\'un quartier ('.$row['denomination'].').<br />';
				$erreur++;
				}
			if (($lih<=$haut) AND ($lih>=$bas)){
				$html.='La limite impaire haute encodée est déjà reprise comme faisant partie d\'un quartier ('.$row['denomination'].').<br />';
				$erreur++;
				}
			}		
		if ($erreur==0){
			// /* INSERTION DES DONNEES EN BDD */
			$html=recDonnees($rue,$lpb,$lph,$lib,$lih,$quartier,$pdo);
			}
		}
	
	echo $html;	
	}
	


function recDonnees($rue,$lpb,$lph,$lib,$lih,$quartier,$pdo){
	if (($lpb!='NaN')&&($lph!='NaN')){
		$sql='INSERT INTO z_quartier_rue (id_quartier, IdRue, cote, limiteBas, limiteHaut) VALUES ("'.$quartier.'","'.$rue.'","P","'.$lpb.'","'.$lph.'")';
		$pdo->exec($sql);
		}
	if (($lib!='NaN')&&($lih!='NaN')){
		$sql='INSERT INTO z_quartier_rue (id_quartier, IdRue, cote, limiteBas, limiteHaut) VALUES ("'.$quartier.'","'.$rue.'","I","'.$lib.'","'.$lih.'")';
		$pdo->exec($sql);
		}
	$html='L\'enregistrement a été effectué correctement. <br />';
	$html.='Créer une <a href="?component=quartier&action=assocrq">autre association</a>.';
	return $html;
	}
?>
