<?php

namespace extensions\administrator;
use \frameworks\adapt as adapt;

/* Prevent direct access */
defined('ADAPT_STARTED') or die;

$adapt = $GLOBALS['adapt'];

/*
 * Include  css & javascript
 */
//$adapt->dom->head->add(new adapt\html_link(array('type' => 'text/css', 'rel' => 'stylesheet', 'href' => '/adapt/extensions/forms/static/css/forms.css')));
//$adapt->dom->head->add(new adapt\html_script(array('type' => 'text/javascript', 'src' => '/adapt/extensions/forms/static/js/forms.js')));


/*
 * Lets add the administrator view controller
 * to the application root controller
 */
\application\controller_root::extend('view_administrator', function($_this){
    return $_this->load_controller('\\extensions\\administrator\\controller_administrator');
});

?>