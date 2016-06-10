<?php
include ('connect.php');

$html='';

$sql='SELECT num_arme FROM armes WHERE marque_arme="UZI" AND disponible="O"';
$rep=$pdo->query($sql);

$html='Arme numéro : <select name=UZI><option></option>';

while ($row=$rep->fetch())
	{
	$html.='<option value="'.$row['num_arme'].'">'.$row['num_arme'].'</option>';
	}
	
$html.='</select>';

echo $html;

?>