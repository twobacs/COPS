<?php

include ('connect.php');
include ('/var/www/class/vacancier.class.php');

if (isset($_GET['quantite']))
	{
	$q=$_GET['quantite']; //quantite de véhicules a encoder selon la demande de l'utilisateur
	$id=$_GET['idhab']; //id de l'habitation trouvee en historique
	$html='';
	
	$vac = new Vacancier($pdo);
	$pers=$vac->getPersByIdVac($id);
	$total=$pers['total'];
		
	for ($i=1;$i<=$q;$i++)
		{
		$html.='<tr><td class=noborder></td><th class=sstitre colspan="2">Personne '.$i.'</th><td class=noborder></td></tr>';
		$html.='<tr><th>Nom :</th><td><input type=text name=nomCont'.$i.' value="'.$pers[$i]['nom'].'"';
		if ($i==1){$html.=' autofocus';}
		$html.='></td><th>Prénom :</th><td><input type=text name=prenomCont'.$i.' value="'.$pers[$i]['prenom'].'"</td></tr>';
		$html.='<tr><th>Adresse :</th><td><input type=text name=adresseCont'.$i.' value="'.$pers[$i]['adresse'].'"></td><th>Numéro :</th><td><input type=text name=numCont'.$i.' value="'.$pers[$i]['numero'].'"></td></tr>';
		$html.='<tr><th>Code postal : </th><td><input type=text name=CPCont'.$i.' value="'.$pers[$i]['CP'].'"></td><th>Ville :</th><td><input type=text name=villeCont'.$i.' value="'.$pers[$i]['ville'].'"></td></tr>';
		$html.='<tr><th>Téléphone :</th><td><input type=text name=telCont'.$i.' value="'.$pers[$i]['tel'].'"></td><th>Téléphone :</th><td><input type=text name=tel2Cont'.$i.' value="'.$pers[$i]['tel2'].'"><input type=hidden name=ttCont value="'.$q.'"></td></tr>';
		}

	
	
	// echo $pers[0]['nom'];
	echo $html;
	}
	

// else echo 'test';

?>
