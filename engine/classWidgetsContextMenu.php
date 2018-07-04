<?php

require_once 'classControl.php';

class WidgetsContextMenu extends Control {
    //put your code here

    private $menuElements;
    
    function __construct($menuElements){
        $this->menuElements = $menuElements;
    }

    public function init() {
        parent::init();
    }
    
    public function render(&$stream) {
//        $stream
    }

}
