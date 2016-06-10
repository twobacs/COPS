<?php
include ('../connect.php');

$text=$_GET['champs'];
$html='';
$i=0;
$j=0;

//RECHERCHE SUR LE NOM DE RUE
$sql='SELECT NomRue, IdRue FROM z_rues WHERE NomRue LIKE "%'.stripAccents(strtoupper($text)).'%"';
$rep=$pdo->query($sql);
while ($row=$rep->fetch())
	{
	$rue[$i]['idRue']=$row['IdRue'];
	$rue[$i]['NomRue']=$row['NomRue'];
	$i++;
	}

$html='<h3>Rue(s) correspondante(s)</h3>';
if ($i==0)
	{
	$html.='Aucune rue ne correspond à votre recherche';	
	}
else
	{
	for ($j=0;$j<=$i;$j++)
		{
		$html.='<a href="index.php?component=vacancier&action=search&type=rue&key='.$rue[$j]['idRue'].'">'.$rue[$j]['NomRue'].'</a><br />';
			}
	}
$html.='<hr>';
//RECHERCHE SUR LE NOM DE PERSONNE
$i=0;
$sql='SELECT id_dem, nom_dem, prenom_dem, dn_dem FROM z_vac_demandeur WHERE nom_dem LIKE "%'.stripAccents(strtoupper($text)).'%"';
$rep=$pdo->query($sql);
while ($row=$rep->fetch())
	{
	$pers[$i]['id']=$row['id_dem'];
	$pers[$i]['nom']=$row['nom_dem'];
	$pers[$i]['prenom']=$row['prenom_dem'];
	$pers[$i]['dn']=$row['dn_dem'];
	$i++;
	}

$html.='<h3>Personne(s) correspondante(s)</h3>';	
if ($i==0)
	{
	$html.='Aucune personne ne correspond à votre recherche';	
	}
else
{
	for ($j=0;$j<=$i;$j++)
		{
		$html.='<a href="index.php?component=vacancier&action=search&type=pers&key='.$pers[$j]['id'].'">'.$pers[$j]['nom'].' '.$pers[$j]['prenom'].'</a><br />';
			}
}


echo $html;
?>
