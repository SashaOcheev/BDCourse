<?php

require_once 'login.php';
require_once 'medoo.php';

class Press {
    protected $DB;
    protected $limits_names;
    
    public function __construct() {
        global $db_database, $db_hostname, $db_password, $db_username;
        $this->DB = new medoo(['database_type' => 'mysql',
        	'database_name' => $db_database,
        	'server' => $db_hostname,
        	'username' => $db_username,
        	'password' => $db_password,
            'charset' => 'utf8']);
        
        $this->limits_names = $this->DB->select('edition_discount_scope', '*');
    }
    
    //Product
    public function get_all_product() {
        return $this->DB->select('product', '*');
    }
    
    protected function has_product_with_title($product_title) {
        return $this->DB->has('product', ['title' => $product_title]);
    }
    
    protected function has_product_id($id) {
        return $this->DB->has('product', ['id' => $id]);
    }
    
    public function insert_product($product_title) {
         if (!$this->has_product_with_title($product_title) && !empty($product_title)) {
            return $this->DB->insert('product', ['id' => null, 'title' => $product_title]);
         } else {
            return false;
         }
    }
    
    public function update_product($product_id, $title) {
        if (!empty($title)) {
            return $this->DB->update('product', ['title' => $title], ['id' => $product_id]);
        }
    }
    
    public function delete_product($product_id) {
        return $this->DB->delete('product', ['id' => $product_id]);
    }
    //_Product
    
    
    //Supplier
    public function get_all_supplier() {
        return $this->DB->select('supplier', '*');
    }
    
    protected function has_supplier_id($id) {
        return $this->DB->has('supplier', ['id' => $id]);
    }
    
    protected function can_not_insert_supplier($title, $fullname, $phone, $email) {
        return empty($title) && empty($fullname) && empty($phone) && empty($email);
    }
    
    public function insert_supplier($title, $fullname, $phone, $email) {
         if ($this->can_not_insert_supplier($title, $fullname, $phone, $email)) {
            return false;
         }
         return $this->DB->insert('supplier', ['id' => null,
                                                'title' => $title,
                                                'full_name' => $fullname,
                                                'phone' => $phone,
                                                'email' => $email]
         );
    }
    
    public function update_supplier($supplier_id, $title, $fullname, $phone, $email) {
        if ($this->can_not_insert_supplier($title, $fullname, $phone, $email)) {
            return false;
        }
        $update_data = ['title' => $title, 'full_name' => $fullname, 'phone' => $phone, 'email' => $email];
        return $this->DB->update('supplier', $update_data, ['id' => $supplier_id]);
    }
    
    public function delete_supplier($supplier_id) {
        return $this->DB->delete('supplier', ['id' => $supplier_id]);
    }
    //_Supplier
    
    
    //Raw
    public function get_all_raw() {
        return $this->DB->select('raw', '*');
    }
    
    protected function has_raw_with_title($raw_title) {
        return $this->DB->has('raw', ['title' => $raw_title]);
    }
    
    protected function has_raw_id($id) {
        return $this->DB->has('raw', ['id' => $id]);
    }
    
    public function insert_raw($raw_title) {
         if (!$this->has_product_with_title($raw_title) && !empty($raw_title)) {
            return $this->DB->insert('raw', ['id' => null, 'title' => $raw_title]);
         } else {
            return false;
         }
    }
    
    public function update_raw($raw_id, $title) {
        if (!empty($title)) {
            return $this->DB->update('raw', ['title' => $title], ['id' => $raw_id]);
        }
    }
    
    public function delete_raw($raw_id) {
        return $this->DB->delete('raw', ['id' => $raw_id]);
    }
    //_Raw
    
    
    //Work_type
    public function get_all_work_type() {
        return $this->DB->select('work_type', '*');
    }
    
    protected function has_work_type_with_title($work_type_title) {
        return $this->DB->has('work_type', ['title' => $work_type_title]);
    }
    
    protected function has_work_type_id($id) {
        return $this->DB->has('work_type', ['id' => $id]);
    }
    
