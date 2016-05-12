<?php

namespace adapt\administrator{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;

    
    class view_form_page_section_password_policy extends \adapt\forms\view_form_page_section{
        public function __construct($form_data, &$user_data){
            parent::__construct($form_data, $user_data);
            
            $sql = $this->data_source->sql
                ->select(
                    array(
                        'id' => 'pp.password_policy_id',
                        '...' => 'rpp.password_policy_id',
                        'Name' => 'pp.label',
                        'Description' => 'pp.description'
                    )
                )
                ->from('password_policy', 'pp')
                ->left_join('role_password_policy', 'rpp',
                    new sql_and(
                        new sql_cond('rpp.password_policy_id', sql::EQUALS, 'pp.password_policy_id'),
                        new sql_cond('rpp.role_id', sql::EQUALS, sql::q($user_data['role[role_id]'])),
                        new sql_cond('rpp.date_deleted', sql::IS, new sql_null())
                    ))
                ->where(
                    new sql_and(
                        new sql_cond('pp.date_deleted', sql::IS, new sql_null())
                    )
                )
                ->order_by('pp.label');
            
            //$this->add(new html_pre($sql));
            
            $results = $sql->execute(0)->results();
            
            for($i = 0; $i < count($results); $i++){
                if ($results[$i]['...'] != ""){
                    $results[$i]['...'] = new html_input(array('name' => 'role_password_policy[password_policy_id][]', 'type' => 'checkbox', 'class' => '', 'value' => $results[$i]['id'], 'checked' => 'checked'));
                }else{
                    $results[$i]['...'] = new html_input(array('name' => 'role_password_policy[password_policy_id][]', 'type' => 'checkbox', 'class' => '', 'value' => $results[$i]['id']));
                }
                
                unset($results[$i]['id']);
            }
            
            $this->add(new \adapt\view_table($results));
        }
        
        public function add_layout_engine($view){
            
        }
        
        
        public function add($view){
            $this->find('.groups')->append($view);
        }
    }
}

?>