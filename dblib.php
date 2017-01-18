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
    
    protected function GetPriceForRaw($raw_id) {
        var_dump($this->orders->select());
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

class PressForEconomist {

}