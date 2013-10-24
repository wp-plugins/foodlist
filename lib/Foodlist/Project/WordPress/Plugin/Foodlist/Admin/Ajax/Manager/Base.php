<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Ajax\Manager;

use Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Ajax\Base as ParentBase;

class Base extends ParentBase
{
    
    protected $metaKeys = array(
        'items' => '',
        'multi_number' => '',
        'order' => '',
    );
    
    public function getMetaKey($type)
    {
        return $this->metaKeys[$type];
    }
    
    public function ajaxSaveOrder()
    {
        if (!$this->getArg('items')) {
            return;
        }
        
        $items = $this->getArg('items');
        if (!is_array($items)) {
            return;
        }
        foreach ($items as $item => $order) {
            update_post_meta($item, $this->getMetaKey('order'), $order);
        }
        die('1');
    }
    
    public function ajaxSaveList()
    {
        if (!$this->getArg('item') || !$this->getArg('post_id') || !$this->getArg('multi_number')) {
            return;
        }
        $items = get_post_meta($this->getArg('item'), $this->getMetaKey('items'), true);
        if (!is_array($items)) {
            $items = array();
        }

        //var_dump($this->getArg('delete_widget'), isset($items[$this->getArg('post_id')][$this->getArg('multi_number')]));
        if ($this->getArg('delete_widget') && isset($items[$this->getArg('post_id')][$this->getArg('multi_number')])) {
            unset($items[$this->getArg('post_id')][$this->getArg('multi_number')]);
            $items = array_filter($items);
        } else {
            $items[$this->getArg('post_id')][$this->getArg('multi_number')] = '1';
        }
        
        // if not delete, then it is add
        if (!$this->getArg('delete_widget')) {
            // increment multi_number
            
            $multi_number = get_post_meta($this->getArg('post_id'), $this->getMetaKey('multi_number'), true);
            if ($multi_number) {
                ++$multi_number;
            } else {
                $multi_number = $this->getArg('multi_number')+1;
            }
            
            update_post_meta($this->getArg('post_id'), $this->getMetaKey('multi_number'), $multi_number);
        }
        
        update_post_meta($this->getArg('item'), $this->getMetaKey('items'), $items);
        die('1');
    }
    
    
}