<?php

include ('connect.php');
include ('/var/www/class/vacancier.class.php');

if (isset($_GET['quantite']))
	{
	$q=$_GET['quantite']; //quantite de véhicules a encoder selon la demande de l'utilisateur
	$id=$_GET['idhab']; //id de l'habitation trouvee en historique
	$html='';
	
	$vac = new Vacancier($pdo);
	$vv=$vac->getVVbyIdVac($id);
	$total=$vv['total'];
		
	for ($i=1;$i<=$q;$i++)
		{
		$html.='<tr><td class=noborder></td><th class=sstitre colspan="2">Véhicule '.$i.'</th><td class=noborder></td></tr>';
		$html.='<tr><th>Immatriculation :</th><td><input type=text name=immVV'.$i.' value="'.$vv[$i]['imm'].'"';
		if ($i==1){$html.=' autofocus';}
		$html.='></td><th>Marque et modèle :</th><td><input type=text name=marqueVV'.$i.' value="'.$vv[$i]['marque'].'"</td></tr>';
		$html.='<tr><th>Lieu d\'entreposage :</th><td colspan="1"><input type=text name=lieuVV'.$i.' value="'.$vv[$i]['lieu'].'"></td><td colspan="2" class=noborder></td></tr>';
		}
	$html.='<tr><th class=titre colspan="4">Informations relatives aux personnes de contact</th></tr>';
	$html.='<tr><th colspan="2">Nombre de personne(s) à encoder :</th><td colspan="2"><input id=nbContact type=text onkeyup="addFormContact(\''.$id.'\');"><input type=hidden name=ttvv value="'.$q.'"></td></tr>';
	
	
	
	echo $html;
	}

?>