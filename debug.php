<?php

require_once('dblib.php');

$press = new Press();

function echo_data($get_all) {
    global $press;
    
    $objs = call_user_func(array($press, $get_all));
    foreach($objs as $obj) {
        var_dump($obj);
        echo ('<br>');
    }
}

function suppliers() {
    global $press;
    
    $id = $press->insert_supplier('Zenon', 'Morpheus', null, 'zenon@mail.ru');
    $press->update_supplier($id, 'Zenon', null, null, 'zenon@mail.ru');
    $press->insert_supplier('Omega Metal', null, null, 'omegametal@mail.ru');
    $press->insert_supplier('Megastroy', null, null, 'megastroy@mail.ru');
    $press->insert_supplier('Three-R', null, null, 'threer@mail.ru');
    $press->insert_supplier('Papillons', null, null, 'papillons@mail.ru');
    $id = $press->insert_supplier('Sb', null, null, 'sb@mail.ru');
    $press->delete_supplier($id);
    
    echo_data('get_all_supplier');
}

function raw() {
    global $press;
    
    $id = $press->insert_raw('blanch');
    $press->update_raw($id, 'blank');
    
    $press->insert_raw('galvanized sheet');
    $press->insert_raw('solvent paint');
    $press->insert_raw('PVC film');
    $press->insert_raw('rivet');
    $id = $press->insert_product('sth');
    $press->delete_raw($id);
    
    echo_data('get_all_raw');
}

function work_type() {
    global $press;
    
    $id = $press->insert_work_type('rivets installing abracadabra', 0.042);
    $press->update_work_type($id, 'rivets installing', 0.042);
    
    $press->insert_work_type('galvanized arches cutting', 0.042);
    $press->insert_work_type('film printing', 0.042);
    $press->insert_work_type('plastic knurling', 0.042);
    $press->insert_work_type('KK', 0.042);
    $id = $press->insert_work_type('sth', 0.042);
    $press->delete_work_type($id);
    
    echo_data('get_all_work_type');
}

function client() {
    global $press;
    
    $id = $press->insert_client('vip', null, 'John Smith Smith', null, null);
    $press->update_client($id, 'vip', null, 'John Smith', null, null);
    $id = $press->insert_client(null, null, 'sb', null, null);
    $press->delete_client($id);
    
    echo_data('get_all_client');
}

function product() {
    global $press;
    
    $id = $press->insert_product('pillar abra-cadabra');
    $press->update_product($id, 'pillar');
    $id = $press->insert_product('sth');
    $press->delete_product($id);
    $press->insert_product('diploma');
    $press->insert_product('visiting card');
    $press->insert_product('bracket journal');
    $press->insert_product('invitation');
    $press->insert_product('ticket');
    
    echo_data('get_all_product');
}



function add_raw_for_pillar() {
    global $press;
    
    $press->insert_product_raw(1, 1, 1);
    $press->insert_product_raw(1, 2, 0.25);
    $press->insert_product_raw(1, 3, 10);
    $press->insert_product_raw(1, 4, 1.92);
    $press->insert_product_raw(1, 5, 0.05);
}

function echo_raw_for_pillar() {
    global $press;
    
    $objs = $press->get_all_raws_for_product(1);
    foreach($objs as $obj) {
        var_dump($obj);
        echo ('<br>');
    }
}

function echo_product_for_blank() {
    global $press;
    
    $objs = $press->get_all_products_for_raw(1);
    foreach($objs as $obj) {
        var_dump($obj);
        echo ('<br>');
    }
}


function insert_supplies() {
    global $press;
    
    $press->insert_supplies(1, 1, 700);
    $press->insert_supplies(2, 2, 706);
    $press->insert_supplies(3, 3, 5.5);
    $press->insert_supplies(4, 4, 310);
    $press->insert_supplies(5, 5, 1880);
}

function delete_supplies() {
    global $press;
    
    $press->delete_supplies(1);
    $press->delete_supplies(2);
    $press->delete_supplies(3);
    $press->delete_supplies(4);
    $press->delete_supplies(5);
}

