<?php

/* Prevent Direct Access */
defined('ADAPT_STARTED') or die;

$adapt = $GLOBALS['adapt'];
$sql = $adapt->data_source->sql;



/* Add administator role */
$role = new model_role();
$role->bundle_name = 'administrator';
$role->name = 'Administrator';
$role->description = 'Accounts used for administration.';
$role->save();


/*
 * Add permissions
 */
$cat = new model_permission_category();
$cat->bundle_name = 'administrator';
$cat->name = 'administration';
$cat->label = 'Administration';
$cat->save();


$permission = new model_permission();
$permission->permission_category_id = $cat->permission_category_id;
$permission->bundle_name = 'administrator';
$permission->name = 'can_login_to_administrator';
$permission->label = 'Can login to administrator';
$permission->description = 'Allows the user to access the administration area of the site.';
$permission->php_key = 'PERM_CAN_LOGIN_TO_ADMINISTRATOR';
$permission->save();

define($permission->php_key, $permission->permission_id);
$login_permission = $permission->permission_id;

/* Add the permission to the administator role */
$role_permission = new model_role_permission();
$role_permission->role_id = $role->role_id;
$role_permission->permission_id = $permission->permission_id;
$role_permission->save();

$permission = new model_permission();
$permission->permission_category_id = $cat->permission_category_id;
$permission->bundle_name = 'administrator';
$permission->name = 'can_manage_contacts';
$permission->label = 'Can manange site contacts';
$permission->description = 'Allows the user to manage contacts in the administration area of the site.';
$permission->php_key = 'PERM_CAN_MANAGE_CONTACTS';
$permission->save();

define($permission->php_key, $permission->permission_id);
$contact_permission = $permission->permission_id;

/* Add the permission to the administator role */
$role_permission = new model_role_permission();
$role_permission->role_id = $role->role_id;
$role_permission->permission_id = $permission->permission_id;
$role_permission->save();

$permission = new model_permission();
$permission->permission_category_id = $cat->permission_category_id;
$permission->bundle_name = 'administrator';
$permission->name = 'can_manage_users';
$permission->label = 'Can manange site users';
$permission->description = 'Allows the user to manage other user accounts in the administration area of the site.';
$permission->php_key = 'PERM_CAN_MANAGE_USER_ACCOUNTS';
$permission->save();

define($permission->php_key, $permission->permission_id);
$user_permission = $permission->permission_id;
 
 /* Add the permission to the administator role */
$role_permission = new model_role_permission();
$role_permission->role_id = $role->role_id;
$role_permission->permission_id = $permission->permission_id;
$role_permission->save();
 
/*
 * Add login permissions to the administrator role
 */
$role_permission = new model_role_permission();
$role_permission->role_id = $role->role_id;
$role_permission->permission_id = PERM_CAN_CHANGE_PASSWORD;
$role_permission->save();

$role_permission = new model_role_permission();
$role_permission->role_id = $role->role_id;
$role_permission->permission_id = PERM_CAN_LOGIN;
$role_permission->save();

$role_permission = new model_role_permission();
$role_permission->role_id = $role->role_id;
$role_permission->permission_id = PERM_CAN_MANAGE_CONTACTS;
$role_permission->save();

$role_permission = new model_role_permission();
$role_permission->role_id = $role->role_id;
$role_permission->permission_id = PERM_CAN_MANAGE_USER_ACCOUNTS;
$role_permission->save();

$cat = new model_permission_category();
$cat->bundle_name = 'administrator';
$cat->name = 'site_settings';
$cat->label = 'Site settings';
$cat->save();

$permission = new model_permission();
$permission->permission_category_id = $cat->permission_category_id;
$permission->bundle_name = 'administrator';
$permission->name = 'can_manage_bundles';
$permission->label = 'Can manage bundles';
$permission->description = 'Allows the user to manage bundles.';
$permission->php_key = 'PERM_CAN_MANAGE_BUNDLES';
$permission->save();
define($permission->php_key, $permission->permission_id);

 /* Add the permission to the administator role */
