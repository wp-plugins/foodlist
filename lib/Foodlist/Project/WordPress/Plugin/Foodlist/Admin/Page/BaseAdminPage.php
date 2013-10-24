<?php

namespace Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Page;

class BaseAdminPage
{

    /**
     * Heading
     * @var string 
     */
    protected $heading;
    
    /**
     * Set heading
     * 
     * @param string $heading
     * @return \Foodlist\Project\WordPress\Plugin\Foodlist\Admin\Page\BaseAdminPage
     */
    public function setHeading($heading)
    {
        $this->heading = $heading;
        return $this;
    }
    
    /**
     * Get heading
     * 
     * @return string
     */
    public function getHeading()
    {
        return $this->heading;
    }
    
    /**
     * Get Page title
     */
    protected function getTitle()
    {
        $title = get_screen_icon().'
            <h2>'.$this->getHeading().'</h2>
        ';
        return $title;
    }
    
    public function setPageData($pageData)
    {
        $this->pageData = $pageData;
        return $this;
    }
    
    public function getPageData()
    {
        return $this->pageData;
    }
    
    public function content()
    {
        echo $this->getContent();
    }
    
    public function getContent()
    {
        $result = '
            <div class="wrap">
                '.$this->getTitle().'
                '.$this->getPageData().'
            </div>
        ';

        return $result;
    }
    
}