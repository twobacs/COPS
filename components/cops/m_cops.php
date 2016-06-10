<?php

class MCops extends MBase {

	private $checkDbPDO = false;

	public function __construct($appli) {
		parent::__construct($appli);
		
	}
	
private function htm($data)
	{
	$rep=htmlentities($data, ENT_QUOTES, "UTF-8");
	// $rep=htmlentities($rep);
	return $rep;
	}
	
public function getNivAcces()
	{
	$sql='SELECT id_nivAcces FROM z_user_app WHERE id_user="'.$_COOKIE['iduser'].'" AND id_app="7"';
	$rep=$this->appli->dbPdo->query($sql);
	while ($row=$rep->fetch())
		{
		$nivAcces=$row['id_nivAcces'];
		}
	return $nivAcces;
	}
	
public function getSection()
	{
	include_once('./class/cops.class.php');
	$cops = new Cops($this->appli->dbPdo);
	$sections=$cops->getSection();
	return $sections;
	}
	
public function getCateg()
	{
	include_once('./class/cops.class.php');
	$cops = new Cops($this->appli->dbPdo);
	$categ=$cops->getCateg();
	return $categ;
	}
	
public function editNewFiche()
	{
	$categ=$_POST['categ'];
	$qPers=$_POST['qPers'];
	$qVV=$_POST['qVV'];
	$qLDits=$_POST['qLDits'];
	$qCommerces=$_POST['qCommerces'];
	$qTxt=$_POST['qTxt'];
	$qPics=$_POST['qPics'];
	$dateBasse=$_POST['HrBasse'];
	$dateHaute=$_POST['HrHaute'];
	$interaction=$_POST['interaction'];
	$texte=$_POST['txtInfo'];
	
	$idFiche=uniqid();
	
	$req=$this->appli->dbPdo->prepare('INSERT INTO z_fiche (id_fiche, id_categ, dateHr_encodage, date_debut, date_fin, interaction, id_encodeur, texteInfo)
	VALUES (:idFiche, :idCateg, NOW(), :dateBasse, :dateHaute, :interaction, :encodeur, :txtinfo)');
	$req->execute(array(
	'idFiche' => $idFiche,
	'idCateg' => $categ,
	'dateBasse' => $dateBasse,
	'dateHaute' => $dateHaute,
	'interaction' => $interaction,
	'encodeur' => $_COOKIE['iduser'],
	'txtinfo' => $texte
	));
	
	$data=array();
	$data['idFiche']=$idFiche;
	$data['qPers']=$qPers;
	$data['qVV']=$qVV;
	$data['qLDits']=$qLDits;
	$data['qCommerces']=$qCommerces;
	$data['qTxt']=$qTxt;
	$data['qPics']=$qPics;
	
	$sql='SELECT id_liaison, denomination FROM z_liaison ORDER BY denomination';
	$data['liaisons']=$this->appli->dbPdo->query($sql);

	include_once('./class/rues.class.php');
	$rues=new Rue($this->appli->dbPdo);
	$data['rues']=$rues->selectRues();
	
	return $data;
	}
	
public function recMoreInfosFiche()
	{
	$idFiche=$_POST['idFiche'];
	$qPers=$_POST['qPers'];
	$qVV=$_POST['qVV'];
	for ($i=0;$i<$qPers;$i++)
		{
		$nomUp='';
		$image_name=$_FILES['imagePers_'.$i]['name'];     //Le nom original du fichier, comme sur le disque du visiteur (exemple : mon_icone.png).
		$image_type=$_FILES['imagePers_'.$i]['type'];     //Le type du fichier. Par exemple, cela peut être « image/png ».
		$image_size=$_FILES['imagePers_'.$i]['size'];     //La taille du fichier en octets.
		$image_tmpname=$_FILES['imagePers_'.$i]['tmp_name']; //L'adresse vers le fichier uploadé dans le répertoire temporaire.
		$image_error=$_FILES['imagePers_'.$i]['error'];    //Le code d'erreur, qui permet de savoir si le fichier a bien été uploadé.
		
		$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' );
		//1. strrchr renvoie l'extension avec le point (« . »).
		//2. substr(chaine,1) ignore le premier caractère de chaine.
		//3. strtolower met l'extension en minuscules.
		$extension_upload = strtolower(  substr(  strrchr($_FILES['imagePers_'.$i]['name'], '.')  ,1)  );
		
		if ( in_array($extension_upload,$extensions_valides) ) //Vérifie si l'extension fait partie de celles autorisées
			{
			$nomUp = $i.md5(uniqid(rand(), true)).'.'.$extension_upload;
			$nom ='/var/www/media/images/'.$nomUp;
			$resultat = move_uploaded_file($_FILES['imagePers_'.$i]['tmp_name'],$nom);
			}
		$req=$this->appli->dbPdo->prepare('INSERT INTO z_personne (nom, prenom, date_naissance, photo, pays, ville, CP, adresse, numero, descriptif)
		VALUES (:nom, :prenom, :date_naissance, :photo, :pays, :ville, :CP, :adresse, :numero, :descriptif)');
		$req->execute(array(
		'nom' => strtoupper($this->htm($_POST['persnom'.$i])),
		'prenom' => $this->htm($_POST['persprenom'.$i]),
		'date_naissance' => $_POST['persnaissance'.$i],
		'photo' => $nomUp,
		'pays' => $this->htm($_POST['perspays'.$i]),
		'ville' => strtoupper($this->htm($_POST['persville'.$i])),
		'CP' => $this->htm($_POST['persCP'.$i]),
		'adresse' => $this->htm($_POST['persrue'.$i]),
		'numero' => $this->htm($_POST['persnum'.$i]),
		'descriptif' => $this->htm($_POST['persdescriptif'.$i])
		));
		
		$sql='SELECT id_personne FROM z_personne WHERE photo="'.$nomUp.'"';
		$rep=$this->appli->dbPdo->query($sql);
		while ($row=$rep->fetch())
			{
			$id_pers=$row['id_personne'];
			}
			
		$req=$this->appli->dbPdo->prepare('INSERT INTO z_fiche_personne (id_fiche, id_personne, id_liaison) VALUES (:idfiche, :idpers, :liaison)');
		$req->execute(array(
		'idfiche' => $idFiche,
		'idpers' => $id_pers,
		'liaison' => $_POST['persliaison'.$i]
		));
		}
	for ($i=0;$i<$qVV;$i++)
		{
		$idVV=md5(uniqid());
		$req=$this->appli->dbPdo->prepare('INSERT INTO z_vehicule (id_vv, marque, modele, immatriculation, chassis, couleur, descriptif)
		VALUES (:idVV, :marque, :modele, :immatriculation, :chassis, :couleur, :descriptif)');
		$req->execute(array(
		'idVV' => $idVV,
		'marque' => ((!empty($_POST['VVmarque'.$i])) ? ucfirst($this->htm($_POST['VVmarque'.$i])) : 'Inconnu'),
		'modele' => ((!empty($_POST['VVmodele'.$i])) ? ucfirst($this->htm($_POST['VVmodele'.$i])) : 'Inconnu'),
		'immatriculation' => ((!empty($_POST['VVimmatriculation'.$i])) ? strtoupper($this->htm($_POST['VVimmatriculation'.$i])) : 'Inconnu'),
		'chassis' => ((!empty($_POST['VVchassis'.$i])) ? strtoupper($this->htm($_POST['VVchassis'.$i])) : 'Inconnu'),
		'couleur' => ((!empty($_POST['VVcouleur'.$i])) ? ucfirst($this->htm($_POST['VVcouleur'.$i])) : 'Inconnu'),
		'descriptif' => ((!empty($_POST['VVdescriptif'.$i])) ? $this->htm($_POST['VVdescriptif'.$i]) : 'Inconnu')
		));
		
		$req=$this->appli->dbPdo->prepare('INSERT INTO z_fiche_vehicule (id_fiche, id_vehicule, id_liaison) VALUES (:idfiche, :idVV, :idliaison)');
		$req->execute(array(
		'idfiche' => $idFiche,
		'idVV' => $idVV,
		'idliaison' => $_POST['VVliaison'.$i]
		));
		}
	for ($i=0;$i<$_POST['qLDits'];$i++)
		{
		$idLDit=md5(uniqid());
		$req=$this->appli->dbPdo->prepare('INSERT INTO z_lieudit (id_lieudit, description) VALUES (:idlieudit, :description)');
		$req->execute(array(
		'idlieudit' => $idLDit,
		'description' => ((!empty($_POST['LDDenom'.$i])) ? $this->htm($_POST['LDDenom'.$i]) : 'Non communiqu&eacute;')
		));
		
		$req=$this->appli->dbPdo->prepare('INSERT INTO z_fiche_lieudit (id_fiche, id_lieudit, id_liaison) VALUES (:idfiche, :idLD, :idliaison)');
		$req->execute(array(
		'idfiche' => $idFiche,
		'idLD' => $idLDit,
		'idliaison' => $_POST['LDliaison'.$i]
		));
		}
	for ($i=0;$i<$_POST['qCommerces'];$i++)
		{
		$idCom=md5(uniqid());
		$req=$this->appli->dbPdo->prepare('INSERT INTO z_commerce (id_commerce, nom, ville, CP, idRue, numero, descriptif) 
		VALUES (:idcom, :nom, :ville, :CP, :rue, :numero, :descriptif)');
		$req->execute(array(
		'idcom' => $idCom,
		'nom' => ((!empty($_POST['comnom'.$i])) ? ucfirst($this->htm($_POST['comnom'.$i])) : 'N.C.'),
		'ville' => ((!empty($_POST['comville'.$i])) ? ucfirst($this->htm($_POST['comville'.$i])) : 'N.C.'),
		'CP' => ((!empty($_POST['comCP'.$i])) ? ucfirst($this->htm($_POST['comCP'.$i])) : 'N.C.'),
		'rue' => $_POST['comrue'.$i],
		'numero' => ((!empty($_POST['comnum'.$i])) ? ucfirst($this->htm($_POST['comnum'.$i])) : 'N.C.'),
		'descriptif' => ((!empty($_POST['comdescriptif'.$i])) ? ucfirst($this->htm($_POST['comdescriptif'.$i])) : 'Non communiqu&eacute;')
		));
		
		$req=$this->appli->dbPdo->prepare('INSERT INTO z_fiche_commerce (id_fiche, id_commerce, id_liaison)
		VALUES (:idfiche, :idcommerce, :idliaison)');
		$req->execute(array(
		'idfiche' => $idFiche,
		'idcommerce' => $idCom,
		'idliaison' => $_POST['comliaison'.$i]
		));
		}
	for ($i=0;$i<$_POST['qTxt'];$i++)
		{
		$idTxt=md5(uniqid());
		$req=$this->appli->dbPdo->prepare('INSERT INTO z_texte_libre (id_textelibre, texte, titre) VALUES (:id, :texte, :titre)');
		$req->execute(array(
		'id' => $idTxt,
		'texte' => ((!empty($_POST['txttxt'.$i])) ? ucfirst($this->htm($_POST['txttxt'.$i])) : 'N&eacute;ant'),
		'titre' => ((!empty($_POST['txttitre'.$i])) ? ucfirst($this->htm($_POST['txttitre'.$i])) : 'N&eacute;ant')
		));
		
		$req=$this->appli->dbPdo->prepare('INSERT INTO z_fiche_textelibre (id_fiche, id_textelibre) VALUES (:fiche, :txt)');
		$req->execute(array(
		'fiche' => $idFiche,
		'txt' => $idTxt
		));
		}
	for ($i=0;$i<$_POST['qPics'];$i++)
		{
		$idPic=md5(uniqid());
		$nomUp='';
		$image_name=$_FILES['imageSup_'.$i]['name'];
		$image_type=$_FILES['imageSup_'.$i]['type'];
		$image_size=$_FILES['imageSup_'.$i]['size'];
		$image_tmpname=$_FILES['imageSup_'.$i]['tmp_name'];
		$image_error=$_FILES['imageSup_'.$i]['error'];
		
		$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' );
		$extension_upload = strtolower(  substr(  strrchr($_FILES['imageSup_'.$i]['name'], '.')  ,1)  );
		
		if ( in_array($extension_upload,$extensions_valides) )
			{
			$nomUp = $i.md5(uniqid(rand(), true)).'.'.$extension_upload;
			$nom ='/var/www/media/images/'.$nomUp;
			$resultat = move_uploaded_file($_FILES['imageSup_'.$i]['tmp_name'],$nom);
			}
		$req=$this->appli->dbPdo->prepare('INSERT INTO z_photo (id_photo, commentaire, lien) 
		VALUES (:idPhoto, :commentaire, :lien)');	
		$req->execute(array(
		'idPhoto' => $idPic,
		'commentaire' => ((!empty($_POST['picComment'.$i])) ? ucfirst($this->htm($_POST['picComment'.$i])) : 'N&eacute;ant'),
		'lien' => $nomUp
		));
		$req=$this->appli->dbPdo->prepare('INSERT INTO z_fiche_photo (id_fiche, id_photo) VALUES (:fiche, :photo)');
		$req->execute(array(
		'fiche' => $idFiche,
		'photo' => $idPic
		));
		}
	}
	
public function getListInfosCops()
	{
	$data=array();
	include_once ('./class/cops.class.php');
	$info = NEW Cops($this->appli->dbPdo);
	$tri = (isset($_GET['tri'])) ? $_GET['tri'] : 'N';
	$page = (isset($_GET['page'])) ? $_GET['page'] : 1;
	$data['sql']=$info->getListInfosCops($tri,$page);
	$data['page']=$page;
	return $data;
	}

public function getListInfosHot(){ /* 15.09.2015 */
	include_once ('./class/cops.class.php');
	$info = NEW Cops($this->appli->dbPdo);
	$data['sql']=$info->getListInfosHot();
	$data['page']='';
	return $data;
}	
	
public function getInfoFicheById($id)
	{
	$data=array();
	include_once ('./class/cops.class.php');
	$info = new Cops($this->appli->dbPdo);
	$data=$info->getInfoFicheById($id);
	// $this->appli->ctContent=$data['sql'];
	return $data;
	}

public function getOptionsImplication()
	{
	$sql='SELECT id_liaison, denomination FROM z_liaison';
	$rep=$this->appli->dbPdo->query($sql);
	$html='';
	while ($row=$rep->fetch())
		{
		$html.='<option value="'.$row['id_liaison'].'">'.$row['denomination'].'</option>';
		}
	return $html;
	}

public function getImplication()
	{
	$sql='SELECT id_liaison, denomination FROM z_liaison';
	return $this->appli->dbPdo->query($sql);
	}
	
public function addPersonneByIdFiche($fiche)
	{
	$nomUp='';
	$image_name=$_FILES['imagePers']['name'];     //Le nom original du fichier, comme sur le disque du visiteur (exemple : mon_icone.png).
	$image_type=$_FILES['imagePers']['type'];     //Le type du fichier. Par exemple, cela peut être « image/png ».
	$image_size=$_FILES['imagePers']['size'];     //La taille du fichier en octets.
	$image_tmpname=$_FILES['imagePers']['tmp_name']; //L'adresse vers le fichier uploadé dans le répertoire temporaire.
	$image_error=$_FILES['imagePers']['error'];    //Le code d'erreur, qui permet de savoir si le fichier a bien été uploadé.
	
	$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' );
	//1. strrchr renvoie l'extension avec le point (« . »).
	//2. substr(chaine,1) ignore le premier caractère de chaine.
	//3. strtolower met l'extension en minuscules.
	$extension_upload = strtolower(  substr(  strrchr($_FILES['imagePers']['name'], '.')  ,1)  );
	
	if ( in_array($extension_upload,$extensions_valides) ) //Vérifie si l'extension fait partie de celles autorisées
		{
		$nomUp = md5(uniqid(rand(), true)).'.'.$extension_upload;
		$nom ='/var/www/media/images/'.$nomUp;
		$resultat = move_uploaded_file($_FILES['imagePers']['tmp_name'],$nom);
		}
	
	
	$nom=$_POST['nom'];
	$prenom=$_POST['prenom'];
	$DN=$_POST['DN'];
	$pays=$_POST['pays'];
	$CP=$_POST['CP'];
	$ville=$_POST['ville'];
	$adresse=$_POST['rue'];
	$numero=$_POST['num'];
	$implication=$_POST['implication'];
	$descriptif=$_POST['desc'];
	
	$sql='INSERT INTO z_personne (nom, prenom, date_naissance, photo, pays, ville, CP, adresse, numero, descriptif) VALUES ("'.$nom.'", "'.$prenom.'", "'.$DN.'", "'.$nomUp.'", "'.$pays.'","'.$ville.'", "'.$CP.'", "'.$adresse.'", "'.$numero.'", "'.$descriptif.'")';
	$this->appli->dbPdo->exec($sql);
	
	$sql='SELECT id_personne FROM z_personne WHERE nom="'.$nom.'" AND prenom="'.$prenom.'" AND date_naissance="'.$DN.'"';
	$rep=$this->appli->dbPdo->query($sql);
	while ($row=$rep->fetch())
		{
		$idPers=$row['id_personne'];
		}
	$sql='INSERT INTO z_fiche_personne (id_fiche, id_personne, id_liaison) VALUES ("'.$fiche.'", "'.$idPers.'", "'.$implication.'")';
	$this->appli->dbPdo->exec($sql);
	
	}
	
public function addVVByIdFiche($id)
	{
	$implication = $_POST['implication'];
	$idVV=md5(uniqid());
	
	$req=$this->appli->dbPdo->prepare('INSERT INTO z_vehicule (id_vv, marque, modele, immatriculation, chassis, couleur, descriptif)
		VALUES (:idVV, :marque, :modele, :immatriculation, :chassis, :couleur, :descriptif)');
		$req->execute(array(
		'idVV' => $idVV,
		'marque' => ((!empty($_POST['marque'])) ? ucfirst($this->htm($_POST['marque'])) : 'Inconnu'),
		'modele' => ((!empty($_POST['modele'])) ? ucfirst($this->htm($_POST['modele'])) : 'Inconnu'),
		'immatriculation' => ((!empty($_POST['imma'])) ? strtoupper($this->htm($_POST['imma'])) : 'Inconnu'),
		'chassis' => ((!empty($_POST['chassis'])) ? strtoupper($this->htm($_POST['chassis'])) : 'Inconnu'),
		'couleur' => ((!empty($_POST['couleur'])) ? ucfirst($this->htm($_POST['couleur'])) : 'Inconnue'),
		'descriptif' => ((!empty($_POST['infos'])) ? $this->htm($_POST['infos']) : 'Inconnu')
		));
		
		$req=$this->appli->dbPdo->prepare('INSERT INTO z_fiche_vehicule (id_fiche, id_vehicule, id_liaison) VALUES (:idfiche, :idVV, :idliaison)');
		$req->execute(array(
		'idfiche' => $id,
		'idVV' => $idVV,
		'idliaison' => $_POST['implication']
		));

	}
	
public function addLDByIdFiche($id)
	{
	$idLDit=md5(uniqid());
	$req=$this->appli->dbPdo->prepare('INSERT INTO z_lieudit (id_lieudit, description) VALUES (:idlieudit, :description)');
	$req->execute(array(
	'idlieudit' => $idLDit,
	'description' => ((!empty($_POST['denomination'])) ? $this->htm($_POST['denomination']) : 'Non communiqu&eacute;')
	));
	
	$req=$this->appli->dbPdo->prepare('INSERT INTO z_fiche_lieudit (id_fiche, id_lieudit, id_liaison) VALUES (:idfiche, :idLD, :idliaison)');
	$req->execute(array(
	'idfiche' => $id,
	'idLD' => $idLDit,
	'idliaison' => $_POST['implication']
	));	
	}
	
public function addComByIdFiche($id)
	{
	$idCom=md5(uniqid());
	$req=$this->appli->dbPdo->prepare('INSERT INTO z_commerce (id_commerce, nom, ville, CP, idRue, numero, descriptif) 
	VALUES (:idcom, :nom, :ville, :CP, :rue, :numero, :descriptif)');
	$req->execute(array(
	'idcom' => $idCom,
	'nom' => ((!empty($_POST['nomCom'])) ? ucfirst($this->htm($_POST['nomCom'])) : 'N.C.'),
	'ville' => ((!empty($_POST['villeCom'])) ? ucfirst($this->htm($_POST['villeCom'])) : 'N.C.'),
	'CP' => ((!empty($_POST['CPCom'])) ? ucfirst($this->htm($_POST['CPCom'])) : 'N.C.'),
	'rue' => $_POST['rueCom'],
	'numero' => ((!empty($_POST['numCom'])) ? ucfirst($this->htm($_POST['numCom'])) : 'N.C.'),
	'descriptif' => ((!empty($_POST['descCom'])) ? ucfirst($this->htm($_POST['descCom'])) : 'Non communiqu&eacute;')
	));
	
	$req=$this->appli->dbPdo->prepare('INSERT INTO z_fiche_commerce (id_fiche, id_commerce, id_liaison)
	VALUES (:idfiche, :idcommerce, :idliaison)');
	$req->execute(array(
	'idfiche' => $id,
	'idcommerce' => $idCom,
	'idliaison' => $_POST['implicCom']
	));	
	}
	
public function addTLByIdFiche($id)
	{
	$idTxt=md5(uniqid());
	$req=$this->appli->dbPdo->prepare('INSERT INTO z_texte_libre (id_textelibre, texte, titre) VALUES (:id, :texte, :titre)');
	$req->execute(array(
	'id' => $idTxt,
	'texte' => ((!empty($_POST['texte'])) ? ucfirst($this->htm($_POST['texte'])) : 'N&eacute;ant'),
	'titre' => ((!empty($_POST['titre'])) ? ucfirst($this->htm($_POST['titre'])) : 'N&eacute;ant')
	));
	
	$req=$this->appli->dbPdo->prepare('INSERT INTO z_fiche_textelibre (id_fiche, id_textelibre) VALUES (:fiche, :txt)');
	$req->execute(array(
	'fiche' => $id,
	'txt' => $idTxt
	));	
	}
	
public function addPicByIdFiche($id)
	{
	$idPic=md5(uniqid());
	$nomUp='';
	$image_name=$_FILES['newPic']['name'];     //Le nom original du fichier, comme sur le disque du visiteur (exemple : mon_icone.png).
	$image_type=$_FILES['newPic']['type'];     //Le type du fichier. Par exemple, cela peut être « image/png ».
	$image_size=$_FILES['newPic']['size'];     //La taille du fichier en octets.
	$image_tmpname=$_FILES['newPic']['tmp_name']; //L'adresse vers le fichier uploadé dans le répertoire temporaire.
	$image_error=$_FILES['newPic']['error'];    //Le code d'erreur, qui permet de savoir si le fichier a bien été uploadé.
	
	$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' );
	//1. strrchr renvoie l'extension avec le point (« . »).
	//2. substr(chaine,1) ignore le premier caractère de chaine.
	//3. strtolower met l'extension en minuscules.
	$extension_upload = strtolower(  substr(  strrchr($_FILES['newPic']['name'], '.')  ,1)  );
	
	if ( in_array($extension_upload,$extensions_valides) ) //Vérifie si l'extension fait partie de celles autorisées
		{
		$nomUp = md5(uniqid(rand(), true)).'.'.$extension_upload;
		$nom ='/var/www/media/images/'.$nomUp;
		$resultat = move_uploaded_file($_FILES['newPic']['tmp_name'],$nom);
		}
		
	$sql='INSERT INTO z_photo (id_photo, commentaire, lien) VALUES ("'.$idPic.'", "'.$_POST['comPic'].'", "'.$nomUp.'")';
	$this->appli->dbPdo->exec($sql);
	$sql='INSERT INTO z_fiche_photo (id_fiche, id_photo) VALUES ("'.$id.'", "'.$idPic.'")';
	$this->appli->dbPdo->exec($sql);
	}

public function getRues()
	{
	include_once('./class/rues.class.php');
	$rues=new Rue($this->appli->dbPdo);
	 return $rues->selectRues();
	}
	
public function newInfoCopsWRel()
	{
	$idEncodeur=$_COOKIE['iduser'];
	
	//Récupération des valeurs nécessaires à la création d'une nouvelle fiche COPS
	$idFiche=uniqid();
	$section=$_POST['section'];
	$categ=$_POST['categorie'];
	$dhDebut=$_POST['dhDebut'];
	$dhFin=$_POST['dhFin'];
	$interaction=$_POST['interaction'];
	$texteInfo=$this->htm($_POST['txtInfo']);
	
	//Requête d'insertion en base de données z_fiche
	$sql='INSERT INTO z_fiche (id_fiche, id_categ, dateHr_encodage, date_debut, date_fin, interaction, id_encodeur, texteInfo) VALUES ("'.$idFiche.'", "'.$categ.'", NOW(), "'.$dhDebut.'", "'.$dhFin.'", "'.$interaction.'", "'.$idEncodeur.'", "'.$texteInfo.'")';
	$this->appli->dbPdo->exec($sql);
	
	//PERSONNES
	//Récupération du nombre de personnes encodées en liaison avec la fiche
	$nbPers=$_POST['nbPers'];
	// echo $nbPers;
	for($i=1;$i<=$nbPers;$i++)
		{
		// echo $_POST['nomPers_'.$i];
		if (isset($_POST['nomPers_'.$i]))
			{
			$nomPers=$_POST['nomPers_'.$i];
			$prenom=$_POST['prenom_'.$i];
			$dn=$_POST['DN_'.$i];
			$pays=$_POST['PaysRes_'.$i];
			$CP=$_POST['CPPers_'.$i];
			$ville=$_POST['villePers_'.$i];
			$rue=$_POST['RuePers_'.$i];
			$num=$_POST['numPers_'.$i];
			$desc=$this->htm($_POST['descrPers_'.$i]);
			$implication=$_POST['implicationPers_'.$i];
			
			//*************************
			// print_r($_FILES);
			if (isset($_FILES['imagePers_'.$i]['name']))
				{
				$nomUp='';
				$image_name=$_FILES['imagePers_'.$i]['name'];     //Le nom original du fichier, comme sur le disque du visiteur (exemple : mon_icone.png).
				$image_type=$_FILES['imagePers_'.$i]['type'];     //Le type du fichier. Par exemple, cela peut être « image/png ».
				$image_size=$_FILES['imagePers_'.$i]['size'];     //La taille du fichier en octets.
				$image_tmpname=$_FILES['imagePers_'.$i]['tmp_name']; //L'adresse vers le fichier uploadé dans le répertoire temporaire.
				$image_error=$_FILES['imagePers_'.$i]['error'];    //Le code d'erreur, qui permet de savoir si le fichier a bien été uploadé.
				
				$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' );
				//1. strrchr renvoie l'extension avec le point (« . »).
				//2. substr(chaine,1) ignore le premier caractère de chaine.
				//3. strtolower met l'extension en minuscules.
				$extension_upload = strtolower(  substr(  strrchr($_FILES['imagePers_'.$i]['name'], '.')  ,1)  );
				
				if ( in_array($extension_upload,$extensions_valides) ) //Vérifie si l'extension fait partie de celles autorisées
					{
					$nomUp = md5(uniqid(rand(), true)).'.'.$extension_upload;
					$nom ='/var/www/media/images/'.$nomUp;
					$resultat = move_uploaded_file($_FILES['imagePers_'.$i]['tmp_name'],$nom);
					}
				}	
			else 
				{
				$nomUp=$_POST['photo_'.$i];
				}
			$idPersonne=uniqid();
			$sql='INSERT INTO z_personne (nom, prenom, date_naissance, photo, pays, ville, CP, adresse, numero, descriptif) VALUES ("'.$nomPers.'", "'.$prenom.'", "'.$dn.'", "'.$nomUp.'", "'.$pays.'", "'.$ville.'", "'.$CP.'", "'.$rue.'", "'.$num.'", "'.$desc.'")';
			$this->appli->dbPdo->exec($sql);
			
			$sql='SELECT id_personne FROM z_personne WHERE nom="'.$nomPers.'" AND prenom="'.$prenom.'" AND date_naissance="'.$dn.'" AND photo="'.$nomUp.'" AND pays="'.$pays.'" AND ville="'.$ville.'" AND CP="'.$CP.'" AND adresse="'.$rue.'" AND numero="'.$num.'" AND descriptif="'.$desc.'"';
			$rep=$this->appli->dbPdo->query($sql);
			while ($row=$rep->fetch())
				{
				$idPersonne=$row['id_personne'];
				}
			$sql='INSERT INTO z_fiche_personne (id_fiche, id_personne, id_liaison) VALUES ("'.$idFiche.'", "'.$idPersonne.'", "'.$implication.'")';
			$this->appli->dbPdo->exec($sql);			
			}
		}
	//VEHICULES	
	$nbVV=$_POST['nbVV'];
	// echo $nbVV;
	for($i=1;$i<=$nbVV;$i++)
		{
		if (isset($_POST['marqueVV_'.$i]))
			{
			$marque=strtoupper($_POST['marqueVV_'.$i]);
			$modele=ucfirst($_POST['modeleVV_'.$i]);
			$immat=strtoupper($_POST['immatVV_'.$i]);
			$chassis=strtoupper($_POST['chassisVV_'.$i]);
			$couleur=ucfirst($_POST['couleurVV_'.$i]);
			$desc=$this->htm($_POST['descVV_'.$i]);
			$implication=$_POST['implicationVV_'.$i];
			$idVV=md5(uniqid());
			$sql='INSERT INTO z_vehicule (id_vv, marque, modele, immatriculation, chassis, couleur, descriptif) VALUES ("'.$idVV.'", "'.$marque.'", "'.$modele.'", "'.$immat.'", "'.$chassis.'", "'.$couleur.'", "'.$desc.'")';
			$this->appli->dbPdo->exec($sql);
			$sql='INSERT INTO z_fiche_vehicule (id_vehicule, id_liaison, id_fiche) VALUES ("'.$idVV.'", "'.$implication.'", "'.$idFiche.'")';
			$this->appli->dbPdo->exec($sql);
			}
		}
	//LIEUDITS
	$nbLD=$_POST['nbLD'];
	for($i=1;$i<=$nbLD;$i++)
		{
		if (isset($_POST['denomLD_'.$i]))
			{
			$denom=$this->htm($_POST['denomLD_'.$i]);
			$implication=$_POST['implicationLD_'.$i];
			$idLD=md5(uniqid());
			$sql='INSERT INTO z_lieudit (id_lieudit, description) VALUES ("'.$idLD.'", "'.$denom.'")';
			$this->appli->dbPdo->exec($sql);
			$sql='INSERT INTO z_fiche_lieudit (id_fiche, id_lieudit, id_liaison) VALUES ("'.$idFiche.'", "'.$idLD.'", "'.$implication.'")';
			$this->appli->dbPdo->exec($sql);
			}
		}
		//COMMERCES	
	$nbCom=$_POST['nbCommerce'];
	for($i=1;$i<=$nbCom;$i++)
		{
			if (isset($_POST['denomCom_'.$i]))
				{
				$denom=$this->htm($_POST['denomCom_'.$i]);
				$ville=strtoupper($this->htm($_POST['comCom_'.$i]));
				$CP=$_POST['CPCom_'.$i];
				$idRue=$_POST['idRueCom_'.$i];
				$num=$_POST['numCom_'.$i];
				$desc=$this->htm($_POST['descCom_'.$i]);
				$implication=$_POST['implicationCom_'.$i];
				$idCom=md5(uniqid());
				$sql='INSERT INTO z_commerce (id_commerce, nom, ville, CP, idRue, numero, descriptif) VALUES(:id_commerce, :nom, :ville ,:CP, :idRue, :numero, :descriptif)';
				$req=$this->appli->dbPdo->prepare($sql);
				$req->execute(array(
				'id_commerce'=>$idCom,
				'nom'=>$denom,
				'ville'=>$ville,
				'CP'=>$CP,
				'idRue'=>$idRue,
				'numero'=>$num,
				'descriptif'=>$desc
				));
				$sql='INSERT INTO z_fiche_commerce (id_fiche, id_commerce, id_liaison) VALUES(:id_fiche, :id_commerce, :id_liaison)';
				$req=$this->appli->dbPdo->prepare($sql);
				$req->execute(array(
				'id_fiche'=>$idFiche,
				'id_commerce'=>$idCom,
				'id_liaison'=>$implication
				));
				}
		}
	//TEXTES LIBRES
	$nbTL=$_POST['nbTL'];
	for($i=1;$i<=$nbTL;$i++)
		{
		if (isset($_POST['titreTL_'.$i]))
			{
				$titre=$this->htm($_POST['titreTL_'.$i]);
				$text=$this->htm($_POST['texteTL_'.$i]);
				$idTL=md5(uniqid());
				$sql='INSERT INTO z_texte_libre (id_textelibre,texte,titre) VALUES(:id_textelibre, :texte, :titre)';
				$req=$this->appli->dbPdo->prepare($sql);
				$req->execute(array(
				'id_textelibre'=>$idTL,
				'texte'=>$text,
				'titre'=>$titre
				));
				$sql='INSERT INTO z_fiche_textelibre(id_fiche, id_textelibre) VALUES(:id_fiche, :id_textelibre)';
				$req=$this->appli->dbPdo->prepare($sql);
				$req->execute(array(
				'id_fiche'=>$idFiche,
				'id_textelibre'=>$idTL
				));
			}
	
		}
	//PHOTOS
	$nbPic=$_POST['nbPic'];
	for($i=1;$i<=$nbPic;$i++)
		{
		if(isset($_POST['comPic_'.$i]))
			{
			$com=$_POST['comPic_'.$i];
			if (isset($_FILES['photoFiche_'.$i]['name']))
				{
				$nomUp='';
				$image_name=$_FILES['photoFiche_'.$i]['name'];     //Le nom original du fichier, comme sur le disque du visiteur (exemple : mon_icone.png).
				$image_type=$_FILES['photoFiche_'.$i]['type'];     //Le type du fichier. Par exemple, cela peut être « image/png ».
				$image_size=$_FILES['photoFiche_'.$i]['size'];     //La taille du fichier en octets.
				$image_tmpname=$_FILES['photoFiche_'.$i]['tmp_name']; //L'adresse vers le fichier uploadé dans le répertoire temporaire.
				$image_error=$_FILES['photoFiche_'.$i]['error'];    //Le code d'erreur, qui permet de savoir si le fichier a bien été uploadé.
				
				$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' );
				//1. strrchr renvoie l'extension avec le point (« . »).
				//2. substr(chaine,1) ignore le premier caractère de chaine.
				//3. strtolower met l'extension en minuscules.
				$extension_upload = strtolower(  substr(  strrchr($_FILES['photoFiche_'.$i]['name'], '.')  ,1)  );
				
				if ( in_array($extension_upload,$extensions_valides) ) //Vérifie si l'extension fait partie de celles autorisées
					{
					$nomUp = md5(uniqid(rand(), true)).'.'.$extension_upload;
					$nom ='/var/www/media/images/'.$nomUp;
					$resultat = move_uploaded_file($_FILES['photoFiche_'.$i]['tmp_name'],$nom);
					}
				}	
			else 
				{
				$nomUp=$_POST['photo_'.$i];
				}
			$idPic=md5(uniqid());
			$sql='INSERT INTO z_photo (id_photo, commentaire, lien) VALUES (:id_photo, :commentaire, :lien)';
			$req=$this->appli->dbPdo->prepare($sql);
			$req->execute(array(
			'id_photo'=>$idPic,
			'commentaire'=>$this->htm($com),
			'lien'=>$nomUp
			));
			$sql='INSERT INTO z_fiche_photo (id_fiche, id_photo) VALUES (:id_fiche, :id_photo)';
			$req=$this->appli->dbPdo->prepare($sql);
			$req->execute(array(
			'id_fiche'=>$idFiche,
			'id_photo'=>$idPic
			));
			}
		}
	}

	public function searchInFiches($search){
		$html='';
		$data=array();
		$i=0;
		$search='%'.$search.'%';
		$sql='SELECT a.id_vv, a.immatriculation, a.marque, a.modele, a.couleur, a.descriptif, a.chassis, c.id_fiche
		FROM z_vehicule a 
		LEFT JOIN z_fiche_vehicule b ON b.id_vehicule=a.id_vv
		LEFT JOIN z_fiche c ON c.id_fiche=b.id_fiche
		WHERE a.immatriculation LIKE :search
		AND (c.date_fin > NOW() OR c.date_fin="0000-00-00 00:00:00")';
		$req=$this->appli->dbPdo->prepare($sql);
		if($req->execute(array('search'=>$search))){
			while ($row=$req->fetch()){
				//$html.='<a href="?component=cops&action=moreInfos&idFiche='.$row['id_fiche'].'">Lien</a><br />';
				$data[$i]['type']='vv';
				$data[$i]['vv']['marque']=$row['marque'];
				$data[$i]['vv']['immatriculation']=$row['immatriculation'];
				$data[$i]['vv']['modele']=$row['modele'];
				$data[$i]['vv']['couleur']=$row['couleur'];
				$data[$i]['vv']['chassis']=$row['chassis'];
				$data[$i]['vv']['descriptif']=$row['descriptif'];
				$data[$i]['vv']['id_fiche']=$row['id_fiche'];
				$i++;
			}
			$sql='SELECT a.id_personne, a.nom, a.prenom, a.date_naissance, a.photo, c.id_fiche
			FROM z_personne a
			LEFT JOIN z_fiche_personne b ON b.id_personne = a.id_personne
			LEFT JOIN z_fiche c ON c.id_fiche = b.id_fiche
			WHERE a.nom LIKE :search OR a.prenom LIKE :search
			AND (c.date_fin > NOW() OR c.date_fin="0000-00-00 00:00:00")';
			$req=$this->appli->dbPdo->prepare($sql);
			if($req->execute(array('search'=>$search))){
				while ($row=$req->fetch()){
				$data[$i]['type']='pers';
				$data[$i]['pers']['nom']=$row['nom'];
				$data[$i]['pers']['prenom']=$row['prenom'];
				$data[$i]['pers']['dn']=$row['date_naissance'];
				$data[$i]['pers']['photo']=$row['photo'];
				$data[$i]['pers']['id_fiche']=$row['id_fiche'];
				$i++;
				}
			}
		$data['ttl']=$i;
		return $data;
		}
	}

public function getMsgs(){ /* 15.09.2015 */
	$sql='SELECT 
	a.id_info, a.titre, a.info, a.dateIn,
	b.nom, b.prenom
	FROM z_info_push a
	LEFT JOIN users b ON b.id_user = a.id_user
	WHERE a.valid="1"';
	return $this->appli->dbPdo->query($sql);
}

public function delInfoPushById($id){ /* 15.09.2015 */
	$sql='UPDATE z_info_push SET valid="0" WHERE id_info="'.$id.'"';
	$this->appli->dbPdo->exec($sql);
}
}
?>