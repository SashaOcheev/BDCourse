<?php

require_once 'login.php';
require_once 'medoo.php';

class DB {
    public function __construct() {
        global $db_database, $db_hostname, $db_password, $db_username;
        $this->DB = new medoo(['database_type' => 'mysql',
        	'database_name' => $db_database,
        	'server' => $db_hostname,
        	'username' => $db_username,
        	'password' => $db_password,
            'charset' => 'utf8']);
            
        $this->setDiscountEditionLevels();
        $this->setClientStatuses();
        $this->setDiscountLevels();
    }
    
    protected function setDiscountEditionLevels() {
        $this->edition_discount_levels = $this->DB->select('edition_discount_info', '*');
        $this->edition_discount_levels = uasort($this->edition_discount_levels, $this->getUasortFunc('top'));
    }
    
    protected function setClientStatuses() {
        $this->client_statuses = $this->DB->select('client_status', '*');
        $this->client_statuses = uasort($this->client_statuses, $this->getUasortFunc('id'));
    }
    
    protected function setDiscountLevels() {
        $this->discount_levels = $this->DB->select('discount_levels', '*');
        $this->discount_levels = uasort($this->discount_levels, $this->getUasortFunc('id'));
    }
    
    protected function getUasortFunc($field) {
        return function($val1, $val2) {
                if ($val1[$field] == $val2[$field]) {
                    return 0;
                }
                return ($val1[$field] > $val2[$field] ? 1 : -1);
            };
    }
    
    protected $DB;
    protected $edition_discount_levels; //array('id' => int, 'bottom' => int, 'top' => int)
    protected $client_statuses; //array('id' => int, 'status' => string)
    protected $discount_levels; //array('id' => int)
}


abstract class Table {
    public function __construct(&$DB) {
        $this->DB =& $DB;
    }
    
    public function select() {
        return $this->DB->select($table, '*');
    }
    
    public function hasId($id) {
        return $this->DB->has($table, ['id' => $id]);
    }
    
    public function delete($id) {
        return $this->DB->delete($table, ['id' => $id]);
    }
    
    protected $DB;
    protected $table;
}


class OnlyTitle extends Table {
    
    public function insert($title) {
        if (!$this->canInsert($title)) {
            return false;
        }
        $insert = [
            'id' => null,
            'title' => $title
        ];
        return $this->DB->insert($table, $insert);
    }
    
    public function update($id, $title) {
        if (!$this->canInsert($title)) {
            return false;
        }
        $insert = ['title' => $title];
        return $this->DB->insert($table, $insert, ['id' => $id]);
    }
    
    protected function canInsert($title) {
        return (!empty($title));
    }    
}


class Product extends OnlyTitle {
    public function __construct() {
        OnlyTitle::__construct();
        $this->table = 'product';
    }   
}


class Raw extends OnlyTitle {
    public function __construct() {
        OnlyTitle::__construct();
        $this->table = 'raw';
    }
}


class WorkType extends Table {
    public function __construct() {
        Table::__construct();
        $this->table = 'work_type';
    }
    
    public function insert($title, $employee_rate) {
        if (!$this->canInsert($title, $employee_rate)) {
            return false;
        }
        $insert = [
            'id' => null,
            'title' => $title
        ];
        return $this->DB->insert($table, $insert);
    }
    
    public function update($id, $title, $employee_rate) {
        if (!$this->canInsert($title, $employee_rate)) {
            return false;
        }
        $insert = ['title' => $title];
        return $this->DB->insert($table, $insert, ['id' => $id]);
    }
    
    protected function canInsert($title, $employee_rate) {
        return !empty($title) && !empty($employee_rate) && $employee_rate > 0;
    }    
}


class Person extends Table {
  
    public function insert($title, $fullname, $phone, $email) {
        if (!$this->canInsert($title, $fullname, $phone, $email)) {
            return false;
        }
        $insert = [
            'id' => null,
            'title' => $title,
            'fullname' => $fullname,
            'phone' => $phone,
            'email' => $email    
        ];
        return $this->DB->insert($table, $insert);
    }
    
    public function update($id, $title, $fullname, $phone, $email) {
        if (!$this->canInsert($title, $fullname, $phone, $email)) {
            return false;
        }
        $insert = [
            'title' => $title,
            'fullname' => $fullname,
            'phone' => $phone,
            'email' => $email    
        ];
        return $this->DB->insert($table, $insert, ['id' => $id]);
    }
    
    protected function canInsert($title, $fullname, $phone, $email) {
        if ($title == '' || $fullname == '' || $email == '' || $phone <= 0) {
            return false;
        }
        if (empty($title) || empty($fullname) && empty($phone) || empty($email)) {
            return false;
        } 
        return true;
    }
}  
 
 
class Client extends Person {
    public function __construct() {
        Table::__construct();
        $this->table = 'client';
    }
}


class Supplier extends Person {
    public function __construct() {
        Table::__construct();
        $this->table = 'supplier';
    }
}


class Margin extends Table {
    public function __construct() {
        Table::__construct();
        $this->table = 'margin';
    }
    
