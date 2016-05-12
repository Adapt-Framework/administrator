<?php

namespace adapt\administrator{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class controller_administrator_menus extends controller_administrator_item{
        
        public function __construct(){
            
            //$this->request['filter']['role_id'] = $this->data_source->escape($this->request['filter']['role_id']);
            //$this->request['filter']['show_inactive'] = $this->data_source->escape($this->request['filter']['show_inactive']);
            
            
            $this->model = new model_menu();
            parent::__construct();
            $this->title = "Menu";
            $this->form_name = 'menu';
            $this->sortable_fields = array('Label' => 'm.label');
            
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
                $this->add_action(new html_button("Delete this menu", array('class' => 'action action-delete btn btn-link btn-block')));
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
                        '#' => 'm.menu_id',
                        'Label' => 'm.label',
                        'Description' => 'm.description'
                    )
                )
                ->from('menu', 'm');
                //TODO: Deal with menu permissions
            $where = new sql_and(new sql_cond('m.date_deleted', sql::IS, new sql_null()));
            
            if ($this->search_string != ''){
                $where->add(new sql_cond('m.label', "like", sql::q("%{$this->search_string}%")));
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
            //print new html_pre(print_r($this->request, true));
            if ($this->model && $this->model instanceof \adapt\model){
                $this->model->push(array('menu' => $this->request['menu'])); //Prevent cascade
                $this->model->owner_id = $this->session->user->user_id;
                $this->model->date_modified = new sql_now(); //Ensure the timestamp is updated
                $this->model->save();
                
                $errors = $this->model->errors(true);
                if ($errors && is_array($errors) && count($errors)){
                    $this->respond($this->form_name, array('errors' => $errors));
                    $this->redirect($this->request['current_url']);
                }else{
                    
                    if (is_array($this->request['menu_item']['menu_item_id']) && count($this->request['menu_item']['menu_item_id'])){
                        
                        /* Get a list of ID's */
                        $ids = array();
                        
                        foreach($this->request['menu_item']['menu_item_id'] as $id){
                            if (is_numeric($id)){
                                $ids[] = $id;
                            }
                        }
                        
                        /* Lets delete any records that are not listed here */
                        if (count($ids)){
                            $sql = $this->data_source->sql;
                            
                            $sql->update('menu_item')
                                ->set('date_deleted', new sql_now())
                                ->where(
                                    new sql_and(
                                        new sql_cond('date_deleted', sql::IS, new sql_null()),
                                        new sql_cond('menu_id', sql::EQUALS, $this->model->menu_id),
                                        new sql_cond('menu_item_id', sql::NOT_IN, "(" . implode(", ", $ids) . ")")
                                    )
                                );
                            
                            $sql->execute();
                        
                            $sql = $this->data_source->sql;
                            
                            $sql->update('menu_item_item')
                                ->set('date_deleted', new sql_now())
                                ->where(
                                    new sql_and(
                                        new sql_cond('date_deleted', sql::IS, new sql_null()),
                                        new sql_cond('menu_item_id', sql::IN, $this->data_source->sql
                                            ->select('menu_item_id')
                                            ->from('menu_item')
                                            ->where(
                                                new sql_cond('menu_id', sql::EQUALS, $this->model->menu_id), //Intentionally not filtered on date_deleted
                                                new sql_cond('menu_item_id', sql::NOT_IN, "(" . implode(", ", $ids) . ")")
                                            )
                                        )
                                    )
                                );
                            
                            $sql->execute();
                        }
                        
                        /* Lets create or update as needed */
                        for($i = 0; $i < count($this->request['menu_item']['menu_item_id']); $i++){
                            $id = $this->request['menu_item']['menu_item_id'][$i];
                            $type = $this->request['menu_item']['menu_item_type'][$i];
                            $priority = $this->request['menu_item']['priority'][$i];
                            $link = $this->request['menu_item']['link'][$i];
                            $name = $this->request['menu_item']['name'][$i];
                            $label = $this->request['menu_item']['label'][$i];
                            $group = $this->request['menu_item']['_group'][$i];
                            
                            $menu_item = new model_menu_item($id);
                            $menu_item->errors(true);
                            $menu_item->menu_item_type = $type;
                            $menu_item->priority = $priority;
                            $menu_item->link = $link;
                            $menu_item->name = $name;
                            $menu_item->label = $label;
                            $menu_item->menu_id = $this->model->menu_id;
                            
                            $menu_item->save();
                            $sub_menu_items = array();
                            $sub_menu_ids = array();
                            
                            if (is_array($this->request['menu_item_item']['menu_item_item_id']) && count($this->request['menu_item_item']['menu_item_item_id'])){
                                
                                /* Get a list of items/ids for this group */
                                for($j = 0; $j < count($this->request['menu_item_item']['menu_item_item_id']); $j++){
                                    $sub_id = $this->request['menu_item_item']['menu_item_item_id'][$j];
                                    $sub_type = $this->request['menu_item_item']['menu_item_item_type'][$j];
                                    $sub_link = $this->request['menu_item_item']['link'][$j];
                                    $sub_priority = $this->request['menu_item_item']['priority'][$j];
                                    $sub_label = $this->request['menu_item_item']['label'][$j];
                                    $sub_group = $this->request['menu_item_item']['_group'][$j];
                                    //print "{$group} vs {$sub_group}<br>";
                                    if ($group == $sub_group){
                                        if ($sub_id) $sub_menu_ids[] = $sub_id;
                                        print "fubar";
                                        $sub_menu_items[] = array(
                                            'id' => $sub_id,
                                            'type' => $sub_type,
                                            'priority' => $sub_priority,
                                            'label' => $sub_label,
                                            'link' => $sub_link
                                        );
                                    }
                                }
                                
                                /* Delete any records that we are missing */
                                if (count($sub_menu_ids)){
                                    $sql = $this->data_source->sql;
                                    
                                    $sql->update('menu_item_item')
                                        ->set('date_deleted', new sql_now())
                                        ->where(
                                            new sql_and(
                                                new sql_cond('date_deleted', sql::IS, new sql_null()),
                                                new sql_cond('menu_item_id', sql::EQUALS, $menu_item->menu_item_id),
                                                new sql_cond('menu_item_item_id', sql::NOT_IN, "(" . implode(", ", $sub_menu_ids) . ")")
                                            )
                                        );
                                    
                                    $sql->execute();
                                }
                                
                                
                                /* Create or update existing menu items */
                                //print new html_pre(print_r($sub_menu_items, true));
                                //exit(1);
                                foreach($sub_menu_items as $sub_item){
                                    $sub_model = new model_menu_item_item($sub_item['id']);
                                    $sub_model->errors(true);
                                    $sub_model->menu_item_item_type = $sub_item['type'];
                                    $sub_model->priority = $sub_item['priority'];
                                    $sub_model->label = $sub_item['label'];
                                    $sub_model->link = $sub_item['link'];
                                    $sub_model->menu_item_id = $menu_item->menu_item_id;
                                    $sub_model->save();
                                }
                                
                                
                            }else{
                                /* Delete all sub menus */
                                $sql = $this->data_source->sql;
                                
                                $sql->update('menu_item_item')
                                    ->set('date_deleted', new sql_now())
                                    ->where(
                                        new sql_and(
                                            new sql_cond('date_deleted', sql::IS, new sql_null()),
                                            new sql_cond('menu_item_id', sql::EQUALS, $menu_item->menu_item_id)
                                        )
                                    );
                                
                                $sql->execute();
                            }
                            
                        }
                        
                    }else{
                        /* Delete all menu items and sub items */
                        $sql = $this->data_source->sql;
                        
                        $sql->update('menu_item')
                            ->set('date_deleted', new sql_now())
                            ->where(
                                new sql_and(
                                    new sql_cond('date_deleted', sql::IS, new sql_null()),
                                    new sql_cond('menu_id', sql::EQUALS, $this->model->menu_id)
                                )
                            );
                        
                        $sql->execute();
                        
                        $sql = $this->data_source->sql;
                        $sql->update('menu_item_item')
                            ->set('date_deleted', new sql_now())
                            ->where(
                                new sql_and(
                                    new sql_cond('date_deleted', sql::IS, new sql_null()),
                                    new sql_cond('menu_item_id', sql::IN, $this->data_source->sql
                                        ->select('menu_item_id')
                                        ->from('menu_item')
                                        ->where(
                                            new sql_cond('menu_id', sql::EQUALS, $this->model->menu_id) //Intentionally not filtered on date_deleted
                                        )
                                    )
                                )
                            );
                        
                        $sql->execute();
                        
                    }
                    
                }
                $this->redirect('/' . $this->request['url']);
            }
        }
        
