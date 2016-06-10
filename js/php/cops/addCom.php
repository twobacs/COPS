<?php

include ('../connect.php');

if (isset($_GET['idFiche']))
	{
	$sql='SELECT id_liaison, denomination FROM z_liaison ORDER BY denomination';
	$rep=$pdo->query($sql);	
	$sql='SELECT IdRue, NomRue FROM z_rues';
	$rues=$pdo->query($sql);
	$html='<form method="post" name="formNewCom" action="index.php?component=cops&action=addCom&idFiche='.$_GET['idFiche'].'"><table>';
	$html.='<tr><th class="sstitre" colspan="4">Ajout d\'un commerce</th></tr>';
	$html.='<tr><th width="25%">Dénomination :</th><td width="25%"><input type="text" name="nomCom"></td><th width="25%">Implication :</th><td width="25%"><select name="implicCom" required>';
	while ($row=$rep->fetch())
		{
		$html.='<option value="'.$row['id_liaison'].'">'.$row['denomination'].'</option>';
		}
	$html.='</select></td></tr>';
	$html.='<tr><th>Adresse :</th><td colspan="3">CP : <input type="text" name="CPCom" style="width:15%;"> Ville : <input type="text" name="villeCom" style="width:15%;"> Rue : ';
	$html.='<select name="rueCom" style="width:25%;">';
	while ($street=$rues->fetch())
		{
		$html.='<option value="'.$street['IdRue'].'">'.$street['NomRue'].'</option>';
		}
	$html.='</select> ';
	$html.='Numéro : <input type="text" name="numCom" style="width:10%;"></td></tr>';
	$html.='<tr><th>Descriptif :</th><td colspan="3"><input type="text" name="descCom" style="width:90%;"></td></tr>';
	$html.='<tr><td colspan="4" class="noborder"><input type="submit" name="submitCom" value="Enregistrer"></td></tr>';
	$html.='</table></form>';
	}
echo $html;

?>