    public function selectByProductId($product_id) {
        return $this->DB->select($table, '*', ['product_id' => $product_id]);
    }
    
    public function insert(&$product, $product_id, $self) {
        if (!$this->canInsert($product, $product_id, $self)) {
            return false;
        }
        $insert = [
            'id' => null,
            'product_id' => $product_id,
            'self' => $self
        ];
        $this->DB->insert($table, $insert);
    }
    
    public function update($id, &$product, $produc_id, $self) {
        if (!$this->canInsert($product, $product_id, $self)) {
            return false;
        }
        $insert = [
            'product_id' => $product_id,
            'self' => $self
        ];
        $this->DB->update($table, $insert, ['id' => $id]);
    }
    
    protected function canInsert(&$product, $product_id, $self) {
        return $product->hasId($product_id) && $self >= 0;
    }
}


class ActionTime extends Table {
    public function __construct() {
        Table::__construct();
        $this->table = 'action_time';
    }

    public function selectByProductId($product_id) {
        return $this->DB->select($table, '*', ['product_id' => $product_id]);
    }

    public function insert(&$product, $product_id, $start_time, $end_time) {
        if (!$this->canInsert($product, $product_id, $start_time, $end_time)) {
            return false;
        }
        $insert = [
            'id' => null,
            'product_id' => $product_id,
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
        $this->DB->insert($table, $insert);
    }

    public function update($id, &$product, $produc_id, $start_time, $end_time) {
        if (!$this->canInsert($product, $product_id, $start_time, $end_time)) {
            return false;
        }
        $insert = [
            'product_id' => $product_id,
            'start_time' => $start_time,
            'end_time' => $end_time
        ];
        $this->DB->update($table, $insert, ['id' => $id]);
    }
    
    //time date('Y-m-d')
    protected function canInsert(&$product, $product_id, $start_time, $end_time) {
        return $product->hasId($product_id) && $end_time > $start_time;
    }
}


/**
 * id int
 * product_id int
 * client_id int
 * edition int
 */
class Orders extends Table {
    public function __construct(&$edition_discount_levels) {
        Table::__construct();
        $this->table = 'orders';
        $this->edition_discount_levels =& $edition_discount_levels;
    }
    
    public function selectByProductId($product_id) {
        return $this->DB->select($table, '*', ['product_id' => $product_id]);
    }
    
    public function selectByClientId($client_id) {
        return $this->DB->select($table, '*', ['client_id' => $client_id]);
    }
    
    public function insert(&$product, $product_id, &$client, $client_id, $edition) {
        if (!$this->canInsert($product, $product_id, $client, $client_id, $edition)) {
            return false;
        }
        $insert = [
            'id' => null,
            'product_id' => $product_id,
            'client_id' => $client_id,
            'edition' => $edition
        ];
        $this->DB->insert($table, $insert);
    }
    
    public function update($id, &$product, $product_id, &$client, $client_id, $edition) {
        if (!$this->canInsert($product, $product_id, $client, $client_id, $edition)) {
            return false;
        }
        $insert = [
            'product_id' => $product_id,
            'client_id' => $client_id,
            'edition' => $edition
        ];
        $this->DB->update($table, $insert, ['id' => $id]);
    }
    
    protected function canInsert(&$product, $product_id, &$client, $client_id, $edition) {
        return $product->hasId($product_id)
            && $client->hasId($client_id)
            && $edition >= $this->edition_discount_levels[0]['top']
            && $edition <= end($this->edition_discount_levels)['bottom'];
    }
    
    protected $edition_discount_levels;
}


/**
 * id int
 * raw_id int
 * supplier_id int
 * price float(10, 2)
 */
class Supplies extends Table {
    
    public function __construct() {
        Table::__construct();
        $this->table = 'supplies';
    }

    public function selectByRawId($raw_id) {
        return $this->DB->select($table, '*', ['raw_id' => $raw_id]);
    }
    
    public function selectByClientId($supplier) {
        return $this->DB->select($table, '*', ['supplier_id' => $supplier_id]);
    }
    
    public function insert(&$raw, $raw_id, &$supplier, $supplier_id, $price) {
        if (!$this->canInsert($raw, $raw_id, $supplier, $supplier_id, $price)) {
            return false;
        }
        $insert = [
            'id' => null,
            'raw_id' => $raw_id,
            'supplier_id' => $supplier_id,
            'price' => $price
        ];
        $this->DB->insert($table, $insert);
    }
    
    public function update($id, &$product, $product_id, &$client, $client_id, $edition) {
        if (!$this->canInsert($raw, $raw_id, $supplier, $supplier_id, $price)) {
            return false;
        }
        $insert = [
            'raw_id' => $raw_id,
            'supplier_id' => $supplier_id,
            'price' => $price
        ];
        $this->DB->update($table, $insert, ['id' => $id]);
    }
    
    protected function canInsert(&$raw, $raw_id, &$supplier, $supplier_id, $price) {
        return $raw->hasId($raw_id)
            && $suppplier->hasId($supplier_id)
            && $price >= 0;
    }
}



