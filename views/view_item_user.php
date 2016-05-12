<?php

namespace adapt\administrator{
    
    /* Prevent Direct Access */
    defined('ADAPT_STARTED') or die;
    
    class view_item_user extends view_item{
        
        
        public function __construct($model){
            parent::__construct($model);
            
            //$this->add_class('inner');
            
            //$this->add(new html_pre(print_r($model->to_hash(), true)));
            
            if ($model && $model instanceof \adapt\model && $model->table_name == "user"){
                $title = null;
                
                if (trim($model->contact->name) != ""){
                    $title = $model->contact->name;
                }else{
                    if ($this->setting("users.username_type") == "Username"){
                        $title = $model->username;
                    }else{
                        
                        $children = $model->contact->get();
                        foreach($children as $child){
                            if ($child instanceof \adapt\model && $child->table_name == 'contact_email'){
                                $title = $child->email;
                            }
                        }
                    }
                }
                
                if (is_null($title) || $title == ""){
                    $title = "User #" . $model->user_id;
                }
                
                $this->title->add($title);
                
                $account_form = new \adapt\forms\model_form();
                $account_form->load_by_name('user_account_details');
                
                $this->add_pane('Account details', $account_form->get_view($model->to_hash_string()));
                $this->add_pane('Personal information', 'Personal information');
                $this->add_pane('Permission groups', 'Permission groups');
                $this->add_pane('Session history', 'Sessions');
                $this->add_pane('Security tokens', 'Tokens');
                
                $this->add_action(new \font_awesome\views\view_icon('key'), 'default', 'Reset password', '');
                $this->add_action(new \font_awesome\views\view_icon('ban'), 'warning', 'Suspend user', '');
                $this->add_action(new \font_awesome\views\view_icon('trash'), 'danger', 'Delete user', '');
            }
            
        }
        
    }
    
}

?>