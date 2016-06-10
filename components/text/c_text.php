<?php

class CText extends CBase {

    public function __construct($appli) {
        parent::__construct($appli);
    }

    
  public function afficher()
  {
   $this->appli->urlVars['txt']=$this->appli->defaultText;
   $this->model->loadText($this->appli->urlVars['txt']);
   $this->view->afficherText();
  }
  
  
  

}

?>