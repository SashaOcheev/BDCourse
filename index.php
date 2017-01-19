<?php

require_once('dblib.php');
require_once('debug.php');
require_once('dbutility.php');

$t = new FillPress();
//$t->fill();

$DB = new PressForEconomist();
echo $DB->getRawAndSupplyCost(1);
echo '<br>';
echo $DB->getWorkCost(1);
echo '<br>';