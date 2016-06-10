<?php

if (isset($_GET['idVac'])){
	$idVac=$_GET['idVac'];
	$html='<form method="POST" action="index.php?component=vacancier&action=addRowToCr&idVac='.$idVac.'"><table>';
	$html.='<tr><th width="25%">Date :</th><td width="25%"><input type="date" name="DateNewRow" required autofocus></td><th width="25%">Heure :</th><td width="25%"><input type="time" name="TimeNewRow" required></td></tr>';
	$html.='<tr><th>Commentaire :</th><td colspan="2"><input type="text" name="ComNewRow" required></td><td class="noborder"><input type="submit" value="Ajouter"></td></tr>';
	$html.='</table></form>';
	echo $html;
}

?>