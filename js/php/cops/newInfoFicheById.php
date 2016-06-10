<?php

if (isset($_GET['nb']))
	{
	include ('../connect.php');
	$i=$_GET['nb'];
	$sql='SELECT id_liaison, denomination FROM z_liaison ORDER BY denomination';
	$rep=$pdo->query($sql);
	$html.='<tr><th class="sstitre" colspan="4">Ajout d\'une personne</th></tr>';
	$html.='<tr><th width="25%">Nom : </th><td width="25%"><input type="text" name="nomPers_'.$i.'" style="text-transform:uppercase"></td><th width="25%">Prénom :</th><td width="25%"><input type="text" name="prenom_'.$i.'"></td></tr>';
	$html.='<tr><th>Date de naissance :</th><td><input type="date" name="DN_'.$i.'"></td><th>Pays de résidence :</th><td><input type="text" name="PaysRes_'.$i.'"></td></tr>';
	$html.='<tr><th>Adresse :</th><td colspan="3">CP : <input type="text" name="CPPers_'.$i.'" style="width:15%;"> Ville : <input type="text" name="villePers_'.$i.'" style="width:15%;"> Rue : <input type="text" name="RuePers_'.$i.'" style="width:25%;"> Numéro : <input type="text" name="numPers_'.$i.'" style="width:10%;"><input type="hidden" name="nbPers" value="'.($i+1).'"></td></tr>';
	$html.='<tr><th>Descriptif :</th><td colspan="2"><textarea rows="3" cols="50" name=descrPers_'.$i.'  placeholder="Entrez ici une description"></textarea></td><td>Implication :	<select name="implicationPers_'.$i.'">';
	$data=$rep->fetchAll();
	foreach	($data as $row)
		{
		$html.='<option value="'.$row['id_liaison'].'">'.$row['denomination'].'</option>';
		}
	$html.='</select></td></tr>';
	$html.='<tr><th colspan="4"><input type="hidden" name="MAX_FILE_SIZE" value="5000000" />Photo : <input type="file" name="imagePers_'.$i.'" id="imagePers_'.$i.'"/></th></tr>';
	echo $html;
	}

?>