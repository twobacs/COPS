<?php

class CLive extends CBase {

    public function __construct($appli) {
        parent::__construct($appli);
    }

    
public function verifCo()  //fonction de vérification de l'existence de la variable de session iduser
{
if (isset($_COOKIE["iduser"]) && $_COOKIE["iduser"]>'')
	{
	return true;
	}
}
public function test()
{
	$this->appli->tplIndex = 'indexLive.html';
}

public function seeMessages()
{
	if(isset($_GET['setRead']))
	{
		$this->model->setRead($_GET['setRead']);
	}
	$this->appli->tplIndex="pop.html";
	$mInfos=$this->model->getMessagesUnread();
	$this->view->showUnreads($mInfos);
}

}
?>