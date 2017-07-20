<?php

$idLoc=\filter_input(INPUT_GET, 'idLoc');
if(isset($idLoc)){
    include('../autoload.php');
    $sql='SELECT denomination, commentaire FROM local WHERE id=:idLoc';
    $req=$pdo->prepare($sql);
    $req->bindValue('idLoc',$idLoc,PDO::PARAM_INT);
    $req->execute();
    while($row=$req->fetch()){
        $html='<input class="form-control" type="text" name="editingDenomLcl'.$idLoc.'" id="editingDenomLcl'.$idLoc.'" placeholder="'.$row['denomination'].'" value="'.$row['denomination'].'" autofocus>'
                . '<input class="form-control" type="text" name="editingComLcl'.$idLoc.'" id="editingComLcl'.$idLoc.'" placeholder="'.$row['commentaire'].'">'
                . '<input class="form-control" type="button" value="Enregistrer modifications" onclick="recordModifLcl(\''.$idLoc.'\');">';
    }
    echo $html;
}
