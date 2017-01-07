<?php

abstract class UnchangeableTable {
    public function __construct(&$DB) {
        $this->DB =& $DB;
    }
    
    public function select() {
        return $this->DB->select($table, '*');
    }
    
    public function hasId($id) {
        return $this->DB->has($table, ['id' => $id]);
    }
        
    protected $DB;
    protected $table;
}


abstract class Table extends UnchangeableTable{
    public function delete($id) {
        return $this->DB->delete($table, ['id' => $id]);
    }
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
        return $this->DB->update($table, $insert, ['id' => $id]);
    }
    
    protected function canInsert($title) {
        return (!empty($title));
    }    
}


class Product extends OnlyTitle {
    public function __construct(&$DB) {
        OnlyTitle::__construct($DB);
        $this->table = 'product';
    }   
}


class Raw extends OnlyTitle {
    public function __construct(&$DB) {
        OnlyTitle::__construct($DB);
        $this->table = 'raw';
    }
}


class WorkType extends Table {
    public function __construct(&$DB) {
        Table::__construct($DB);
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
        return $this->DB->update($table, $insert, ['id' => $id]);
    }
    
    protected function canInsert($title, $employee_rate) {
        return !empty($title) && !empty($employee_rate) && $employee_rate > 0;
    }    
}


abstract class Person extends Table {
  
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
        return $this->DB->update($table, $insert, ['id' => $id]);
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
    public function __construct(&$DB) {
        Table::__construct($DB);
        $this->table = 'client';
    }
}


class Supplier extends Person {
    public function __construct(&$DB) {
        Table::__construct($DB);
        $this->table = 'supplier';
    }
}


class Distributor extends Person {
    public function __construct(&$DB) {
        Table::__construct($DB);
        $this->table = 'distributor';
    }
}


class Margin extends Table {
    public function __construct(&$DB) {
        Table::__construct($DB);
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
        return $this->DB->insert($table, $insert);
    }
    
    public function update($id, &$product, $produc_id, $self) {
        if (!$this->canInsert($product, $product_id, $self)) {
            return false;
        }
        $insert = [
            'product_id' => $product_id,
            'self' => $self
        ];
        return $this->DB->update($table, $insert, ['id' => $id]);
    }
    
    protected function canInsert(&$product, $product_id, $self) {
        return $product->hasId($product_id) && $self >= 0;
    }
}


class ActionTime extends Table {
    public function __construct(&$DB) {
        Table::__construct($DB);
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
        return $this->DB->insert($table, $insert);
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
        return $this->DB->update($table, $insert, ['id' => $id]);
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
    public function __construct(&$DB) {
        Table::__construct($DB);
        $this->table = 'orders';
    }
    
    public function selectByProductId($product_id) {
        return $this->DB->select($table, '*', ['product_id' => $product_id]);
    }
    
    public function selectByClientId($client_id) {
        return $this->DB->select($table, '*', ['client_id' => $client_id]);
    }
    
    public function insert(&$product, $product_id, &$client, $client_id, &$edition_discount_info, $edition) {
        if (!$this->canInsert($product, $product_id, $client, $client_id, $edition_discount_info, $edition)) {
            return false;
        }
        $insert = [
            'id' => null,
            'product_id' => $product_id,
            'client_id' => $client_id,
            'edition' => $edition
        ];
        return $this->DB->insert($table, $insert);
    }
    
    public function update($id, &$product, $product_id, &$client, $client_id, &$edition_discount_info, $edition) {
        if (!$this->canInsert($product, $product_id, $client, $client_id, $edition_discount_info, $edition)) {
            return false;
        }
        $insert = [
            'product_id' => $product_id,
            'client_id' => $client_id,
            'edition' => $edition
        ];
        return $this->DB->update($table, $insert, ['id' => $id]);
    }
    
    protected function canInsert(&$product, $product_id, &$client, $client_id, &$edition_discount_info, $edition) {
        $edition_discount_levels = $edition_discount_info->select();
        return $product->hasId($product_id)
            && $client->hasId($client_id)
            && $edition >= $this->edition_discount_levels[0]['top']
            && $edition <= end($this->edition_discount_levels)['bottom'];
    }
}


/**
 * id int
 * raw_id int
 * supplier_id int
 * price float(10, 2)
 */
class Supplies extends Table {
    
    public function __construct(&$DB) {
        Table::__construct($DB);
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
        return $this->DB->insert($table, $insert);
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
        return $this->DB->update($table, $insert, ['id' => $id]);
    }
    
    protected function canInsert(&$raw, $raw_id, &$supplier, $supplier_id, $price) {
        return $raw->hasId($raw_id)
            && $suppplier->hasId($supplier_id)
            && $price >= 0;
    }
}


/**
 * id int
 * prodict_id int
 * raw_id int
 * quantity float(10, 2)
 */
class ProductRaw extends Table {

