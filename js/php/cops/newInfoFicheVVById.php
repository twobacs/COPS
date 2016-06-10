<?php

if (isset($_GET['nb']))
	{
	include ('../connect.php');
	$i=$_GET['nb'];
	$sql='SELECT id_liaison, denomination FROM z_liaison ORDER BY denomination';
	$rep=$pdo->query($sql);
	$html.='<tr><th class="sstitre" colspan="4">Ajout d\'un véhicule</th></tr>';
	$html.='<tr><th>Marque :</th><td><input type="text" name="marqueVV_'.$i.'" style="text-transform:uppercase"></td><th>Modèle :</th><td><input type="text" name="modeleVV_'.$i.'"></td></tr>';
	$html.='<tr><th>Immatriculation :</th><td><input type="text" name="immatVV_'.$i.'" style="text-transform:uppercase"></td><th>N° chassis :</th><td><input type="text" name="chassisVV_'.$i.'" style="text-transform:uppercase"></td></tr>';
	$html.='<tr><th>Couleur :</th><td><input type="text" name="couleurVV_'.$i.'"></td><th>Descriptif :</th><td><input type="text" name="descVV_'.$i.'"></td></tr>';
	$html.='<tr><th>Implication :</th><td><input type="hidden" name="nbVV" value="'.$i.'"><select name="implicationVV_'.$i.'">';
	$data=$rep->fetchAll();
	foreach	($data as $row)
		{
		$html.='<option value="'.$row['id_liaison'].'">'.$row['denomination'].'</option>';
		}
	
	$html.='</select></td></tr>';
	echo $html;
	}

?>