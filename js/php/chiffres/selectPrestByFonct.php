<?php

include ('../connect.php');
$html='';
if (isset($_GET['idFonct'])){
	$sql='SELECT id_prestation, denomination, descriptif FROM z_prestations WHERE id_fonctionnalite=:id';
	$req=$pdo->prepare($sql);
	$req->execute(array(
	'id' => $_GET['idFonct']
	));
	$html.='<select name="indicateur" id="indicateur">';
	while($row=$req->fetch()){
		$html.='<option value="'.$row['id_prestation'].'">'.$row['denomination'].'</option>';
	}
	$html.='</select>';
}
else $html.='Oups, il y a eu un souci';
echo $html;

?>