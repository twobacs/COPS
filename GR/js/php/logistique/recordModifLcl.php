<?php

$idLoc=\filter_input(INPUT_GET, 'idLoc');
$nDenom=\filter_input(INPUT_GET, 'nDenom');
$nCom=\filter_input(INPUT_GET, 'nCom');

if(isset($idLoc)){
    include ('../autoload.php');
    $sql='UPDATE local SET denomination=:nDenom, commentaire=:nCom WHERE id=:idLoc';
    $req=$pdo->prepare($sql);
    $req->bindValue('nDenom',$nDenom,PDO::PARAM_STR);
    $req->bindValue('nCom',$nCom,PDO::PARAM_STR);
    $req->bindValue('idLoc',$idLoc,PDO::PARAM_STR);
    $req->execute();
    echo $req->rowCount();
}