$role_permission = new model_role_permission();
$role_permission->role_id = $role->role_id;
$role_permission->permission_id = $permission->permission_id;
$role_permission->save();

$permission = new model_permission();
$permission->permission_category_id = $cat->permission_category_id;
$permission->bundle_name = 'administrator';
$permission->name = 'can_change_global_bundle_settings';
$permission->label = 'Can change bundle settings';
$permission->description = 'Allows the user to change the global bundle settings file (settings.xml).';
$permission->php_key = 'PERM_CAN_CHANGE_BUNDLE_SETTINGS';
$permission->save();
define($permission->php_key, $permission->permission_id);

 /* Add the permission to the administator role */
$role_permission = new model_role_permission();
$role_permission->role_id = $role->role_id;
$role_permission->permission_id = $permission->permission_id;
$role_permission->save();



 
/*
 * Create the administration menu
 */
$menu_type = new model_menu_type();
$menu_type->load_by_name('bs_navbar_nav');

if ($menu_type->is_loaded){
    
    $menu = new model_menu();
    $menu->menu_type_id = $menu_type->menu_type_id;
    $menu->bundle_name = 'administrator';
    $menu->name = 'administrator_main_navigation';
    $menu->label = "Administration menu";
    $menu->permission_id = $login_permission;
    $menu->save();
    
    $menu_item = new model_menu_item();
    $menu_item->menu_id = $menu->menu_id;
    $menu_item->bundle_name = 'administrator';
    $menu_item->label = 'Dashboard';
    $menu_item->menu_item_type = 'Link';
    $menu_item->link = '/administrator';
    $menu_item->priority = 1;
    $menu_item->permission_id = $login_permission;
    $menu_item->save();
    
    $menu_item = new model_menu_item();
    $menu_item->menu_id = $menu->menu_id;
    $menu_item->bundle_name = 'administrator';
    $menu_item->label = 'Contacts';
    $menu_item->menu_item_type = 'Link';
    $menu_item->link = '/administrator/contacts';
    $menu_item->priority = 98;
    $menu_item->permission_id = $contact_permission;
    $menu_item->save();
    
    $menu_item = new model_menu_item();
    $menu_item->menu_id = $menu->menu_id;
    $menu_item->bundle_name = 'administrator';
    $menu_item->label = 'Users';
    $menu_item->menu_item_type = 'Link';
    $menu_item->link = '/administrator/users';
    $menu_item->priority = 99;
    $menu_item->permission_id = $user_permission;
    $menu_item->save();
    
    /*
     * Add the left hand settings menu
     */
    $menu = new model_menu();
    $menu->menu_type_id = $menu_type->menu_type_id;
    $menu->bundle_name = 'administrator';
    $menu->name = 'administrator_settings_menu';
    $menu->label = 'Administrator Settings Menu';
    $menu->permission_id = $login_permission;
    $menu->save();
    
    $menu_item = new model_menu_item();
    $menu_item->menu_id = $menu->menu_id;
    $menu_item->bundle_name = 'administrator';
    $icon = new \extensions\font_awesome_views\view_icon('user');
    $menu_item->label = $icon->render();
    $menu_item->menu_item_type = 'Dropdown menu';
    $menu_item->name = 'user_account_menu';
    $menu_item->priority = 1;
    $menu_item->permission_id = PERM_CAN_LOGIN;
    $menu_item->save();
    
    $menu_item_item = new model_menu_item_item();
    $menu_item_item->menu_item_id = $menu_item->menu_item_id;
    $menu_item_item->bundle_name = 'administrator';
    $menu_item_item->menu_item_item_type = 'Link';
    $menu_item_item->link = '/profile/me';
    $menu_item_item->permission_id = PERM_CAN_LOGIN;
    $menu_item_item->label = 'View account';
    $menu_item_item->priority = 1;
    $menu_item_item->save();
    
    $menu_item_item = new model_menu_item_item();
    $menu_item_item->menu_item_id = $menu_item->menu_item_id;
    $menu_item_item->bundle_name = 'administrator';
    $menu_item_item->menu_item_item_type = 'Link';
    $menu_item_item->link = '/profile/change-password';
    $menu_item_item->permission_id = PERM_CAN_CHANGE_PASSWORD;
    $menu_item_item->label = 'Change password';
    $menu_item_item->priority = 2;
    $menu_item_item->save();
    
    $menu_item_item = new model_menu_item_item();
    $menu_item_item->menu_item_id = $menu_item->menu_item_id;
    $menu_item_item->bundle_name = 'octane_ats';
    $menu_item_item->menu_item_item_type = 'Divider';
    $menu_item_item->permission_id = PERM_CAN_LOGIN;
    $menu_item_item->label = '-';
    $menu_item_item->priority = 3;
    $menu_item_item->save();
    
    $menu_item_item = new model_menu_item_item();
    $menu_item_item->menu_item_id = $menu_item->menu_item_id;
    $menu_item_item->bundle_name = 'administrator';
    $menu_item_item->menu_item_item_type = 'Link';
    $menu_item_item->link = '/?action=sign-out';
    $menu_item_item->permission_id = PERM_CAN_LOGIN;
    $menu_item_item->label = 'Sign out';
    $menu_item_item->priority = 4;
    $menu_item_item->save();
    
    
    $menu_item = new model_menu_item();
    $menu_item->menu_id = $menu->menu_id;
    $menu_item->bundle_name = 'administrator';
    $icon = new \extensions\font_awesome_views\view_icon('cog');
    $menu_item->label = $icon->render();
    $menu_item->name = 'site_settings_menu';
    $menu_item->menu_item_type = 'Dropdown menu';
    $menu_item->priority = 2;
    $menu_item->permission_id = $login_permission;
    $menu_item->save();
    
    
    $menu_item_item = new model_menu_item_item();
    $menu_item_item->menu_item_id = $menu_item->menu_item_id;
    $menu_item_item->bundle_name = 'administrator';
    $menu_item_item->menu_item_item_type = 'Link';
    $menu_item_item->link = '/administrator/bundles';
    $menu_item_item->permission_id = PERM_CAN_MANAGE_BUNDLES;
    $menu_item_item->label = 'Manage bundles';
    $menu_item_item->priority = 1;
    $menu_item_item->save();
    
    $menu_item_item = new model_menu_item_item();
    $menu_item_item->menu_item_id = $menu_item->menu_item_id;
    $menu_item_item->bundle_name = 'administrator';
    $menu_item_item->menu_item_item_type = 'Link';
    $menu_item_item->link = '/administrator/settings';
    $menu_item_item->permission_id = PERM_CAN_CHANGE_BUNDLE_SETTINGS;
    $menu_item_item->label = 'Site settings';
    $menu_item_item->priority = 2;
    $menu_item_item->save();
    
    $menu_item = new model_menu_item();
    $menu_item->menu_id = $menu->menu_id;
    $menu_item->bundle_name = 'administrator';
    $menu_item->label = 'Main Site';
    $menu_item->menu_item_type = 'Link';
    $menu_item->link = '/';
    $menu_item->priority = 3;
    $menu_item->save();
}


/*
 * Create a table for dashboard views
 */
$sql->create_table('administrator_dashboard')
    ->add('administrator_dashboard_id', 'bigint')
    ->add('permission_id', 'bigint')
    ->add('name', 'varchar(64)')
    ->add('label', 'varchar(64)')
    ->add('custom_view', 'varchar(1024)', false)
    ->add('priority', 'int')
    ->add('date_created', 'datetime')
    ->add('date_modified', 'timestamp')
    ->add('date_deleted', 'datetime')
    ->primary_key('administrator_dashboard_id')
    ->foreign_key('permission_id', 'permission', 'permission_id')
    ->execute();


?>