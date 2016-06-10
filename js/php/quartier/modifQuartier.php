<?php

include ('../connect.php');
include ('../../../class/quartier.class.php');

if (isset($_GET['id']))
	{
	$html='';
	$id=$_GET['id'];
	$quartier=new quartier($pdo);
	// $rues=$quartier->getRues();
	$quart=$quartier->getInfosQuartiers($id);
	$html.='<table>';
	while ($row=$quart->fetch())
		{
		// $html='<table>';
		$html.='<tr><th>Dénomination :</th><td><input type=text id=denom'.$id.' autofocus value="'.$row['denomination'].'"></td><th>GSM associé :</th><td><input type=text id=gsm'.$id.' value="'.$row['gsm'].'">';
		// $html.='> n° <input type=text size=5 id=num'.$id.' value="'.$row['numero'].'"></td></tr>';
		// $html.='<tr><th>Téléphone :</th><td><input type=text id=phone'.$id.' value="'.$row['telephone'].'"></td><th>Fax :</th><td><input type=text id=fax'.$id.' value="'.$row['fax'].'"></td></tr>';
		$html.='<tr><td colspan="4" class=noborder><input type=button onclick=recModifsQuart(\''.$id.'\'); value=Enregistrer></td></tr>';
		}
	$html.='</table>';
	echo $html;
	}
?>