<?php

class VText extends VBase {

    function __construct($appli, $model) {
        parent::__construct($appli, $model);
    }

    public function afficherText()
	{
	$this->appli->ctContent.=$this->model->txtData;
	}

}
?>