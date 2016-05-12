<?php

namespace adapt\administrator{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    use \bootstrap\views as bs;
    
    class controller_administrator extends controller {
        
        protected $_content;
        
        public function __construct(){
            parent::__construct();
            /* Detach the root controllers view from the dom */
            $this->dom->find('.controller.root')->detach();
            
            /* Attach our view to the dom */
            $this->dom->add($this->view);
            
            $header = new html_header();
            
            $navbar = new bs\view_navbar();
            $navbar->static_top = true;
            //$navbar->brand = new html_img(array('src' => '/adapt/applications/octane_ats/static/images/tgif_logo.png'));
            $navbar->brand = 'Administrator';
            $navbar->brand_url = "/administrator";
            //$navbar->add('Fridays <strong>Facts</strong>', '/getting-started');
            
            $menu = new \extensions\menus\model_menu();
            $menu->load_by_name('administrator_main_navigation');
            $view = $menu->get_view();
            $view->set_variables($this->session->user->to_hash_string());
            $navbar->find('.navbar-collapse')->append($view);
            
            
            $menu = new \extensions\menus\model_menu();
            $menu->load_by_name('administrator_settings_menu');
            $view = $menu->get_view();
            $view->set_variables($this->session->user->to_hash_string());
            $view->add_class('navbar-right');
            $navbar->find('.navbar-collapse')->append($view);
            
            
            
            
            //$nav = new bs\view_nav('navbar');
            //$nav->add_class('navbar-right');
            //$navbar->find('.navbar-collapse')->append($nav);
            
            //$navbar->add('Main <strong>sign</strong>', '/sign-in');
            
            $header->add($navbar);
            
            $this->view->add($header);
            
            $row = new bs\view_row();
            
            $content = new bs\view_cell(null, 12, 12, 12, 12);
            
            $row->add($content);
            
            $this->view->add(new bs\view_container($row));
            $this->_content = $content;
            //$this->_content = $row;
            
                        
            $footer = new html_footer('Powered by Adapt Framework');
            $this->view->add($footer);
        }
        
        /*
         * Overrides
         */
        public function add_view($data){
            $this->_content->add($data);
        }
        
        /*
         * Permissions
         */
        public function permission_view_default(){
            return $this->session->user->has_permission(PERM_CAN_LOGIN_TO_ADMINISTRATOR);
        }
        
        public function permission_view_users(){
            return $this->session->user->has_permission(PERM_CAN_MANAGE_USER_ACCOUNTS);
        }
        
        public function permission_view_contacts(){
            return $this->session->user->has_permission(PERM_CAN_MANAGE_CONTACTS);
        }
        
        public function permission_view_site_settings(){
            return $this->session->user->has_permission(PERM_CAN_CHANGE_BUNDLE_SETTINGS);
        }
        
        
        /*
         * Views
         */
        public function view_default(){
            //$top = new bs\view_cell(new html_h1('Dashboard'), 12, 12, 12, 12);
            //$this->add_view($top);
            
            $this->add_view(new html_h1('Dashbaord'));
            
            $left = new bs\view_cell('left', 12, 6, 4, 4);
            $this->add_view($left);
            
            $center = new bs\view_cell('center', 12, 6, 4, 4);
            $this->add_view($center);
            
            $right = new bs\view_cell('right', 12, 6, 4, 4);
            $this->add_view($right);
        }
        
        public function view_users(){
           return $this->load_controller("\\adapt\\administrator\\controller_administrator_users"); 
        }
        
        public function view_roles(){
           return $this->load_controller("\\adapt\\administrator\\controller_administrator_roles"); 
        }
        
        public function view_password_policy(){
           return $this->load_controller("\\adapt\\administrator\\controller_administrator_password_policy"); 
        }
        
        public function view_contacts(){
            return $this->load_controller("\\adapt\\administrator\\controller_administrator_contacts");
            //$this->add_view(new \extensions\contacts\view_form_page_contact());
        }
        
        public function view_site_settings(){
           return $this->load_controller("\\adapt\\administrator\\controller_administrator_settings"); 
        }
        
        public function view_menus(){
           return $this->load_controller("\\adapt\\administrator\\controller_administrator_menus"); 
        }
    }
    
    
}

?>