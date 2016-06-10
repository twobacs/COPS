<?php

include ('connect.php');

$sql='SELECT num_GSM FROM z_GSM';
$rep=$pdo->query($sql);
$html='Numéro d\'appel : <select name="gsm"><option></option>';
while ($row=$rep->fetch())
	{
	$html.='<option value="'.$row['num_GSM'].'">'.$row['num_GSM'].'</option>';
	}
$html.='</select>';

echo $html;
?>