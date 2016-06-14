<?php

if(isset($_GET['idPanier'])){
	$html='';
	include ('../autoload.php');
	$cart=new Cart($pdo); 
	$data=$cart->getOrderDetailsById($_GET['idPanier']);
	$html='<div id="toPrint">';
	while($row=$data['demandeur']->fetch()){
		$html.='<h4>Commande du '.dateHrfr($row['date_envoi'],1).' introduite par '.ucfirst($row['prenom']).' '.ucfirst($row['nom']).'</h4>';
	}
	$html.='<div class="form-inline">';
	$i=0;
	while($row=$data['article']->fetch()){
		$html.='
		<div class="form-group">
			<div class="input-group-addon" style="width:140px;">D&eacute;nomination article</div>
			<div class="input-group-addon" style="width:350px;">'.$row['denomArticle'].'</div>
			<div class="input-group-addon" style="width:50px;">Quantit&eacute; demand&eacute;e</div>
			<div class="input-group-addon" style="width:50px;">'.$row['quantite'].'</div>
			<div class="input-group-addon" style="width:39px;cursor:pointer;" onclick="editRowOrder(\''.$i.'\',\''.$row['id_ligne'].'\');"><span class="glyphicon glyphicon-list" aria-hidden="true"></span></div>
		</div>
		<div id="editRowOrder'.$i.'"></div>
		';
		$i++;
	}
	$html.='</div>';
	$html.='
	</div>
	<div class="form-inline" id="bPrint" style="padding-top:15px">
		<a class="btn btn-default" href="#" role="button" onclick="window.print()">Imprimer cette commande</a>
	</div>';
	$html.='
	</div>
	<div class="form-inline" id="bPrint" style="padding-top:15px">
		<button type="button" class="btn btn-danger">Cl&ocirc;turer cette commande</button>
	</div>';	
	echo $html;
}

?>