        /*
         * Views
         */
        public function view_example(){
            //return new html_pre(print_r($this->request, true));
            $menu_type_id = $this->data_source->escape($this->request['menu']['menu_type_id']);
            if (!is_numeric($menu_type_id)){
                $model = new model_menu_type();
                $model->load_by_name('bs_navbar_nav');
                $menu_type_id = $model->menu_type_id;
            }
            
            return view_form_page_section_menu_style::example($menu_type_id);
        }
        
        public function view_menu_item_form(){
            $section = new html_div(array('class' => 'menu-item-form-section form-horizontal'));
            
            $left_col = new html_div(array(/*'class' => 'col-xs-12 col-sm-6'*/));
            //$right_col = new html_div(array('class' => 'col-xs-12 col-sm-6'));
            
            $types = array("Link", "Dropdown menu");
            $options = array();
            foreach($types as $type){
                $option = new html_option($type);
                if ($type == $this->request['menu_item']['menu_item_type']){
                    $option->attr('selected', 'selected');
                }
                $options[] = $option;
            }
            
            $section->add($left_col/*, $right_col*/);
            
            
            $link_group_class = 'form-group';
            $primary_button = new html_button("Add", array('class' => 'btn btn-sm btn-primary action add-menu-item'));
            if ($this->request['menu_item']['menu_item_type'] == "Dropdown menu"){
                $link_group_class .= " hidden";
            }
            
            if (isset($this->request['menu_item']['menu_item_type'])){
                $primary_button = new html_button("Update", array('class' => 'btn btn-sm btn-primary action edit-menu-item'));
            }
            
            $left_col->add(
                array(
                    new html_div(
                        array(
                            new html_label("Type", array('class' => 'control-label col-sm-2')),
                            new html_div(
                                new html_select(
                                    $options,
                                    array('class' => 'form-control input-sm', 'name' => 'menu_item[menu_item_type]')
                                ),
                                array('class' => 'col-sm-10')
                            )
                        ),
                        array('class' => 'form-group')
                    ),
                    new html_div(
                        array(
                            new html_label("Label", array('class' => 'control-label col-sm-2')),
                            new html_div(
                                new html_input(array('class' => 'form-control input-sm', 'name' => 'menu_item[label]', 'type' => 'text', 'value' => $this->request['menu_item']['label'])),
                                array('class' => 'col-sm-10')
                            )
                        ),
                        array('class' => 'form-group')
                    ),
                    new html_div(
                        array(
                            new html_label("URL", array('class' => 'control-label col-sm-2')),
                            new html_div(
                                new html_input(array('class' => 'form-control input-sm', 'name' => 'menu_item[link]', 'type' => 'text', 'value' => $this->request['menu_item']['link'])),
                                array('class' => 'col-sm-10')
                            )
                        ),
                        array('class' => $link_group_class)
                    ),
                    new html_div(
                        array(
                            new html_button("Close", array('class' => 'btn btn-sm btn-default action close-popover')),
                            $primary_button
                        ),
                        array(
                            'class' => 'text-right'
                        )
                    )
                )
            );
            
            
            return $section;
        }
        
