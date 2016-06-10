<?php

class CVacancier extends CBase {

    public function __construct($appli) {
        parent::__construct($appli);
    }

	
public function verifCo()
	{
	if (isset($_COOKIE['iduser']) && $_COOKIE['iduser']>'')
		{
		return true;
		}
	else 
		{
		$this->view->nonco();
		return false;
		}
	}
	
public function mainMenu($from='') // -> Affichage du menu avec les options disponibles selon le niveau d'accès de l'utilisateur connnecté
	{
	$level=$this->model->getNivAcces();
	$this->view->showMenu($level,$from);
	$_SESSION['appli']='vacancier';
	$_SESSION['nivApp']=$level;
	}
	
public function addHab() // -> Création d'une nouvelle entrée en bdd si formulaire complété, sinon, affichage du formulaire d'encodage
	{
	$co=$this->verifCo();
	if ($co)
		{
		if (isset($_GET['etape']))
			{
			if ($_GET['etape']=='1') //reception des donnes deja verifiees si existantes en bdd via vacancier.js (checkNewDemandeur)
				{
				if (isset($_POST['idDem'])) //reception de l'id d'une personne existant deja en bdd
					{
					$idDem=$_POST['idDem'];
					$this->model->updateDem($idDem);
					}
				
				else  //personne inexistante en bdd
					{
					$idDem=$this->model->addDemandeur();
					}
				$dem=$this->model->getInfoDemById($idDem);
				$hab=$this->model->getInfoVacByIdDem($idDem);
				$this->view->formAddHabS2($dem,$hab,$idDem);
				}
				else if ($_GET['etape']=="2") //"Enregistrement des données de formulaire nouvelle surveillance";
					{
					$this->model->insertNewVac(); 
					$this->mainMenu('addVac');
					}
			
			}
		else $this->view->formAddHabS1();
		}
	}
	
public function listEnCours()
	{
	$co=$this->verifCo();
	if ($co)
		{
		$level=$this->model->getNivAcces();
		$data=$this->model->getListCurrentVac();
		$this->view->listVac($data,$level);
		}
	}
	
public function editVac($id=0)
	{
	$co=$this->verifCo();
	if (($co) && ($this->model->getNivAcces()>9))
		{
		if ((isset($_GET['id'])) OR ($id!=0))
			{
			$vac=(isset($_GET['id'])) ? $_GET['id'] : $id;
							
			if ((isset($_GET['modif']))&&(isset($_GET['part'])))
				{
				switch ($_GET['part'])
					{
					case 'h':
						$this->model->updateHouse($vac);
						break;
					case 'vv':
						$this->model->updateVV($vac);
						break;
					case 'contact':
						$this->model->updateContact($vac);
						break;
					case 'dem':
						$this->model->updateDemFull($vac);
						break;
					}
				}
			$data=$this->model->getInfoVacByIdVac($vac);
			$this->view->editVac($data,$vac,'edit');
			}
		else 
			{
			$this->view->listVac($this->model->getListAllVac(),$this->model->getNivAcces(),'edit');			
			}
		}
	}
	
public function searchVac()
	{
	$co=$this->verifCo();
	if ($co)
		{
		if (isset($_GET['type']))
			{
			$data=$this->model->searchVac($_GET['type']);
			$this->view->afficheResult($data);
			}
		}
	}
	
public function delVac()
	{
	$this->model->delVacById($_GET['id']);
	$this->view->listVac($this->model->getListAllVac(),$this->model->getNivAcces(),'edit');
	}
	
public function indicateurs()
	{
	$co=$this->verifCo();
	if (($co) && ($this->model->getNivAcces()>9))
		{
		if((isset($_GET['search'])) && ($_GET['search']=='1'))
			{
			$data=$this->model->getIndic($_POST['annee']);
			$this->view->showIndic($_POST['annee'],$data);
			}
		else
			{
			$this->view->menuIndicateurs();	
			}
		}
	}
	
public function selectCR()
	{
	$co=$this->verifCo();
	$level=$this->model->getNivAcces();
	if (($co) && ($level=='20') || ($level=='30') || ($level=='50'))
		{
		$data=$this->model->getVacFinished();
		$this->view->showListCRToDo($data);
		}
	else $this->view->nonco();
	}
	
public function doCR()
	{
	$co=$this->verifCo();
	$level=$this->model->getNivAcces();
	if (($co) && ($level=='20') || ($level=='30') || ($level=='50'))
		{
		$data=$this->model->getInfoCRById($_GET['idVac']);
		$this->view->doCR($data);	
		
		//$this->model->doCR($_GET['idVac']);
		}
	}

public function search()
	{
	$co = $this->verifCo();
	if (($co) && ($this->model->getNivAcces()>9))		
		{
		if (!isset($_GET['type']))
			{
			$this->view->formSearchVacancier();
			}
		else
			{
			switch($_GET['type'])
				{
				case 'rue' :
					$data=$this->model->getInfoVacByIdRue($_GET['key']);
					$this->view->afficheResult($data);
					break;
				case 'pers' :
					$data=$this->model->getInfoVacByIdDemandeur($_GET['key']);
					$this->view->afficheResult($data);
					break;
				}
			}
		}
	
	}
	
public function infoVac()
	{
	$co = $this->verifCo();
	if (($co) && ($this->model->getNivAcces()>4))		
		{	
		$data=$this->model->getInfoVacByIdVac($_GET['idVac']);
		$this->view->ShowVac($data);
		}
	}
	
public function addVV()
	{
	$co = $this->verifCo();
	if (($co) && ($this->model->getNivAcces()>9))		
		{	
		$this->model->addVVById();
		$this->editVac($_GET['id']);
		}
	}
	
public function addPers()
	{
	$co = $this->verifCo();
	if (($co) && ($this->model->getNivAcces()>9))		
		{
		$this->model->addPersById();
		$this->editVac($_GET['id']);
		}
	}
	
public function vacSend()
	{
	$co = $this->verifCo();
	if (($co) && ($this->model->getNivAcces()>9))		
		{
		$this->model->vacSend();	
		$this->selectCR();
		}
	}
	
public function verifVac(){
	$co = $this->verifCo();
	if (($co) && ($this->model->getNivAcces()>9))		
		{	
		$data=$this->model->verifVac();
		$this->view->showPassages($data);
		}
}

public function delRowById(){
	$co = $this->verifCo();
	if (($co) && ($this->model->getNivAcces()>9))		
		{
		$this->model->delRowById();
		$this->verifVac();
		}
}

public function addRowToCr(){
	$co = $this->verifCo();
	if (($co) && ($this->model->getNivAcces()>9))		
		{	
		$this->model->addRowToCr();
		$this->verifVac();
		}
}
	
}
?>
