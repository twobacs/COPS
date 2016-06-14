<?php

class Batons{

private $pdo;

public function __construct($dbPdo){
	$this->pdo=$dbPdo;
}

public function getAllBatons($dispo=''){
	if(isset($_GET['tri'])){
		switch ($_GET['tri']){
			case 'numBatonDESC':
				$tri='num_baton DESC';
				break;
			case 'numBatonASC':
				$tri='num_baton ASC';
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
			default:
				$tri='num_baton ASC';
				break;
		}
	}	
	$sql='SELECT num_baton, marque_baton, dateLivraison, disponible, id_user FROM batons';
	$sql.=($dispo!='') ? ' WHERE disponible="'.$dispo.'"' : '';

	$sql.=(isset($tri)) ? ' ORDER BY '.$tri : '';	
	return $this->pdo->query($sql);
}

public function getHisto($from){
	$sql='SELECT a.id, a.num_baton, a.dateA, a.motifA, a.dateR, a.motifR, a.id_user, b.nom, b.prenom, c.marque_baton
	FROM histo_baton a
	LEFT JOIN users b ON b.id_user = a.id_user
	LEFT JOIN batons c ON c.num_baton = a.num_baton';
	switch($from){
		case 'arme';
			$sql.=' ORDER BY a.num_baton';
			break;
		case 'user';
			$sql.=' ORDER BY b.nom';
			break;
	}
	return $this->pdo->query($sql);	
}

public function insertNewBaton($marque_baton, $num_baton, $dateAcquis, $nomfichier){
	$sql='INSERT INTO batons (num_baton, dateLivraison, marque_baton, disponible) VALUE (:num, :dateL, :marque, :dispo)';
	$req=$this->pdo->prepare($sql);
	$req->execute(array('num'=>$num_baton, 'dateL'=>$dateAcquis, 'marque'=>$marque_baton, 'dispo'=>"O"));
	$sql='INSERT INTO mat_fichier (type_obj, id_obj, nom_fichier, type_doc) VALUES (:type, :objet, :fichier, :typedoc)';
	$req=$this->pdo->prepare($sql);
	$req->execute(array('type'=>'baton', 'objet'=>$num_baton, 'fichier'=>$nomfichier, 'typedoc'=>'acquisition'));	
}

public function getFullInfos($id){
	$sql='SELECT a.num_baton, a.dateLivraison, a.marque_baton, a.disponible, 
	b.nom, b.prenom
	FROM batons a 
	LEFT JOIN users b ON b.id_user = a.id_user
	WHERE a.num_baton=:idArme';
	$req=$this->pdo->prepare($sql);
	$req->execute(array('idArme'=>$id));
	$data=array();
	while ($row=$req->fetch()){
		$data['arme']['num_baton']=$row['num_baton'];
		$data['arme']['marque_baton']=$row['marque_baton'];
		$data['arme']['dateLivraison']=$row['dateLivraison'];
		$data['arme']['disponible']=$row['disponible'];
		$data['arme']['nom']=$row['nom'];
		$data['arme']['prenom']=$row['prenom'];
	}
	$sql='SELECT id_user, dateA, motifA, dateR, motifR FROM histo_baton WHERE num_baton=:idArme';
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
	$sql='SELECT nom_fichier, type_doc FROM mat_fichier WHERE type_obj="baton" AND id_obj=:idArme';
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