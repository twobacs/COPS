<?php
if (isset($_SESSION['idUser'])){
	$html='<iframe src="index.php?component=live&action=test"></iframe>';
	$this->botCentre=$html;
}
?>