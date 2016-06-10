<?php

include ('../connect.php');



if(isset($_GET['fiche']))
	{
	$fiche=$_GET['fiche'];
	$sql='SELECT id_liaison, denomination FROM z_liaison ORDER BY denomination';
	$rep=$pdo->query($sql);
	$html='<form method="POST" enctype="multipart/form-data" name="newPersonne" action="index.php?component=cops&amp;action=addPers&amp;idFiche='.$fiche.'#personne"><table>';
	$html.='<tr><th class="titre" colspan="4">Ajout d\'une personne</th></tr>';
	$html.='<tr><th width="25%">Nom :</th><td width="25%"><input type="text" name="nom" required autofocus></td><th width="25%">Prénom :</th><td width="25%"><input type="text" name="prenom" required></td></tr>';
	$html.='<tr><th>Date de naissance :</th><td><input type="date" name="DN" required></td><th>Pays de résidence :</th><td><input type="text" name="pays"></td></tr>';
	$html.='<tr><th>Adresse :</th><td colspan="3">CP : <input type="text" name="CP" style="width:15%;"> Ville : <input type="text" name="ville" style="width:15%;"> Rue : <input type="text" name="rue" style="width:25%;"> Numéro : <input type="text" name="num" style="width:10%;"></td></tr>';
	$html.='<tr><th>Infos complémentaires :</th><td colspan="3"><input type="text" name="desc" style="width:90%;"></td></tr>';
	$html.='<tr><th>Implication :</th><td><select name="implication">';
	$data=$rep->fetchAll();
	foreach	($data as $row)
		{
		$html.='<option value="'.$row['id_liaison'].'">'.$row['denomination'].'</option>';
		}
	$html.='</select></td><th><input type="hidden" name="MAX_FILE_SIZE" value="5000000" />Photo :</th><td><input type="file" name="imagePers" id="imagePers"/></td></tr>';
	$html.='<tr><td class="noborder" colspan="4"><input type="submit" value="Enregistrer"></td></tr>';
	}
$html.='</table></form>';
echo $html;

?>