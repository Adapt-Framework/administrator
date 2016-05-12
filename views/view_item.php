<?php

namespace adapt\administrator{
    
    /* Prevent Direct Access */
    defined('ADAPT_STARTED') or die;
    
    use \bootstrap\views as bs;
    
    class view_item extends view{
        
        protected $_model;
        //protected $_title;
        //protected $_description;
        //protected $_actions;
        //
        //protected $_pills;
        //protected $_panes;
        
        public function __construct($title, $model, $form_view, $actions = array()){
            parent::__construct();
            $this->add_class('under-panel top bottom');
            
            $this->add(new html_h1($title));
            $actions_cell = new html_div(array('class' => 'actions-cell col-xs-12'));
            $content_cell = new html_div(array('class' => 'content-cell col-xs-12'));
            
            if (count($actions)){
                $actions_cell->add_class('col-sm-3');
                $content_cell->add_class('col-sm-9');
                $this->add($actions_cell, $content_cell);
                
                $actions_cell->add(new html_h3("Things you can do"));
                foreach($actions as $action){
                    $actions_cell->add($action);
                }
                
                $actions_cell->add(new html_h3("Information"));
                $actions_cell->add(
                    new html_div(
                        array(
                            new html_label("Date created"),
                            new html_p($this->sanitize->format('locales_datetime', $model->date_created), array("class" => "form-control-static input-static"))
                        )
                    , array('class' => 'form-group'))
                );
                
                $actions_cell->add(
                    new html_div(
                        array(
                            new html_label("Last updated"),
                            new html_p($this->sanitize->format('locales_datetime', $model->date_modified), array("class" => "form-control-static input-static"))
                        )
                    , array('class' => 'form-group'))
                );
                
                if ($model->bundle_name != ""){
                    $actions_cell->add(
                    new html_div(
                        array(
                            new html_label("Installed by bundle"),
                            new html_p($model->bundle_name, array("class" => "form-control-static input-static"))
                        )
                    , array('class' => 'form-group group-bundle-name'))
                    );
                }
                
                if ($model->name != ""){
                    $actions_cell->add(
                    new html_div(
                        array(
                            new html_label("Internal name"),
                            new html_p($model->name, array("class" => "form-control-static input-static"))
                        )
                    , array('class' => 'form-group group-name'))
                    );
                }
                
                
            }else{
                $this->add($content_cell);
            }
            
            //$form_data = array_merge($this->request, $model->to_hash_string());
            $content_cell->add($form_view);
            $this->add(new html_div(array('class' => 'clearfix')));
            
            //$this->_model = $model;
            //
            //$this->_title = new html_h1();
            //
            //$cell = new bs\view_cell($this->_title, 12);
            //$cell->add_class('under-panel top bottom');
            //$this->add($cell);
            //
            //$main_content = new bs\view_cell(null, 12, 9);
            //$action_content = new bs\view_cell(null, 12, 3);
            //$cell->add($action_content, $main_content);
            //$action_content->add_class('actions');
            //$main_content->add_class('main-content');
            //
            //$this->_pills = new html_ul(array('class' => 'nav nav-pills nav-stacked col-xs-12 col-sm-4', 'role' => 'tablist'));
            ////$main_content->add($this->_pills);
            //
            //$this->_panes = new html_div(array('class' => 'tab-content col-xs-12 col-sm-8'));
            ////$main_content->add($this->_panes);
            //
            //$this->_actions = new html_div(array('class' => 'actions'));
            //$action_content->add($this->_actions);
        }
        
        //public function pget_title(){
        //    return $this->_title;
        //}
        //
        //public function pset_title($title){
        //    $this->_title = $title;
        //}
        //
        //public function add_pane($name, $content){
        //    $pane = new html_div($content, array('role' => 'tabpanel', 'class' => 'tab-pane'));
        //    $pane->set_id();
        //    
        //    $pill = new html_li(new html_a($name, array('href' => '#' . $pane->id, 'role' => 'tab', 'data-toggle' => 'tab')), array('role' => 'presentation'));
        //    
        //    if ($this->_pills->count() == 0){
        //        $pane->add_class('active');
        //        $pill->add_class('active');
        //    }
        //    
        //    $this->_pills->add($pill);
        //    $this->_panes->add($pane);
        //}
        //
        //public function add_action($icon, $style, $label, $action){
        //    $button = new html_button($icon . " " . $label, array('class' => "btn btn-block btn-" . $style));
        //    
        //    if ($this->_actions->count() == 0){
        //        $this->_actions->add(new html_h4('Actions'));
        //    }
        //    $this->_actions->add($button);
        //}
        
    }
    
}

?>