<?php

$html='Appareil photo numéro : <select name=appPhoto><option></option>';
for ($i=1;$i<5;$i++)
	{
	$html.='<option value="App.'.$i.'">'.$i.'</option>';
	}
$html.='</select>';
echo $html;

?>