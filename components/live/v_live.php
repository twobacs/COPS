<?php

class VLive extends VBase {

    function __construct($appli, $model) {
        parent::__construct($appli, $model);
    }

public function nonco()
{
	$this->appli->ctContent.='Vous ne pouvez accéder à cette partie du site, <a href=\"?component=users&action=logForm\">connectez-vous.</a>';
}

public function afficheHtml($html)
{
	$this->appli->ctContent=$html;
	$this->appli->jScript= '<script type="text/javascript" src="./js/infos.js"></script>';
}

private function datefr($date,$h=0)
	{
    $split = explode(" ",$date);
    $jour = $split[0];
	$heure = ($h==0) ? '' : $split[1];

	$split2 = explode("-",$jour);
	$annee = $split2[0];
    $mois = $split2[1];
    $jour = $split2[2];

	$return = ($h==0) ? $jour."-".$mois."-".$annee : $jour."-".$mois."-".$annee." à ".$heure;

	return $return;
	}

public function showUnreads($mInfos)
{
	$html='<h3>Aucun message non lu</h3>';
	if($mInfos)
	{
		$html='<h3>Messages non lus ('.sizeof($mInfos).')</h3>';
		$html.='<table>';
		for($i=0;$i<sizeof($mInfos);$i++)
		{
			$html.='<th>'.$mInfos[$i]['titre'].'</th><td>'.$mInfos[$i]['info'].'</td><td><a href="?mode=pop&component=live&action=seeMessages&setRead='.$mInfos[$i]['id'].'">Marquer comme lu</a></td></tr>';
		}
		$html.='<tr><th colspan="3"><a href="?mode=pop&component=infos&action=showInfos" target="_blank">Toutes les infos</a></th></table>';
	}
	$this->afficheHtml($html);
}
	
}
?>
