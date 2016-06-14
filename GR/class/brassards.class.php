<?php

class Brassards{
private $pdo;
public $num_brassard;
public $disponible;

public function __construct($dbPdo){
	$this->pdo=$dbPdo;
}

public function getAllBrassards($dispo=''){
	if(isset($_GET['tri'])){
		switch($_GET['tri']){
			case 'numBrassardASC':
				$tri='num_brassard ASC';
				break;
			case 'numBrassardDESC':
				$tri='num_brassard DESC';
				break;
			case 'livraisonDESC':
				$tri='dateLivraison DESC';
				break;
			case 'livraisonASC':
				$tri='dateLivraison ASC';
				break;
			case 'dispoDESC':
				$tri='disponible DESC';
				break;
			case 'dispoASC':
				$tri='disponible ASC';
				break;
			default:
				$tri='num_brassard ASC';
				break;
		}
	}
	$sql='SELECT num_brassard, dateLivraison, disponible, id_user FROM brassards';
	$sql.=($dispo!='') ? ' WHERE disponible="'.$dispo.'"' : '';
	$sql.=(isset($tri)) ? ' ORDER BY '.$tri : ' ORDER BY num_brassard ASC';
	return $this->pdo->query($sql);
}

public function getHisto($from){
	$sql='SELECT a.id, a.num_brassard, a.dateA, a.motifA, a.dateR, a.motifR, a.id_user, b.nom, b.prenom
	FROM histo_brassard a
	LEFT JOIN users b ON b.id_user = a.id_user
	LEFT JOIN brassards c ON c.num_brassard = a.num_brassard';
	switch($from){
		case 'arme';
			$sql.=' ORDER BY a.num_brassard';
			break;
		case 'user';
			$sql.=' ORDER BY b.nom';
			break;
	}
	return $this->pdo->query($sql);	
}

public function getFullInfos($id){
	$sql='SELECT a.num_brassard, a.dateLivraison, a.disponible, 
	b.nom, b.prenom
	FROM brassards a 
	LEFT JOIN users b ON b.id_user = a.id_user
	WHERE a.num_brassard=:idArme';
	$req=$this->pdo->prepare($sql);
	$req->execute(array('idArme'=>$id));
	$data=array();
	while ($row=$req->fetch()){
		$data['arme']['num_brassard']=$row['num_brassard'];
		$data['arme']['dateLivraison']=$row['dateLivraison'];
		$data['arme']['disponible']=$row['disponible'];
		$data['arme']['nom']=$row['nom'];
		$data['arme']['prenom']=$row['prenom'];
	}
	$sql='SELECT id_user, dateA, motifA, dateR, motifR FROM histo_brassard WHERE num_brassard=:idArme';
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
	$sql='SELECT nom_fichier, type_doc FROM mat_fichier WHERE type_obj="brassard" AND id_obj=:idArme';
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

public function insertNewBrassard($numBrassard, $dateAcquis, $nomFichier){
	$sql='INSERT INTO brassards (num_brassard, dateLivraison) VALUE (:num, :dateL)';
	$req=$this->pdo->prepare($sql);
	$req->execute(array('num'=>$numBrassard, 'dateL'=>$dateAcquis));
	$sql='INSERT INTO mat_fichier (type_obj, id_obj, nom_fichier, type_doc) VALUES (:type, :objet, :fichier, :typedoc)';
	$req=$this->pdo->prepare($sql);
	$req->execute(array('type'=>'brassard', 'objet'=>$numBrassard, 'fichier'=>$nomFichier, 'typedoc'=>'acquisition'));
	// echo $nomFichier;
}


}
?>