<?php

if (isset($_GET['idPat']))
	{
	include ('../connect.php');
	$sql='DELETE FROM z_pat_missions WHERE id_patrouille="'.$_GET['idPat'].'"';
	$pdo->exec($sql);
	$sql='DELETE FROM z_patrouille WHERE id_patrouille="'.$_GET['idPat'].'"';
	$pdo->exec($sql);
	}

?>