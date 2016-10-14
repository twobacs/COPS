<?php

class VBs extends VBase {

    function __construct($appli, $model) {
        parent::__construct($appli, $model);
    }
	
public function nonCo()
		{
		$this->appli->ctContent='Vous n\'êtes pas connecté ou votre session a expiré.  Veuillez vous (re)connecter.';
		}
		
public function afficheHtml($html)
	{
	$this->appli->ctContent=$html;
	$this->appli->jScript= '<script type="text/javascript" src="./js/bs.js"></script>';
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

		return $jour."-".$mois."-".$annee.' à '.$heure;
	}		

public function afficheListBS($data)
	{
	
	$html='<div id="viewBS"><h2>Gestion des BS</h2>';
	$html.='<form method="POST" action="?component=bs&action=mainMenu&date=true"><table>';
	$html.='<tr><th>Recherche sur une date :</th><td><input type="date" name="dateBS"></td><td><input type="submit" value="Trouver"></td></tr>';
	$html.='</table></form>';
	$html.='<table>';
	$html.='<tr><th width="20%"><a href="?component=bs&action=mainMenu&orderBy=indicatifUp"><img src="'.MEDIA.'/icons/up.png" height="15" align="left"></a>Indicatif<a href="?component=bs&action=mainMenu&orderBy=indicatifDown"><img src="'.MEDIA.'/icons/down.png" height="15" align="right"></a></th><th width=20%"><a href="?component=bs&action=mainMenu&orderBy=DeUp"><img src="'.MEDIA.'/icons/up.png" height="15" align="left"></a>De<a href="?component=bs&action=mainMenu&orderBy=DeDown"><img src="'.MEDIA.'/icons/down.png" height="15" align="right"></a></th><th width="20%"><a href="?component=bs&action=mainMenu&orderBy=AUp"><img src="'.MEDIA.'/icons/up.png" height="15" align="left"></a>A<a href="?component=bs&action=mainMenu&orderBy=ADown"><img src="'.MEDIA.'/icons/down.png" height="15" align="right"></a></th><td class="noborder" width="10%"></td></tr>';
	while ($row=$data->fetch())
		{
		// $html.='<tr><td>'.$row['indicatif'].'</td><td>'.$this->datefr($row['date_heure_debut']).'</td><td>'.$this->datefr($row['date_heure_fin']).'</td><td><input type="button" onclick="viewBS(\''.$row['id_patrouille'].'\',\''.$row['id_bs'].'\',\''.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"].'\');" value="Voir BS"></td></tr>';
		$html.='<tr><td>'.$row['indicatif'].'</td><td>'.$this->datefr($row['date_heure_debut']).'</td><td>'.$this->datefr($row['date_heure_fin']).'</td><td><a href="/js/php/bs/viewBS.php?idPat='.$row['id_patrouille'].'&idbs='.$row['id_bs'].'" target="_blank">Voir BS</td></tr>';
		}
	$html.='</table></div>';
	
	$this->afficheHtml($html);
	}

}
?>