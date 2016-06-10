<?php

include ('../connect.php');
include ('../../../class/quartier.class.php');
// include ('/var/www/zoom5317/class/quartier.class.php');

if (isset($_GET['id']))
	{
	$html='';
	$id=$_GET['id'];
	$quartier=new quartier($pdo);
	$rues=$quartier->getRues();
	$antenne=$quartier->getInfosAntennes($id);
	$agents=$quartier->getInfosAgents();
	$html.='<table>';
	while ($row=$antenne->fetch())
		{
		// $html='<table>';
		$html.='<tr><th>Dénomination :</th><td><input type=text id=denom'.$id.' autofocus value="'.$row['denomination'].'"></td><th>Adresse :</th><td><select id=adresse'.$id.'><option value=""></option>';
		while ($rowa=$rues->fetch())
			{
			$html.='<option value="'.$rowa['IdRue'].'" title="('.$rowa['StraatNaam'].')"';
			if ($rowa['IdRue']==$row['IdRue']){
				$html.=' selected';
				}
			$html.='>'.$rowa['NomRue'].'</option>';
			}
		$html.='</select> n° <input type=text size=5 id=num'.$id.' value="'.$row['numero'].'"></td></tr>';
		$html.='<tr><th>Téléphone :</th><td><input type=text id=phone'.$id.' value="'.$row['telephone'].'"></td><th>Fax :</th><td><input type=text id=fax'.$id.' value="'.$row['fax'].'"></td></tr>';
		$html.='<tr><th>Responsable antenne :</th><td><select name=resp id=resp'.$id.'><option value=""></option>';
		while ($rowb=$agents->fetch()){
			$html.='<option value="'.$rowb['id_user'].'">'.$rowb['nom'].' '.$rowb['prenom'].'</option>';
			}
		$html.='</td></tr>';
		$html.='<tr><td colspan="4" class=noborder><input type=button onclick=recModifsAnt(\''.$id.'\'); value=Enregistrer></td></tr>';
		}
	$html.='</table>';
	echo $html;
	}

?>
