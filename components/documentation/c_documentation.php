<?php

class CDocumentation extends CBase {

    public function __construct($appli) {
        parent::__construct($appli);
    }

public function verifCo()
{
	if (isset($_COOKIE['iduser']) && ($_COOKIE['iduser']>""))
	{
		return true;
	}
	else
	{
		$this->view->nonco();
		return false;
	}
}

public function mainmenu()
{
	$co=$this->verifco();
	if ($co)
	{
		$this->view->mainMenu();
	}
	else $this->view->nonCo();
}

public function gestDoc(){
    $co=$this->verifCo();
    if($co){
        $files=$this->model->getDocs();
        $this->view->showFiles($files);
    }
}

public function addFile(){
    $co=$this->verifCo();
    if($co){
        $categ=$this->model->getCategFiles();
        $this->view->menuAddFile($categ);
    }
}


}
?>