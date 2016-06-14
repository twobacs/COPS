<?php

class Services{
public $id;
public $denomination;
private $pdo;

public function __construct($dbpdo){
	$this->pdo=$dbpdo;
}

public function getServices(){
	$sql='SELECT id_service, denomination_service FROM services ORDER BY denomination_service';
	return $this->pdo->query($sql);
}
	
}

?>