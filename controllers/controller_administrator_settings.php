<?php

namespace adapt\administrator{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class controller_administrator_settings extends controller{
        
        public function permission_action_save(){
            return $this->session->user->has_permission(PERM_CAN_CHANGE_BUNDLE_SETTINGS);
        }
        
        public function permission_view_default(){
            return $this->session->user->has_permission(PERM_CAN_CHANGE_BUNDLE_SETTINGS);
        }
        
        public function action_save(){
            $settings = array();
            $new_settings = array();
            
            $bundles = $this->bundles->list_bundles();
            
            foreach($bundles as $bundle_name){
                $bundle = $this->bundles->load_bundle($bundle_name);
                
                if ($bundle instanceof \adapt\bundle){
                    $bundle_settings = $bundle->get_bundle_settings();
                    
                    foreach($bundle_settings as $cat => $items){
                        foreach($items as $item){
                            $key = $item['name'];
                            $request_key = str_replace(".", "-", $key);
                            $settings[$key] = $this->setting($key);
                            $new_settings[$key] = $this->request[$request_key];
                            
                            if (stripos($new_settings[$key], "\n")){
                                $new_settings[$key] = str_replace("\r", "", $new_settings[$key]);
                                $new_settings[$key] = explode("\n", $new_settings[$key]);
                                $last_item = count($new_settings[$key]) - 1;
                                if ($last_item >= 0){
                                    if (!$new_settings[$key][$last_item]){
                                        array_pop($new_settings[$key]);
                                    }
                                }
                            }
                        }
                        
                    }
                }
            }
            
            $this->bundles->set_global_settings($new_settings);
            $this->bundles->save_global_settings();
            $this->bundles->apply_global_settings();
            
            $this->redirect("/administrator/site-settings");
        }
        
        public function view_default(){
            $container = new html_div(array('class' => 'under-panel top bottom'));
            $this->add_view(new html_div($container, array('class' => 'col-xs-12')));
            
            $form = new html_form(array('method' => 'post', 'action' => '/administrator/site-settings'));
            $form->add(new html_input(array('type' => 'hidden', 'name' => 'actions', 'value' => 'administrator/site-settings/save')));
            $container->add($form);
            
            $form->add(new html_h1("Site settings"));
            
            $settings = array();
            $setting_keys = array();
            
            $bundles = $this->bundles->list_bundles();
            
            foreach($bundles as $bundle_name){
                $bundle = $this->bundles->load_bundle($bundle_name);
                
                if ($bundle instanceof \adapt\bundle){
                    $bundle_settings = $bundle->get_bundle_settings();
                    
                    foreach($bundle_settings as $cat => $items){
                        if (!is_array($settings[$cat])){
                            $settings[$cat] = array();
                        }
                        
                        foreach($items as $item){
                            if (!in_array($item['name'], $setting_keys)){
                                $setting_keys[] = $item['name'];
                                $settings[$cat][] = $item;
                            }
                        }
                        
                    }
                }
            }
            
            foreach($settings as $cat => $items){
                $form->add(new html_h2($cat));
                foreach($items as $item){
                    $group = new html_div(array('class' => 'form-group'));
                    $form->add($group);
                    $group->add(new html_label($item['label']));
                    
                    if (isset($item['allowed_values'])){
                        $select = new html_select(array('name' => str_replace(".", "-", $item['name']), 'class' => 'form-control'));
                        $group->add($select);
                        
                        foreach($item['allowed_values'] as $value){
                            if ($this->setting($item['name']) == $value){
                                $select->add(new html_option($value, array("selected" => "selected")));
                            }else{
                                $select->add(new html_option($value));
                            }
                        }
                    }elseif(isset($item['default_values'])){
                        $textarea = new html_textarea(array('class' => 'form-control', 'name' => str_replace(".", "-", $item['name'])));
                        $group->add($textarea);
                        foreach($this->setting($item['name']) as $value){
                            $textarea->add($value . "\n");
                        }
                    }else{
                        $values = $this->setting($item['name']);
                        if (is_array($values)){
                            $textarea = new html_textarea(array('class' => 'form-control', 'name' => str_replace(".", "-", $item['name'])));
                            $group->add($textarea);
                            foreach($values as $value){
                                $textarea->add($value . "\n");
                            }
                        }else{
                            $group->add(new html_input(array('class' => 'form-control', 'type' => 'text', 'name' => str_replace(".", "-", $item['name']), 'value' => $values)));
                        }
                        
                    }
                }
            }
            
            $form->add(new html_div(new html_button("Save settings", array('class' => 'btn btn-primary control pull-right', 'type' => 'submit')), array('class' => 'controls')));
            $form->add(new html_div(array('class' => 'clearfix')));
        }
        
    }
    
}

?>
