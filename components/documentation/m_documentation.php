<?php

class MDocumentation extends MBase {

	private $checkDbPDO = false;

	public function __construct($appli) {
		parent::__construct($appli);
		
	}
	
public function getNivAcces(){
	$sql='SELECT id_nivAcces FROM z_user_app WHERE id_user="'.$_COOKIE['iduser'].'" AND id_app="10"';
	$rep=$this->appli->dbPdo->query($sql);
	while ($row=$rep->fetch())
		{
		$nivAcces=$row['id_nivAcces'];
		}
	return $nivAcces;
	}
public function old_getDocs($root = 'docroom') {

  $files  = array('files'=>array(), 'dirs'=>array()); 
  $directories  = array(); 
  $last_letter  = $root[strlen($root)-1]; 
  $root  = ($last_letter == '\\' || $last_letter == '/') ? $root : $root.DIRECTORY_SEPARATOR; 
  
  $directories[]  = $root; 
  
  while (sizeof($directories)) { 
    $dir  = array_pop($directories); 
    if ($handle = opendir($dir)) { 
      while (false !== ($file = readdir($handle))) { 
        if ($file == '.' || $file == '..') { 
          continue; 
        } 
        $file  = $dir.$file; 
        if (is_dir($file)) { 
          $directory_path = $file.DIRECTORY_SEPARATOR; 
          array_push($directories, $directory_path); 
          $files['dirs'][]  = $directory_path; 
        } elseif (is_file($file)) { 
          $files['files'][]  = $file; 
        } 
      } 
      closedir($handle); 
    } 
  } 
  
  return($files); 
}

public function getDocs(){
    $sql='SELECT a.id_doc, a.nomDoc, a.actif,'
            . 'b.nomContainer '
            . 'FROM documentation a '
            . 'LEFT JOIN container_docu b '
            . 'ON b.id_container=a.id_container '
            . 'ORDER BY b.nomContainer, a.nomDoc';
    return $this->dbPdo->query($sql)->fetchall();
}

public function getCategFiles() {
    $sql='SELECT id_container, nomContainer FROM container_docu ORDER BY nomContainer';
    return $this->dbPdo->query($sql)->fetchall();
}


}
?>
