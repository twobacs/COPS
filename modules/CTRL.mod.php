<?php

if (isset($_SESSION['idbs']))
	{
	$plus='<img src="./media/icons/bbadd.png" height="4%">';
	$moins='<img src="./media/icons/bbdel.png" height="4%">';
	
	$html='<center><b>MES CONTRÔLES</b></center><br />';
	
	$html.='<center>Personnes :</center><br />';
	$html.='<center><a href="index.php?component=activites&action=actionByPm&subaction=addP&from=modleft">'.$plus.'</a>'.$_SESSION['ctPers'].'<a href="index.php?component=activites&action=actionByPm&subaction=remP&from=modleft">'.$moins.'</a></center><br />';
	
	$html.='<center>Véhicules :</center><br /><center><a href="index.php?component=activites&action=actionByPm&subaction=addVV&from=modleft">'.$plus.'</a>'.$_SESSION['ctVV'].'<a href="index.php?component=activites&action=actionByPm&subaction=remVV&from=modleft">'.$moins.'</a></center><br />';
	
	
	}
?>