<?php
include ('../connect.php');
$html='<th>Nouveau collaborateur :</th><td><select name="newUser" id="newUser">';
$sql='SELECT nom, prenom, id_user FROM users ORDER BY nom, prenom';
$req=$pdo->query($sql);
// $req->execute(array());
while($row=$req->fetch()){
	$html.='<option value="'.$row['id_user'].'">'.$row['nom'].' '.$row['prenom'].'</option>';
}
$html.='</select></td>';
echo $html;

?>