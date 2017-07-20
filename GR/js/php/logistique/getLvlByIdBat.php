<?php
$nBat=\filter_input(INPUT_GET, 'idBat');
if(isset($nBat)){
    include ('../autoload.php');
    $sql='SELECT id, denomination FROM local WHERE id_niveau=:idBat';
    $req=$pdo->prepare($sql);
    $req->bindValue('idBat', $nBat, PDO::PARAM_INT);
    $req->execute();
    $html='<select class="form-control" name="idNivToNewLcl" id="idNivToNewLcl" onchange="addInputTextForNewLcl();"><option></option>';
    while ($row=$req->fetch()){
        $html.='<option value="'.$row['id'].'">'.$row['denomination'].'</option>';
    }
    $html.='</select>';
    echo $html;
}
//echo '<input type="text" value="yoyoyoyoyoyo">';
