<?php

namespace adapt\administrator{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class controller_administrator_password_policy extends controller_administrator_item{
        
        public function __construct(){
            
            //$this->request['filter']['role_id'] = $this->data_source->escape($this->request['filter']['role_id']);
            //$this->request['filter']['show_inactive'] = $this->data_source->escape($this->request['filter']['show_inactive']);
            
            
            $this->model = new model_password_policy();
            parent::__construct();
            $this->title = "Password policy";
            $this->form_name = 'password_policy';
            $this->sortable_fields = array('Name' => 'p.label');
            
            /* Add filters */
            $filters = array();
            
            
            
            /* Filter: Group */
            //$sql = $this->data_source->sql;
            //$sql->select(array('id' => 'role_id', 'name' => 'name'))
            //    ->from('role')
            //    ->where(new sql_cond('date_deleted', sql::IS, new sql_null()))
            //    ->order_by('name');
            //
            //$results = $sql->execute()->results();
            //$items = array('*' => 'All Roles');
            //
            //foreach($results as $result){
            //    $items[$result['id']] = $result['name'];
            //}
            //$control = new \adapt\forms\view_field_select(
            //    array(
            //        'name' => 'filter[role_id]',
            //        'allowed_values' => $items,
            //        'value' => '*',
            //        'label' => 'Role'
            //    )
            //);
            //$control->find('label')->add_class('sr-only');
            //$filters[] = $control;
            //
            ///* Filter: In-active users */
            //$control = new \bootstrap\views\view_input_radio(new \bootstrap\views\view_input("checkbox", 'filter[show_inactive]', 'Yes'), 'Include inactive users');
            //$control->find('.form-control')->remove_class('form-control');
            //$filters[] = $control;
            //
            //$this->filters = $filters;
            //
            //if ($this->permission_action_reset_password()){
            //    $this->add_action(new html_button("Reset the users password", array('class' => 'action action-reset-password btn btn-link btn-block')));
            //}
            //
            //if ($this->permission_action_require_password_change()){
            //    $this->add_action(new html_button("Require the user to change thier password", array('class' => 'action action-require-password-change btn btn-link btn-block')));
            //}
            //
            //if ($this->permission_action_suspend()){
            //    $this->add_action(new html_button("Suspend this user account", array('class' => 'action action-suspend btn btn-link btn-block')));
            //}
            
            if ($this->permission_action_delete()){
                $this->add_action(new html_button("Delete this policy", array('class' => 'action action-delete btn btn-link btn-block')));
            }
        }
        
        /*
         * Permissions
         */
        public function permission_action_delete(){
            return true;
        }
        
        //public function permission_action_suspend(){
        //    return true;
        //}
        //
        //public function permission_action_reset_password(){
        //    return true;
        //}
        //
        //public function permission_action_require_password_change(){
        //    return true;
        //}
        
