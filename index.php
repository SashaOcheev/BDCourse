<?php

require_once('dblib.php');
require_once('debug.php');
require_once('dbutility.php');

$t = new FillPress();
//$t->fill();

$DB = new Press();
var_dump($DB->getRawAndSupplyCost(1));