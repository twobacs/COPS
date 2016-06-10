<?php

if(isset($_REQUEST['action'])){
	switch($_REQUEST['action']){
		
		case 'logoff':
			$this->menuBase.=getBConnect();
			break;
		case 'deco':
			$this->menuBase.=getBConnect();
			break;		
		case 'login':
			$this->menuBase.=getBDeco();
			break;
		case 'logForm':
			$this->menuBase.='';
			break;
		default :
			$this->menuBase.=getBDeco();
			break;
	}
}
else $this->menuBase.=getBDeco();


function getBDeco(){
	$html='<form method="POST" action="?component=users&action=logoff" onsubmit="return confirm(\'Voulez-vous vraiment vous déconnecter ? (Vérifiez les données BS le cas échéant)\');"><table><tr><td class="noborder">';
	if (!isset($_SESSION['idbs']))
	{
		$html.='<a href="?component=applications&action=showApps"><img src="/templates/mytpl/images/home.png" height="40"></a></td><td class="noborder"><input type="submit" value="Déconnexion"></td></tr></table></form>';	
	}
	else
	{
		$html.='<a href="?mode=m&component=users&action=MenuTablette"><img src="/templates/mytpl/images/home.png" height="40"></a></td><td class="noborder"><input type="submit" value="Déconnexion"></td></tr></table></form>';
	}
	return $html;
}

function getBConnect(){
	return '<form method="POST" action="index.php?component=users&action=logForm"><table><tr><td class="noborder"><input type="submit" value="Connexion"></td></tr></table></form>';
}

?>