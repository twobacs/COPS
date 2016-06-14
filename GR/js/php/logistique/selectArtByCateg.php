<?php

if(isset($_GET['id'])){
	include ('../autoload.php');
	$art=new Articles($pdo);
	$categories=$art->getCategories();
	if ($_GET['why']==0){
		$articles=$art->getArticlesByIdCateg($_GET['id']);
		$html='<hr><h4>Modification d\'articles avant suppression cat&eacute;gorie</h4>';
		$html.='<table><td><input type="button" value="Fermer ce menu" onclick="slide2(\'msgSlide2\');"></td></table>';
		$html.='<table>';
		$html.='<tr><th>Article</th><th>Commentaire</th><th>Cat&eacute;gorie actuelle</th><th colspan="2" style="width:25%;">Nouvelle cat&eacute;gorie</th></tr>';
		while($row=$articles->fetch()){
			$html.='<tr><td>'.ucfirst($row['denomination']).'</td><td>'.ucfirst($row['commentaire']).'</td><td>'.ucfirst($row['categorie']).'</td><td><select name="newCateg" id="newCategArt_'.$row['id_article'].'" onchange="updateCategByIdArt(\''.$row['id_article'].'\');"><option></option>';
			foreach ($categories as $key => $rowCat){
				if($rowCat['id_categorie']!=$_GET['id']){
					$html.='<option value="'.$rowCat['id_categorie'].'">'.ucfirst($rowCat['denomination']).'</option>';
				}
			}
			$html.='</select></td><td id="confirmSpace_'.$row['id_article'].'"></td></tr>';
		}
		$html.='</table>';
	}
	else if($_GET['why']==1){
		$articles=$art->getArticlesByIdCateg($_GET['id']);
	 	$html='<div class="form-inline">';
		$html.='<div class="form-group" style="text-align:center;">';
		while ($row=$articles->fetch()){
			if($row['actif']=='O'){
				$html.='
				<div class="input-group">
					<div class="input-group-addon" style="width:230px;">
						D&eacute;nomination :
					</div>
					<input type="text" style="width:200px;" class="form-control" id="denomArt" name="denomArt" placeholder="D&eacute;nomination article" value="'.$row['denomination'].'" readonly>
					<div class="input-group-addon">';
					if($row['stock']>'0'){
						$sql='SELECT quantite FROM lignePanier WHERE id_article=:art AND id_panier=:panier';
						$req=$pdo->prepare($sql);
						$req->bindValue('art',$row['id_article'],PDO::PARAM_INT);
						$req->bindValue('panier',$_GET['idPanier'],PDO::PARAM_INT);
						$req->execute();
						$count=$req->rowCount();
						if($count==0){
							$html.='<div id="cartArt'.$row['id_article'].'""><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true" title="Ajouter au panier" onclick="addToCart(\''.$row['id_article'].'\',\''.$_GET['idUser'].'\',\''.$_GET['idPanier'].'\',\'1\');"></span></div>';
						}
						else{
							$html.='<span class="glyphicon glyphicon-ok" aria-hidden="true" title="Ajout&eacute;"></span>';
						}
					}
					else{						
						$html.='
						<span class="glyphicon glyphicon-lock" aria-hidden="true" title="&Eacute;puis&eacute;" style="cursor:not-allowed"></span>';
					}
				$html.='
					</div>
				</div>
				<br />';
			}
		}
		$html.='</div>';
		$html.='</div>';
		}
	else if ($_GET['why']==2){
		$articles=$art->getInfosArticles(0,'O',$_GET['id']);
		$html='<div class="form-inline">';
		$html.='<div class="form-group" style="text-align:center;">';
		for ($i=0;$i<$articles['nbArts'];$i++){
			if($articles[$i]['actif']=='O'){
				$html.='
				<div class="input-group">
					<div class="input-group-addon" style="width:230px;">
						D&eacute;nomination :
					</div>
					<input type="text" style="width:200px;" class="form-control" id="denomArt" name="denomArt" placeholder="D&eacute;nomination article" value="'.$articles[$i]['denomination'].'" readonly>
					<div class="input-group-addon">';
					if($articles[$i]['stock']>'0'){
						$sql='SELECT quantite FROM lignePanier WHERE id_article=:art AND id_panier=:panier';
						$req=$pdo->prepare($sql);
						$req->bindValue('art',$articles[$i]['id_article'],PDO::PARAM_INT);
						$req->bindValue('panier',$_GET['idPanier'],PDO::PARAM_INT);
						$req->execute();
						$count=$req->rowCount();
						if($count==0){
							$html.='<div id="cartArt'.$articles[$i]['id_article'].'""><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true" title="Ajouter au panier" onclick="addToCart(\''.$articles[$i]['id_article'].'\',\''.$_GET['idUser'].'\',\''.$_GET['idPanier'].'\',\'2\');"></span></div>';
						}
						else{
							$html.='<span class="glyphicon glyphicon-ok" aria-hidden="true" title="Ajout&eacute;"></span>';
						}
					}
					else{						
						$html.='
						<span class="glyphicon glyphicon-lock" aria-hidden="true" title="&Eacute;puis&eacute;" style="cursor:not-allowed"></span>';
					}
				$html.='
					</div>
				</div>
				<br />';
			}
		}
		$html.='</div>';
		$html.='</div>';
		// print_r($articles);
	 }
	echo $html;
}
?>