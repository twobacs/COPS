<?php

include ('../connect.php');
if (isset($_GET['fiche']))
	{
	$DD=$_GET['DD'];
	$HD=$_GET['HD'];
	$DF=$_GET['DF'];
	$HF=$_GET['HF'];
	$idfiche=$_GET['fiche'];

	$dhd=$DD.' '.$HD;
	$dhf=$DF.' '.$HF;

	$sql='UPDATE z_fiche SET date_debut="'.$_GET['DD'].' '.$_GET['HD'].'", date_fin="'.$_GET['DF'].' '.$_GET['HF'].'" WHERE id_fiche="'.$idfiche.'"';
	$pdo->exec($sql);
	}
// echo $sql;

?>