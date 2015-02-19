<?php

namespace Artprima\WordPress\API\Wrapper\Admin\Metabox;


abstract class Metabox {
    
    /*
   add_meta_box( $id, $title, $callback, $post_type, $context,
         $priority, $callback_args );    
     */

    /**
     * @var string 
     */
    protected $id;
    protected $title;
    protected $callback;
    protected $screen;
    protected $context;
    protected $priority;
    protected $callbackArgs;
    
    
    /**
     * HTML 'id' attribute of the edit screen section 
     * 
     * @param string $id
     * @return \Artprima\WordPress\API\Wrapper\Admin\MetaBox
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    
    /**
     * Title of the edit screen section, visible to user 
     * 
     * @param string $title
     * @return \Artprima\WordPress\API\Wrapper\Admin\MetaBox
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
    
    /**
     * Function that prints out the HTML for the edit screen section.
     * The function name as a string, or, within a class, an array to call one of the class's methods.
     * The callback can accept up to two arguments, see Callback args.
     * 
     * @param callback $callback
     * @return \Artprima\WordPress\API\Wrapper\Admin\MetaBox
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;
        return $this;
    }
    
    /**
     * The part of the page where the edit screen section should be shown ('normal', 'advanced', or 'side').
     * (Note that 'side' doesn't exist before 2.7) 
     * 
     * @param string $context
     * @return \Artprima\WordPress\API\Wrapper\Admin\MetaBox
     */
    public function setContext($context)
    {
        $this->context = $context;
        return $this;
    }
    
    /**
     * The priority within the context where the boxes should show ('high', 'core', 'default' or 'low') 
     * 
     * @param string $priority
     * @return \Artprima\WordPress\API\Wrapper\Admin\MetaBox
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
        return $this;
    }
    
    /**
     * Arguments to pass into your callback function. The callback will receive the $post object and whatever
     * parameters are passed through this variable. 
     * 
     * @param array $callbackArgs
     * @return \Artprima\WordPress\API\Wrapper\Admin\MetaBox
     */
    public function setCallbackArgs(array $callbackArgs)
    {
        $this->callbackArgs = $callbackArgs;
        return $this;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function getCallback()
    {
        if ($this->callback === null) {
            $this->callback = array($this, 'content');
        }
        
        return $this->callback;
    }
    
    public function getContext()
    {
        return $this->context;
    }
    
    public function getPriority()
    {
        return $this->priority;
    }
    
    public function getCallbackArgs()
    {
        return $this->callbackArgs;
    }
    
    public function init()
    {
        add_action('add_meta_boxes', array($this, 'onAddMetaBoxes'));
        return $this;
    }
    
    /**
     * The type of Write screen on which to show the edit screen section ('post', 'page', 'dashboard', 'link',
     * 'attachment' or 'custom_post_type' where custom_post_type is the custom post type slug) 
     * 
     * @param string $screen
     * @return \Artprima\WordPress\API\Wrapper\Admin\Metabox\Metabox
     */
    public function setScreen($screen)
    {
        $this->screen = $screen;
        return $this;
    }
    
    public function getScreen()
    {
        return $this->screen;
    }
    
    
    public function onAddMetaBoxes($screen)
    {
        if ($this->getScreen() == $screen) {
            add_meta_box(
                $this->getId(),
                $this->getTitle(),
                $this->getCallback(),
                $this->getScreen(),
                $this->getContext(),
                $this->getPriority(),
                $this->getCallbackArgs()
            );
        }
    }
    
    abstract function content();
    
}