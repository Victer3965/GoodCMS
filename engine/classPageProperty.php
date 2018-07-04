<?php

require_once 'classControl.php';
require_once 'classPage.php';

class PageProperty extends Control {

    protected $property;
    
    public static function fieldMap() {
        return parent::fieldMap() + [
            'cmp:Property' => 'property'
        ];
    }

    public function init() {
        //echo '<!--PageProperty:init-->';
        parent::init();
    }


    public function render(&$stream) {
        $page = $this->getPage();
        //echo '<!--PageProperty:render, '.get_class($page).'-->';
        if (isset($page->properties)){
            $stream[] = $page->properties[$this->property];
        } else{
            $stream[] = $this->property;
        }
        
    }

}
