<?php
include ('../connect.php');
$html='<th>Nouveau v&eacute;hicule :</th><td><select name="newVV" id="newVV">';
$sql='SELECT immatriculation FROM z_vv_zp ORDER BY immatriculation';
$req=$pdo->query($sql);
// $req->execute(array());
while($row=$req->fetch()){
	$html.='<option value="'.$row['immatriculation'].'">'.$row['immatriculation'].'</option>';
}
$html.='</select></td>';
echo $html;
?>