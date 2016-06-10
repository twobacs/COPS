<?php

include ('connect.php');

$html='<table><tr><th>Collaborateur(s) à engager : </th><td><select name=nbCol id=nbCol onchange="collabos();" />';
for ($i=0;$i<11;$i++)
	{
	$html.='<option value="'.$i.'">'.$i.'</option>';
	}
$html.='<select></td></tr>';

$html.='<tr><th>Nombre de véhicule(s) à engager :</th><td><select name=nbVV id=nbVV />';
for ($i=0;$i<11;$i++)
	{
	$html.='<option value='.$i.'>'.$i.'</option>';
	}
$html.='</select></td></tr>';
$html.='</table>';

echo $html;

?>