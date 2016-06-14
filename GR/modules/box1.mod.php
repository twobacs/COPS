<?php
if(isset($_SESSION['idUser'])){
	$component=(isset($_REQUEST['component'])) ? $_REQUEST['component'] : '';	
	$action=(isset($_REQUEST['action'])) ? $_REQUEST['action'] : '';
	$subAction=(isset($_REQUEST['subAction'])) ? $_REQUEST['subAction'] : '';

	$accueil='<li><a href="index.php?component=user&action=home">Accueil</a></li>';
	$personnel='<li><a href="index.php?component=personnel&action=home">Gestion Personnel</a></li>';
	$grh='<li><a href="index.php?component=grh&action=home">GRH</a></li>';
	$logistique='<li><a href="index.php?component=logistique&action=home">Menu logistique</a></li>';
	$finances='<li><a href="index.php?component=finances&action=home">Gestion Finances</a></li>';
	$admin='<li><a href="index.php?component=admin&action=home">Administration site</a></li>';
	$infosUser='<li><a href="index.php?component=user&action=infosUser">Mes donn&eacute;es</a></li>';
	$infosPersUrg='<li><a href="index.php?component=user&action=infosPersUrg&id_user='.$_SESSION['idUser'].'">Contacts incident</a></li>';
	$addPersoIncident='<li><a href="index.php?component=user&action=addPersIncident&id_user='.$_SESSION['idUser'].'">Ajout contact</a></li>';
	$confirmUpdate='<li id="LiOkRecord">Enregistrement OK !</li>';
	$confirmSupp='<li id="LiOkSupp">Suppression OK !</li>';
	$error='<li id="liError">Erreur !</li>';
	$gestAdminSite='<li><a href="index.php?component=admin&action=gestAdminSite">Gestion admins site</a></li>';
	$addAdminSite='<li><a href="index.php?component=admin&action=addAdminSite">Ajout admin site</a></li>';
	$gestAdminModules='<li><a href="index.php?component=admin&action=gestAdminModules">Gest. admins modules</a></li>';
	$gestUsersLogistique='<li><a href="index.php?component=logistique&action=gestUsers">Gest. users logistique</a></li>';
	
	$gestUsersPersonnel='<li><a href="index.php?component=personnel&action=gestUsers">Gest. users personnel</a></li>';
	
	$gestArmes='<li><a href="index.php?component=logistique&action=gestArmes';
	$gestArmes.=(($action=='assoc')||($action=='retour')) ? '&visible=attrib">Gestion armes</a></li>' : '">Gestion armes</a></li>';
	
	$gestUsers='<li><a href="?component=user&action=gestUsers">Gestion utilisateurs</a></li>';
	
	$gestBrassards='<li><a href="index.php?component=logistique&action=gestBrassards';
	$gestBrassards.=(($action=='assoc')||($action=='retour')) ? '&visible=attrib">Gestion brassards</a></li>' : '">Gestion brassards</a></li>';
	
	$gestRadios='<li><a href="index.php?component=logistique&action=gestRadios';
	$gestRadios.=(($action=='assoc')||($action=='retour')) ? '&visible=attrib">Gestion radios</a></li>' : '">Gestion radios</a></li>';	

	$gestBatons='<li><a href="index.php?component=logistique&action=gestBatons';
	$gestBatons.=(($action=='assoc')||($action=='retour')) ? '&visible=attrib">Gestion b&acirc;tons</a></li>' : '">Gestion b&acirc;tons</a></li>';	
	
	$gestETTs='<li><a href="index.php?component=logistique&action=gestETTs';
	$gestETTs.=(($action=='assoc')||($action=='retour')) ? '&visible=attrib">Gestion ETT</a></li>' : '">Gestion ETT</a></li>';
	
	$gestPMB='<li><a href="index.php?component=logistique&action=gestPMB">Gestion petit mat&eacute;riel</a>';
	
	$commande='<li><a href="index.php?component=logistique&action=formComPMB">Commande</a></li>';
	
	$html='<ul id="ariane">';

	if(($component=='user')&&($action=='infosUser')){
		$html.=$accueil;
		$html.=$infosUser;
		if($subAction=='updateDonnees'){
			$html.=$confirmUpdate;
		}
	}
	
	if(($component=='user')&&($action=='infosPersUrg')||($action=='updatePersCont')){
		$html.=$accueil;
		$html.=$infosUser;
		$html.=$infosPersUrg;
		$html.=($action=='updatePersCont') ? $confirmUpdate : '';
	}
	
	if(($component=='user')&&($action=='addPersIncident')){
		$html.=$accueil;
		$html.=$infosUser;
		$html.=$infosPersUrg;
		$html.=$addPersoIncident;
	}
	
	if(($component=='user')&&($action=='addPersIncident')&&($subAction=='addNewContact')){
		$html='<ul>';
		$html.=$accueil;
		$html.=$infosUser;
		$html.=$infosPersUrg;
		$html.=$confirmUpdate;
	}
	
	if(($component=='user')&&($action=='DelPersUrg')){
		$html.=$accueil;
		$html.=$infosUser;
		$html.=$infosPersUrg;
		$html.=$confirmSupp;
	}
	
	if(($component=='user')&&($action=='gestUsers')){
		$html.=$accueil;
		$html.=$gestUsers;
	}
	
	if(($component=='user')&&($action=='getUserById')){
		$html.=$accueil;
		$html.=$gestUsers;
		$html.='<li>D&eacute;tails utilisateur</li>';
		if(isset($_GET['record'])){
			$html.=$confirmUpdate;
		}
	}
		
	if(($component=='user')&&($action=='addUser')){
		$html.=$accueil;
		$html.=$gestUsers;
		$html.='<li>Ajout d\'un utilisateur</li>';
		if(isset($_GET['record'])){
			$html.=$confirmUpdate;
		}
	}
	
	if(($component=='user')&&($action=='modifpassword')){
		// $html.=$accueil;
		$html.='<li>Modifier mot de passe</li>';
	}	
	
	if(($component=='personnel')&&($action=='home')){
		$html.=$accueil;
		$html.=$personnel;
	}
	
	if(($component=='grh')&&($action=='home')){
		$html.=$accueil;
		$html.=$grh;
	}
	
	if(($component=='logistique')&&($action=='home')){
		$html.=$accueil;
		$html.=$logistique;
	}

	if(($component=='logistique')&&($action=='gestUsers')){
		$html.=$accueil;
		$html.=$logistique;
		$html.=$gestUsersLogistique;
		if($subAction!=''){
			switch($_GET['error']){
				case '0':
					$html.=($_GET['subAction']=='add') ? $confirmUpdate : $confirmSupp;
					break;
				case '1':
					$html.=$error;
					break;
			}
		}
	}
	
	if(($component=='logistique')&&($action=='gestPMB')){
		$html.=$accueil;
		$html.=$logistique;
		$html.=$gestPMB;
	}
	
	if(($component=='personnel')&&($action=='gestUsers')){
		$html.=$accueil;
		$html.=$personnel;
		$html.=$gestUsersPersonnel;
		if($subAction!=''){
			switch($_GET['error']){
				case '0':
					$html.=($_GET['subAction']=='add') ? $confirmUpdate : $confirmSupp;
					break;
				case '1':
					$html.=$error;
					break;
			}
		}
	}	

	if(($component=='logistique')&&($action=='gestArmes')){
		$html.=$accueil;
		$html.=$logistique;
		$html.=$gestArmes;
		$html.=(isset($_GET['retrait'])) ? $confirmSupp : '';
		$html.=(isset($_GET['ajout'])) ? $confirmUpdate : '';
	}
	
	if(($component=='logistique')&&($action=='gestRadios')){
		$html.=$accueil;
		$html.=$logistique;
		$html.=$gestRadios;
		$html.=(isset($_GET['retrait'])) ? $confirmSupp : '';
		$html.=(isset($_GET['ajout'])) ? $confirmUpdate : '';
	}	
	
	if(($component=='logistique')&&($action=='gestBatons')){
		$html.=$accueil;
		$html.=$logistique;
		$html.=$gestBatons;
		$html.=(isset($_GET['retrait'])) ? $confirmSupp : '';
		$html.=(isset($_GET['ajout'])) ? $confirmUpdate : '';
	}	
	
	if(($component=='logistique')&&($action=='gestBrassards')){
		$html.=$accueil;
		$html.=$logistique;
		$html.=$gestBrassards;
		$html.=(isset($_GET['retrait'])) ? $confirmSupp : '';
		$html.=(isset($_GET['ajout'])) ? $confirmUpdate : '';
	}	

	if(($component=='logistique')&&($action=='gestETTs')){
		$html.=$accueil;
		$html.=$logistique;
		$html.=$gestETTs;
		$html.=(isset($_GET['retrait'])) ? $confirmSupp : '';
		$html.=(isset($_GET['ajout'])) ? $confirmUpdate : '';
	}
	
	if(($component=='logistique')&&($action=='addPMB')){
		$html.=$accueil;
		$html.=$logistique;
		$html.=$gestPMB;
		$html.='<li>Ajout mat&eacute;riel</li>';
	}
	
	if(($component=='logistique')&&($action=='assoc')){
		$html.=$accueil;
		$html.=$logistique;
		switch($_GET['type']){
			case 'arme':
				$html.=$gestArmes.'<li>Attribution d\'une arme</li>';
				break;
			case 'brassard':
				$html.=$gestBrassards.'<li>Attribution d\'un brassard</li>';
				break;
			case 'baton':
				$html.=$gestBatons.'<li>Attribution d\'un b&acirc;ton</li>';
				break;
			case 'radio':
				$html.=$gestRadios.'<li>Attribution d\'une radio</li>';
				break;
		}
	}
	
	if(($component=='logistique')&&($action=='addMat')){
		$html.=$accueil;
		$html.=$logistique;
		switch($_GET['type']){
			case 'arme':
				$html.=$gestArmes;
				$html.='<li>Ajout d\'une arme</li>';
				break;
			case 'brassard':
				$html.=$gestBrassards;
				$html.='<li>Ajout d\'un brassard</li>';
				break;
			case 'radio':
				$html.=$gestRadios;
				$html.='<li>Ajout d\'une radio</li>';
				break;
			case 'baton':
				$html.=$gestBatons;
				$html.='<li>Ajout d\'un b&acirc;ton</li>';
				break;				
		}
	}
	
	if(($component=='logistique')&&($action=='retour')&&($subAction=='arme')){
		$html.=$accueil;
		$html.=$logistique;
		$html.=$gestArmes;	
		$html.='<li>Retrait</li>';
		
	}
	
	if(($component=='logistique')&&($action=='retour')&&($subAction=='brassard')){
		$html.=$accueil;
		$html.=$logistique;
		$html.=$gestBrassards;	
		$html.='<li>Retrait</li>';
	}

	if(($component=='logistique')&&($action=='retour')&&($subAction=='radio')){
		$html.=$accueil;
		$html.=$logistique;
		$html.=$gestRadios;	
		$html.='<li>Retrait</li>';
	}
	
	if(($component=='logistique')&&($action=='retour')&&($subAction=='baton')){
		$html.=$accueil;
		$html.=$logistique;
		$html.=$gestBatons;	
		$html.='<li>Retrait</li>';
	}

	if(($component=='logistique')&&($action=='formComPMB')){
		$html.=$accueil;
		$html.=$logistique;
		$html.='<li>Commande</li>';
	}
	
	if(($component=='logistique')&&($action=='gestPanierPMB')){
		$html.=$accueil;
		$html.=$logistique;
		$html.=$commande;
		$html.='<li>Panier en cours</li>';
	}	
	
	if(($component=='logistique')&&($action=='details')){
		$html.=$accueil;
		$html.=$logistique;
		switch($_GET['type']){
			case 'arme':
				$html.='<li><a href="index.php?component=logistique&action=gestArmes&visible='.$_GET['visible'].'">Gestion armes</a></li>';
				$html.='<li>'.$_GET['id'].'</li>';
				break;
			case 'brassard':
				$html.='<li><a href="index.php?component=logistique&action=gestBrassards&visible='.$_GET['visible'].'">Gestion brassards</a></li>';
				$html.='<li>'.$_GET['id'].'</li>';
				break;
			case 'radio':
				$html.='<li><a href="index.php?component=logistique&action=gestRadios&visible='.$_GET['visible'].'">Gestion radios</a></li>';
				$html.='<li>'.$_GET['id'].'</li>';
				break;
			case 'baton':
				$html.='<li><a href="index.php?component=logistique&action=gestbatons&visible='.$_GET['visible'].'">Gestion b&acirc;tons</a></li>';
				$html.='<li>'.$_GET['id'].'</li>';
				break;
			case 'ETT':
				$html.='<li><a href="index.php?component=logistique&action=gestETTs">Gestion ETT</a></li>';
				$html.='<li>'.$_GET['id'].'</li>';
				break;
		}
	}
	
	if(($component=='logistique')&&($action=='showNewOrders')){
		$html.=$accueil;
		$html.=$logistique;
		$html.='<li>Commandes &agrave; tra&icirc;ter</li>';
	}
	
	if(($component=='finances')&&($action=='home')){
		$html.=$accueil;
		$html.=$finances;
	}
	
	
	if(($component=='admin')&&($action=='gestAdminSite')||($action=='delAdminSite')){
		$html.=$accueil;
		$html.=$admin;
		$html.=$gestAdminSite;
		if(isset($_REQUEST['user'])){			
			$html.=$confirmSupp;
		}
	}	
	
	if(($component=='admin')&&($action=='addAdminSite')){
		$html.=$accueil;
		$html.=$admin;
		$html.=$gestAdminSite;		
		if(isset($_REQUEST['record'])){			
			$html.=$confirmUpdate;
		}
		else{
			$html.=$addAdminSite;
		}
	}
	
	if(($component=='admin')&&($action=='gestAdminModules')){
		$html.=$accueil;
		$html.=$admin;
		$html.=$gestAdminModules;
		if($subAction!=''){
			switch ($subAction){
				case 'delAdmin' :
					if(isset($_REQUEST['error'])){
						$html.=$error;
					}
					else $html.=$confirmSupp;
					break;
			}
		}
	}
	$html.='</ul>';
	$html.='<ul id="pop_picture"><li style="text-align:center;"></li></ul>';
	
	$this->box1=$html;
}
else $this->box1='';
?>