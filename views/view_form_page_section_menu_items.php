<?php

namespace adapt\administrator{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;

    
    class view_form_page_section_menu_items extends \adapt\forms\view_form_page_section{
        public function __construct($form_data, &$user_data){
            parent::__construct($form_data, $user_data);
            
            //$this->add(new html_pre(print_r($user_data, true)));
            $this->add(new html_label("Items"));
            //$this->add(new html_div(self::example($user_data['menu[menu_type_id]']), array('class' => 'under-panel ajax-example example bottom')));
            $builder = new html_div(array('class' => 'bottom menu-builder'));
            $this->add($builder);
            $this->add(new html_button(new \font_awesome\views\view_icon('plus'), array('class' => 'btn btn-link action new-menu')));
            
            $menu_items = array();
            
            if (isset($user_data['menu_item[menu_item_id][]']) && is_array($user_data['menu_item[menu_item_id][]'])){
                for($i = 0; $i < count($user_data['menu_item[menu_item_id][]']); $i++){
                    $key = 'menu_item-' . $user_data['menu_item[menu_item_id][]'][$i];
                    
                    $item_data = array(
                        'menu_item_id' => $user_data['menu_item[menu_item_id][]'][$i],
                        'name' => $user_data['menu_item[name][]'][$i],
                        'label' => $user_data['menu_item[label][]'][$i],
                        'link' => $user_data['menu_item[link][]'][$i],
                        'priority' => $user_data['menu_item[priority][]'][$i],
                        'menu_item_type' => $user_data['menu_item[menu_item_type][]'][$i],
                        '_group' => md5($user_data['menu_item[menu_item_id][]'][$i]),
                        'menu_item_items' => array()
                    );
                    
                    $menu_items[$key] = $item_data;
                }
            }elseif (isset($user_data['menu_item[menu_item_id]'])){
                $key = 'menu_item-' . $user_data['menu_item[menu_item_id]'];
                
                $item_data = array(
                    'menu_item_id' => $user_data['menu_item[menu_item_id]'],
                    'name' => $user_data['menu_item[name]'],
                    'label' => $user_data['menu_item[label]'],
                    'link' => $user_data['menu_item[link]'],
                    'priority' => $user_data['menu_item[priority]'],
                    'menu_item_type' => $user_data['menu_item[menu_item_type]'],
                    '_group' => md5($user_data['menu_item[menu_item_id]']),
                    'menu_item_items' => array()
                );
                
                $menu_items[$key] = $item_data;
            }
            
            if (isset($user_data['menu_item_item[menu_item_item_id][]']) && is_array($user_data['menu_item_item[menu_item_item_id][]'])){
                
                for($i = 0; $i < count($user_data['menu_item_item[menu_item_item_id][]']); $i++){
                    $key = 'menu_item-' . $user_data['menu_item_item[menu_item_id][]'][$i];
                    
                    $menu_items[$key]['menu_item_items'][] = array(
                        'menu_item_item_id' => $user_data['menu_item_item[menu_item_item_id][]'][$i],
                        'label' => $user_data['menu_item_item[label][]'][$i],
                        'priority' => $user_data['menu_item_item[priority][]'][$i],
                        'link' => $user_data['menu_item_item[link][]'][$i],
                        'menu_item_item_type' => $user_data['menu_item_item[menu_item_item_type][]'][$i],
                        '_group' => md5($user_data['menu_item_item[menu_item_item_id][]'][$i])
                    );
                }
                
            }elseif (isset($user_data['menu_item_item[menu_item_item_id]'])){
                $key = 'menu_item-' . $user_data['menu_item_item[menu_item_id]'];
                
                $menu_items[$key]['menu_item_items'][] = array(
                    'menu_item_item_id' => $user_data['menu_item_item[menu_item_item_id]'],
                    'label' => $user_data['menu_item_item[label]'],
                    'priority' => $user_data['menu_item_item[priority]'],
                    'link' => $user_data['menu_item_item[link]'],
                    'menu_item_item_type' => $user_data['menu_item_item[menu_item_item_type]'],
                    '_group' => md5($user_data['menu_item_item[menu_item_item_id]'])
                );
            }
            
            //$this->add(new html_pre(print_r($menu_items, true)));
            
            foreach($menu_items as $key => $item){
                $item_view = new html_div(array('class' => 'under-panel menu-item'));
                $builder->add($item_view);
                
                
                $controls = new html_div(array('class' => 'controls pull-right'));
                $controls->add(new html_button(new html_span(array('class' => 'fa fa-pencil')), array('class' => 'btn btn-link action edit-menu')));
                $controls->add(new html_button(new html_span(array('class' => 'fa fa-trash')), array('class' => 'btn btn-link action delete-menu')));
                $item_view->add($controls);
                
                $item_view->add(new html_span($item['label'], array('class' => 'menu-label')));
                
                if ($item['menu_item_type'] == "Dropdown menu"){
                    $item_view->add(new html_span(new html_span(array('class' => 'caret')), array('class' => 'menu-dropdown-indicator')));
                    $item_view->add(new html_span("(" . $item['link'] . ")", array('class' => 'menu-link hidden')));
                }else{
                    $item_view->add(new html_span(new html_span(array('class' => 'caret')), array('class' => 'menu-dropdown-indicator hidden')));
                    $item_view->add(new html_span("(" . $item['link'] . ")", array('class' => 'menu-link')));
                }
                
                $form_data = new html_div(array('class' => 'form-menu-data'));
                $item_view->add($form_data);
                
                $form_data->add(new html_input(array('type' => 'hidden','name' => 'menu_item[menu_item_id][]', 'value' => $item['menu_item_id'])));
                $form_data->add(new html_input(array('type' => 'hidden','name' => 'menu_item[menu_item_type][]', 'value' => $item['menu_item_type'])));
                $form_data->add(new html_input(array('type' => 'hidden','name' => 'menu_item[priority][]', 'value' => $item['priority'])));
                $form_data->add(new html_input(array('type' => 'hidden','name' => 'menu_item[link][]', 'value' => $item['link'])));
                $form_data->add(new html_input(array('type' => 'hidden','name' => 'menu_item[name][]', 'value' => $item['name'])));
                $form_data->add(new html_input(array('type' => 'hidden','name' => 'menu_item[label][]', 'value' => $item['label'])));
                $form_data->add(new html_input(array('type' => 'hidden', 'class' => 'menu-group', 'name' => 'menu_item[_group][]', 'value' => $item['_group'])));
                
                $sub_menu = new html_div(array('class' => 'sub-menu-items'));
                $item_view->add($sub_menu);
                
                $button = new html_button(new html_span(array('class' => 'fa fa-plus')), array('class' => 'btn btn-link action new-sub-menu-item'));
                $item_view->add($button);
                
                if ($item['menu_item_type'] == "Dropdown menu"){
                    foreach($item['menu_item_items'] as $sub_item){
                        $sub_item_view = new html_div(array('class' => 'under-panel sub-menu-item'));
                        $sub_menu->add($sub_item_view);
                        $sub_item_view->add(
                            new html_div(
                                array(
                                    new html_button(
                                        new html_span(array('class' => 'fa fa-pencil')),
                                        array('class' => 'btn btn-link action edit-sub-menu-item')
                                    ),
                                    new html_button(
                                        new html_span(array('class' => 'fa fa-trash')),
                                        array('class' => 'btn btn-link action delete-sub-menu-item')
                                    )
                                ),
                                array('class' => 'controls pull-right')
                            )
                        );
                        
                        $hr = new html_hr();
                        $sub_item_view->add($hr);
                        
                        $label = new html_span($sub_item['label'], array('class' => 'menu-label'));
                        $sub_item_view->add($label);
                        
                        $link = new html_span('(' . $sub_item['link'] . ')', array('class' => 'menu-link'));
                        $sub_item_view->add($link);
                        
                        $sub_item_view->add(
                            new html_div(
                                array(
                                    new html_input(array('type' => 'hidden', 'name' => 'menu_item_item[menu_item_item_id][]', 'value' => $sub_item['menu_item_item_id'])),
                                    new html_input(array('type' => 'hidden', 'name' => 'menu_item_item[menu_item_item_type][]', 'value' => $sub_item['menu_item_item_type'])),
                                    new html_input(array('type' => 'hidden', 'name' => 'menu_item_item[priority][]', 'value' => $sub_item['priority'])),
                                    new html_input(array('type' => 'hidden', 'name' => 'menu_item_item[link][]', 'value' => $sub_item['link'])),
                                    new html_input(array('type' => 'hidden', 'name' => 'menu_item_item[label][]', 'value' => $sub_item['label'])),
                                    new html_input(array('type' => 'hidden', 'name' => 'menu_item_item[_group][]', 'value' => $item['_group']))
                                ),
                                array('class' => 'form-sub-menu-data')
                            )
                        );
                        
                        if ($sub_item['menu_item_item_type'] == "Link"){
                            $hr->add_class('hidden');
                        }elseif ($sub_item['menu_item_item_type'] == "Heading"){
                            $hr->add_class('hidden');
                            $link->add_class('hidden');
                        }else{
                            $label->add_class('hidden');
                            $link->add_class('hidden');
                        }
                    }
                }else{
                    $sub_menu->add_class('hidden');
                    $button->add_class('hidden');
                }
                
                
            }
            
        }
        
        public function add_layout_engine($view){
            
        }
        
        
        public function add($view){
            $this->find('.groups')->append($view);
        }
        
    }
}

?>