    public function insert_work_type($work_type_title, $employee_rate) {
         if (!$this->has_work_type_with_title($work_type_title)
            && !empty($work_type_title)
            && !empty($employee_rate)) {
            return $this->DB->insert('work_type', ['id' => null, 'title' => $work_type_title, 'employee_rate' => $employee_rate]);
         } else {
            return false;
         }
    }
    
    public function update_work_type($work_type_id, $title, $employee_rate) {
        if (!empty($title) && !empty($employee_rate)) {
            return $this->DB->update('work_type', ['title' => $title, 'employee_rate' => $employee_rate],
                ['id' => $work_type_id]);
        }
    }
    
    public function delete_work_type($work_type_id) {
        return $this->DB->delete('work_type', ['id' => $work_type_id]);
    }
    //_Work_type
    
    
    //Client
    public function get_all_client() {
        return $this->DB->select('client', '*');
    }
    
    protected function has_client_id($id) {
        return $this->DB->has('client', ['id' => $id]);
    }
    
    protected function can_not_insert_client($state, $title, $fullname, $email, $phone) {
        return empty($title) && empty($fullname) && empty($phone) && empty($email) &&
            !empty($state) && $state !== 'vip' && $state !== 'ra' && $state !== 'gold';
    }
    
    public function insert_client($state, $title, $fullname, $email, $phone) {
         if ($this->can_not_insert_client($state, $title, $fullname, $email, $phone)) {
            return false;
         }
         return $this->DB->insert('client', ['id' => null,
                                                'state' => $state,
                                                'title' => $title,
                                                'fullname' => $fullname,
                                                'phone' => $phone,
                                                'email' => $email]
         );
    }
    
     public function update_client($id, $state, $title, $fullname, $email, $phone) {
         if ($this->can_not_insert_client($state, $title, $fullname, $email, $phone)) {
            return false;
         }
        $update_data = ['state' => $state,
                        'title' => $title,
                        'fullname' => $fullname,
                        'phone' => $phone,
                        'email' => $email];
        return $this->DB->update('client', $update_data, ['id' => $id]);
    }
    
    public function delete_client($id) {
        return $this->DB->delete('client', ['id' => $id]);
    }
    //_Client
    
    
    //Product raw
    public function get_all_raws_for_product($product_id) {
        if (!$this->has_product_id($product_id)) {
            return false;
        }
        
        return $this->DB->select('product_raw', '*', ['product_id' => $product_id]);
    }
    
    public function get_all_products_for_raw($raw_id) {
        if (!$this->has_raw_id($raw_id)) {
            return false;
        }
        
        return $this->DB->select('product_raw', '*', ['raw_id' => $raw_id]);
    }
    
    protected function can_not_insert_product_raw($product_id, $raw_id, $quantity) {
        return empty($quantity) || $quantity <= 0 ||
            !$this->has_product_id($product_id) || !$this->has_raw_id($raw_id);
    }
    
    public function insert_product_raw($product_id, $raw_id, $quantity) {
        if ($this->can_not_insert_product_raw($product_id, $raw_id, $quantity)) {
            return false;       
        }
        return $this->DB->insert('product_raw', ['id' => null,
                                                'product_id' => $product_id,
                                                'raw_id' => $raw_id,
                                                'quantity' => $quantity]);
    }
    
    public function update_product_raw($id, $product_id, $raw_id, $quantity) {
        if ($this->can_not_insert_product_raw($product_id, $raw_id, $quantity)) {
            return false;       
        }
        $update = ['product_id' => $product_id, 'raw_id' => $raw_id, 'quantity' => $quantity];
        return $this->DB->update('product_raw', $update, ['id' => $id]);
    }
    
    public function delete_product_raw($id) {
        return $this->DB->delete('product_raw', ['id' => $id]);
    }
    //_Product_raw
    
    
    //Supplies
    public function get_all_suppliers_for_raw($raw_id) {
        if (!$this->has_raw_id($raw_id)) {
            return false;
        }
        
        return $this->DB->select('supplies', '*', ['raw_id' => $raw_id]);
    }
    
