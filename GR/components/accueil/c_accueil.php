<?php

class CAccueil extends CBase {

    public function __construct($appli) {
        parent::__construct($appli);
    }

public function homepage(){
	$this->view->formIdentification();
}
}
?>