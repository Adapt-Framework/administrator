<?php

namespace adapt\administrator{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;

    
    class view_form_page_section_permissions extends \adapt\forms\view_form_page_section{
        public function __construct($form_data, &$user_data){
            parent::__construct($form_data, $user_data);
            
            $sql = $this->data_source->sql
                ->select(
                    array(
                        '...' => 'rp.role_permission_id',
                        'id' => 'p.permission_id',
                        'cat' => 'pc.label',
                        'Permission' => 'p.label',
                        
                        
                    )
                )
                ->from('permission', 'p')
                ->join('permission_category', 'pc', 'permission_category_id')
                ->left_join(
                    'role_permission',
                    'rp',
                    new sql_and(
                        new sql_cond('rp.permission_id', sql::EQUALS, 'p.permission_id'),
                        new sql_cond('rp.date_deleted', sql::IS, new sql_null()),
                        new sql_cond('rp.role_id', sql::EQUALS, sql::q($user_data['role[role_id]']))
                    )
                )
                ->where(
                    new sql_and(
                        new sql_cond('p.date_deleted', sql::IS, new sql_null()),
                        new sql_cond('pc.date_deleted', sql::IS, new sql_null())
                    )
                )
                ->order_by('pc.label')
                ->order_by('p.label');
            
            //$this->add(new html_pre($sql));
            
            $results = $sql->execute(0)->results();
            $last_cat = "";
            $last_results = array();
            for($i = 0; $i < count($results); $i++){
                if ($results[$i]['cat'] != $last_cat){
                    if (count($last_results)){
                        $this->add(new \adapt\view_table($last_results));
                        $last_results = array();
                    }
                    $last_cat = $results[$i]['cat'];
                    $this->add(new html_h4($last_cat));
                    
                }
                if ($results[$i]['...'] != ""){
                    $results[$i]['...'] = new html_input(array('name' => 'role_permission[permission_id][]', 'type' => 'checkbox', 'class' => '', 'value' => $results[$i]['id'], 'checked' => 'checked'));
                }else{
                    $results[$i]['...'] = new html_input(array('name' => 'role_permission[permission_id][]', 'type' => 'checkbox', 'class' => '', 'value' => $results[$i]['id']));
                }
                
                unset($results[$i]['id']);
                unset($results[$i]['cat']);
                $last_results[] = $results[$i];
            }
            
            if (count($last_results)){
                $this->add(new \adapt\view_table($last_results));
                $last_results = array();
            }
            
            //$this->add(new \adapt\view_table($results));
        }
        
        public function add_layout_engine($view){
            
        }
        
        
        public function add($view){
            $this->find('.groups')->append($view);
        }
    }
}

?>