<?php

namespace adapt\administrator{
    
    /* Prevent Direct Access */
    defined('ADAPT_STARTED') or die;
    
    class bundle_administrator extends \adapt\bundle{
        
        public function __construct($data){
            parent::__construct('administrator', $data);
        }
        
        public function boot(){
            if (parent::boot()){
                
                $this->dom->head->add(new html_link(array('type' => 'text/css', 'rel' => 'stylesheet', 'href' => "/adapt/administrator/administrator-{$this->version}/static/css/administrator.css")));
                $this->dom->head->add(new html_script(array('type' => 'text/javascript', 'src' => "/adapt/administrator/administrator-{$this->version}/static/js/administrator.js")));
                
                /*
                 * Lets add the administrator view controller
                 * to the application root controller and
                 * permission it.
                 */
                \application\controller_root::extend('view_administrator', function($_this){
                    return $_this->load_controller('\\adapt\\administrator\\controller_administrator');
                });
                
                \application\controller_root::extend('permission_view_administrator', function($_this){
                
                    /* If we are not logged in then we need to redirect to the login page */
                    if (!$_this->session->is_logged_in){
                        $redirect = "/";
                        if (isset($_this->request['url']) && strtolower($_this->request['url']) != "sign-in"){
                            $redirect = "/" . urlencode($_this->request['url']);
                        }
                        header('location: /sign-in?redirect_url=' . $redirect);
                        exit(0);
                    }else{
                        
                        /* Check if the user is permissioned */
                        return $_this->session->user->has_permission(PERM_CAN_LOGIN_TO_ADMINISTRATOR);
                    }
                    
                });
                
                
                
                return true;
            }
            
            return false;
        }
        
    }
    
    
}

?>