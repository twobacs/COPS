<?php

/*BEFORE
$html='<ul id="MC">';
$html.='<li id="MC-1">Bonjour</li>';
*/


$html='';

if(isset($_SESSION['Prenom'])){
	$html.='<li id="MP-1" onclick="slide(\'MP-1-1\');"><img src="./media/icons/mobile-menu-button.png" height="30px"> Bonjour '.$_SESSION['Prenom'].'</li>';
	$html.='<ul id="MP-1-1"><li><a href="?component=user&action=infosUser">Mes donn&eacute;es</a></li><li><a href="?component=user&action=modifpassword">Changer mot de passe</a></li><li><a href="?component=user&action=logoff">Me d&eacute;connecter</a></li></ul>';	
	$this->menu_perso=$html;
}


/* BEFORE 
else{
	$html.='<li id="MC-1" onclick="slide(\'MC-1-1\');">Se connecter</li>';
	$html.='<form action="index.php?component=user&action=login" method="POST">
	<ul id="MC-1-1"><li>Identifiant</li><li><input type="text" name="login" id="login" autofocus /></li><li>Password</li><li><input type="password" name="password"  id="password" style="text-align:center;"></li><li><input type="submit" value="Go !"></li></ul></form>';
}
$html.='</ul>';
*/

// else{
	// $html.='<form role="form" action="index.php?component=user&action=login" method="POST" autocomplete="off">';
		// $html.='<div class="login">';
			// $html.='<h1>Connexion</h1>';
			// $html.='<input type="text" name="login" id="identifiant" placeholder="Identifiant"/>';
			// $html.='<input type="password" name="password"  id="password" placeholder="Mot de passe">';		
			// $html.='<button type="submit" class="btn btn-primary btn-block btn-large">Entrer</button>';			
		// $html.='</div>';
	// $html.='</form>';
	// $menu='<h3>Site optimis&eacute; pour <a href="https://www.google.com/chrome/browser/desktop/index.html" target="_blank">Chrome</a></h3>';
	// $this->content=$html;
	// $this->menu_perso=$menu;
// }
?>