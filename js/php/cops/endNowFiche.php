<?php

include ('../connect.php');

$id=$_GET['id'];
$sql='UPDATE z_fiche SET date_fin=NOW() WHERE id_fiche="'.$id.'"';
$pdo->exec($sql);
$sql='SELECT date_fin FROM z_fiche WHERE id_fiche="'.$id.'"';
$rep=$pdo->query($sql);
while ($row=$rep->fetch())
	{
	$date=$row['date_fin'];
	}
$split=explode(" ",$date);
$split2=explode("-",$split[0]);
$a=$split2[0];
$m=$split2[1];
$j=$split2[2];
echo $j.'-'.$m.'-'.$a;

?>