        /*
         * Helper functions
         */
        public function get_statement(){
            $sql = $this->data_source->sql;
            
            $where = null;
            
            $sql
                ->select(
                    array(
                        '#' => 'p.password_policy_id',
                        'Name' => 'p.label',
                        'Description' => 'p.description'
                    )
                )
                ->from('password_policy', 'p');
                
            $where = new sql_and(new sql_cond('p.date_deleted', sql::IS, new sql_null()));
            
            if ($this->search_string != ''){
                $where->add(new sql_cond('p.label', "like", sql::q("%{$this->search_string}%")));
            }
            
            
            //if ($this->setting('users.username_type') == 'Email'){
            //    $sql->select(
            //        array(
            //            '#' => 'u.user_id',
            //            'Name' => new sql_trim(new sql_concat('c.title', sql::q(" "), 'c.forename', sql::q(" "), 'c.surname')),
            //            'Email' => 'ce.email'
            //        )
            //    );
            //    
            //    $sql->from('user', 'u')
            //        ->join('contact', 'c', 'contact_id')
            //        ->join('contact_email', 'ce', 'contact_id');
            //    
            //    $where = new sql_and(
            //        new sql_cond('u.date_deleted', sql::IS, new sql_null()),
            //        new sql_cond('c.date_deleted', sql::IS, new sql_null()),
            //        new sql_cond('ce.date_deleted', sql::IS, new sql_null()),
            //        new sql_cond('ce.priority', sql::EQUALS, sql::q("1"))
            //    );
            //    
            //    
            //    if ($this->search_string != ''){
            //        $where->add(new sql_cond(new sql_cond(new sql_trim(new sql_concat('c.title', sql::q(" "), 'c.forename', sql::q(" "), 'c.surname')), "like", sql::q("%{$this->search_string}%"))));
            //        $where->add(new sql_cond(new sql_cond('ce.email', "like", sql::q("%{$this->search_string}%"))));
            //        //$where = new \adapt\sql_and($where, new \adapt\sql_condition(new \adapt\sql("trim(concat(c.title, ' ', c.forename, ' ', c.surname))"), 'like', "%{$this->search_string}%"));
            //        //$where = new \adapt\sql_and($where, new \adapt\sql_condition(new \adapt\sql("ce.email"), 'like', "%{$this->search_string}%"));
            //    }
            //    
            //}else{
            //    $sql->select(
            //        array(
            //            '#' => 'u.user_id',
            //            'Name' => new sql_trim(new sql_concat('c.title', sql::q(" "), 'c.forename', sql::q(" "), 'c.surname')),
            //            'Username' => 'u.username'
            //        )
            //    );
            //    
            //    $sql->from('user', 'u')
            //        ->left_join('contact', 'c', 'contact_id');
            //    
            //    $where = new sql_new(
            //        new sql_cond('u.date_deleted', sql::IS, new sql_null()),
            //        new sql_cond('c.date_deleted', sql::IS, new sql_null())
            //    );
            //    
            //    //$where = new \adapt\sql_condition(new \adapt\sql('u.date_deleted'), 'is', new \adapt\sql('null'));
            //    //$where = new \adapt\sql_and($where, new \adapt\sql_condition(new \adapt\sql('c.date_deleted'), 'is', new \adapt\sql('null')));
            //    
            //    //if ($this->search_string != ''){
            //    //    $where = new \adapt\sql_and($where, new \adapt\sql_condition(new \adapt\sql("trim(concat(c.title, ' ', c.forename, ' ', c.surname))"), 'like', "%{$this->search_string}%"));
            //    //    $where = new \adapt\sql_and($where, new \adapt\sql_condition(new \adapt\sql("u.username"), 'like', "%{$this->search_string}%"));
            //    //}
            //    
            //    if ($this->search_string != ''){
            //        $where->add(new sql_cond(new sql_cond(new sql_trim(new sql_concat('c.title', sql::q(" "), 'c.forename', sql::q(" "), 'c.surname')), "like", sql::q("%$this->search_string%"))));
            //        $where->add(new sql_cond(new sql_cond('ce.email', "like", sql::q("%$this->search_string%"))));
            //        //$where = new \adapt\sql_and($where, new \adapt\sql_condition(new \adapt\sql("trim(concat(c.title, ' ', c.forename, ' ', c.surname))"), 'like', "%{$this->search_string}%"));
            //        //$where = new \adapt\sql_and($where, new \adapt\sql_condition(new \adapt\sql("ce.email"), 'like', "%{$this->search_string}%"));
            //    }
            //    
            //}
            //
            //if (strtolower($this->request['filter']['show_inactive']) != 'yes'){
            //    $where->add(new sql_cond('u.status', sql::EQUALS, sql::q("Active")));
            //    //$where = new \adapt\sql_and($where, new \adapt\sql_condition(new \adapt\sql('u.status'), '=', 'Active'));
            //}
            //
            //
            //if (!in_array($this->request['filter']['role_id'], array('', '*'))){
            //    $sql->join('role_user', 'ru', 'user_id');
            //    //$where = new \adapt\sql_and($where, new \adapt\sql_condition(new \adapt\sql('ru.date_deleted'), 'is', new \adapt\sql('null')));
            //    //$where = new \adapt\sql_and($where, new \adapt\sql_condition(new \adapt\sql('ru.role_id'), '=', $this->request['filter']['role_id']));
            //    $where->add(new sql_cond('ru.date_deleted', sql::IS, new sql_null()));
            //    $where->add(new sql_cond('ru.role_id', sql::EQUALS, $this->request['filter']['role_id']));
            //}
            
            
            $sql->where($where);
            //print new html_pre($sql);
            return $sql;
        }
        
        /*
         * Action Permissions
         */
        public function permission_action_save(){
            return $this->session->user->has_permission(PERM_CAN_MANAGE_PASSWORD_POLICIES);
        }
        
        /*
         * View Permissions
         */
        public function permission_view_default(){
            return $this->session->user->has_permission(PERM_CAN_MANAGE_PASSWORD_POLICIES);
        }
        
        public function permission_view_new(){
            return $this->session->user->has_permission(PERM_CAN_MANAGE_PASSWORD_POLICIES);
        }
        
        //public function view_edit(){
            //$this->add_view($this->model->to_form());
        //    if ($this->model->is_loaded){
        //        $this->add_view(new view_item_user($this->model));
        //    }else{
        //        parent::edit();
        //    }
        //}
        
        
    }
    
}

?>
