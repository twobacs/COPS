<?php

if (isset($_GET['idMission']))
	{
	include ('../connect.php');
	$step=$_GET['step'];
	$idMission=$_GET['idMission'];
	$idPat=$_GET['idPat'];
	$i=$_GET['i'];
	if ($step=="start")
		{
		$sql='UPDATE z_pat_missions SET date_heure_in=NOW() WHERE id_fiche="'.$idMission.'" AND id_patrouille="'.$idPat.'"';
		$pdo->exec($sql);
		$html='<a href="#" class="bSP" onclick="chgOtherMission(\'end\',\''.$i.'\', \''.$idMission.'\',\''.$idPat.'\');">Fin</a>';
		}
	else if ($step=="end")
		{
		$sql='UPDATE z_pat_missions SET date_heure_out=NOW() WHERE id_fiche="'.$idMission.'" AND id_patrouille="'.$idPat.'"';
		$pdo->exec($sql);
		$html='';
		}
	echo $html;
	}

?>