<?php

if((isset($_SESSION['idUser']))&&(isset($_SESSION['appli']))){
	$html='';
	switch($_SESSION['appli']){
		case 'logistique':
			if($_SESSION['acces']>='8'){
				$sql='SELECT id_panier FROM panierPMB WHERE dateCloture=:dateC';
				$req=$this->dbPdo->prepare($sql);
				$req->bindValue('dateC','0000-00-00',PDO::PARAM_STR);
				// $req->bindValue('dateA','0000-00-00',PDO::PARAM_STR);
				$req->execute();
				$count=$req->rowCount();
				$html.='<p class="text-left"><a class="btn btn-default" href="?component=logistique&action=showNewOrders" role="button"  style="width:200px;">'.$count.' commande';
				$html.=($count>1) ? 's sont &agrave; tra&icirc;ter' : 'est &agrave; tra&icirc;ter';
				$html.='</a></p>';
			}
			break;
			
		default :
			$html.='';
			break;
			
	}
}
else $html='';
$this->alert=$html;
?>