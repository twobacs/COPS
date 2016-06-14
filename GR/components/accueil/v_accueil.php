<?php

class VAccueil extends VBase {

    function __construct($appli, $model) {
        parent::__construct($appli, $model);
    }
	
public function formIdentification(){
	$this->appli->content="";
}

}
?>