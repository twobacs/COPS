<?php

if(isset($_GET['art'])){
	include ('../autoload.php');
	$html='<div class="horizontal-form" style="padding-top:5px;padding-bottom:5px;">';
	$panier=new Panier($pdo);
	$data=$panier->getInfosLignePanier($_GET['art']);
	while($row=$data->fetch()){
		$html.='
		<div class="form-group">
			<div class="input-group-addon" style="width:200px;">Quantit&eacute; livr&eacute;e</div>
			<div class="input-group-addon" style="width:25px;cursor:pointer;" onclick="modifQLivree(\''.$_GET['art'].'\',\'plus\');"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></div>
			<div class="input-group-addon" style="width:61px;" name="qLivree_'.$_GET['art'].'" id="qLivree_'.$_GET['art'].'">'.$row['quantite_livree'].'</div>
			<div class="input-group-addon" style="width:25px;cursor:pointer;"  onclick="modifQLivree(\''.$_GET['art'].'\',\'moins\');"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></div>
		</div><br />
		<div class="form-group">
			<div class="input-group-addon" style="width:200px;">Date livraison</div>
			<div class="input-group-addon" style="width:140px;">';
			$html.=($row['date_livraison']=='0000-00-00') ? '<input type="date" class="form-control" name="dateLiv_'.$_GET['art'].'" id="dateLiv_'.$_GET['art'].'" title="Enregistrement auto" onchange="updateLignePanier_Date(\''.$_GET['art'].'\');">' : dateHrfr($row['date_livraison'],1);
			$html.='</div>
		</div><br />
		<div class="form-group">
			<div class="input-group-addon" style="width:150px;">Par</div>
			<div class="input-group-addon" style="width:190px;">';
			if($row['idLog_livraison']!=0){
				$html.=ucfirst($row['nom']).' '.ucfirst($row['prenom']);
			}
			else{
				$users = new Users($pdo);
				$logisticiens = $users->getLogisticiens();
				$html.='<select name="newLog_'.$_GET['art'].'" id="newLog_'.$_GET['art'].'" onchange="updateLignePanier_Log(\''.$_GET['art'].'\');" title="Enregistrement auto"><option disabled selected>S&eacute;lectionner</option>';
				while($log=$logisticiens->fetch()){
					$html.='<option value="'.$log['id_user'].'">'.$log['nom'].' '.$log['prenom'].'</option>';
				}
				$html.='</select>';
			}
			$html.='</div>
		</div><br />
		<div class="form-group">
			<div class="input-group-addon" style="width:340px;">
			<input type="text" class="form-control" id="ComLog'.$_GET['art'].'" style="width:300px;" ';
			if($row['com_logistique']==''){
				$html.='placeHolder="Commentaire"';
			}
			else{
				$html.='value="'.$row['com_logistique'].'"';
			}
			
			$html.=' onkeyup="updateComLogRowPanier(\''.$_GET['art'].'\');"></div>
		</div><br />
		<div class="form-group">			
			<div class="input-group-addon" style="width:340px;cursor:pointer;" onclick="closeRow(\''.$_GET['i'].'\');">Fermer</div>
		</div>
		';
	}
	$html.='</div>';
	echo $html;
}
?>