<?php

if(isset($_GET['idFiche']))
	{
	$html='<form method="post" enctype="multipart/form-data" name="formNewPic" action="?component=cops&action=addPic&idFiche='.$_GET['idFiche'].'"><table>';
	$html.='<tr><th class="sstitre" colspan="4">Ajout d\'une photo</th></tr>';
	$html.='<tr><th>Commentaire :</th><td><input type="text" name="comPic"><th><input type="hidden" name="MAX_FILE_SIZE" value="5000000"/>Photo :</th><td><input type="file" name="newPic" id="newPic"/></td></tr>';
	$html.='<tr><td colspan="4" class="noborder"><input type="submit" value="Enregistrer"></td></tr>';
	$html.='</table>';
	}
	
echo $html;

?>