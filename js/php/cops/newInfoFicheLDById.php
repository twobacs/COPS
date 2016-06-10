<?php

if (isset($_GET['nb']))
	{
	include ('../connect.php');
	$i=$_GET['nb'];
	$sql='SELECT id_liaison, denomination FROM z_liaison ORDER BY denomination';
	$rep=$pdo->query($sql);
	$html='<tr><th class="sstitre" colspan="4">Ajout d\'un lieudit</th></tr>';
	$html.='<tr><th>Démination :</th><td><input type="text" name="denomLD_'.$i.'"></td>';
	$html.='<th>Implication :</th><td><input type="hidden" name="nbLD" value="'.$i.'"><select name="implicationLD_'.$i.'">';
	$data=$rep->fetchAll();
	foreach	($data as $row)
		{
		$html.='<option value="'.$row['id_liaison'].'">'.$row['denomination'].'</option>';
		}
	
	$html.='</select></td></tr>';
	echo $html;
	}
?>