    public function get_all_raws_for_supplier($supplier_id) {
        if (!$this->has_raw_id($supplier_id)) {
            return false;
        }
        
        return $this->DB->select('supplies', '*', ['supplier_id' => $supplier_id]);
    }
    
    protected function can_not_insert_supplies($raw_id, $supplier_id, $price) {
        return !$this->has_supplier_id($supplier_id) ||
            !$this->has_raw_id($raw_id) ||
            $price <= 0;
    }
    
    public function insert_supplies($raw_id, $supplier_id, $price) {
        if ($this->can_not_insert_supplies($raw_id, $supplier_id, $price)) {
            return false;        
        }
        
        return $this->DB->insert('supplies', [
            'id' => null,
            'raw_id' => $raw_id,
            'supplier_id' => $supplier_id,
            'price' => $price
        ]);
    }
    
    public function update_supplies($id, $raw_id, $supplier_id, $price) {
        if ($this->can_not_insert_supplies($raw_id, $supplier_id, $price)) {
            return false;        
        }
        
        return $this->DB->update('supplies', [
            'raw_id' => $raw_id,
            'supplier_id' => $supplier_id,
            'price' => $price
        ], ['id' => $id]);
    }
    
    public function delete_supplies($id) {
        return $this->DB->delete('supplies', ['id' => $id]);
    }
    //_Supplies
    
    //Orders
    public function get_all_clients_for_product($product_id) {
        if (!$this->has_product_id($product_id)) {
            return false;
        }
        
        return $this->DB->select('orders', '*', ['product_id' => $product_id]);
    }
    
    public function get_all_products_for_client($client_id) {
        if (!$this->has_client_id($client_id)) {
            return false;
        }
        
        return $this->DB->select('orders', '*', ['client_id' => $client_id]);
    }
    
    protected function can_not_insert_order($client_id, $product_id, $edition) {
        return $edition <= 0 || $edition > end($this->limits_names['top']) ||
            !$this->has_product_id($product_id) ||
            !$this->has_client_id($client_id);
    }
    
    public function insert_order($client_id, $product_id, $edition) {
        if ($this->can_not_insert_order($client_id, $product_id, $edition)) {
            return false;        
        }
        return $this->DB->insert('orders', [
            'id' => null,
            'client_id' => $client_id,
            'product_id' => $product_id,
            'edition' => $edition
        ]);
    }
    
    public function update_order($id, $client_id, $product_id, $edition) {
        if ($this->can_not_insert_order($client_id, $product_id, $edition)) {
            return false;        
        }
        return $this->DB->update('orders', [
            'id' => null,
            'client_id' => $client_id,
            'product_id' => $product_id,
            'edition' => $edition
        ], ['id' => $id]);
    }
    
    public function delete_order($id) {
        return $this->DB->delete('orders', ['id' => $id]);
    } 
    //_Orders
    
    //Production
    public function get_all_works_type_for_product($product_id) {
        if (!$this->has_product_id($product_id)) {
            return false;
        }
        
        return $this->DB->select('production', '*', ['product_id' => $product_id]);
    }
    
    public function get_all_products_for_work_type($work_type_id) {
        if (!$this->has_work_type_id($work_type_id)) {
            return false;
        }
        
        return $this->DB->select('production', '*', ['work_type_id' => $work_type_id]);
    }
    
    protected function can_not_insert_production($product_id, $work_type_id, $spend_time) {
        return $spend_time <= 0 ||
            !$this->has_work_type_id($work_type_id) ||
            !$this->has_product_id($product_id);
    }
    
    public function insert_production($product_id, $work_type_id, $spend_time) {
        if ($this->can_not_insert_production($product_id, $work_type_id, $spend_time)) {
            return false;        
        }
        return $this->DB->insert('production', [
            'id' => null,
            'product_id' => $product_id,
            'work_type_id' => $work_type_id,
            'spend_time' => $spend_time
        ]);
    }
    
    public function update_production($id, $product_id, $work_type_id, $spend_time) {
        if ($this->can_not_insert_production($product_id, $work_type_id, $spend_time)) {
            return false;        
        }
        return $this->DB->update('production', [
            'product_id' => $product_id,
            'work_type_id' => $work_type_id,
            'spend_time' => $spend_time
        ], ['id' => $id]);
    }
    
