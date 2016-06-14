<?php

class Armes{
public $num_arme;
public $dateLivraison;
public $calibre;
public $validite_arme;
public $marque_arme;
public $disponible;
public $id_user;
private $pdo;

public function __construct($dbPdo){
	$this->pdo=$dbPdo;
}
	
public function getAllArmes($dispo='', $type=''){
	if(isset($_GET['tri'])){
		switch ($_GET['tri']){
			case 'modArmeASC':
				$tri='marque_arme ASC';
				break;
			case 'modArmeDESC':
				$tri='marque_arme DESC';
				break;
			case 'numArmeASC':
				$tri='num_arme ASC';
				break;
			case 'numArmeDESC':
				$tri='num_arme DESC';
				break;				
			case 'calibreASC':
				$tri='calibre ASC';
				break;
			case 'calibreDESC':
				$tri='calibre DESC';
				break;
			case 'livraisonASC':
				$tri='dateLivraison ASC';
				break;
			case 'livraisonDESC':
				$tri='dateLivraison DESC';
				break;
			case 'dispoASC':
				$tri='disponible ASC';
				break;
			case 'dispoDESC':
				$tri='disponible DESC';
				break;
			case 'ctrlASC':
				$tri='validite_arme ASC';
				break;
			case 'ctrlDESC':
				$tri='validite_arme DESC';
				break;
			default:
				$tri='num_arme ASC';
				break;
		}
	}
	$sql='SELECT num_arme, dateLivraison, calibre, validite_arme, marque_arme, disponible, id_user FROM armes';
	$sql.=($dispo!='') ? ' WHERE disponible="'.$dispo.'"' : '';
	if($type!=''){
		$sql.=($dispo!='') ? ' AND type="'.$type.'"' : 'WHERE type="'.$type.'"';
	}
	$sql.=(isset($tri)) ? ' ORDER BY '.$tri : '';
	return $this->pdo->query($sql);
}

public function getHisto($from){
	$sql='SELECT a.id, a.num_arme, a.dateA, a.motifA, a.dateR, a.motifR, a.id_user, b.nom, b.prenom, c.marque_arme
	FROM histo_arme a
	LEFT JOIN users b ON b.id_user = a.id_user
	LEFT JOIN armes c ON c.num_arme = a.num_arme';
	switch($from){
		case 'arme';
			$sql.=' ORDER BY a.num_arme';
			break;
		case 'user';
			$sql.=' ORDER BY b.nom';
			break;
	}
	// echo $sql.'<br />';
	return $this->pdo->query($sql);	
}

public function insertNewArme($modArme, $numArme, $calibre, $dateAcquis, $nomFichier, $type){
	$sql='INSERT INTO armes (num_arme, dateLivraison, calibre, validite_arme, marque_arme, disponible, type, coffre) VALUE (:num, :dateL, :calibre, :valide, :marque, :dispo , :type, :coffre)';
	$req=$this->pdo->prepare($sql);
	$req->execute(array('num'=>$numArme, 'dateL'=>$dateAcquis, 'calibre'=>$calibre, 'valide'=> date("Y-m-d", strtotime(date("Y-m-d", strtotime($dateAcquis)) . " + 2 year")), 'marque'=>$modArme,'dispo'=>"O", 'type'=>$type, 'coffre'=>"O"));
	$sql='INSERT INTO mat_fichier (type_obj, id_obj, nom_fichier, type_doc) VALUES (:type, :objet, :fichier, :typedoc)';
	$req=$this->pdo->prepare($sql);
	$req->execute(array('type'=>'arme', 'objet'=>$numArme, 'fichier'=>$nomFichier, 'typedoc'=>'acquisition'));
	// echo $nomFichier;
}

public function getFullInfos($id){
	$sql='SELECT a.num_arme, a.dateLivraison, a.calibre, a.validite_arme, a.marque_arme, a.disponible, a.type, a.coffre, 
	b.nom, b.prenom
	FROM armes a 
	LEFT JOIN users b ON b.id_user = a.id_user
	WHERE a.num_arme=:idArme';
	$req=$this->pdo->prepare($sql);
	$req->execute(array('idArme'=>$id));
	$data=array();
	while ($row=$req->fetch()){
		$data['arme']['num_arme']=$row['num_arme'];
		$data['arme']['dateLivraison']=$row['dateLivraison'];
		$data['arme']['calibre']=$row['calibre'];
		$data['arme']['validite_arme']=$row['validite_arme'];
		$data['arme']['marque_arme']=$row['marque_arme'];
		$data['arme']['disponible']=$row['disponible'];
		$data['arme']['type']=$row['type'];
		$data['arme']['coffre']=$row['coffre'];
		$data['arme']['nom']=$row['nom'];
		$data['arme']['prenom']=$row['prenom'];
	}
	$sql='SELECT id_user, dateA, motifA, dateR, motifR FROM histo_arme WHERE num_arme=:idArme';
	$req=$this->pdo->prepare($sql);
	$req->execute(array('idArme'=>$id));
	$i=0;
	while($row=$req->fetch()){
		$data['histo'][$i]['dateA']=$row['dateA'];
		$data['histo'][$i]['motifA']=$row['motifA'];
		$data['histo'][$i]['dateR']=$row['dateR'];
		$data['histo'][$i]['motifR']=$row['motifR'];
		$sql='SELECT nom, prenom FROM users WHERE id_user=:user';
		$reqa=$this->pdo->prepare($sql);
		$reqa->execute(array('user'=>$row['id_user']));
		while($row2=$reqa->fetch()){
			$data['histo'][$i]['nom']=$row2['nom'];
			$data['histo'][$i]['prenom']=$row2['prenom'];
		}
		$i++;
	}
	$sql='SELECT nom_fichier, type_doc FROM mat_fichier WHERE type_obj="arme" AND id_obj=:idArme';
	$req=$this->pdo->prepare($sql);
	$req->execute(array('idArme'=>$id));
	$k=0;
	while($row3=$req->fetch()){
		$data['doc'][$k]['nom_fichier']=$row3['nom_fichier'];
		$data['doc'][$k]['type_doc']=$row3['type_doc'];
		$data['histo']['rowsDocs']=$k;
		$k++;
	}
	$data['histo']['rows']=$i;
	$data['histo']['rowsDocs']=$k;
	return $data;
	}	

}
?>