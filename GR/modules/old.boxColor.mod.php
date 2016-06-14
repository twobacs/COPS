<?php
if(isset($_SESSION['idUser'])){
	$html='<form method="POST" action="?component=user&action=changeColor&fromC='.$_GET['component'].'&fromA='.$_GET['action'].'">';
	$html.='Couleur du site :<br />';
	$html.='<input type="color" name="textcolor"><input type="submit" id="bEnregistrer" name="bChoix" value="Choisir">';
	$html.='</form>';
	$this->boxColor=$html;
	}

?>