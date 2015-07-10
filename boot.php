<?php

namespace extensions\administrator;
use \frameworks\adapt as adapt;

/* Prevent direct access */
defined('ADAPT_STARTED') or die;

$adapt = $GLOBALS['adapt'];

/*
 * Include  css & javascript
 */
$adapt->dom->head->add(new adapt\html_link(array('type' => 'text/css', 'rel' => 'stylesheet', 'href' => '/adapt/extensions/administrator/static/css/administrator.css')));
$adapt->dom->head->add(new html_script(array('type' => 'text/javascript', 'src' => '/adapt/extensions/administrator/static/js/administrator.js')));


/*
 * Lets add the administrator view controller
 * to the application root controller
 */
\application\controller_root::extend('view_administrator', function($_this){
    return $_this->load_controller('\\extensions\\administrator\\controller_administrator');
});

/*
 * Lets permission the the administrator view
 */
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

?>