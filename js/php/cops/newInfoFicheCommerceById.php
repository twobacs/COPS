<?php

if (isset($_GET['nb']))
	{
	include ('../connect.php');
	$i=$_GET['nb'];
	$sql='SELECT id_liaison, denomination FROM z_liaison ORDER BY denomination';
	$rep=$pdo->query($sql);
	$sql='SELECT IdRue, NomRue FROM z_rues';
	$rues=$pdo->query($sql);
	$html='<tr><th class="sstitre" colspan="4">Ajout d\'un commerce</th></tr>';
	$html.='<tr><th width="18%">Dénomination :</th><td><input type="text" name="denomCom_'.$i.'" autofocus></td><th>Descriptif :</th><td><input type="text" name="descCom_'.$i.'"></td></tr>';
	$html.='<tr><th>Adresse :</th><td colspan="3">CP : <input type="text" name="CPCom_'.$i.'" style="width:15%;"> Ville : <input type="text" name="comCom_'.$i.'" style="width:15%;"> Rue : <select name="idRueCom_'.$i.'" style="width:25%;">';
	while ($row=$rues->fetch())
		{
			$html.='<option value="'.$row['IdRue'].'">'.$row['NomRue'].'</option>';
		}
	$html.='</select> n° : <input type="text" name="numCom_'.$i.'" style="width:10%;"></td></tr>';
	$html.='<tr><th>Implication :</th><td colspan="3"><select name="implicationCom_'.$i.'">';
	while ($row=$rep->fetch())
		{
			$html.='<option value="'.$row['id_liaison'].'">'.$row['denomination'].'</option>';
		}
	$html.='<input type="hidden" name="nbCommerce" value="'.$i.'"></td></tr>';
	echo $html;
	}

?>