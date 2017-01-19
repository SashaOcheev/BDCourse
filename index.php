<?php

require_once('dblib.php');
require_once('dbutility.php');

$t = new FillPress();
//$t->fill();

$DB = new PressForEconomist();
echo "economist info:<br>";
echo 'raw and supply cost: ';
echo $DB->getRawAndSupplyCost(1);
echo '<br>';
echo 'work cost: ';
echo $DB->getWorkCost(1);
echo '<br>';
echo 'distribution price: ';
echo $DB->getDistributionPrice(1);
echo '<br>';
echo 'without margin: ';
echo $DB->getOrderCostWithoutMargin(1);
echo '<br>';
echo 'margin: ';
echo $DB->getMargin(1);
echo '<br>';
echo 'cost: ';
echo $DB->getOrderCost(1);
echo '<br>';

echo '<br>';
$client = new PressForClient;
echo 'client info:<br>';
echo 'cost: <br>';
echo $client->getOrderCost(1);
