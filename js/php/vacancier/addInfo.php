<?php
$type=$_GET['type'];
$idVac=$_GET['idVac'];

switch ($type)
	{
	case 'AddVV' :
		$html='<form method="post" action="index.php?component=vacancier&action=addVV&id='.$idVac.'"><table>';
		$html.='<tr><th class="titre" colspan="4">Véhicule à ajouter</th></tr>';
		$html.='<tr><th>Marque + modèle</th><td><input type="text" name="newVV" required><th>Immatriculation : </th><td><input type="text" name="newImmat" required></td></tr>';
		$html.='<tr><th>Lieu d\'entreposage :</th><td><input type="text" name="newLieu" required></td><td class="noborder"><input type="submit" value="Enregistrer"></td></tr>';
		break;
	case 'AddPers' :	
		$html.='<form method="post" action="index.php?component=vacancier&action=addPers&id='.$idVac.'"><table>';
		$html.='<tr><th class="titre" colspan="4">Personne de contact à ajouter</th></tr>';
		$html.='<tr><th>Nom :</th><td><input type="text" name="newNom" required></td><th>Prénom :</th><td><input type="text" name="newPrenom" required></td></tr>';
		$html.='<tr><th>Adresse :</th><td><input type="text" name="newAdress" required><input type="text" name="newNum" size="5" required></td><th>CP + ville :</th><td><input type="text" size="5" name="newCP" required><input type="text" name="newCity" required></td></tr>';
		$html.='<tr><th>Numéros d\'appel :</th><td><input type="text" name="newTel1" required></td><td><input type="text" name="newTel2"></td><td class="noborder"><input type="submit" value="Enregistrer"></td></tr>';
	}
$html.='</table></form>';
echo $html;
?>