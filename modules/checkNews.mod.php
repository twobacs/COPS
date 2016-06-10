<?php
// $this->appli->tplIndex = 'indexLive.html';
$html='Nous sommes le '.date('d-m-Y').', il est '.date('H').'h'.date('i').'.<br />';
if (isset($_COOKIE['iduser']))
{
	$html.='Vous n\'avez pas de message non lu.';	
}
/*
Créer une table contenant les id de :
- user, id infos proposées
- user, id fiche cops

Faire un check d'existence sur la ligne dans cette table, si pas existante, proposer un lien vers la lecture de celle-ci.
Déterminer la durée de vie des lignes0
Contrôler si la fiche info concernée existe encore.
Contrôler si il existe une nouvelle fiche bio
Pour chacun des types, établir un lien avec le nombre d'infos à lire
Pour chaque lien, proposer un pop up avec les titres des infos à lire, sou forme de lien menant vers la fiche cops / l'info concernée

*/
// echo $html;

?>