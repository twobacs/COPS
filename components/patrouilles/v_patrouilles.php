<?php

class VPatrouilles extends VBase {

    function __construct($appli, $model) {
        parent::__construct($appli, $model);
    }

public function nonco()
	{
	$this->appli->ctContent.="Vous ne pouvez accéder à cette partie du site, <a href=\"?component=users&action=logForm\">connectez-vous.</a>";
	}
	
	public function nonDroit()
	{
	$this->appli->ctContent.="Votre profil ne vous permet pas d'accéder à cette partie du site.";
	}

private function datefr($date,$dateOnly=0) 
	{
    $split = explode(" ",$date);
    $jour = $split[0];
	if(isset($split[1]))
		{
		$heure = $split[1];
		}
	
	$split2 = explode("-",$jour);	
	$annee = $split2[0];
    $mois = $split2[1];
    $jour = $split2[2];
	
	if($dateOnly==1)
		{
		return $jour."-".$mois."-".$annee;
		}
	else
		{
		return $jour."-".$mois."-".$annee.' à '.$heure;
		}
	}	
	
public function afficheHtml($data,$menu=0)	
	{
	$this->appli->ctContent=$data;
	$this->appli->jScript= '<script type="text/javascript" src="./js/patrouilles.js"></script>';
	// if ($menu!=0)
		// {
		// echo 'blah';
		// $this->appli->left='<a href=?component=patrouilles&action=newPat>Ajouter</a> une patrouille <br />';
		// $this->appli->left.='<a href=?component=patrouilles&action=viewPat>Aperçu</a> des patrouilles <br />';
		// $this->appli->left.='<a href=?component=patrouilles&action=searchPat>Faire une recherche</a><br />';
		// }
	}
	
function splitDateTime($data,$sep=0)
	{
	$val = ($sep==0) ? " " : "T";
	$var = explode ($val, $data);
	$date = $var[0];
	$splittedJour=explode ("-", $date);
	$annee=$splittedJour[0];
	$mois=$splittedJour[1];
	$jour=$splittedJour[2];
	$return=$jour.'-'.$mois.'-'.$annee.' '.$var[1];
	return $return;
	}

public function mainMenu($level)
	{
	if ($level<30)
		{
		$this->nonDroit();
		}
	else
		{
		$html='<h2>Gestion des patrouilles</h2>';
		$html.='<h3>Choisissez une action à effectuer</h3>';
		$html.='<ul>';
		$html.='<li><a href=?component=patrouilles&action=newPat>Ajouter</a> une patrouille</li>';
		$html.='<li><a href=?component=patrouilles&action=viewPat>Aperçu</a> des patrouilles</li>';
		$html.='</ul>';		
		$this->afficheHtml($html);
		}
	}
	
public function formNewPat($return=0)
	{
	$html='<h2>Création d\'une patrouille</h2>';
	$html.='<h3>De quel type de patrouille s\'agira-t-il ?</h3>';
	$html.='<a href=index.php?component=patrouilles&action=newPat&type=pmob>Patrouille mobile</a><br />';
	$html.='<a href=index.php?component=patrouilles&action=newPat&type=autre>Autre</a><br />';
	$html.='<a href=index.php?component=patrouilles&action=newPat&type=recu>Patrouille récurrente</a><br />';
	
	if ($return==1)
		{
		return $html;
		}
		
	else $this->afficheHtml($html,1);
	}
	
public function formNewPMob()
	{
	$html=$this->formNewPat(1);
	$html.='<h4>Nouvelle patrouille mobile</h4>';
	$html.='<form name="addPat" method="POST" onsubmit="return verifForm(\'3\');" action="?component=patrouilles&action=newPat&rec=true&from=pmob"><table>';
	$html.='<tr><th width="50%">Dénomination :</th><td><input type=text id="0" name="denPat" autofocus></td></tr>';
	$html.='<tr><th>Indicatif :</th><td><input type=text id="1" name="indicPat"></td></tr>';
	$html.='<tr><th>Date - heure début :</th><td><input type="datetime-local" id="2" name="dhDebut"></td></tr>';
	$html.='<tr><th>Date - heure fin :</th><td><input type="datetime-local" id="3" name="dhFin"></td></tr>';
	$html.='<tr><td colspan="2" class=noborder><input type=submit value="Enregistrer"><input type=reset></td></tr>';
	$html.='</table></form>';	
	$this->afficheHtml($html,1);
	}
	
public function formNewAutrePat($data)
	{
	$html=$this->formNewPat(1);
	$html.='<h4>Nouvelle patrouille autre</h4>';
	$html.='<form name="addPat" method="POST" onsubmit="return verifForm(\'5\');" action="?component=patrouilles&action=newPat&rec=true&from=other"><table>';
	$html.='<tr><th width="50%">Dénomination :</th><td><input type=text id="0" name="denPat" autofocus></td></tr>';
	$html.='<tr><th>Indicatif :</th><td><input type=text id="1" name="indicPat"></td></tr>';
	$html.='<tr><th>Fonctionnalité :</th><td>';
	$html.='<select id ="2" name=fonctionnalite onchange="upPresta();"><option value=""></option>';
	while ($row=$data->fetch())
		{
		$html.='<option value="'.$row['id_fonctionnalite'].'">'.$row['denomination'].'</option>';
		}
	$html.='</select></td></tr>';
	$html.='<tr><th>Prestation :</th><td><div id="presta"><select name="prest" id="3"><option value=""></option></select></div></td></tr>';
	$html.='<tr><th>Date - heure début :</th><td><input type="datetime-local" id="4" name="dhDebut"></td></tr>';
	$html.='<tr><th>Date - heure fin :</th><td><input type="datetime-local" id="5" name="dhFin"></td></tr>';
	$html.='<tr><td colspan="2" class=noborder><input type=submit value="Enregistrer"><input type=reset></td></tr>';
	$html.='</table></form>';	
	$this->afficheHtml($html,1);
	}
	
public function okNewPat($data)
	{
	switch ($data)
		{
		case -1 :
			$html='<h4>La patrouille que vous désirez créer existe déjà en base de données, veuillez vérifier.</h4>';
			break;
			
		default :
			$html='<h4>La patrouille a été encodée avec succès.</h4>';
			break;
		}
	$html.=$this->formNewPat(1);
	$this->afficheHtml($html,1);
	}
	
public function okNewPatRec($data)
	{
	if (sizeof($data)>0)
		{
		$html='Des patrouilles de cette série existent déjà en base de données, elles n\'ont donc pas été recréées.  Veuillez vérifier et, au besoin, recommencer.<br />Dates concernées : <br />';
		for($i=0;$i<sizeof($data);$i++)
			{
			$html.=$this->datefr($data[$i]['date'],1).'<br />';
			}
		}
	else
		{
		$html='Toutes les patrouilles ont été créées avec succès.<br />';
		}
	$html.=$this->formNewPat(1);	
	$this->afficheHtml($html,1);	
	}
	
public function viewPat($data)
	{
	$html='<h2>Patrouilles en cours et à venir</h2>';
	$html.='<table>
	<tr>
	<th>Dénomination <a href=?component=patrouilles&action=viewPat&tri=denUp><img src=../media/icons/up.png height=50%></a><a href=?component=patrouilles&action=viewPat&tri=denDown><img src=../media/icons/down.png height=50%></a></th>
	<th>Indicatif <a href=?component=patrouilles&action=viewPat&tri=indUp><img src=../media/icons/up.png height=50%></a><a href=?component=patrouilles&action=viewPat&tri=indDown><img src=../media/icons/down.png height=50%></a></th>
	<th>Début prévu <a href=?component=patrouilles&action=viewPat&tri=dhdUp><img src=../media/icons/up.png height=50%></a><a href=?component=patrouilles&action=viewPat&tri=dhdDown><img src=../media/icons/down.png height=50%></a></th>
	<th>Fin prévue <a href=?component=patrouilles&action=viewPat&tri=dhfUp><img src=../media/icons/up.png height=50%></a><a href=?component=patrouilles&action=viewPat&tri=dhfDown><img src=../media/icons/down.png height=50%></a></th>
	<td class="noborder"></td>
	</tr>';
	while ($row=$data->fetch())
		{
		$html.='<tr><td>'.$row['denomination'].'</td><td>'.$row['indicatif'].'</td><td>'.$this->splitDateTime($row['date_heure_debut']).'</td><td>'.$this->splitDateTime($row['date_heure_fin']).'</td><td><input type="button" onclick="delPat(\''.$row['id_patrouille'].'\');" value="Supprimer"></td></tr>';
		}
	$html.='</table>';
	$html.='<a href=index.php?component=patrouilles&action=mainMenu>Menu Patrouilles</a>';
	$this->afficheHtml($html,1);
	// $this->appli->left='<a href=index.php?component=patrouilles&action=mainMenu>Menu Patrouilles</a>';
	}
	
public function formSearchPat()
	{
	$html='<h2>Rechercher</h2>';
	$html.='<h4>Laissez vide pour les patrouilles du jour</h4>';
	$html.='<form name=searchPat method=POST action="?component=patrouilles&action=searchPat"><table>';
	$html.='<tr><th>Date - heure basse :</th><td><input type=datetime-local name=dhb></td></tr>';
	$html.='<tr><th>Date - heure haute :</th><td><input type=datetime-local name=dhh></td></tr>';
	$html.='<tr><td colspan="2" class=noborder><input type=submit value="Trouver !"></td></tr>';
	$html.='</table></form>';
	$this->afficheHtml($html,1);
	}
	
public function HistoPat($data, $dhb, $dhh)
	{
	$html='<h2>Résultat de votre recherche de patrouilles</h2>';
	$html.='<h3>Période recherchée : entre le '.$this->splitDateTime($dhb,1).' et le '.$this->splitDateTime($dhh,1).'</h3>';
	$html.='<table>';
	$html.='<tr><th>Date - heure début</th><th>Date - heure fin</th><th>Indicatif</th><th>Dénomination</th></tr>';
	while ($row=$data->fetch())
		{
		$html.='<tr><td>'.$this->splitDateTime($row['date_heure_debut']).'</td><td>'.$this->splitDateTime($row['date_heure_fin']).'</td><td>'.$row['indicatif'].'</td><td>'.$row['denomination'].'</td></tr>';
		}
	$html.='</table>';
	$this->afficheHtml($html,1);
	}
	
public function formPatRecu($data)
	{
	$html=$this->formNewPat(1);
	$html.='<h4>Nouvelle patrouille avec récurrence</h4>';
	$html.='<form><table>';
	$html.='<tr><th width="50%">Type de patrouille</th><td><select name="TPat" id="TPat" onchange="formNewRecu();"><option value="0"></option><option value="PM">Patrouille mobile</option><option value="Other">Autre</option></select></td></tr>';
	$html.='</table></form>';
	$html.='<div id="laSuite"></div>';
	
	$this->afficheHtml($html,1);
	}
	
}
?>