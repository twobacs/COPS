<?php

include ('../connect.php');
if (isset($_GET['idQ']))
	{
	$i=0;
	$idQ=$_GET['idQ'];
	$sql='SELECT b.idRue, b.NomRue, b.StraatNaam FROM z_quartier_rue a 
	LEFT JOIN z_rues b ON b.IdRue = a.idRue
	WHERE id_quartier="'.$idQ.'"
	GROUP BY NomRue ORDER BY NomRue';
	
	$html='<select name=rue id=rue><option value=""></option>';
	$rep=$pdo->query($sql);
	while ($row=$rep->fetch())
		{
		$html.='<option value="'.$row['idRue'].'">'.$row['NomRue'].'</option>';
		}
	$html.='</select>';
	echo $html;
	}
?>