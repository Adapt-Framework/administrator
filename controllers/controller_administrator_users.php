<?php

namespace extensions\administrator{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class controller_administrator_users extends controller_administrator_item{
        
        public function __construct(){
            
            $this->request['filter']['role_id'] = $this->data_source->escape($this->request['filter']['role_id']);
            $this->request['filter']['show_inactive'] = $this->data_source->escape($this->request['filter']['show_inactive']);
            
            
            $this->model = new model_user();
            parent::__construct();
            $this->title = 'User';
            $this->form_name = 'user';
            $this->sortable_fields = array('Name' => 'c.surname', 'Email' => 'ce.email', 'Username' => 'u.username');
            
            /* Add filters */
            $filters = array();
            
            
            
            /* Filter: Group */
            $sql = $this->data_source->sql;
            $sql->select(array('id' => 'role_id', 'name' => 'name'))
                ->from('role')
                ->where(new \frameworks\adapt\sql_condition($this->data_source->sql('date_deleted'), 'is', $this->data_source->sql('null')))
                ->order_by('name');
            
            $results = $sql->execute()->results();
            $items = array('*' => 'All Roles');
            
            foreach($results as $result){
                $items[$result['id']] = $result['name'];
            }
            $control = new \extensions\forms\view_field_select(
                array(
                    'name' => 'filter[role_id]',
                    'allowed_values' => $items,
                    'value' => '*',
                    'label' => 'Role'
                )
            );
            $control->find('label')->add_class('sr-only');
            $filters[] = $control;
            
            /* Filter: In-active users */
            $control = new \extensions\bootstrap_views\view_input_radio(new \extensions\bootstrap_views\view_input("checkbox", 'filter[show_inactive]', 'Yes'), 'Include inactive users');
            $control->find('.form-control')->remove_class('form-control');
            $filters[] = $control;
            
            $this->filters = $filters;
        }
        
        /*
         * Helper functions
         */
        public function get_statement(){
            $sql = $this->data_source->sql;
            
            $where = null;
            
            if ($this->setting('users.username_type') == 'Email'){
                $sql->select(
                    array(
                        '#' => 'u.user_id',
                        'Name' => $this->data_source->sql("trim(concat(c.title, ' ', c.forename, ' ', c.surname))"),
                        'Email' => 'ce.email'
                    )
                );
                
                $sql->from('user', 'u')
                    ->join('contact', 'c', 'contact_id')
                    ->join('contact_email', 'ce', 'contact_id');
                
                $where = new \frameworks\adapt\sql_condition(new \frameworks\adapt\sql('u.date_deleted'), 'is', new \frameworks\adapt\sql('null'));
                $where = new \frameworks\adapt\sql_and($where, new \frameworks\adapt\sql_condition(new \frameworks\adapt\sql('c.date_deleted'), 'is', new \frameworks\adapt\sql('null')));
                $where = new \frameworks\adapt\sql_and($where, new \frameworks\adapt\sql_condition(new \frameworks\adapt\sql('ce.date_deleted'), 'is', new \frameworks\adapt\sql('null')));
                $where = new \frameworks\adapt\sql_and($where, new \frameworks\adapt\sql_condition(new \frameworks\adapt\sql('ce.priority'), '=', '1'));
                
                if ($this->search_string != ''){
                    $where = new \frameworks\adapt\sql_and($where, new \frameworks\adapt\sql_condition(new \frameworks\adapt\sql("trim(concat(c.title, ' ', c.forename, ' ', c.surname))"), 'like', "%{$this->search_string}%"));
                    $where = new \frameworks\adapt\sql_and($where, new \frameworks\adapt\sql_condition(new \frameworks\adapt\sql("ce.email"), 'like', "%{$this->search_string}%"));
                }
                
            }else{
                $sql->select(
                    array(
                        '#' => 'u.user_id',
                        'Name' => $this->data_source->sql("trim(concat(c.title, ' ', c.forename, ' ', c.surname))"),
                        'Username' => 'u.username'
                    )
                );
                
                $sql->from('user', 'u')
                    ->join('contact', 'c', 'contact_id', \frameworks\adapt\sql::LEFT_JOIN);
                
                $where = new \frameworks\adapt\sql_condition(new \frameworks\adapt\sql('u.date_deleted'), 'is', new \frameworks\adapt\sql('null'));
                $where = new \frameworks\adapt\sql_and($where, new \frameworks\adapt\sql_condition(new \frameworks\adapt\sql('c.date_deleted'), 'is', new \frameworks\adapt\sql('null')));
                
                if ($this->search_string != ''){
                    $where = new \frameworks\adapt\sql_and($where, new \frameworks\adapt\sql_condition(new \frameworks\adapt\sql("trim(concat(c.title, ' ', c.forename, ' ', c.surname))"), 'like', "%{$this->search_string}%"));
                    $where = new \frameworks\adapt\sql_and($where, new \frameworks\adapt\sql_condition(new \frameworks\adapt\sql("u.username"), 'like', "%{$this->search_string}%"));
                }
                
            }
            
            if (strtolower($this->request['filter']['show_inactive']) != 'yes'){
                $where = new \frameworks\adapt\sql_and($where, new \frameworks\adapt\sql_condition(new \frameworks\adapt\sql('u.status'), '=', 'Active'));
            }
            
            
            if (!in_array($this->request['filter']['role_id'], array('', '*'))){
                $sql->join('role_user', 'ru', 'user_id');
                $where = new \frameworks\adapt\sql_and($where, new \frameworks\adapt\sql_condition(new \frameworks\adapt\sql('ru.date_deleted'), 'is', new \frameworks\adapt\sql('null')));
                $where = new \frameworks\adapt\sql_and($where, new \frameworks\adapt\sql_condition(new \frameworks\adapt\sql('ru.role_id'), '=', $this->request['filter']['role_id']));
            }
            
            
            $sql->where($where);
            
            return $sql;
        }
        
        /*
         * Action Permissions
         */
        public function permission_action_save(){
            return $this->session->user->has_permission(PERM_CAN_MANAGE_USER_ACCOUNTS);
        }
        
        /*
         * View Permissions
         */
        public function permission_view_default(){
            return $this->session->user->has_permission(PERM_CAN_MANAGE_USER_ACCOUNTS);
        }
        
        public function permission_view_new(){
            return $this->session->user->has_permission(PERM_CAN_MANAGE_USER_ACCOUNTS);
        }
        
    }
    
}

?>
