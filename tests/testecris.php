<?php
require_once("../include/class.pdogsb.inc.php");
include_once("../include/fct.inc.php");
$lePdo = PdoGsb::getPdoGsb();
$donne=$lePdo->getport(1);
ecrisData($donne,1,'../dossier/');