function echo_raws_for_supplier() {
    global $press;
    
    $objs = $press->get_all_raws_for_supplier(1);
    foreach($objs as $obj) {
        var_dump($obj);
        echo ('<br>');
    }
}

function echo_suppliers_for_raw() {
    global $press;
    
    $objs = $press->get_all_suppliers_for_raw(1);
    foreach($objs as $obj) {
        var_dump($obj);
        echo ('<br>');
    }
}


function insert_productions() {
    global $press;
    
    $press->insert_production(1, 1, 430);
    $press->insert_production(1, 2, 620);
    $press->insert_production(1, 3, 5320);
    $press->insert_production(1, 4, 520);
    $press->insert_production(1, 5, 35);
}

function delete_productions() {
    global $press;
    
    $press->delete_production(1);
    $press->delete_production(2);
    $press->delete_production(3);
    $press->delete_production(4);
    $press->delete_production(5);
}

function echo_products_for_work_type() {
    global $press;
    
    $objs = $press->get_all_products_for_work_type(1);
    foreach($objs as $obj) {
        var_dump($obj);
        echo ('<br>');
    }
}

function echo_work_type_for_product() {
    global $press;
    
    $objs = $press->get_all_works_type_for_product(1);
    foreach($objs as $obj) {
        var_dump($obj);
        echo ('<br>');
    }
}

function insert_margin() {
    global $press;
    
    $press->insert_margin(1, 0.3);
    $press->insert_margin(2, 0.3);
    $press->insert_margin(3, 0.3);
    $press->insert_margin(4, 0.3);
    $press->insert_margin(5, 0.3);
    $press->insert_margin(6, 0.3);
    $press->insert_margin(7, 0.3);
}

function echo_margin_for_product() {
    global $press;
    
    var_dump($press->get_margin_for_proguct(1));
}

function insert_disount() {
    global $press;
    
    $press->insert_discount(1, [5, 7, 25, 30], null, null);
    $press->insert_discount(2, [10, 20, 35, 45], null, null);
    $press->insert_discount(3, [10, 20, 35, 55], null, null);
    $press->insert_discount(4, [5, 7, 25, 30], null, null);
    $press->insert_discount(5, [5, 7, 25, 30], null, null);
    $press->insert_discount(6, [5, 7, 25, 30], null, null);
    $press->insert_discount(7, [5, 7, 25, 30], null, null);
}

function insert_edition_discount() {
    global $press;
    
    $arr1 = [1, 0.99, 0.9, 0.85, 0.75, 0.67, 0.6, 0.58, 0.55, 0.46, 0.4, 0.35, 0.31, 0.29, 0.27, 0.22];
    $arr2 = [1, 0.8, 0.74, 0.7, 0.65, 0.5, 0.45, 0.5, 0.35, 0.32, 0.3, 0.27, 0.25, 0.23, 0.2, 0.19];
    $press->insert_edition_discount(1, $arr1);
    $press->insert_edition_discount(2, $arr2);
    $press->insert_edition_discount(3, $arr1);
    $press->insert_edition_discount(4, $arr1);
    $press->insert_edition_discount(5, $arr1);
    $press->insert_edition_discount(6, $arr1);
    $press->insert_edition_discount(7, $arr1);
}

function delete_edition_discount() {
    global $press;
    
    $arr1 = [1, 0.99, 0.9, 0.85, 0.75, 0.67, 0.6, 0.58, 0.55, 0.46, 0.4, 0.35, 0.31, 0.29, 0.27, 0.22];
    $arr2 = [1, 0.8, 0.74, 0.7, 0.65, 0.5, 0.45, 0.5, 0.35, 0.32, 0.3, 0.27, 0.25, 0.23, 0.2, 0.19];
    $press->delete_edition_discount(1);
    $press->delete_edition_discount(2);
    $press->delete_edition_discount(3);
    $press->delete_edition_discount(4);
    $press->delete_edition_discount(5);
    $press->delete_edition_discount(6);
    $press->delete_edition_discount(7);
}