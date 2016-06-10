<?php

class CCops extends CBase {

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
	
public function mainMenu()
	{
	$level=$this->model->getNivAcces();
	$this->view->showMenu($level);
	$_SESSION['appli']='cops';
	$_SESSION['nivApp']=$level;
	}
	
public function newInfoCops()
	{
	$co=$this->verifCo();
	if ($co)
		{
		$level=$this->model->getNivAcces();
		if ($level>9)
			{
			if (!isset($_GET['step']))
				{
				$this->view->newInfoCops($this->model->getSection(),'',$this->model->getListInfosCops());
				}
			else if ($_GET['step']=='1')
				{
				$data=$this->model->editNewFiche();
				$this->view->newFicheStep2($data);
				}
			else if ($_GET['step']=='2')
				{
				$this->model->recMoreInfosFiche();
				$from='step2';
				$this->view->newInfoCops($this->model->getSection(),$from,$this->model->getListInfosCops());
				}
			}
		}
	}
	
public function listing()
	{
	$co=$this->verifCo();
	if ($co)
		{
		$level=$this->model->getNivAcces();
		if ($level>0)
			{
			$data=$this->model->getListInfosCops();
			$this->view->showListInfosCops($data);
			}
		}
	}
	
public function listHot(){ /* 15.09.2015 */
	$co=$this->verifCo();
	if ($co){
		$level=$this->model->getNivAcces();
		if ($level>0){
		$data=$this->model->getListInfosHot();
		$this->view->showListInfosCops($data,'hot');			
		}
	}
}	
	
public function moreInfos()
	{
	$co=$this->verifCo();
	if ($co)
		{
		$level=$this->model->getNivAcces();
		if ($level>0)
			{
			$data=$this->model->getInfoFicheById($_GET['idFiche']);
			$this->view->showInfoFiche($data);
			}
		}
	}
	
public function editInfoCops()
	{
	$co=$this->verifCo();
	// $level=$this->model->getNivAcces();
		// echo $level;
	if ($co)
		{
		$level=$this->model->getNivAcces();
		if ($level>9)
			{
			$data=$this->model->getListInfosCops();
			$this->view->editList($data);
			}
		}
	}
	
public function editFiche()
	{
	$co=$this->verifCo();
	if ($co)
		{
		$data=$this->model->getInfoFicheById($_GET['idFiche']);
		$section=$this->model->getSection();
		$categ=$this->model->getCateg();
		$implication=$this->model->getImplication();
		$rues=$this->model->getRues();
		$this->view->editFiche($data,$section,$categ,$implication,$rues);
		}
	}
	
public function addPers()
	{
	$co=$this->verifCo();
	if ($co)
		{
		$this->model->addPersonneByIdFiche($_GET['idFiche']);
		$this->editFiche();
		}
	}
	
public function addVV()
	{
	$co=$this->verifCo();
	if ($co)
		{
		$this->model->addVVByIdFiche($_GET['idFiche']);
		$this->editFiche();
		}
	}
	
public function addLD()
	{
	$co=$this->verifCo();
	if ($co)
		{
		$this->model->addLDByIdFiche($_GET['idFiche']);
		$this->editFiche();
		}	
	}
	
public function addCom()
	{
	$co=$this->verifCo();
	if ($co)
		{
		$this->model->addComByIdFiche($_GET['idFiche']);
		$this->editFiche();
		}	
	}
	
public function addTL()
	{
	$co=$this->verifCo();
	if ($co)
		{
		$this->model->addTLByIdFiche($_GET['idFiche']);
		$this->editFiche();
		}	
	}
	
public function addPic()
	{
	$co=$this->verifCo();
	if ($co)
		{
		$this->model->addPicByIdFiche($_GET['idFiche']);
		$this->editFiche();
		}	
	}
	
public function newFicheById()
	{
	$co=$this->verifCo();
	if ($co)
		{
		$level=$this->model->getNivAcces();
		if ($level>19)
			{
			$data=$this->model->getInfoFicheById($_GET['idFiche']);
			$section=$this->model->getSection();
			$categ=$this->model->getCateg();
			$implication=$this->model->getImplication();
			$rues=$this->model->getRues();
			$this->view->newInfoFicheById($data,$section,$categ,$implication,$rues);
			}
		}
	}
	
public function newInfoCopsWRel()
	{
	$this->model->newInfoCopsWRel();
	}
	
public function inputSearch(){
	$co=$this->verifCo();
	if ($co){
		$data=$this->model->searchInFiches($_POST['inputSearch']);
		$this->view->afficheResultSearch($data,$_POST['inputSearch']);	
		}
	}
	
public function gstMsgs(){ /* 15.09.2015 */
	$co=$this->verifCo();
	if ($co){
		$data=$this->model->getMsgs();
		$this->view->listMsgs($data);
	}
}

public function delMsgPush(){ /* 15.09.2015 */
	$co=$this->verifCo();
	if ($co){
		$this->model->delInfoPushById($_GET['id']);
		$this->gstMsgs();
	}
}
}
?>