<?php

class MText extends MBase {

	private $checkDbPDO = false;

	public function __construct($appli) {
		parent::__construct($appli);
		
	}

	public function loadText($text)
	{
	$this->txtData=file_get_contents('articles/'.$text.'.txt');
	}

}
?>