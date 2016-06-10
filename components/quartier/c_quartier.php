<?php

class CQuartier extends CBase {

    public function __construct($appli) {
        parent::__construct($appli);
    }

    
public function verifCo()  //fonction de vérification de l'existence de la variable de session iduser
	{
	if (isset($_COOKIE['iduser']) && $_COOKIE['iduser']>'')
		{
		return true;
		}
	}

public function mainMenu()
	{
	$co=$this->verifCo();
	if ($co)
		{
		$level=$this->model->checkLevel();
		$this->view->mainMenu($level);
		}
	}
	
public function ajouter()
	{
	$co=$this->verifCo();
	if ($co)
		{
		$level=$this->model->checkLevel();
		if ($level<30)
			{
			$this->view->nonDroit();				
			}
		else
			{
			if (isset($_GET['type'])){
				$type=$_GET['type'];
				if ($type=='agent'){
					$agent=$this->model->getInfos('agent');
					$quartier=$this->model->getInfos('quartier');
					$this->view->ajouter($type,'',$agent,$quartier);
					}
				else{
					$rues=$this->model->getRues();
					$agent=$this->model->getInfos('agent');
					$this->view->ajouter($type,$rues,$agent);
					}
				}
			}
			
		}
	else $this->view->nonco();
	}
	
public function modifier()
	{
	$co=$this->verifCo();
	if ($co)
		{
		$level=$this->model->checkLevel();
		if ($level<30)
			{
			$this->view->nonDroit();				
			}
		else
			{
			if (isset($_GET['type']))			
				{
				$type=$_GET['type'];
				$data=$this->model->getInfos($type);
				$rues=$this->model->getRues();
				$this->view->modifier($type,$data,$rues);
				}
			}
		}
	}
	
public function afficher(){
	$co=$this->verifCo();
	if ($co){
		$level=$this->model->checkLevel();
		if ($level<30){
			$this->view->nonDroit();
			}
		else{
			if (isset($_GET['type'])){
				$type=$_GET['type'];
				$data=$this->model->getInfos($type);
				$this->view->afficheInfos($type,$data);
				}
			}
		}
	}
	
public function assocrq(){
	$co=$this->verifCo();
	if ($co){
		$level=$this->model->checkLevel();
		if ($level<30){
			$this->view->nonDroit();
			}
		else{
			$rues=$this->model->getRues();
			$quartiers=$this->model->getInfos('quartier');
			$this->view->assocrq($rues,$quartiers);
			}
		}
	}
	
public function assocqa(){
	$co=$this->verifCo();
	if ($co){
			$level=$this->model->checkLevel();
			if ($level<30){
				$this->view->nonDroit();
				}
			else{
				$antennes=$this->model->getInfos('antennes');
				$quartiers=$this->model->getInfos('quartier');
				$this->view->assocqa($antennes,$quartiers);
				}
		}
	}
	
public function search(){
	$co=$this->verifCo();
	if ($co){
			$level=$this->model->checkLevel();
			if ($level<30){
				$this->view->nonDroit();
				}
			else{
			$type=$_GET['type'];
			$rues=$this->model->getRues();
			$agents=$this->model->getInfos('agent');
			$antennes=$this->model->getInfos('antennes');
			$quartiers=$this->model->getInfos('quartier');
			$this->view->formSearch($type,$rues,$agents,$antennes,$quartiers);
			}
		}
	}
}
?>