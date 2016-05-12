<?php

namespace adapt\administrator{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;

    
    class view_form_page_section_menu_style extends \adapt\forms\view_form_page_section{
        public function __construct($form_data, &$user_data){
            parent::__construct($form_data, $user_data);
            
            
            $this->add(new html_label("Example"));
            $this->add(new html_div(self::example($user_data['menu[menu_type_id]']), array('class' => 'under-panel ajax-example example bottom')));
        }
        
        public function add_layout_engine($view){
            
        }
        
        
        public function add($view){
            $this->find('.groups')->append($view);
        }
        
        public static function example($menu_type_id){
            $adapt = $GLOBALS['adapt'];
            
            $sql = $adapt->data_source->sql;
            
            $sql->select('view')
                ->from('menu_type')
                ->where(
                    new sql_and(
                        new sql_cond('menu_type_id', sql::EQUALS, sql::q($menu_type_id)),
                        new sql_cond('date_deleted', sql::IS, new sql_null())
                    )
                );
            //print $sql;
            $results = $sql->execute()->results();
            
            if (is_array($results) && count($results) == 1){
                $class_name = $results[0]['view'];
                
                if (class_exists($class_name)){
                    $items = array();
                    
                    $item = new model_menu_item();
                    $item->menu_item_type = "Link";
                    $item->label = "Item 1";
                    $item->link = "#";
                    $items[] = $item;
                    
                    $item = new model_menu_item();
                    $item->menu_item_type = "Link";
                    $item->label = "Item 2";
                    $item->link = "#";
                    $items[] = $item;
                    
                    $item = new model_menu_item();
                    $item->menu_item_type = "Dropdown menu";
                    $item->label = "Item 3";
                    //$item->link = "#";
                    $items[] = $item;
                    
                    $sub_item = new model_menu_item_item();
                    $sub_item->menu_item_item_type = 'Link';
                    $sub_item->link = "#";
                    $sub_item->label = "Sub item 1";
                    $item->add($sub_item);
                    
                    $sub_item = new model_menu_item_item();
                    $sub_item->menu_item_item_type = 'Link';
                    $sub_item->link = "#";
                    $sub_item->label = "Sub item 2";
                    $item->add($sub_item);
                    
                    $sub_item = new model_menu_item_item();
                    $sub_item->menu_item_item_type = 'Link';
                    $sub_item->link = "#";
                    $sub_item->label = "Sub item 3";
                    $item->add($sub_item);
                    
                    
                    $item = new model_menu_item();
                    $item->menu_item_type = "Link";
                    $item->label = "Item 4";
                    $item->link = "#";
                    $items[] = $item;
                    
                    return new html_div(array(new $class_name($items, "Item 1"), new html_div(array('class' => 'clearfix'))));
                }
            }
            
        }
    }
}

?>