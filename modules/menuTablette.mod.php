<?php

if ((isset($_SESSION['idbs'])) && ($_REQUEST['action'] != 'MenuTablette') && ($_REQUEST['component']!='documentation') && ($_REQUEST['component']!='garde'))
	{
	$html='<ul>';
	$html.='<li><a href="index.php?mode=m&component=users&action=fromMenuTablette">Mes missions</a></li>';
	//$html.='<li><a href="index.php?mode=m&component=activites&action=intervention">Intervention</a></li>';
	$html.='<li><a href="index.php?mode=m&component=activites&action=patrouille">Initiative</a></li>';	
	$html.='<li><a href="index.php?mode=m&component=activites&action=SvInt">Service intérieur</a></li>';
	$html.='<li><a href="index.php?mode=m&component=activites&action=mainMenu">Mes activités</a></li>';
	$html.='<li><a href="index.php?mode=m&component=activites&action=GenBS">Générer BS</a></li>';
	}
	
else{
	$html='';
}	

$this->menuTablette=$html;

?>