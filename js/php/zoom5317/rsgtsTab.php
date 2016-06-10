<?php

include ('connect.php');

$html.='<th>Nombre de véhicule(s) à engager :</th><td><select name="nbVV" id="nbVV">';
for ($i=0;$i<11;$i++)
	{
	$html.='<option value='.$i.'>'.$i.'</option>';
	}
$html.='</select></td>';
// $html.='</table>';

echo $html;

?>