    public function __construct(&$DB) {
        Table::__construct($DB);
        $this->table = 'product_raw';
    }
    
    public function selectByRawId($raw_id) {
        return $this->DB->select($table, '*', ['raw_id' => $raw_id]);
    }
    
    public function selectByProductId($product) {
        return $this->DB->select($table, '*', ['product_id' => $product_id]);
    }
    
    public function insert(&$raw, $raw_id, &$supplier, $supplier_id, $price) {
        if (!$this->canInsert($product, $product_id, $raw, $raw_id, $quantity)) {
            return false;
        }
        $insert = [
            'id' => null,
            'product_id' => $product_id,
            'raw_id' => $raw_id,
            'quantity' => $quantity
        ];
        return $this->DB->insert($table, $insert);
    }
    
    public function update($id, &$product, $product_id, &$raw, $raw_id, $quantity) {
        if (!$this->canInsert($product, $product_id, $raw, $raw_id, $quantity)) {
            return false;
        }
        $insert = [
            'product_id' => $product_id,
            'raw_id' => $raw_id,
            'quantity' => $quantity
        ];
        return $this->DB->update($table, $insert, ['id' => $id]);
    }
    
    protected function canInsert(&$product, $product_id, &$raw, $raw_id, $quantity) {
        return $raw->hasId($raw_id)
            && $product->hasId($product_id)
            && $quantity > 0;
    }
}


class ClientStatus extends UnchangeableTable {
    public function __construct(&$DB) {
        UnchangeableTable::__construct($DB);
        $this->table = 'client_status';    
    }
}


class EditionDiscountInfo extends UnchangeableTable {
    public function __construct(&$DB) {
        UnchangeableTable::__construct($DB);
        $this->table = 'edition_discount_info';    
    }
}


class DiscountLevel extends UnchangeableTable {
    public function __construct(&$DB) {
        UnchangeableTable::__construct($DB);
        $this->table = 'discount_level';    
    }
}

/**
 * id int
 * client_id int
 * client_status_id int
 */
class ClientCard extends Table {
    public function __construct(&$DB) {
        Table::__construct($DB);
        $this->table = 'client_card';
    }
    
    public function selectByClientId($client_id) {
        return $this->DB->select($table, '*', ['client_id' => $client_id]);
    }
    
    public function selectByClientStatusId($client_status_id) {
        return $this->DB->select($table, '*', ['client_status_id' => $client_status_id]);
    }
    
    public function insert(&$client, $client_id, &$client_status, $client_status_id) {
        if (!$this->canInsert($client, $client_id, $client_status, $client_status_id)) {
            return false;
        }
        $insert = [
            'id' => null,
            'client_id' => $client_id,
            'client_status_id' => $client_status_id,
        ];
        return $this->DB->insert($table, $insert);
    }
    
    public function update($id, &$client, $client_id, &$client_status, $client_status_id) {
        if (!$this->canInsert($client, $client_id, $client_status, $client_status_id)) {
            return false;
        }
        $insert = [
            'client_id' => $client_id,
            'client_status_id' => $client_status_id,
        ];
        return $this->DB->update($table, $insert, ['id' => $id]);
    }
    
    protected function canInsert(&$client, $client_id, &$client_status, $client_status_id) {
        return $client->hasId($client_id)
            && $client_status->hasId($client_status_id);
    }
}


/**
 * id int
 * product_id int
 * discount_level_id int
 */
class Discount extends Table {
    public function __construct(&$DB) {
        Table::__construct($DB);
        $this->table = 'discount';
    }
    
    public function selectByDiscountLevelId($discount_level_id) {
        return $this->DB->select($table, '*', ['discount_level_id' => $discount_level_id]);
    }
    
    public function selectByProductId($product_id) {
        return $this->DB->select($table, '*', ['product_id' => $product_id]);
    }
    
    public function insert(&$product, $product_id, &$discount_level, $discount_level_id) {
        if (!$this->canInsert($product, $product_id, $discount_level, $discount_level_id)) {
            return false;
        }
        $insert = [
            'id' => null,
            'product_id' => $product_id,
            'discount_level_id' => $discount_level_id,
        ];
        return $this->DB->insert($table, $insert);
    }
    
    public function update($id, &$product, $product_id, &$discount_level, $discount_level_id) {
        if (!$this->canInsert($product, $product_id, $discount_level, $discount_level_id)) {
            return false;
        }
        $insert = [
            'product_id' => $product_id,
            'discount_level_id' => $discount_level_id,
        ];
        return $this->DB->update($table, $insert, ['id' => $id]);
    }
    
