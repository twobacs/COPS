<?php

$newCateg=\filter_input(INPUT_GET, 'newCateg');
if(isset($newCateg)){
    include('../autoload.php');
    $sql='INSERT INTO type_mobilier (denomination) VALUES (:newDen)';
    $req=$pdo->prepare($sql);
    $req->bindValue('newDen',$newCateg,PDO::PARAM_STR);
    $req->execute();
    echo $req->rowCount();
}

