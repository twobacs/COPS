<?php

class Grades{
public $denomination;
private $pdo;

public function __construct($dbpdo){
	$this->pdo=$dbpdo;
}

public function getGrades(){
	$sql='SELECT id,denomination_grade FROM grades ORDER BY denomination_grade';
	return $this->pdo->query($sql);
}
}

?>