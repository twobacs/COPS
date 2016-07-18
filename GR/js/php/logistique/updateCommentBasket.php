<?php

if(isset($_GET['basket'])){
	include ('../autoload.php');
	$cart = new Cart ($pdo);
	$verif=$cart->updateCommentBasket($_GET['basket'],htmltosql($_GET['newComment']));
}

?>