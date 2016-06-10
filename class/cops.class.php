<?php

class Cops
{

private $pdo;
public $text='test';

public function __construct($dbPdo)
	{
	$this->pdo=$dbPdo;
	}
	
public function getSection()
	{
	$sql='SELECT id_section, denomination FROM z_section_fiche ORDER BY denomination';
	$rep=$this->pdo->query($sql);
	return $rep;
	}
	
public function getCategBySection($section)
	{
	$sql='SELECT id_categ, denomination FROM z_categorie_fiche WHERE id_section="'.$section.'"';
	return $this->pdo->query($sql);
	}

public function getListInfosCops($tri='a',$page=1)
	{
	$sql='
	SELECT a.id_fiche AS IDFiche, a.id_categ AS IDCateg, a.dateHr_encodage AS DHIn, a.date_debut AS DHStart, a.date_fin AS DHEnd, a.interaction AS Interaction, a.id_encodeur AS EncodeurFiche, a.texteInfo AS TextInfo, 
	b.denomination AS DenomCateg,
	c.denomination AS DenomSection 
	FROM z_fiche a
	LEFT JOIN z_categorie_fiche b ON a.id_categ = b.id_categ
	LEFT JOIN z_section_fiche c ON b.id_section = c.id_section
	WHERE a.date_fin>=CURDATE() OR date_fin="0000-00-00 00:00:00"
	ORDER BY 
	';
	// WHERE a.date_fin>=CURDATE() 
	switch ($tri)
		{
		case 'categ' :
			$sql.='a.id_categ';
			break;
		
		case 'DateInD' :
			$sql.='a.dateHr_encodage ASC';
			break;
			
		case 'DateInU' :
			$sql.='a.dateHr_encodage DESC';
			break;	
			
		case 'DateDebD' :
			$sql.='a.date_debut ASC';
			break;
			
		case 'DateDebU' :
			$sql.='a.date_debut DESC';
			break;

		case 'DateFinD' :
			$sql.='a.date_fin ASC';
			break;
			
		case 'DateFinU' :
			$sql.='a.date_fin DESC';
			break;
			
		default :
			$sql.='a.date_debut';
			break;
		}
		
	$sql.=' LIMIT ';
	if ($page!=1)
		{
		$limitBas=((10*($page-1))+1);
		$limitHaut=(10);
		}
	else
		{
		$limitBas=0;
		$limitHaut=10;
		}
	$sql.=$limitBas.', '.$limitHaut;
	// return $sql;
	return $this->pdo->query($sql);
	}
	
public function getListInfosHot(){ /* 15.09.2015 */
	$sql='
	SELECT a.id_fiche AS IDFiche, a.id_categ AS IDCateg, a.dateHr_encodage AS DHIn, a.date_debut AS DHStart, a.date_fin AS DHEnd, a.interaction AS Interaction, a.id_encodeur AS EncodeurFiche, a.texteInfo AS TextInfo, 
	b.denomination AS DenomCateg,
	c.denomination AS DenomSection 
	FROM z_fiche a
	LEFT JOIN z_categorie_fiche b ON a.id_categ = b.id_categ
	LEFT JOIN z_section_fiche c ON b.id_section = c.id_section
	WHERE a.id_categ="34" AND (a.date_fin>=CURDATE() OR date_fin="0000-00-00 00:00:00")';
	return $this->pdo->query($sql);
}
	
public function getInfoFicheById($id)
	{
	$sql='
	SELECT a.id_fiche, a.id_categ, a.dateHr_encodage, a.date_debut, a.date_fin, a.interaction, a.id_encodeur, a.texteInfo,
	b.denomination AS DenomCateg, b.id_categ AS IdCateg,
	c.denomination AS DenomSection, c.id_section AS IdSection ,
	d.nom, d.prenom
	FROM z_fiche a
	LEFT JOIN z_categorie_fiche b ON a.id_categ = b.id_categ
	LEFT JOIN z_section_fiche c ON b.id_section = c.id_section
	LEFT JOIN users d ON a.id_encodeur = d.id_user 
	WHERE a.id_fiche="'.$id.'"
	';
	$data['fiche']=$this->pdo->query($sql);

	//Récupération des données véhicule(s) lié(s)
	$data['vv']['sql']=$this->getInfoVVbyIdFiche($id);
	$data['vv']['q']=$this->getNbEntByIdFiche($id,'VV');
	//*******************************************

	//Récupération des données personne(s) liée(s)
	$data['pers']['sql']=$this->getInfoPersByIdFiche($id);
	$data['pers']['q']=$this->getNbEntByIdFiche($id,'pers');
	//*******************************************
	
	//Récupération des données lieudit(s) lié(s)
	$data['ld']['sql']=$this->getInfoLDByIdFiche($id);
	$data['ld']['q']=$this->getNbEntByIdFiche($id,'LD');
	//*******************************************	
	
	//Récupération des données commerce(s) lié(s)
	$data['com']['sql']=$this->getInfoComByIdFiche($id);
	$data['com']['q']=$this->getNbEntByIdFiche($id,'com');
	//*******************************************	
	
	//Récupération des données texte(s) libre(s) lié(s)
	$data['tl']['sql']=$this->getInfoTLByIdFiche($id);
	$data['tl']['q']=$this->getNbEntByIdFiche($id,'tl');
	//*******************************************	

	//Récupération des données photo(s) liée(s)
	$data['pic']['sql']=$this->getInfoPicByIdFiche($id);
	$data['pic']['q']=$this->getNbEntByIdFiche($id,'pic');
	//*******************************************	
	
	return $data;
	}
	
public function getNbEntByIdFiche($id,$ent)
	{
	switch ($ent)
		{
		case 'VV' :
			$table='z_fiche_vehicule';
			break;
		case 'pers' :
			$table='z_fiche_personne';
			break;
		case 'LD' :
			$table='z_fiche_lieudit';
			break;
		case 'com' :
			$table='z_fiche_commerce';
			break;
		case 'tl' :
			$table='z_fiche_textelibre';
			break;
		case 'pic' :
			$table='z_fiche_photo';
		}
	$rep=$this->pdo->query('SELECT COUNT(*) FROM '.$table.' WHERE id_fiche="'.$id.'"');
	while ($row=$rep->fetch())
		{
		$q=$row['COUNT(*)'];
		}
	return $q;	
	}

public function getInfoVVbyIdFiche($id)
	{
	$sql='
	SELECT 
	a.id_liaison,
	b.id_vv, b.marque, b.modele, b.immatriculation, b.chassis, b.couleur, b.descriptif,
	c.denomination AS liaison
	FROM z_fiche_vehicule a
	LEFT JOIN z_vehicule b ON a.id_vehicule = b.id_vv
	LEFT JOIN z_liaison c ON a.id_liaison = c.id_liaison
	WHERE a.id_fiche = "'.$id.'"
	';
	return $this->pdo->query($sql);
	}
	
public function getInfoPersByIdFiche($id)
	{
	$sql='
	SELECT 
	a.id_liaison,
	b.id_personne, b.nom, b.prenom, b.date_naissance, b.photo, b.pays, b.CP, b.ville, b.adresse, b.numero, b.descriptif,
	c.denomination AS liaison
	FROM z_fiche_personne a
	LEFT JOIN z_personne b ON a.id_personne = b.id_personne
	LEFT JOIN z_liaison c ON a.id_liaison = c.id_liaison
	WHERE a.id_fiche = "'.$id.'"
	';
	return $this->pdo->query($sql);	
	}
	
public function getInfoLDByIdFiche($id)
	{
	$sql='
	SELECT 
	a.id_liaison,
	b.description, b.id_lieudit, 
	c.denomination AS liaison
	FROM z_fiche_lieudit a
	LEFT JOIN z_lieudit b ON a.id_lieudit = b.id_lieudit
	LEFT JOIN z_liaison c ON a.id_liaison = c.id_liaison
	WHERE a.id_fiche = "'.$id.'"
	';
	return $this->pdo->query($sql);		
	}
	
public function getInfoComByIdFiche($id)
	{
	$sql='
	SELECT 
	a.id_liaison,
	b.id_commerce, b.nom, b.ville, b.CP, b.idRue, b.numero, b.descriptif,
	c.denomination AS liaison,
	d.NomRue 
	FROM z_fiche_commerce a
	LEFT JOIN z_commerce b ON a.id_commerce = b.id_commerce
	LEFT JOIN z_liaison c ON a.id_liaison = c.id_liaison
	LEFT JOIN z_rues d ON d.IdRue = b.idRue
	WHERE a.id_fiche = "'.$id.'"
	';
	return $this->pdo->query($sql);		
	}
	
public function getInfoTLByIdFiche($id)
	{
	$sql='
	SELECT 
	b.titre, b.texte, b.id_textelibre 
	FROM z_fiche_textelibre a
	LEFT JOIN z_texte_libre b ON a.id_textelibre = b.id_textelibre
	WHERE a.id_fiche = "'.$id.'"
	';
	return $this->pdo->query($sql);		
	}
	
public function getInfoPicByIdFiche($id)
	{
	$sql='
	SELECT 
	b.lien, b.commentaire, b.id_photo 
	FROM z_fiche_photo a
	LEFT JOIN z_photo b ON a.id_photo = b.id_photo
	WHERE a.id_fiche = "'.$id.'"
	';
	return $this->pdo->query($sql);		
	}
	
public function getCateg()
	{
	$sql='SELECT id_categ, id_section, denomination FROM z_categorie_fiche';
	return $this->pdo->query($sql);
	}
	
public function getMissionsCopsInTime($debut,$fin)
	{
	$i=0;
	$sql='SELECT id_fiche, texteInfo, id_categ FROM z_fiche WHERE 
	(date_fin>"'.$debut.'" AND date_debut<"'.$fin.'" AND interaction="O") OR
	(date_debut>"'.$debut.'" AND date_debut<"'.$fin.'" AND interaction="O") OR 
	(date_debut<"'.$debut.'" AND date_fin="0000-00-00 00:00:00" AND interaction="O")
	ORDER BY date_debut';
	$rep=$this->pdo->query($sql);
	while ($row=$rep->fetch())
		{
		$data[$i]['id']=$row['id_fiche'];
		$data[$i]['texte']=$row['texteInfo'];
		$data[$i]['idCateg']=$row['id_categ'];
		$data[$i]['infos']=$this->getInfoFicheById($row['id_fiche']);
		$i++;
		}
	$data['ttl']=$i;
	return $data;
	}

public function getMissionsCopsInTime_($debut,$fin)
	{
	$i=0;
	$sql='SELECT 
	a.id_fiche, a.texteInfo,
	b.id_commerce, b.id_liaison,
	h.nom AS nomCom, h.ville AS villeCom, h.CP AS CPVille, h.idRue AS idRueCom, h.numero AS numCom, h.descriptif AS descCom,
	i.NomRue AS rueCom,
	j.description AS descLieuDit,
	k.nom AS nomPers, k.prenom AS prenomPers, k.date_naissance AS DNPers,
	l.lien AS urlImg,
	m.texte AS txtTexte, m.titre AS titreTexte,
	n.marque AS marqueVV, n.modele AS modeleVV, n.immatriculation AS immatVV
	FROM z_fiche a 
	LEFT JOIN z_fiche_commerce b ON b.id_fiche = a.id_fiche
	LEFT JOIN z_fiche_lieudit c ON c.id_fiche = a.id_fiche
	LEFT JOIN z_fiche_personne d ON d.id_fiche = a.id_fiche
	LEFT JOIN z_fiche_photo e ON e.id_photo = a.id_fiche
	LEFT JOIN z_fiche_textelibre f ON f.id_fiche = a.id_fiche
	LEFT JOIN z_fiche_vehicule g ON g.id_fiche = a.id_fiche
	LEFT JOIN z_commerce h ON h.id_commerce = b.id_commerce
	LEFT JOIN z_rues i ON i.IdRue = h.idRue
	LEFT JOIN z_lieudit j ON j.id_lieudit = c.id_lieudit
	LEFT JOIN z_personne k ON k.id_personne = d.id_personne
	LEFT JOIN z_photo l ON l.id_photo = e.id_photo
	LEFT JOIN z_texte_libre m ON m.id_textelibre = f.id_textelibre
	LEFT JOIN z_vehicule n ON n.id_vv = g.id_vehicule
	WHERE 
	(a.date_fin>"'.$debut.'" AND a.date_debut<"'.$fin.'" AND a.interaction="O") OR
	(a.date_debut>"'.$debut.'" AND a.date_debut<"'.$fin.'" AND a.interaction="O")
	ORDER BY a.date_debut';
	$rep=$this->pdo->query($sql);
	while ($row=$rep->fetch())
		{
		$data[$i]['id']=$row['id_fiche'];
		$data[$i]['texte']=$row['texteInfo'];
		// $data[]
		$i++;
		}
	$data['ttl']=$i;
	echo $sql;
	return $data;
	}	
}

?>