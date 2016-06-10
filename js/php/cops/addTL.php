<?php

include ('../connect.php');

if (isset($_GET['idFiche']))
	{
	$html='<form method="post" name="formNewTL" action="index.php?component=cops&action=addTL&idFiche='.$_GET['idFiche'].'"><table>';
	$html.='<tr><th class="sstitre" colspan="2">Ajout d\'un texte libre</th></tr>';
	$html.='<tr><th width="25%">Titre :</th><td><input type="text" name="titre" style="width:90%;"></td></tr>';
	$html.='<tr><th>Texte :</th><td><input type="text" name="texte" style="width:90%;"></td></tr>';
	$html.='<tr><td colspan="2" class="noborder"><input type="submit" value="Enregistrer"></td></tr>';
	}
echo $html;

?>