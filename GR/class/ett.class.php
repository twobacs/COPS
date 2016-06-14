<?php

class ETT{
private $pdo;

public function __construct($dbPdo){
	$this->pdo=$dbPdo;
}

public function getAllETT($tri){
	$sql='SELECT id_ETT, date_validite FROM z_ETT';
	if($tri!=''){
		switch ($_GET['tri']){
			case 'numASC':
				$sql.=' ORDER BY id_ETT ASC';
				break;
			case 'numDESC':
				$sql.=' ORDER BY id_ETT DESC';
				break;
			case 'validiteASC':
				$sql.=' ORDER BY date_validite ASC';
				break;
			case 'validiteDESC':
				$sql.=' ORDER BY date_validite DESC';
				break;
		}
	}
	return $this->pdo->query($sql);
}

public function getFullInfos($id){
	$sql='SELECT id_ETT, date_validite, dateLivraison, dateSortie, marque, modele FROM z_ETT WHERE id_ETT=:id';
	$req=$this->pdo->prepare($sql);
	$req->execute(array('id'=>$id));
	while($row=$req->fetch()){
		$data['id']=$row['id_ETT'];
		$data['dateV']=$row['date_validite'];
		$data['dateL']=$row['dateLivraison'];
		$data['dateSortie']=$row['dateSortie'];
		$data['marque']=$row['marque'];
		$data['modele']=$row['modele'];
	}
	$sql='SELECT nom_fichier, type_doc FROM mat_fichier WHERE id_obj=:id AND type_obj="ETT"';
	$req=$this->pdo->prepare($sql);
	$req->execute(array('id'=>$id));
	$i=0;
	while($row=$req->fetch()){
		$data[$i]['nom_fichier']=$row['nom_fichier'];
		$data[$i]['type_doc']=$row['type_doc'];
		$i++;
	}
	$data['nbDocs']=$i;
	return $data;
}

public function insertNewETT($marque, $modele, $numero, $dateA, $dateVal, $nomFichier){
	$sql='INSERT INTO z_ETT (id_ETT, date_validite, dateLivraison, marque, modele) VALUES (:id, :dateV, :dateA, :marque, :modele)';
	$req=$this->pdo->prepare($sql);
	$req->execute(array('id'=>$numero, 'dateV'=>$dateVal, 'dateA'=>$dateA, 'marque'=>$marque, 'modele'=>$modele));
	$sql='INSERT INTO mat_fichier (type_obj, id_obj, nom_fichier, type_doc) VALUES (:type, :objet, :fichier, :typedoc)';
	$req=$this->pdo->prepare($sql);
	$req->execute(array('type'=>'ETT', 'objet'=>$numero, 'fichier'=>$nomFichier, 'typedoc'=>'acquisition'));
	
}

}

?>