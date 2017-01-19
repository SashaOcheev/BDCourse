<?php

require_once 'login.php';
require_once 'medoo.php';
require_once 'dbcomponents.php';

class Press {
    public function __construct() {
        global $db_database, $db_hostname, $db_password, $db_username;
        $this->DB = new medoo(['database_type' => 'mysql',
        	'database_name' => $db_database,
        	'server' => $db_hostname,
        	'username' => $db_username,
        	'password' => $db_password,
            'charset' => 'utf8']);
            
         $this->setComponents();
    }
    
    
    protected function setComponents() {
        $this->action_time = new ActionTime($this->DB);
        $this->client = new Client($this->DB);
        $this->client_card = new ClientCard($this->DB);
        $this->client_status = new ClientStatus($this->DB);
        $this->discount = new Discount($this->DB);	 
        $this->discount_level = new DiscountLevel($this->DB);
        $this->distribution = new Distribution($this->DB);
        $this->distributor = new Distributor($this->DB);
        $this->edition_discount = new EditionDiscount($this->DB);
        $this->edition_discount_info = new EditionDiscountInfo($this->DB);
        $this->margin = new Margin($this->DB);	
        $this->orders = new Orders($this->DB);	
        $this->product = new Product($this->DB);	
        $this->production = new Production($this->DB);
        $this->product_raw = new ProductRaw($this->DB);
        $this->raw = new Raw($this->DB);	  	 
        $this->supplier = new Supplier($this->DB);
        $this->supplies = new Supplies($this->DB);
        $this->work_type = new WorkType($this->DB);
    }
    
    public function insertSupplies($raw_id, $product_id, $price) {
        return $this->supplies->insert($this->raw, $raw_id, $this->supplier, $product_id, $price);
    }
    
    public function insertProductRaw($product_id, $raw_id, $quantity) {
        return $this->product_raw->insert($this->product, $product_id, $this->raw, $raw_id, $quantity);
    }
    
    public function insertProduction($product_id, $work_type_id, $spend_time) {
        $this->production->insert($this->product, $product_id, $this->work_type, $work_type_id, $spend_time);
    }
    
    public function insertMargin($product_id, $margin) {
        $this->margin->insert($this->product, $product_id, $margin);
    }
    
    public function insertDistribution($product_id, $distributor_id, $price) {
        $this->distribution->insert($this->product, $product_id, $this->distributor, $distributor_id, $price);
    }
    
    public function insertEditionDiscount($product_id, $values) {
        $edition_discount_info = $this->edition_discount_info->select();
        $count = count($values);
        if ($count != count($edition_discount_info)) {
            return false;
        }
        
        foreach($values as $value) {
            if ($value > 1.00 || $value <= 0.00) {
                return false;
            }
        }
        
        for ($i = 0; $i < $count; ++$i) {
            $this->edition_discount->insert(
                $this->product,
                $product_id,
                $this->edition_discount_info,
                $edition_discount_info[$i]['id'],
                $values[$i]
            );
        }
    }
    
    public function insertClientCard($client_id, $client_status_id) {
        $this->client_card->insert($this->client, $client_id, $this->client_status, $client_status_id);
    }
    
    public function insertOrder($product_id, $client_id, $distribution_id, $edition) {
        $this->orders->insert(
            $this->product,
            $client_id,
            $this->client,
            $client_id,
            $this->distribution,
            $distribution_id,
            $this->edition_discount_info,
            $edition
        );
    }
    
    public function insertDiscount($product_id, $values) {
        $count = count($this->discount_level->select());
        if ($count != count($values)) {
            return false;
        }
        
        $i = 0;
        foreach ($values as $value) {
            $this->discount->insert($this->product, $product_id, $this->discount_level, $i + 1, $value);
            ++$i;
        }
    }
    
