<?php
	$html='<form action="?component=cops&action=inputSearch" method="POST" target="_blank"><table>';
	if(isset($_SESSION['idbs'])){
	$html.='<tr><td><input type="text" name="inputSearch"></td><td><input type="submit" value="Chercher"></td></tr>';	
	}

if(isset($_REQUEST['action'])){
	switch($_REQUEST['action']){
		
		case 'logoff':
			$html.='';
			break;
		// case 'login':
			// $html.=getListeGarde();
			// break;
		case 'logForm':
			$html.='';
			break;
		// default :
			// $html.=getListeGarde();
			// break;
	}
}
function getListeGarde(){
	return '<tr><td colspan="2"><a href="?component=documentation&action=mainMenu">Documentation</a></td></tr>';
}
		
	$html.='</table></form>';
	$this->search=$html;

?>