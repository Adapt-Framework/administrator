<?php

/* Prevent Direct Access */
defined('ADAPT_STARTED') or die;

$adapt = $GLOBALS['adapt'];
$sql = $adapt->data_source->sql;





$menu_type = new model_menu_type();
$menu_type->load_by_name('bs_navbar_nav');

if ($menu_type->is_loaded){
    
    $menu = new model_menu();
    $menu->menu_type_id = $menu_type->menu_type_id;
    $menu->bundle_name = 'administrator';
    $menu->name = 'administrator_main_navigation';
    $menu->label = "Administration menu";
    $menu->save();
    
    $menu_item = new model_menu_item();
    $menu_item->menu_id = $menu->menu_id;
    $menu_item->bundle_name = 'administrator';
    $menu_item->label = 'Contacts';
    $menu_item->menu_item_type = 'Link';
    $menu_item->link = '/administrator/contacts';
    $menu_item->priority = 98;
    $menu_item->save();
    
    $menu_item = new model_menu_item();
    $menu_item->menu_id = $menu->menu_id;
    $menu_item->bundle_name = 'administrator';
    $menu_item->label = 'Users';
    $menu_item->menu_item_type = 'Link';
    $menu_item->link = '/administrator/users';
    $menu_item->priority = 99;
    $menu_item->save();
}



 
?>