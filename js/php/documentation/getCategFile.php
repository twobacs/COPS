<?php

include('../connect.php');
 $sql='SELECT id_container, nomContainer FROM container_docu ORDER BY nomContainer';
 $categ=$pdo->query($sql)->fetchall();
 $html='<h3>Ajouter un document sur le serveur</h3>';
 $options='<SELECT name="categFile" id="categFile" required><option selected disabled></option>';
 foreach ($categ as $key => $row){
   $options.='<option value="'.$row['id_container'].'">'.$row['nomContainer'].'</option>';   
 }
 $options.='</option>';
 
 
 
 $html.=$options;
 echo $html;