<?php

namespace adapt\administrator{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class controller_administrator_roles extends controller_administrator_item{
        
        public function __construct(){
            
            //$this->request['filter']['role_id'] = $this->data_source->escape($this->request['filter']['role_id']);
            //$this->request['filter']['show_inactive'] = $this->data_source->escape($this->request['filter']['show_inactive']);
            
            
            $this->model = new model_role();
            parent::__construct();
            $this->title = "Role";
            $this->form_name = 'role';
            $this->sortable_fields = array('Name' => 'r.label');
            
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
                $this->add_action(new html_button("Delete this role", array('class' => 'action action-delete btn btn-link btn-block')));
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
                        '#' => 'r.role_id',
                        'Name' => 'r.label',
                        'Description' => 'r.description'
                    )
                )
                ->from('role', 'r');
                
            $where = new sql_and(new sql_cond('r.date_deleted', sql::IS, new sql_null()));
            
            if ($this->search_string != ''){
                $where->add(new sql_cond('r.name', "like", sql::q("%{$this->search_string}%")));
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
        
        
        /*
         * Actions
         */
        public function action_save(){
            if ($this->model && $this->model instanceof \adapt\model){
                $this->model->push($this->request);
                $this->model->save();
                $errors = $this->model->errors(true);
                
                if ($errors && is_array($errors) && count($errors)){
                    $this->respond($this->form_name, array('errors' => $errors));
                    $this->redirect($this->request['current_url']);
                }else{
                    /* Update permissions */
                    $permissions = array();
                    if (isset($this->request['role_permission']['permission_id']) && is_array($this->request['role_permission']['permission_id'])){
                        $permissions = $this->request['role_permission']['permission_id'];
                    }
                    
                    if (count($permissions)){
                        /* Make the permissions sql safe */
                        $safe_permissions = array();
                        foreach($permissions as $permission){
                            $safe_permissions[] = sql::q($permission);
                        }
                        
                        /* Remove permissions */
                        $sql = $this->data_source->sql
                            ->update('role_permission')
                            ->set('date_deleted', new sql_now())
                            ->where(
                                new sql_and(
                                    new sql_cond('role_id', sql::EQUALS, sql::q($this->model->role_id)),
                                    new sql_cond('permission_id', sql::NOT_IN, "(" . implode(", ", $safe_permissions) . ")"),
                                    new sql_cond('date_deleted', sql::IS, new sql_null())
                                )
                            );
                        //print new html_pre($sql);
                        //exit(1);
                        $sql->execute();
                            
                        /* Find the permissions we have */
                        $sql = $this->data_source->sql
                            ->select('permission_id')
                            ->from('role_permission')
                            ->where(
                                new sql_and(
                                    new sql_cond('role_id', sql::EQUALS, sql::q($this->model->role_id)),
                                    new sql_cond('date_deleted', sql::IS, new sql_null())
                                )
                            );
                        
                        $results = $sql->execute(0)->results();
                        
                        $current_permissions = array();
                        foreach($results as $result){
                            $current_permissions[] = $result['permission_id'];
                        }
                        
                        $missing_permissions = array();
                        foreach($permissions as $permission){
                            if (!in_array($permission, $current_permissions)){
                                $missing_permissions[] = $permission;
                            }
                        }
                        
                        if (count($missing_permissions)){
                            $data = array();
                            foreach($missing_permissions as $permission){
                                $data[] = array(
                                    'role_id' => $this->model->role_id,
                                    'permission_id' => $permission,
                                    'date_created' => new sql_now(),
                                    'date_modified' => new sql_now()
                                );
                            }
                            
                            $sql = $this->data_source->sql
                                ->insert_into('role_permission', array_keys($data[0]));
                                //->values($data);
                            //print_r($data);
                            foreach($data as $row) $sql->values(array_values($row));
                            //print $sql;
                            //print new html_pre(print_r($this->data_source->errors(), true));
                            //print new html_pre(print_r($sql->errors(), true));
                            //exit(1);
                            $sql->execute();
                            
                        }
                    }else{
                        print $this->data_source->sql
                            ->update('role_permission')
                            ->set('date_deleted', new sql_now())
                            ->where(
                                new sql_and(
                                    new sql_cond('role_id', sql::EQUALS, sql::q($this->model->role_id)),
                                    new sql_cond('date_deleted', sql::IS, new sql_null())
                                )
                            )
                            ->execute();
                    }
                    
                    /* Update password policies */
                    $policies = array();
                    if (isset($this->request['role_password_policy']['password_policy_id']) && is_array($this->request['role_password_policy']['password_policy_id'])){
                        $policies = $this->request['role_password_policy']['password_policy_id'];
                    }
                    
                    if (count($policies)){
                        /* Make the policy sql safe */
                        $safe_policies= array();
                        foreach($policies as $policy){
                            $safe_policies[] = sql::q($policy);
                        }
                        
                        /* Remove policies */
                        $this->data_source->sql
                            ->update('role_password_policy')
                            ->set('date_deleted', new sql_now())
                            ->where(
                                new sql_and(
                                    new sql_cond('role_id', sql::EQUALS, sql::q($this->model->role_id)),
                                    new sql_cond('password_policy_id', sql::NOT_IN, "(" . implode(", ", $safe_policies) . ")"),
                                    new sql_cond('date_deleted', sql::IS, new sql_null())
                                )
                            )
                            ->execute();
                            
                        /* Find the policies we have */
                        $sql = $this->data_source->sql
                            ->select('password_policy_id')
                            ->from('role_password_policy')
                            ->where(
                                new sql_and(
                                    new sql_cond('role_id', sql::EQUALS, sql::q($this->model->role_id)),
                                    new sql_cond('date_deleted', sql::IS, new sql_null())
                                )
                            );
                        
                        $results = $sql->execute(0)->results();
                        
                        $current_policies = array();
                        foreach($results as $result){
                            $current_policies[] = $result['password_policy_id'];
                        }
                        
                        $missing_policies = array();
                        foreach($policies as $policy){
                            if (!in_array($policy, $current_policies)){
                                $missing_policies[] = $policy;
                            }
                        }
                        
                        if (count($missing_policies)){
                            $data = array();
                            foreach($missing_policies as $policy){
                                $data[] = array(
                                    'role_id' => $this->model->role_id,
                                    'password_policy_id' => $policy,
                                    'date_created' => new sql_now(),
                                    'date_modified' => new sql_now()
                                );
                            }
                            
                            $sql = $this->data_source->sql
                                ->insert_into('role_password_policy', array_keys($data[0]));
                                //->values($data);
                            
                            foreach($data as $row) $sql->values(array_values($row));
                            $sql->execute();
                            
                        }
                    }else{
                        $this->data_source->sql
                            ->update('role_password_policy')
                            ->set('date_deleted', new sql_now())
                            ->where(
                                new sql_and(
                                    new sql_cond('role_id', sql::EQUALS, sql::q($this->model->role_id)),
                                    new sql_cond('date_deleted', sql::IS, new sql_null())
                                )
                            )
                            ->execute();
                    }
                }
                
                /* Mark the role as modified */
                $this->model->date_modified = new sql_now();
                $this->model->save();
                
                $this->redirect('/' . $this->request['url']);
            }
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
