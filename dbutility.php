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
        $this->fillWorkType();
        $this->fillProduction();
        $this->fillEditionDiscount();
        $this->fillMargin();
        $this->fillDistributor();
        $this->fillDistribution();
        $this->fillClient();
        $this->fillClientCard();
        $this->fillOrder();
        $this->fillDiscount();
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
    
    public function fillWorkType() {
        $this->work_type->insert('rivets nstalling' , 0.042);
        $this->work_type->insert('galvazing arches cutting' , 0.042);
        $this->work_type->insert('film printing' , 0.042);
        $this->work_type->insert('lamination' , 0.042);
        $this->work_type->insert('CC' , 0.042);
    }
    
    public function fillProduction() {
        $this->insertProduction(1, 1, 430);
        $this->insertProduction(1, 2, 610);
        $this->insertProduction(1, 3, 5320);
        $this->insertProduction(1, 4, 520);
        $this->insertProduction(1, 5, 35);
    }
    
    public function fillClient() {
        $this->client->insert('Big Magazine', null, null, 'BigMagazine@mail.ru');
    }
    
    public function fillClientCard() {
        $this->insertClientCard(1, 1);
    }
    
    public function fillEditionDiscount() {
        $this->insertEditionDiscount(1, [
            1,
            0.99, 
            0.9, 
            0.85, 
            0.75, 
            0.67,
            0.6, 
            0.58, 
            0.55, 
            0.46, 
            0.4, 
            0.35,
            0.31, 
            0.29, 
            0.27, 
            0.22
        ]);   
    }
    
    public function fillOrder() {
        $this->insertOrder(1, 1, 1, 10000);
    }
    
    public function fillMargin() {
        $this->insertMargin(1, 0.3);
    }
    
    public function fillDistributor() {
        $this->distributor->insert('OP', null, null, 'op@mail.ru');
        $this->distributor->insert('RA', null, null, 'ra@mail.ru');
        $this->distributor->insert('PR', null, null, 'pr@mail.ru');
    }
    
    public function fillDistribution() {
        $this->insertDistribution(1, 1, 3540);
        $this->insertDistribution(1, 2, 3600);
        $this->insertDistribution(1, 3, 3660);
    }
    
    public function fillDiscount() {
        $this->insertDiscount(1, [5, 7, 25, 30]);
    }
    
    protected function truncateTable($table) {
       $this->DB->query('SET FOREIGN_KEY_CHECKS = 0');
       $this->DB->query('TRUNCATE ' . $table);
       $this->DB->query('SET FOREIGN_KEY_CHECKS = 1');
    }
}