<?php
require_once ("../include/class.pdogsb.inc.php");
include_once("../include/fct.inc.php");
$test=PdoGsb::getPdoGsb();

echo($test->addCode(7,genererCode()));