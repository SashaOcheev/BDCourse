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

class PressForUser extends Press {
    
}

class PressForEconomist extends Press {
    public function getRawAndSupplyCost($product_id) {
        return Press::getRawAndSupplyCost($product_id);
    }
    
    public function getWorkCost($product_id) {
        return Press::getWorkCost($product_id);
    }
}

