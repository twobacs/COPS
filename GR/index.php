<?php
 ini_set("display_errors", 1);
 error_reporting(-1);
 session_start();
 ini_set('session.gc_maxlifetime', 3600); 
 include_once('/var/lib/webapp/v0.12.02.09/webapp.class.php');
 // print_r($_SESSION);
 $webApp = new WebApp($_REQUEST);
 $webApp->websitePath = realpath('.');
 $webApp->execute();
 $webApp->loadTemplate();
?>