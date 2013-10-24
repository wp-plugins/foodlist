<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Ajax;

class Base
{
    protected $args;
    
    public function handle()
    {
        if (empty($_POST['method'])) {
            die('-1');
        }
        
        if (!is_callable(array($this, 'ajax'.$_POST['method']))) {
            die('-1');
        }

        if (!wp_verify_nonce($this->getArg('nonce'), $this->getNonceAction())) {
            die('-1');
        }
        
        call_user_func(array($this, 'ajax'.$_POST['method']));
        die();
    }
  
    public function getNonceAction()
    {
        return 'foodlist-abstract';
    }
    
    public function getArg($name)
    {
        $result = null;

        if ($this->args === null) {
            if (empty($_POST['args']) || !is_array($_POST['args'])) {
                $this->args = array();
            } else {
                $this->args = $_POST['args'];
            }
        }
        
        if (isset($this->args[$name])) {
            $result = $this->args[$name];
        }
        
        return $result;
    }
    
 }