<?php

class VGestpers extends VBase {

    function __construct($appli, $model) {
        parent::__construct($appli, $model);
    }

public function datefr($date){
	$a=explode("-", $date);
	$annee=$a[0];
	$mois=$a[1];
	$jour=$a[2];
	$rep=$jour.'-'.$mois.'-'.$annee;
	return $rep;
}


public function unconnected(){
	$html='<div id="unconnected">';
	$html.='Vous n\'&ecirc;tes pas connect&eacute;(e) ou votre session a expir&eacute;, veuillez vous connecter.';
	$html.='</div>';
	$this->appli->content=$html;
}

public function accueil(){
	$html='<div id="menuAccueil">';
	$html.='<h1>Gestion du personnel</h1>';
	$html.='</div>';
	$this->appli->content=$html;
}
}
?>