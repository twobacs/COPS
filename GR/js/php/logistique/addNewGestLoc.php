<?php

$nBat=\filter_input(INPUT_GET, 'nBat');
$nLvl=\filter_input(INPUT_GET, 'nLvl');
$nLcl=\filter_input(INPUT_GET, 'nLcl');
$com=\filter_input(INPUT_GET, 'com');

if(isset($nBat)){
    //echo $nBat.' '.$nLvl;
    include('../autoload.php');
    if(($nLvl=='0')&&($nLcl=='0')){
        echo 'pouet';
        $sql='INSERT INTO local (denomination) VALUES (:den)';
        $req=$pdo->prepare($sql);
        $req->bindValue('den', ucfirst($nBat),PDO::PARAM_STR);
       // $req->execute();
        //echo $req->rowCount();
    }
    
    else if ($nLcl=='0'){
        echo 'niveau';
        $sql='INSERT INTO local (denomination, id_niveau) VALUES (:den, :idNiv)';
        $req=$pdo->prepare($sql);
        $req->bindValue('den', ucfirst($nLvl),PDO::PARAM_STR);
        $req->bindValue('idNiv', $nBat, PDO::PARAM_INT);
    }
    
    else{
        echo $nLvl.' yop ';
        $sql='INSERT INTO local (denomination, id_niveau, commentaire) VALUES (:den, :idNiv, :com)';
        $req=$pdo->prepare($sql);
        $req->bindValue('den', ucfirst($nLcl),PDO::PARAM_STR);
        $req->bindValue('idNiv', $nLvl, PDO::PARAM_INT);
        $req->bindValue('com', $com, PDO::PARAM_STR);
    }
//*/
    $req->execute();
    echo $req->rowCount();
}

?>

