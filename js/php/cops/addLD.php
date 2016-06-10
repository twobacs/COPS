<?php

include ('../connect.php');

if (isset($_GET['idFiche']))
	{
	$sql='SELECT id_liaison, denomination FROM z_liaison ORDER BY denomination';
	$rep=$pdo->query($sql);	
	$html='<form method="post" name="formNewLD" action="index.php?component=cops&action=addLD&idFiche='.$_GET['idFiche'].'"><table>';
	$html.='<tr><th class="sstitre" colspan="4">Ajout d\'un lieudit</th></tr>';
	$html.='<tr><th width="25%">Dénomination :</th><td width="25%"><input type="text" name="denomination"></td><th width="25%">Implication :</th><td width="25%"><select name="implication">';
	while($row=$rep->fetch())
		{
		$html.='<option value="'.$row['id_liaison'].'">'.$row['denomination'].'</option>';
		}
	$html.='</select></td></tr>';
	$html.='<tr><td colspan="4" class="noborder"><input type="submit" value="Enregistrer"></td></tr>';
	}
echo $html;

?>