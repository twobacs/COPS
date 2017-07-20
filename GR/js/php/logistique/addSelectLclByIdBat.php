<?php

$idBat=\filter_input(INPUT_GET, 'idBat');

if(isset($idBat)){
    include('../autoload.php');
    $sql='SELECT id, denomination, commentaire FROM local WHERE id_niveau=:idBat';
    $req=$pdo->prepare($sql);
    $req->bindValue('idBat',$idBat,PDO::PARAM_INT);
    $req->execute();
    $html='<div class="input-group" style="width:650px;">'
            . '<span class="input-group-addon" style="min-width:200px;">Local</span>'
            . '<select required class="form-control" name="selectedLclNewMob" id="selectedLclNewMob"><option value="-1"></option>';
    while($row=$req->fetch()){
        $html.='<option value="'.$row['id'].'">'.$row['denomination'].'</option>';
    }
    $html.='</select>'
            . '</div>';
 echo $html;           
}

