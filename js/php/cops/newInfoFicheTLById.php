<?php

if (isset($_GET['nb']))
{
	include ('../connect.php');
	$i=$_GET['nb'];
	$html='<tr><th class="sstitre" colspan="4">Ajout d\'un texte libre</th></tr></tr>';
	$html.='<tr><th colspan="1">Titre :</th><td colspan="3"><input type="text" name="titreTL_'.$i.'"></td></tr>';
	$html.='<tr><th>Texte :</th><td colspan="3"><textarea name="texteTL_'.$i.'" rows="4" cols="50"></textarea><input type="hidden" name="nbTL" value="'.$i.'"></td></tr>';
	echo $html;
}

?>