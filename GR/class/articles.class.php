<?php

class Articles{
private $pdo;
public $id_article;
public $id_categorie;
public $denomination;
public $id_fournisseur;
public $stock;
public $commentaire;
public $id_mesure;
public $prix_achat;
public $q_min;

public function __construct($dbPdo){
	$this->pdo=$dbPdo;
}

function htmltosql($text)
	{
	$rep=htmlentities($text,ENT_QUOTES, "UTF-8");
	return $rep;
	}

public function getInfosArticles($id=0,$actif="O",$where=''){
	$attributs=func_get_args();
	if (($attributs[0]=='O') || ($attributs[0]=='N') || ($attributs[0]=='A')){
		$id=-1;
		$actif=$attributs[0];
	}
	else{
		$id=$attributs[0];
		$actif=(isset($attributs[1])) ? $attributs[1] : "O";
	}
	$data['nbArts']=0;
	$i=0;
	$sql='SELECT
	a.id_article, a.id_categorie, a.denomination, a.id_fournisseur, a.stock, a.commentaire, a.id_mesure, a.prix_achat, a.q_min, a.actif,
	b.denomination AS denCateg,
	c.id_fournisseur, c.nom AS nomFourn,
	d.denomination AS uMesure
	FROM articles a
	LEFT JOIN categories_articles b ON b.id_categorie=a.id_categorie
	LEFT JOIN fournisseurs c ON c.id_fournisseur=a.id_fournisseur
	LEFT JOIN unite_mesure d ON d.id_uMesure = a.id_mesure';
	if($id!=-1){
		$sql.=($id==0) ? ' WHERE a.actif="'.$actif.'"' : ' WHERE a.id_article="'.$id.'"';
	}
	if($where!=''){
		$sql.=' WHERE a.denomination LIKE "%'.$where.'%" ';
	}
	$sql.=' ORDER BY a.denomination';
	$req=$this->pdo->query($sql);
	while($row=$req->fetch()){
		$data[$i]['id_article']=$row['id_article'];
		$data[$i]['id_categorie']=$row['id_categorie'];
		$data[$i]['denomination']=$row['denomination'];
		$data[$i]['id_fournisseur']=$row['id_fournisseur'];
		$data[$i]['stock']=$row['stock'];
		$data[$i]['commentaire']=$row['commentaire'];
		$data[$i]['id_mesure']=$row['id_mesure'];
		$data[$i]['prix_achat']=$row['prix_achat'];
		$data[$i]['q_min']=$row['q_min'];
		$data[$i]['denCateg']=$row['denCateg'];
		$data[$i]['id_fournisseur']=$row['id_fournisseur'];
		$data[$i]['nomFourn']=$row['nomFourn'];
		$data[$i]['uMesure']=$row['uMesure'];
		$data[$i]['actif']=$row['actif'];
		$j=0;
		$sqlDocs='SELECT id_doc, nom_doc, nom_fichier, type_fichier FROM documents WHERE fk=:idArt AND tab=\'articles\'';
		$reqDocs=$this->pdo->prepare($sqlDocs);
		$reqDocs->execute(array('idArt'=>$data[$i]['id_article']));
		while($rowDocs=$reqDocs->fetch()){
			$data[$i][$j]['id_doc']=$rowDocs['id_doc'];
			$data[$i][$j]['nom_doc']=$rowDocs['nom_doc'];
			$data[$i][$j]['nom_fichier_doc']=$rowDocs['nom_fichier'];
			$data[$i][$j]['type_fichier']=$rowDocs['type_fichier'];
			$j++;
		}
		$data[$i]['nbDocs']=$j;
		$j=0;
		$sqlPhotos='SELECT id_photo, nom_fichier FROM photos_articles WHERE id_article=:idArt';
		$reqPhotos=$this->pdo->prepare($sql);
		$reqPhotos->execute(array('idArt'=>$data[$i]['id_article']));
		while($row=$reqPhotos->fetch()){
			if(isset($row['id_photo'])){
				$data[$i][$j]['id_photo']=$row['id_photo'];
				$data[$i][$j]['nom_fichier_photo']=$row['nom_fichier'];
				$j++;
			}
		}
		$data[$i]['nbPhotos']=$j;
		$i++;
	}
	$data['nbArts']=$i;
	return $data;
	
}

public function getCategories(){
	$sql='SELECT id_categorie, denomination FROM categories_articles ORDER BY denomination';
	return $this->pdo->query($sql)->fetchAll();
}

public function getArticlesByIdCateg($idCateg){
	$sql='SELECT a.id_article, a.denomination, a.id_fournisseur, a.stock, a.commentaire, a.id_mesure, a.prix_achat, a.q_min, a.actif,
	b.nom, b.num_entreprise, b.description,
	c.denomination AS mesure,
	d.denomination AS categorie
	FROM articles a
	LEFT JOIN fournisseurs b ON b.id_fournisseur=a.id_fournisseur
	LEFT JOIN unite_mesure c ON c.id_uMesure=a.id_mesure
	LEFT JOIN categories_articles d ON d.id_categorie=a.id_categorie
	WHERE a.id_categorie=:idCateg
	ORDER BY a.denomination';
	$req=$this->pdo->prepare($sql);
	$req->execute(array('idCateg'=>$idCateg));
	return $req;
}

public function getArticlesByIdMesure($idMesure){
	$sql='SELECT a.id_article, a.denomination, a.id_fournisseur, a.stock, a.commentaire, a.id_mesure, a.prix_achat, a.q_min,
	b.nom, b.num_entreprise, b.description,
	c.denomination AS mesure,
	d.denomination AS categorie
	FROM articles a
	LEFT JOIN fournisseurs b ON b.id_fournisseur=a.id_fournisseur
	LEFT JOIN unite_mesure c ON c.id_uMesure=a.id_mesure
	LEFT JOIN categories_articles d ON d.id_categorie=a.id_categorie
	WHERE a.id_mesure=:idMesure
	ORDER BY a.denomination';
	$req=$this->pdo->prepare($sql);
	$req->execute(array('idMesure'=>$idMesure));
	return $req;
}

public function addCateg($newCateg){
	$sql='INSERT INTO categories_articles (denomination) VALUES (:newCateg)';
	$req=$this->pdo->prepare($sql);
	$req->execute(array('newCateg'=>$this->htmltosql($newCateg)));
}

public function addUMesure($newUMesure){
	$sql='INSERT INTO unite_mesure (denomination) VALUES (:newUMesure)';
	$req=$this->pdo->prepare($sql);
	$req->execute(array('newUMesure'=>$this->htmltosql($newUMesure)));
}

public function getMesures(){
	$sql='SELECT id_uMesure, denomination FROM unite_mesure ORDER BY denomination';
	return $this->pdo->query($sql);
}

public function desactFourn($id){
	
	$req=$this->pdo->exec('UPDATE fournisseurs SET actif="N" WHERE id_fournisseur="'.$id.'"');


}
public function modifFourn($tab){
	$req=$this->pdo->prepare('UPDATE fournisseurs SET (nom) WHERE (:nom)');
	$req->bindParam(':nom',$tab['nom'],PDO::PARAM_STR);

	$req->execute();
}

public function addNewArticle($tab){
	include_once('functions.php');
	if((isset($_FILES['fileToUpload']['name']))&& ($_FILES['fileToUpload']['name']!='')){
					$nomDoc=uploadDoc('logistique');
				}
	if((isset($_FILES['picToUpload']['name'])) && ($_FILES['picToUpload']['name']!='')){
					$nomImg=uploadPic('logistique');
				}
	$sql='INSERT INTO articles (id_categorie,denomination,id_fournisseur,stock,commentaire,id_mesure,prix_achat,q_min) VALUES (:idCat,:denom,:idFourn,:stock,:commentaire,:idMesure,:PA,:qMin)';
	$req=$this->pdo->prepare($sql);
	if($req){
	$req->bindParam(':idCat',$tab['categNewArt'],PDO::PARAM_INT);
	$req->bindParam(':denom',$tab['denNewArt'],PDO::PARAM_STR);
	$req->bindParam(':idFourn',$tab['fournNewArt'],PDO::PARAM_INT);
	$req->bindParam(':stock',$tab['stockNewArt'],PDO::PARAM_INT);
	$req->bindParam(':commentaire',$tab['comNewArt'],PDO::PARAM_STR);
	$req->bindParam(':idMesure',$tab['uMesureNewArt'],PDO::PARAM_INT);
	$req->bindParam(':PA',$tab['paNewArt'],PDO::PARAM_STR);
	$req->bindParam(':qMin',$tab['qMinNewArt'],PDO::PARAM_INT);
	$req->execute();
	}
	$newId=$this->pdo->lastInsertId();
	if(isset($nomDoc)){
		$sql1='INSERT INTO documents (tab, fk, nom_doc, nom_fichier, type_fichier) VALUES (:tab, :idArt, :nomDoc, :nomFichier, :typeFichier)';
		$req1=$this->pdo->prepare($sql1);
		if($req1){
			$req1->bindValue(':tab','articles',PDO::PARAM_STR);
			$req1->bindParam(':idArt',$newId,PDO::PARAM_INT);
			$req1->bindParam(':nomDoc',$_FILES['fileToUpload']['name'],PDO::PARAM_STR);
			$req1->bindValue(':nomFichier',$nomDoc,PDO::PARAM_STR);
			$req1->bindValue(':typeFichier','pdf',PDO::PARAM_STR);
			$req1->execute();
		}		
	}
	if(isset($nomImg)){
		$sql1='INSERT INTO documents (tab, fk, nom_doc, nom_fichier, type_fichier) VALUES (:tab, :idArt, :nomDoc, :nomFichier, :typeFichier)';
		$req1=$this->pdo->prepare($sql1);
		if($req1){
			$req1->bindValue(':tab','articles',PDO::PARAM_STR);
			$req1->bindParam(':idArt',$newId,PDO::PARAM_INT);
			$req1->bindParam(':nomDoc',$_FILES['picToUpload']['name'],PDO::PARAM_STR);
			$req1->bindValue(':nomFichier',$nomImg,PDO::PARAM_STR);
			$req1->bindValue(':typeFichier','image',PDO::PARAM_STR);
			$req1->execute();
		}		
	}	
	return $newId;
}

public function modifActifArticlesById($id,$status){
	$sql='UPDATE articles SET actif=:act WHERE id_article=:id';
	$req=$this->pdo->prepare($sql);
	$req->bindParam(':id',$id,PDO::PARAM_INT);
	$req->bindParam(':act',$status,PDO::PARAM_STR);
	$req->execute();
	return $req->rowCount();
}

public function recordModifsArtById($tab){
	$sql='UPDATE articles SET id_categorie=:idCat, denomination=:denom, id_fournisseur=:idFourn, stock=:stock, commentaire=:com, id_mesure=:idMesure, prix_achat=:PA, q_min=:qMin WHERE id_article=:idArt';
	$req=$this->pdo->prepare($sql);
	$req->bindParam(':idCat',$tab['categ'],PDO::PARAM_INT);
	$req->bindParam(':denom',$tab['denom'],PDO::PARAM_STR);
	$req->bindParam(':idFourn',$tab['fourn'],PDO::PARAM_STR);
	$req->bindParam(':stock',$tab['stock'],PDO::PARAM_INT);
	$req->bindParam(':com',$tab['commentaire'],PDO::PARAM_STR);
	$req->bindParam(':idMesure',$tab['uMesure'],PDO::PARAM_INT);
	$req->bindParam(':PA',$tab['PA'],PDO::PARAM_STR);
	$req->bindParam(':qMin',$tab['qmin'],PDO::PARAM_INT);
	$req->bindParam(':idArt',$tab['idArt'],PDO::PARAM_INT);
	$req->execute();
	return $req->rowCount();
}

public function addDocToArt($post,$get){
	include_once('functions.php');
	$nomDoc=uploadDoc('logistique');
	// echo $nomDoc;
	$sql='INSERT INTO documents (tab, fk, nom_doc, nom_fichier, type_fichier) VALUES (:tab, :idArt, :nomDoc, :nomFichier, :typeFichier)';
		$req=$this->pdo->prepare($sql);
		if($req){
			$req->bindValue(':tab','articles',PDO::PARAM_STR);
			$req->bindParam(':idArt',$get['art'],PDO::PARAM_INT);
			$req->bindParam(':nomDoc',$_FILES['fileToUpload']['name'],PDO::PARAM_STR);
			$req->bindValue(':nomFichier',$nomDoc,PDO::PARAM_STR);
			$req->bindValue(':typeFichier',$get['type'],PDO::PARAM_STR);
			$req->execute();
		}
}

}
?>