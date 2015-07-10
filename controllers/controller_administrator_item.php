<?php

namespace extensions\administrator{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    use \extensions\bootstrap_views as bs;
    
    class controller_administrator_item extends controller {
        
        protected $_title;
        protected $_sql;
        protected $_form_name;
        protected $_page;
        protected $_search_string;
        protected $_items_per_page;
        protected $_sort_order;
        protected $_sort_direction;
        protected $_sortable_fields;
        protected $_record_count;
        protected $_filters;
        
        public function __construct(){
            parent::__construct();
            
            $this->request['page'] = $this->data_source->escape($this->request['page']);
            $this->request['q'] = $this->data_source->escape($this->request['q']);
            $this->request['items_per_page'] = $this->data_source->escape($this->request['items_per_page']);
            $this->request['sort_order'] = $this->data_source->escape($this->request['sort_order']);
            $this->request['sort_direction'] = $this->data_source->escape($this->request['sort_direction']);
            
            if (isset($this->request['page']) && is_numeric($this->request['page']) && $this->request['page'] >= 1){
                $this->page = $this->request['page'];
            }else{
                $this->page = 1;
            }
            
            if (isset($this->request['q'])){
                $this->search_string = $this->request['q'];
            }else{
                $this->search_string = '';
            }
            
            if (isset($this->request['items_per_page']) && is_numeric($this->request['items_per_page']) && $this->request['items_per_page'] >= 1){
                $this->items_per_page = $this->request['items_per_page'];
            }else{
                $this->items_per_page = 50;
            }
            
            if (isset($this->request['sort_order'])){
                $this->sort_order = $this->request['sort_order'];
            }else{
                $this->sort_order = '';
            }
            
            if (isset($this->request['sort_direction']) && in_array(strtolower($this->request['sort_direction']), array('asc', 'desc'))){
                $this->sort_direction = strtolower($this->request['sort_direction']);
            }else{
                $this->sort_direction = 'asc';
            }
            
            $this->_sortable_fields = array();
            $this->_filters = array();
            
            $this->view->add_class('administrator_item');
        }
        
        /*
         * Properties
         */
        public function pget_title(){
            return $this->_title;
        }
        
        public function pset_title($title){
            $this->_title = $title;
        }
        
        public function pget_sql(){
            return $this->_sql;
        }
        
        public function pset_sql($sql){
            $this->_sql = $sql;
        }
        
        public function pget_form_name(){
            return $this->_form_name;
        }
        
        public function pset_form_name($form_name){
            $this->_form_name = $form_name;
        }
        
        public function pget_page(){
            return $this->_page;
        }
        
        public function pset_page($page){
            $this->_page = $page;
        }
        
        public function pget_search_string(){
            return $this->_search_string;
        }
        
        public function pset_search_string($search_string){
            $this->_search_string = $search_string;
        }
        
        public function pget_items_per_page(){
            return $this->_items_per_page;
        }
        
        public function pset_items_per_page($items_per_page){
            $this->_items_per_page = $items_per_page;
        }
        
        public function pget_sort_order(){
            return $this->_sort_order;
        }
        
        public function pset_sort_order($sort_order){
            $this->_sort_order = $sort_order;
        }
        
        public function pget_sort_direction(){
            return $this->_sort_direction;
        }
        
        public function pset_sort_direction($sort_direction){
            if (in_array(strtolower($sort_direction), array('asc', 'desc'))){
                $this->_sort_direction = $sort_direction;
            }
        }
        
        public function pget_sortable_fields(){
            return $this->_sortable_fields;
        }
        
        public function pset_sortable_fields($fields = array()){
            if (is_array($fields)) $this->_sortable_fields = $fields;
        }
        
        public function pget_filters(){
            return $this->_filters;
        }
        
        public function pset_filters($filters = array()){
            if (is_array($filters)) $this->_filters = $filters;
        }
        
