<?php

include ('connect.php');

$sql='SELECT id_ETT FROM z_ETT where date_validite>NOW() ORDER BY id_ETT';
$rep=$pdo->query($sql);
$html='ETT numéro : <select name="ETT"><option></option>';
while ($row=$rep->fetch())
	{
	$html.='<option value="'.$row['id_ETT'].'">'.$row['id_ETT'].'</option>';
	}
$html.='</select>';

echo $html;

?>