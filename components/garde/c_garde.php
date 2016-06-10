<?php

class CGarde extends CBase {

    public function __construct($appli) {
        parent::__construct($appli);
    }

	public function verifCo(){
	if (isset($_COOKIE['iduser']) && ($_COOKIE['iduser']>"")){
		return true;
		}
	else{
		$this->view->nonco();
		return false;
		}
	}

	public function mainMenu($message=0){
		$co=$this->verifco();
		if ($co){
			if((!isset($_REQUEST['action'])) OR ($_REQUEST['action']=='getList')){
				$this->getList();
			}
			else{
				if($this->model->getNivAcces()>19){
					$types=$this->model->getTypesGardes();
					$gardes=$this->model->getGardesNow();
					$this->view->manageGardes($types,$message,$gardes);
					// $gardes=$this->model->getGardesNow('O');
					// $this->view->showGardesNow($gardes);					
				}
				else{
					$gardes=$this->model->getGardesNow('O');
					$this->view->showGardesNow($gardes);
				}
			}
			
		}
	}
	
	public function getList(){
		$co=$this->verifCo();
		if($co){
			$data=$this->model->getList();	
			$this->view->showList($data);			
		}
	}
	
	public function ajoutTypeGarde(){
		$co=$this->verifCo();
		if($co){
			$return=$this->model->ajoutTypeGarde();
			$this->mainMenu($return);
		}
	}
	
	public function addGarde(){
		$co=$this->verifCo();
		if($co){
			if($this->model->getNivAcces()>19){
				$data=$this->model->getInfoGardeById();
				$this->view->formAddPersToGarde($data);
				}
			}
	}

public function addPersToGarde(){
	$co=$this->verifCo();
	if($co){
		if($this->model->getNivAcces()>19){
			$this->model->addPersToGarde();
			$this->mainMenu();
			}
		}
	}
	
public function modifPersGarde(){
	$co=$this->verifCo();
	if($co){
		$garde=$this->model->getInfosGarde();
		if($_GET['selected']=='-1'){
			$pers=$this->model->getPersGardesExt();
		}
		else $pers=$this->model->getUsers();
		$this->view->modifGarde($garde, $pers);
	}
}

public function majGarde(){
	$co=$this->verifCo();
	if($co){	
		$this->model->majGarde();
		// header('location: GR/index.php?component=accueil&action=homepage');	
		header("location: index.php?component=garde&action=mainMenu");
	}
}
}
?>