    protected function canInsert(&$product, $product_id, &$discount_level, $discount_level_id) {
        return $product->hasId($product_id)
            && $discount_level->hasId($discount_level_id);
    }
}


/**
 * id int
 * product_id int
 * edition_discount_info_id int
 */
class EditionDiscount extends Table {
    public function __construct(&$DB) {
        Table::__construct($DB);
        $this->table = 'edition_discount';
    }
    
    public function selectByEditionDiscountInfoId($edition_discount_info_id) {
        return $this->DB->select($table, '*', ['edition_discount_info_id' => $edition_discount_info_id]);
    }
    
    public function selectByProductId($product_id) {
        return $this->DB->select($table, '*', ['product_id' => $product_id]);
    }
    
    public function insert(&$product, $product_id, &$edition_discount_info, $edition_discount_info_id) {
        if (!$this->canInsert($product, $product_id, $edition_discount_info, $edition_discount_info_id)) {
            return false;
        }
        $insert = [
            'id' => null,
            'product_id' => $product_id,
            'edition_discount_info_id' => $edition_discount_info_id,
        ];
        return $this->DB->insert($table, $insert);
    }
    
    public function update($id, &$product, $product_id, &$edition_discount_info, $edition_discount_info_id) {
        if (!$this->canInsert($product, $product_id, $edition_discount_info, $edition_discount_info_id)) {
            return false;
        }
        $insert = [
            'product_id' => $product_id,
            'edition_discount_info_id' => $edition_discount_info_id,
        ];
        return $this->DB->update($table, $insert, ['id' => $id]);
    }
    
    protected function canInsert(&$product, $product_id, &$edition_discount_info, $edition_discount_info_id) {
        return $product->hasId($product_id)
            && $edition_discount_info->hasId($edition_discount_info_id);
    }
}


/**
 * id int
 * product_id int
 * work_type_id int
 * spend_time
 */
class Production extends Table {
    public function __construct(&$DB) {
        Table::__construct($DB);
        $this->table = 'production';
    }
    
    public function selectByWorkTypeId($work_type_id) {
        return $this->DB->select($table, '*', ['work_type_id' => $work_type_id]);
    }
    
    public function selectByProductId($product_id) {
        return $this->DB->select($table, '*', ['product_id' => $product_id]);
    }
    
    public function insert(&$product, $product_id, &$work_type, $work_type_id, $spend_time) {
        if (!$this->canInsert($product, $product_id, $work_type, $work_type_id, $spend_time)) {
            return false;
        }
        $insert = [
            'id' => null,
            'product_id' => $product_id,
            'work_type_id' => $work_type_id,
            'spend_time' => $spend_time
        ];
        return $this->DB->insert($table, $insert);
    }
    
    public function update($id, &$product, $product_id, &$work_type, $work_type_id, $spend_time) {
        if (!$this->canInsert($product, $product_id, $work_type, $work_type_id, $spend_time)) {
            return false;
        }
        $insert = [
            'product_id' => $product_id,
            'work_type_id' => $work_type_id,
            'spend_time' => $spend_time
        ];
        return $this->DB->update($table, $insert, ['id' => $id]);
    }
    
    protected function canInsert(&$product, $product_id, &$work_type, $work_type_id, $spend_time) {
        return $product->hasId($product_id)
            && $work_type->hasId($work_type_id)
            && $spend_time >= 0;
    }
}


/**
 * id int
 * product_id int
 * distributor_id int
 * margin float(2, 2)
 */
class Distribution extends Table {
    public function __construct(&$DB) {
        Table::__construct($DB);
        $this->table = 'distribution';
    }
    
    public function selectByDistributorId($distributor_id) {
        return $this->DB->select($table, '*', ['distributor_id' => $distributor_id]);
    }
    
    public function selectByProductId($product_id) {
        return $this->DB->select($table, '*', ['product_id' => $product_id]);
    }
    
    public function insert(&$product, $product_id, &$distributor, $distributor_id, $margin) {
        if (!$this->canInsert($product, $product_id, $distributor, $distributor_id, $margin)) {
            return false;
        }
        $insert = [
            'id' => null,
            'product_id' => $product_id,
            'distributor_id' => $distributor_id,
            'margin' => $margin
        ];
        return $this->DB->insert($table, $insert);
    }
    
    public function update($id, &$product, $product_id, &$distributor, $distributor_id, $margin) {
        if (!$this->canInsert($product, $product_id, $distributor, $distributor_id, $margin)) {
            return false;
        }
        $insert = [
            'product_id' => $product_id,
            'distributor_id' => $distributor_id,
            'margin' => $margin
        ];
        return $this->DB->update($table, $insert, ['id' => $id]);
    }
    
    protected function canInsert(&$product, $product_id, &$distributor, $distributor_id, $margin) {
        return $product->hasId($product_id)
            && $work_type->hasId($work_type_id)
            && $margin >= 0;
    }
}

