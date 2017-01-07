<?php

require_once('dblib.php');

class TruncatePress extends Press {
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
    
    protected function truncateTable($table) {
       $this->DB->query('SET FOREIGN_KEY_CHECKS = 0');
       $this->DB->query('TRUNCATE ' . $table);
       $this->DB->query('SET FOREIGN_KEY_CHECKS = 1');
    }
}