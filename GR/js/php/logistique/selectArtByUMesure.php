<?php

if(isset($_GET['id'])){
	include ('../autoload.php');
	$art=new Articles($pdo);
	$articles=$art->getArticlesByIdMesure($_GET['id']);
	$mesures=$art->getMesures();
	$html='<hr><h4>Modification d\'articles avant suppression unit&eacute; de mesure</h4>';
	$html.='<table><td><input type="button" value="Fermer ce menu" onclick="slide2(\'msgSlide3\');"></td></table>';
	$html.='<table>';
	$html.='<tr><th>Article</th><th>Commentaire</th><th>Unit&eacute; de mesure actuelle</th><th colspan="2" style="width:25%;">Nouvelle unit&eacute; de mesure</th></tr>';
	while($row=$articles->fetch()){
		$html.='<tr><td>'.ucfirst($row['denomination']).'</td><td>'.ucfirst($row['commentaire']).'</td><td>'.ucfirst($row['mesure']).'</td><td><select name="newUmesure" id="newUmesure_'.$row['id_article'].'" onchange="updateUMesureByIdArt(\''.$row['id_article'].'\');"><option></option>';
		while($rowCat=$mesures->fetch()){
			if($rowCat['id_uMesure']!=$_GET['id']){
				$html.='<option value="'.$rowCat['id_uMesure'].'">'.ucfirst($rowCat['denomination']).'</option>';
			}
		}
		$html.='</select></td><td id="confirmSpace_'.$row['id_article'].'"></td></tr>';
	}
	$html.='</table>';
	echo $html;
}
?>