<?php

include ('connect.php');

$html='<th>Collaborateur(s) à engager : </th><td><select name="nbCol" id="nbCol" onchange="collabos();">';
for ($i=0;$i<11;$i++)
	{
	$html.='<option value="'.$i.'">'.$i.'</option>';
	}
$html.='</select></td>';

echo $html;
?>