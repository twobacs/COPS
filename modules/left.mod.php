<?php

if (isset($_SESSION['appli']))
{
$appli = $_SESSION['appli'];
$html='';

switch ($appli)
	{
	// *******************************************************
	//APPLICATION VACANCIER
	case 'vacancier' :
		$level=$_SESSION['nivApp'];
		if ($_GET['action']!='mainMenu')
			{
			$html.='<ul>';
			if (($level=='5') || ($level=='10') || ($level=='20') || ($level=='30') || ($level=='50'))
				{
				$html='<h4>Menu vacancier</h4>';
				$html.='<li><a href=?component=vacancier&action=listEnCours>Demandes en cours</a></li>';
				}
		
		if (($level=='10') || ($level=='20') || ($level=='30') || ($level=='50'))
				{
				$html.='<li><a href=?component=vacancier&action=addHab>Création</a></li>';
				$html.='<li><a href=?component=vacancier&action=editVac>Edition</a></li>';
				}
			
		if (($level=='20') || ($level=='30') || ($level=='50'))
				{
				$html.='<li><a href="index.php?component=vacancier&action=selectCR">Génération courrier de compte-rendu</a></li>';
				}
			
		if (($level=='30') || ($level=='50'))
				{
				$html.='<li><a href="index.php?component=vacancier&action=indicateurs">Accès indicateurs</a></li>';
				}
			$html.='</ul>';
			}
		break;
	//*******************************************************
	//APPLICATION PATROUILLES
	case 'patrouilles' :
		$level=$_SESSION['nivApp'];
		$html='<a href=?component=patrouilles&action=newPat>Ajouter</a> une patrouille <br />';
		$html.='<a href=?component=patrouilles&action=viewPat>Aperçu</a> des patrouilles <br />';
		$html.='<a href=?component=patrouilles&action=searchPat>Faire une recherche</a><br />';	
		break;
	//*******************************************************
	//APPLICATION MISSIONS
	case 'missions' :
		$level=$_SESSION['nivApp'];
		if ($level>9)
			{
			$html.='<ul>';
			$html.='<li><a href="index.php?component=missions&action=mainMenu">Menu Missions</a>';
			$html.='</ul>';
			}
		break;
	//*******************************************************
	//APPLICATION USERS
	case 'users':
		if ($_REQUEST['action']!='login'){
		$level=$_SESSION['nivApp'];
		if ($level>9)
			{
			$html.='<ul>';
			$html.='<li><a href="?component=applications&action=showApps">Retour</a> au menu principal.</li>';
			$html.='<li><a href="?component=users&action=mainMenu">Menu</a> utilisateurs.</li>';
			$html.='</ul>';
			}
		}
		break;	
	//*******************************************************
	//APPLICATION BS		
	case 'bs':
			$html.='<a href="?component=bs&action=mainMenu">Liste des BS</a>';
			break;
	 }

// if (isset($_SESSION['idbs']))
	// {
	// $html.='<br /><a href="index.php?component=users&action=fromMenuTablette"><img src="./media/icons/mesMissions.png" width="100%"></a><br />';
	// $html.='<br /><a href="index.php?component=activites&action=mainMenu"><img src="./media/icons/mesActivites.png" width="100%"></a><br />';
	// $html.='<br /><a href="index.php?component=activites&action=intervention"><img src="./media/icons/intervention.png" width="100%"></a><br />';
	// $html.='<br /><a href="index.php?component=activites&action=patrouille"><img src="./media/icons/patrouille.png" width="100%"></a><br />';	
	// $html.='<br /><a href="index.php?component=activites&action=SvInt"><img src="./media/icons/SV_Int.png" width="100%"></a><br />';
	// $html.='<br /><a href="index.php?component=vacancier&action=listEnCours"><img src="./media/icons/vacanciers_tab.png" width="100%"></a><br />';
	// $html.='<br /><a href="index.php?component=activites&action=GenBS"><img src="./media/icons/genBS.png" width="100%"></a><br />';
	// }
	 
 $this->left=$html;
 }

?>
