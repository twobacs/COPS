<?php

include ('../connect.php');
$html='<hr>';
$sql='SELECT a.date_heure_debut, a.date_heure_fin, a.id_prestation, 
b.denomination AS denomPrest, 
c.denomination AS denomFonct
FROM z_patrouille a
LEFT JOIN z_prestations b ON a.id_prestation = b.id_prestation
LEFT JOIN z_fonctionnalites c ON b.id_fonctionnalite=c.id_fonctionnalite
WHERE id_patrouille="'.$_GET['pat'].'"';
$rep=$pdo->query($sql);

$sql='SELECT denomination, id_quartier FROM z_quartier ORDER BY denomination';
$quart=$pdo->query($sql);

while ($row=$rep->fetch())
	{
	$html.='Horaire prévu : du '.dateHrfr($row['date_heure_debut']).' au '.dateHrfr($row['date_heure_fin']).'<br />';
	if($row['id_prestation']!='0')
		{
		$html.='Fonctionnalité : '.$row['denomFonct'].'.<br />Prestation : '.$row['denomPrest'].'<br />';
		}
	$html.='<hr>';
	$html.='<form><table>';
	$html.='<tr><th>Lieu :</th><td width=32%><select name=quartier id=quartier onchange="getListRues();"><option value=""></option>';
	while ($rowa=$quart->fetch())
		{
		$html.='<option value="'.$rowa['id_quartier'].'">'.$rowa['denomination'].'</option>';
		}
	$html.='</select></td><td width=32%><div id=listRues></div></td></tr>';
	$html.='<tr><th>Heure début :</th><td colspan="2"><input type="time" name="hrBasse"></td></tr>';
	$html.='<tr><th>Heure fin :</th><td colspan="2"><input type="time" name="hrHt"></td></tr>';
	$html.='<tr><td colspan="3" class="noborder"><input type=submit value="Ajouter"></td></tr>';
	$html.='</table></form>';
	}
echo $html;
?>
