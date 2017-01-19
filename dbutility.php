<?php

require_once('dblib.php');

class FillPress extends Press {
    public function truncateAll() {
        
        $tables = [
            'action_time',
            'client',
            'client_card',
            'discount',	 
            'distribution',
            'distributor',
            'edition_discount',
            'margin',	
            'orders',	
            'product',	
            'production',
            'product_raw',
            'raw',	  	 
            'supplier',
            'supplies',
            'work_type'
        ];
        
        foreach ($tables as $table) {
            $this->truncateTable($table);
        }
    }
    
    public function fill() {
        $this->truncateAll();
        
        $this->fillProduct();
        $this->fillRaw();
        $this->fillSupplier();
        $this->fillSupplies();
        $this->fillProductRaw();
    }
    
    public function fillProduct() {
        $this->product->insert('pillar');
        $this->product->insert('cutaway');
        $this->product->insert('diploma');
    }
    
    public function fillRaw() {
        $this->raw->insert("blank", 700);
        $this->raw->insert("galvanized sheet", 706);
        $this->raw->insert("rivet", 5.5);
        $this->raw->insert("PVC film", 310);
        $this->raw->insert("solvent paint", 1880);
    }
    
    public function fillSupplier() {
        $this->supplier->insert('Zenon', null, null, 'zenon@mail.ru');
        $this->supplier->insert('Omega metal', null, null, 'omegametal@mail.ru');
        $this->supplier->insert('Megastroy', null, null, 'megastroy@mail.ru');
        $this->supplier->insert('Three-R', null, null, 'three-r@mail.ru');
        $this->supplier->insert('Pallilons', null, null, 'pallilons@mail.ru');
    }
    
    public function fillProductRaw() {
        $this->insertProductRaw(1, 1, 1);
        $this->insertProductRaw(1, 2, 0.25);
        $this->insertProductRaw(1, 3, 10);
        $this->insertProductRaw(1, 4, 1.92);
        $this->insertProductRaw(1, 5, 0.05);
    }
    
    public function fillSupplies() {
        $this->insertSupplies(1, 1, 0);
        $this->insertSupplies(2, 2, 50);
        $this->insertSupplies(3, 3, 50);
        $this->insertSupplies(4, 4, 0);
        $this->insertSupplies(5, 5, 100);
    }
    
    protected function truncateTable($table) {
       $this->DB->query('SET FOREIGN_KEY_CHECKS = 0');
       $this->DB->query('TRUNCATE ' . $table);
       $this->DB->query('SET FOREIGN_KEY_CHECKS = 1');
    }
}