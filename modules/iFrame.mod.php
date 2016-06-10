<?php
$html='<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <link rel="shortcut icon" href="http://www.polfed-fedpol.be/favicon.ico" type="image/icon"/>
  <title>COPS - Police Mouscron</title>
  <link rel="stylesheet" href="templates/mytpl/css/style.css">
  <script type="text/javascript" src="./js/zoom5317.js"></script>
  <script type="text/javascript" src="./js/jquery.min.js"></script>
  </head>
 <body>';
 $html.= (isset($this->appli->latestNews)) ? $this->appli->latestNews : ''; 
 $html.='</body></html>';
 $this->iFrame=$html;

?>