    public function delete_production($id) {
        return $this->DB->delete('production', ['id' => $id]);
    }
    //_Production
    
    //Margin
    public function get_margin_for_product($product_id) {
        if (!$this->has_product_id($product_id)) {
            return false;
        }
        
        return $this->DB->select('margin', '*', ['id' => $product_id]);
    }
    
    public function insert_margin($product_id, $self) {
        if (!$this->has_product_id($product_id) || $self <= 0) {
            return false;
        }
        
        return $this->DB->insert('margin', [
            'id' => null,
            'product_id' => $product_id,
            'self' => $self
        ]);
    }
    
    public function update_margin($id, $product_id, $self) {
         if (!$this->has_product_id($product_id) || $self <= 0) {
            return false;
        }
        
        return $this->DB->update('margin', [
            'product_id' => $product_id,
            'self' => $self
        ], ['id' => $id]);
    }
    
    public function delete_margin($id) {
        $this->DB->delete('margin', ['id' => $id]);
    }
    //_Margin
    
    
    //Disount
    public function get_discount_for_product($product_id) {
        if (!$this->has_product_id($product_id)) {
            return false;
        }
        
        return $this->DB->select('discount', '*', ['id' => $product_id]);
    }
    
    
    protected function can_not_insert_discount($product_id, $arr) {
        return !$this->has_product_id($product_id) && count($arr) != 4;
    }
    /*
    date(Y-m-d)
    */
    public function insert_discount($product_id, $arr, $timestart, $timend) {
        if ($this->can_not_insert_discount($product_id, $arr)) {
            return false;
        }
        
        return $this->DB->insert('discount', [
            'id' => null,
            'product_id' => $product_id,
            'time_start' => $timestart,
            'time_end' => $timend,
            'level1' => $arr[0],
            'level2' => $arr[1],
            'level3' => $arr[2],
            'level4' => $arr[3],
        ]);
    }
    
    public function update_discount($id, $product_id, $arr, $timestart, $timend) {
        if (!$this->has_product_id($product_id)) {
            return false;
        }
        
        if (count($arr) != 4) {
            return false;
        }
        
        return $this->DB->update('discount', [
            'product_id' => $product_id,
            'time_start' => $timestart,
            'time_end' => $timend,
            'level1' => $arr[0],
            'level2' => $arr[1],
            'level3' => $arr[2],
            'level4' => $arr[3],
        ], ['id' => $id]);
    }
    
    public function delete_discount($id) {
        $this->DB->delete('discount', ['id' => $id]);
    }
    //_Disocunt
    
    //Edition_discount
    public function get_edition_discount_for_product($product_id) {
        if (!$this->has_product_id($product_id)) {
            return false;
        }
        
        return $this->DB->select('edition_discount', '*', ['id' => $product_id]);
    }
    
    
    protected function get_edition_insert_array($product_id, $arr) {
        if (!$this->has_product_id($product_id)) {
            return false;
        }
        
        $count = count($arr);
        if ($count != count($this->limits_names)) {
            return false;
        }
        
        $levels = array();
        for ($i = 0; $i < $count; ++$i) {
            $levels[$this->limits_names[$i]['column_name']] = $arr[$i];
        }
        
        $insert = ['id' => null, 'product_id' => $product_id];
        $insert = array_merge($insert, $levels);
        
        return $insert;
    }
    
    /*
    date(Y-m-d)
    */
    public function insert_edition_discount($product_id, $arr) {
        $insert = $this->get_edition_insert_array($product_id, $arr);
        if (!$insert) {
            return false;
        }
        
        return $this->DB->insert('edition_discount', $insert);
    }
    
    public function update_edition_discount($id, $product_id, $arr) {
        $insert = $this->get_edition_insert_array($product_id, $arr);
        if (!$insert) {
            return false;
        }
        
        return $this->DB->insert('edition_discount', $insert, ['id' => $id]);
    }
    
    public function delete_edition_discount($id) {
        $this->DB->delete('edition_discount', ['id' => $id]);
    }
    //_Edition_discount
}


