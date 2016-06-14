<?php

if(isset($_GET['idArt'])){
	include ('../autoload.php');
	$art = new Articles($pdo);
	$data=$art->recordModifsArtById($_GET);
	echo $data;
}

?>