    protected function getWorkCost($product_id) {
        $costs = [];
        $productions = $this->production->selectByProductId($product_id);
        if (empty($productions)) {
            return false;
        }
        
        foreach ($productions as &$production) {
            $costs[$production['work_type_id']] = $production['spend_time'] * 1.0;
        }
        
        foreach ($costs as $work_type_id => &$work_type) {
           $price = $this->work_type->selectById($work_type_id);
           if (empty($price)) {
                return false;
           } 
           $work_type *= $price[0]['employee_rate'];
        }
        
        return array_sum($costs);
    }
    
    protected function getRawAndSupplyCost($product_id) {
         $costs = [];
         $product_raws = $this->product_raw->selectByProductId($product_id);;
         foreach($product_raws as &$product_raw) {
            $costs[$product_raw['raw_id']] = $product_raw['quantity'] * 1.0;
         }
         
         foreach($costs as $raw_id => &$quant) {
            $one = $this->raw->selectById($raw_id);
            if (!$one) {
                return false;
            }
            $quant *= $one[0]['price'];
         }  
              
         foreach($costs as $raw_id => &$quant)
         {
            $price = $this->supplies->selectByRawId($raw_id);
            if ($price === false)
            {
                return false;
            }
            
            uasort($price, function($k1, $k2) {
                if ($k1['price'] == $k2['price']) {
                    return 0;
                }
                return ($k1['price'] < $k2['price'] ? -1 : 1);
            });
            
            $quant += $price[0]['price'];
         }
         
         return array_sum($costs);
    }
    
    protected function getDistributionPrice($distribution_id) {
        return $this->distribution->selectById($distribution_id)[0]['price'] * 1.0;
    }
    
    protected function getOrderCostWithoutMargin($order_id) {
        $order = $this->orders->selectById($order_id);
        $distribution_id = $order[0]['distributotion_id'];
        $product_id = $order[0]['product_id'];
        $edition = $order[0]['edition'];
        return ($this->getRawAndSupplyCost($product_id) + $this->getWorkCost($product_id)) * $edition
            + $this->getDistributionPrice($distribution_id);
    }
    
    public function getOrderCost($order_id) {
        $order = $this->orders->selectById($order_id);
        $distribution_id = $order[0]['distributotion_id'];
        $product_id = $order[0]['product_id'];
        $edition = $order[0]['edition'];
        $levels = $this->edition_discount_info->select();
        $level;
        foreach ($levels as &$lev) {
            if ($product_id && $lev['bottom'] * 1.0 <= $edition && $lev['top'] * 1.0 >= $edition) {
                $level = $lev['id'] * 1;
                break;
            }
        }
        $editions = $this->edition_discount->selectByProductId($product_id);
        $edition_discount = $editions[$level]['value'] * 1.0;
        $margin = $this->margin->selectByProductId($product_id)[0]['self'] * 1.0;
        return $this->getOrderCostWithoutMargin($order_id) * (1.0 + $edition_discount * $margin);
    }
    
    protected $DB;
    
    public $action_time;
	public $client;
	public $client_card;
	public $client_status;
	public $discount;	 
	public $discount_level;
	public $distribution;
	public $distributor;
	public $edition_discount;
	public $edition_discount_info;
	public $margin;	
	public $orders;	
	public $product;	
	public $production;
	public $product_raw;
	public $raw;	  	 
	public $supplier;
	public $supplies;
	public $work_type;
}

class PressForClient extends Press {
    
}

class PressForEconomist extends Press {
    public function getRawAndSupplyCost($product_id) {
        return Press::getRawAndSupplyCost($product_id);
    }
    
    public function getWorkCost($product_id) {
        return Press::getWorkCost($product_id);
    }
    
    public function getDistributionPrice($distribution_id) {
        return Press::getDistributionPrice($distribution_id);
    }
    
    public function getOrderCostWithoutMargin($order_id) {
        return Press::getOrderCostWithoutMargin($order_id);
    }
    
    public  function getOrderCost($order_id) {
        return Press::getOrderCost($order_id);
    }
    
    public function getMargin($order_id) {
        return $this->getOrderCost($order_id) - $this->getOrderCostWithoutMargin($order_id);
    }
}

