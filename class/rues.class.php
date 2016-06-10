<?php

class Rue
{
public $nom;
public $naam;
private $pdo;

public function __construct($dbPdo)
	{
	$this->pdo = $dbPdo;
	}
	
public function selectRues()
	{
	$sql='SELECT IdRue, NomRue, StraatNaam FROM z_rues ORDER BY NomRue';
	$rep=$this->pdo->query($sql);
	return $rep;
	}
	
public function selectRueById($id)
	{
	$sql='SELECT IdRue, NomRue, StraatNaam FROM z_rues WHERE IdRue="'.$id.'"';
	$rep=$this->pdo->query($sql);
	return $rep;
	}
	
}

?>