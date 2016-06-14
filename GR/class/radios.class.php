<?php

class Radios{
	
private $pdo;

public function __construct($dbPdo){
	$this->pdo=$dbPdo;
}

public function getAllRadios($dispo=''){
	if(isset($_GET['tri'])){
		switch ($_GET['tri']){
			case 'numTEIDESC':
				$tri='num_TEI DESC';
				break;
			case 'numTEIASC':
				$tri='num_TEI ASC';
				break;
			case 'numISSIASC':
				$tri='num_ISSI ASC';
				break;
			case 'numISSIDESC':
				$tri='num_ISSI DESC';
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
				$tri='num_TEI ASC';
				break;
		}
	}	
	$sql='SELECT num_TEI, num_ISSI, dateLivraison, statut, marque_radio, disponible, id_user FROM radios';
	$sql.=($dispo!='') ? ' WHERE disponible="'.$dispo.'"' : '';

	$sql.=(isset($tri)) ? ' ORDER BY '.$tri : '';	
	return $this->pdo->query($sql);
}

public function getHisto($from){
	$sql='SELECT a.id, a.num_TEI, a.dateA, a.motifA, a.dateR, a.motifR, a.id_user, b.nom, b.prenom, c.marque_radio
	FROM histo_radio a
	LEFT JOIN users b ON b.id_user = a.id_user
	LEFT JOIN radios c ON c.num_TEI = a.num_TEI';
	switch($from){
		case 'radio';
			$sql.=' ORDER BY a.num_TEI';
			break;
		case 'user';
			$sql.=' ORDER BY b.nom';
			break;
	}
	return $this->pdo->query($sql);		
}

public function insertNewRadio($modele, $issi, $tei, $date, $fichier){
	$sql='INSERT INTO radios (num_TEI, num_ISSI, dateLivraison, statut, marque_radio, disponible, id_user) VALUES (:tei, :issi, :date, "Disponible", :marque, "O", "0")';
	$req=$this->pdo->prepare($sql);
	$req->execute(array('tei'=>$tei, 'issi'=>$issi, 'date'=>$date, 'marque'=>$modele));
	$sql='INSERT INTO mat_fichier (type_obj, id_obj, nom_fichier, type_doc) VALUES (:type, :objet, :fichier, :typedoc)';
	$req=$this->pdo->prepare($sql);
	$req->execute(array('type'=>'radio', 'objet'=>$tei, 'fichier'=>$fichier, 'typedoc'=>'acquisition'));
}

public function getFullInfos($id){
	$sql='SELECT a.num_TEI, a.num_ISSI, a.marque_radio, a.dateLivraison, a.disponible, 
	b.nom, b.prenom
	FROM radios a 
	LEFT JOIN users b ON b.id_user = a.id_user
	WHERE a.num_TEI=:idRadio';
	$req=$this->pdo->prepare($sql);
	$req->execute(array('idRadio'=>$id));
	$data=array();
	while ($row=$req->fetch()){
		$data['arme']['num_TEI']=$row['num_TEI'];
		$data['arme']['num_ISSI']=$row['num_ISSI'];
		$data['arme']['marque_radio']=$row['marque_radio'];
		$data['arme']['dateLivraison']=$row['dateLivraison'];
		$data['arme']['disponible']=$row['disponible'];
		$data['arme']['nom']=$row['nom'];
		$data['arme']['prenom']=$row['prenom'];
	}
	$sql='SELECT id_user, dateA, motifA, dateR, motifR FROM histo_radio WHERE num_TEI=:idRadio';
	$req=$this->pdo->prepare($sql);
	$req->execute(array('idRadio'=>$id));
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
	$sql='SELECT nom_fichier, type_doc FROM mat_fichier WHERE type_obj="radio" AND id_obj=:num_TEI';
	$req=$this->pdo->prepare($sql);
	$req->execute(array('num_TEI'=>$id));
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