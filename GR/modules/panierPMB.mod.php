<?php

if((isset($_SESSION['idUser']))&&(isset($_SESSION['appli']))&&($_SESSION['appli']=='logistique')){
	$sql='SELECT id_panier FROM panierPMB WHERE id_user=:user AND date_envoi=:date';
	$req=$this->dbPdo->prepare($sql);
	$req->bindValue('user',$_SESSION['idUser'],PDO::PARAM_STR);
	$req->bindValue('date','0000-00-00',PDO::PARAM_STR);
	$req->execute();
	$count=$req->rowCount();
	if($count==0){
		$_SESSION['panierPMB']=-1;
	}
	else{
		while($row=$req->fetch()){
		$_SESSION['panierPMB']=$row['id_panier'];
		$sql1='SELECT id_ligne FROM lignePanier WHERE id_panier=:panier';
		$req1=$this->dbPdo->prepare($sql1);
		$req1->bindValue('panier',$row['id_panier'],PDO::PARAM_INT);
		$req1->execute();
		$count=$req1->rowCount();
		$this->panierPMB='<p class="text-left"><a class="btn btn-default" href="?component=logistique&action=gestPanierPMB" role="button" style="width:250px;">Vous avez '.$count.' article';
		$this->panierPMB.=($count>1) ? 's' : '';
		$this->panierPMB.=' dans votre panier</a></p>';
		}
	}
}

?>