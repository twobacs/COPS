<?php

class VInfos extends VBase {

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

public function formNewInfo()
{
	$html='<h2>Nouvelle info</h2>';
	$html.='<form method="POST" action="?mode=m&component=infos&action=pushinfo">
			<h3>Titre : </h3><input type="text" name="title" id="title" maxlength="50" size="48" placeholder="Obligatoire" /><br />
            <h3>Info : </h3><textarea name="editor1" id="editor1" rows="10" cols="50" placeholder="Obligatoire" ></textarea><br />          
			<input type="submit" name="bPushInfo" id="bPushInfo" value="Enregistrer et fermer cette fenêtre" onclick="eval(setTimeout(\'window.close()\',1000));" />
        </form>';
	$this->afficheHtml($html);
	//
}

public function showInfosPushed($infos)
{
	$html='<h2>Infos proposées</h2><ul>';
	while($row=$infos->fetch())
	{
		$html.='<li>Par <em>'.$row['nom'].' '.$row['prenom'].'</em> le '.$this->datefr($row['dateIn']).' : <b>'.$row['titre'].'</b><br />'.$row['info'].'</li>';
	}
	$html.='</ul>';

	$this->afficheHtml($html);
}

public function autoClose()
{
	$this->appli->jScript= '<script language="javascript">eval(setTimeout(\'window.close()\',1));</script> ';
}
}
?>
