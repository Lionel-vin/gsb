<?php

require_once ("../include/class.pdogsb.inc.php");
$test=PdoGsb::getPdoGsb();
echo($test->verifCode(7,725212));