<?php

namespace extensions\administrator{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    use \extensions\bootstrap_views as bs;
    
    class controller_administrator extends \frameworks\adapt\controller {
        
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
            $navbar->find('.navbar-collapse')->append($menu->get_view());
            
            
            $nav = new bs\view_nav('navbar');
            $nav->add_class('navbar-right');
            $navbar->find('.navbar-collapse')->append($nav);
            
            $navbar->add('Sign <strong>in</strong>', '/sign-in');
            
            $header->add($navbar);
            
            $this->view->add($header);
            
            $row = new bs\view_row();
            
            $content = new bs\view_cell(null, 12, 12, 12, 12);
            
            $row->add($content);
            
            $this->view->add(new bs\view_container($row));
            $this->_content = $content;
            
                        
            $footer = new html_footer('Powered by Adapt Framework');
            $this->view->add($footer);
        }
        
        public function add_view($data){
            $this->_content->add($data);
        }
        
        public function view_default(){
            $this->add_view(new html_h1('Administrator'));
        }
        
    }
    
    
}

?>