        public function pget_count(){
            if (!is_null($this->_record_count)) return $this->_record_count;
            $sql = $this->data_source->sql;
            $sql->select(array('c' => 'count(*)'))
                ->from($this->get_statement(), 'c')
                ->execute();
            $results = $sql->results();
            
            if (is_array($results) && count($results)){
                $this->_record_count = $results[0]['c'];
                return $this->_record_count;
            }
            
            return 0;
        }
        
        public function pget_page_count(){
            if ($this->count > 0 && $this->items_per_page > 0){
                return ceil($this->count / $this->items_per_page);
            }
            
            return 0;
        }
        
        /*
         * Helper functions
         */
        public function get_statement(){
            
        }

        
        
        /*
         * Actions
         */
        public function action_save(){
            if ($this->model && $this->model instanceof \frameworks\adapt\model){
                //$this->add_view(new html_pre(print_r($this->request, true)));
                $this->model->push($this->request);
                $this->model->owner_id = $this->session->user->user_id;
                $this->model->save();
                //$this->add_view(new html_pre(print_r($this->model->error(), true)));
                header("Location: /{$this->request['url']}");
            }
        }
        
        /*
         * Views
         */
        public function view_data(){
            $sql = $this->get_statement();
            $url = "/" . $this->request['url'];
            if (substr($url, strlen($url) - 4) == 'data'){
                $url = substr($url, 0, strlen($url) - 5);
            }
            $table_name = $this->model->table_name;
            $key = $this->data_source->get_primary_keys($table_name);
            $key = $key[0];
            
            if (in_array($this->sort_order, $this->sortable_fields)){
                $direction = $this->sort_direction == 'asc' ? true : false;
                $sql->order_by($this->sort_order, $direction);
            }
            
            $offset = ($this->items_per_page * ($this->page - 1));
            $sql->limit($this->items_per_page, $offset);
            
            $results = $sql->execute()->results();
            
            
            
            if (count($results)){
                foreach($results as &$result){
                    $result['...'] = new html_a(new \extensions\font_awesome_views\view_icon('pencil'), array('href' => "{$url}/edit?{$table_name}[{$key}]=" . $result['#'], 'title' => 'Edit this ' . strtolower($this->title)));
                    unset($result['#']);
                }
                
                $table = new \frameworks\adapt\view_table($results);
                
                
                /* Make columns sortable */
                foreach($this->sortable_fields as $label => $field){
                    $state = 'inactive';
                    $class = 'inactive';
                    $icon = 'sort';
                    
                    if ($this->sort_order == $field){
                        $state = $this->sort_direction;
                        $class = 'active';
                        $icon = 'sort-' . $this->sort_direction;
                    }
                    
                    $a = new html_a(array("{$label} ", new html_span(new \extensions\font_awesome_views\view_icon($icon), array('class' => $class))), array('href' => 'javascript:void(0);', 'class' => 'sorter', 'data-state' => $state, 'data-value' => $field));
                    
                    /* Update the cols */
                    $cells = $table->find('thead th')->get();
                    foreach($cells as &$cell){
                        if ($cell->text == $label){
                            $cell->clear();
                            $cell->add($a);
                        }
                    }
                }
                
                $output = new html_div(array('class' => 'data'));
                //$output->add(new html_pre($this->page_count));
                $control_wrapper = new html_div(array('class' => 'control-wrapper'));
                if ($this->page_count > 1){
                    $pager = new \extensions\bootstrap_views\view_pagination('/', $this->page_count, $this->page);
                    $control_wrapper->add($pager);
                    
                    $pager->find('a')->add_class('pager-page')->attr('href', 'javascript:void(0);');
                    $pager->find('a')->first()->add_class('pager-previous');
                    $pager->find('a')->last()->add_class('pager-next');
                }
                //$output->add($control_wrapper);
                $output->add(new html_div($table, array('class' => 'table-responsive')));
                $output->add($control_wrapper);
                
                return $output;
            }
            
            return new html_div(new html_span('No results'), array('class' => 'strike'));
        }
        
