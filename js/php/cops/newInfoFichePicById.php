<?php

if(isset($_GET['nb']))
{
	$i=$_GET['nb'];
	$html='<tr><th colspan="4" class="sstitre">Ajout d\'une photo</th></tr>';
	$html.='<tr><th>Commentaire :</th><td colspan="3"><input type="text" name="comPic_'.$i.'"></td></tr>';
	$html.='<tr><th>Fichier :</th><td><input type="hidden" name="MAX_FILE_SIZE" value="5000000" /><input type="file" name="photoFiche_'.$i.'" id="photoFiche_'.$i.'"/><input type="hidden" name="nbPic" value="'.$i.'"></td></tr>';
	echo $html;
}

?>