        public function view_sub_menu_item_form(){
            $section = new html_div(array('class' => 'menu-item-form-section form-horizontal'));
            
            $left_col = new html_div(array(/*'class' => 'col-xs-12 col-sm-6'*/));
            //$right_col = new html_div(array('class' => 'col-xs-12 col-sm-6'));
            
            $section->add($left_col/*, $right_col*/);
            
            
            $types = array("Link", "Divider", "Heading");
            $options = array();
            foreach($types as $type){
                $option = new html_option($type);
                if ($type == $this->request['menu_item_item']['menu_item_item_type']){
                    $option->attr('selected', 'selected');
                }
                $options[] = $option;
            }
            
            $url_group_class = 'form-group';
            $label_group_class = 'form-group';
            
            if ($this->request['menu_item_item']['menu_item_item_type'] == "Divider"){
                $url_group_class = 'form-group hidden';
                $label_group_class = 'form-group hidden';
            }elseif ($this->request['menu_item_item']['menu_item_item_type'] == "Heading"){
                $url_group_class = 'form-group hidden';
                $label_group_class = 'form-group';
            }
            
            $primary_button = null;
            if (isset($this->request['menu_item_item']['menu_item_item_type'])){
                $primary_button = new html_button("Update", array('class' => 'btn btn-sm btn-primary action edit-sub-menu'));
            }else{
                $primary_button = new html_button("Add", array('class' => 'btn btn-sm btn-primary action add-sub-menu-item'));
            }
            
            $left_col->add(
                array(
                    new html_div(
                        array(
                            new html_label("Type", array('class' => 'control-label col-sm-2')),
                            new html_div(
                                new html_select(
                                    $options,
                                    array('class' => 'form-control input-sm', 'name' => 'menu_item_item[menu_item_item_type]')
                                ),
                                array('class' => 'col-sm-10')
                            )
                        ),
                        array('class' => 'form-group')
                    ),
                    new html_div(
                        array(
                            new html_label("Label", array('class' => 'control-label col-sm-2')),
                            new html_div(
                                new html_input(array('class' => 'form-control input-sm', 'name' => 'menu_item_item[label]', 'type' => 'text', 'value' => $this->request['menu_item_item']['label'])),
                                array('class' => 'col-sm-10')
                            )
                        ),
                        array('class' => $label_group_class)
                    ),
                    new html_div(
                        array(
                            new html_label("URL", array('class' => 'control-label col-sm-2')),
                            new html_div(
                                new html_input(array('class' => 'form-control input-sm', 'name' => 'menu_item_item[link]', 'type' => 'text', 'value' => $this->request['menu_item_item']['link'])),
                                array('class' => 'col-sm-10')
                            )
                        ),
                        array('class' => $url_group_class)
                    ),
                    new html_div(
                        array(
                            new html_button("Close", array('class' => 'btn btn-sm btn-default action close-popover')),
                            $primary_button
                        ),
                        array(
                            'class' => 'text-right'
                        )
                    )
                )
            );
            
            
            return $section;
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
