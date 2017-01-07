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
    }
    
    protected $DB;
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


class Product extends Table {
    public function __construct() {
        Table::__construct();
        $this->table = 'product';
    }
    
    public function insert($title) {
        if (!$this->canInsert($title)) {
            return false;
        }
        $insert = ['title' => $title];
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


class Raw extends Table {
    public function __construct() {
        Table::__construct();
        $this->table = 'raw';
    }
    
    public function insert($title) {
        if (!$this->canInsert($title)) {
            return false;
        }
        $insert = ['title' => $title];
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

class WorkType extends Table {
    public function __construct() {
        Table::__construct();
        $this->table = 'work_type';
    }
    
    public function insert($title, $employee_rate) {
        if (!$this->canInsert($title, $employee_rate)) {
            return false;
        }
        $insert = ['title' => $title];
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


