<?php

include ('../connect.php');

$html='';

if (isset($_GET['idfiche']))
	{
	$fiche=$_GET['idfiche'];
	$sql='SELECT id_liaison, denomination FROM z_liaison ORDER BY denomination';
	$rep=$pdo->query($sql);	
	$html.='<form method="post" action="index.php?component=cops&amp;action=addVV&amp;idFiche='.$fiche.'"><table>';
	$html.='<tr><th class="sstitre" colspan="4">Ajout d\'un véhicule</th></tr>';
	$html.='<tr><th width="25%">Marque et modèle :</th><td width="25%"><input type="text" name="marque" style="width:50%;text-transform:uppercase;" autofocus><input type="text" name="modele" style="width:50%;"</td><th width="25%">Couleur :</th><td width="25%"><input type="text" name="couleur"></td></tr>';
	$html.='<tr><th>Immatriculation :</th><td><input type="text" name="imma"><th>N° chassis :</th><td><input type="text" name="chassis"></td></tr>';
	$html.='<tr><th>Infos complémentaires :</th><td><input type="text" name="infos"></td><th>Implication :</th><td><select name="implication" required><option value=""></option>';
	while ($row=$rep->fetch())
		{
		$html.='<option value="'.$row['id_liaison'].'">'.$row['denomination'].'</option>';
		}
	$html.='</select></td></tr>';
	$html.='<tr><td class="noborder" colspan="4"><input type="submit" value="Enregistrer"></td></tr>';
	
	$html.='</table>';
	}
echo $html;	

?>