        public function view_default(){
            $title = $this->title;
            if (strtolower(substr($title, strlen($title) - 1)) == 's'){
                $title .= "'";
            }elseif (substr($title, strlen($title) - 1) == 'y'){
                $title = substr($title, 0, strlen($title) - 1) . "ies";
            }else{
                $title .= "s";
            }
            $h1 = new html_h1($title);
            if ($this->permission_view_new()){
                $icon = new \extensions\font_awesome_views\view_icon('plus');
                $a = new html_a($icon, array('href' => "/" . $this->request['url'] . "/new", 'class' => 'pull-right', 'title' => 'New'));
                $h1->add($a);
            }
            $top = new bs\view_cell($h1, 12, 12, 12, 12);
            $this->add_view($top);
            
            $form = new bs\view_form('/' . $this->request['url'], 'post');
            $top->add($form);
            
            $form->add(new html_input(array('type' => 'hidden', 'name' => 'sort_order', 'value' => $this->sort_order)));
            $form->add(new html_input(array('type' => 'hidden', 'name' => 'sort_direction', 'value' => $this->sort_direction)));
            $form->add(new html_input(array('type' => 'hidden', 'name' => 'page', 'value' => $this->page)));
            $form->add(new html_input(array('type' => 'hidden', 'name' => 'items_per_page', 'value' => $this->items_per_page)));
            
            $control = new \extensions\bootstrap_views\view_input('text', 'q', '', 'Search...', \extensions\bootstrap_views\view_input::LARGE);
            $group = new \extensions\bootstrap_views\view_form_group($control, 'Filter...');
            $group->find('label')->add_class('sr-only');
            $form->add($group);
            
            $form->add(new html_div($this->filters, array('class' => 'filters')));
            
            
            $top->add(new html_div($this->view_data(), array('class' => 'ajax-data')));
        }
        
        public function view_edit(){
            if ($this->model->is_loaded){
                //$this->add_view(new html_pre(print_r($this->model->to_hash(), true)));
                //$this->add_view(new html_pre(print_r($this->model->to_hash_string(), true)));
                $title = $this->title;
                
                if (!is_null($this->model->label)){
                    $title .= ": " . $this->model->label;
                }elseif(!is_null($this->model->name)){
                    $title .= ": " . $this->model->name;
                }
                
                $top = new bs\view_cell(new html_h1($title), 12, 12, 12, 12);
                $this->add_view($top);
                
                $form = new \extensions\forms\model_form();
                $form->load_by_name($this->form_name);
                $view = $form->get_view($this->model->to_hash());
                $view->add_class('two-column');
                $view->find('.field-select,.field-input')->add_class('col-sm-12')->add_class('col-md-6');
                $view->find('.field-text-editor')->add_class('col-sm-12');
                $view->find('h1,h2,h3,h4,h5,h6')->add_class('col-sm-12')->add_class('clearfix');
                $view->find('.controls')->add_class('col-sm-12');
                $top->add($view);
            }else{
                header('Location: ' . $this->url);
                exit(0);
            }
        }
        
        public function view_new(){
            $top = new bs\view_cell(null, 12, 12, 12, 12);
            $this->add_view($top);
            
            //if (isset($this->form_name)){
                $form = new \extensions\forms\model_form();
                $form->load_by_name($this->form_name);
                $view = $form->get_view();
                //$view->add_class('two-column');
                //$view->find('.field-select,.field-input')->add_class('col-sm-12')->add_class('col-md-6');
                //$view->find('.field-text-editor')->add_class('col-sm-12');
                //$view->find('h1,h2,h3,h4,h5,h6')->add_class('col-sm-12')->add_class('clearfix');
                //$view->find('.controls')->add_class('col-sm-12');
                $top->add($view);
            //}
        }
        
        
    }
    
    
}

?>