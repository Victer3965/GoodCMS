<?php

require_once 'classControl.php';
require_once 'classPage.php';

class PageController extends Control{

    function init() {
        parent::init();
        $this->getPage()->setPageController($this);
    }

    public function executeAction(){
        if (!isset($_POST) || !isset($_POST['action']) || !isset($_POST['control']))
            return;
        $control = $this->getPage()->getById($_POST['control']);
        $action = $_POST['action'];
        $param = $_POST['param'];
        if (method_exists($control, $action))
            $control->$action($param);
        else
            error_log("Cannot execute method {$action} of " . get_class($control) . " on " . $_SERVER['PHP_SELF']);
    }

    /**
     * 
     * @global Page $page
     */
    public function SaveState(){
        global $page;
        $state = [];
        $this->SaveChildState($page, $state);
    }

    /**
     * 
     * @param Control $control
     */
    private function SaveChildState($control, &$state) {
        $state[$control->get_id()] = serialize($control->viewState->getDirties());
        foreach($control->children as $child){
            $this->SaveChildState($child, $state);
        }
    }

    /**
     * 
     * @global Page $page
     */
    public function LoadState() {
        global $page;
    }

    public function render(&$stream) {
        $stream[] = '<script type="text/javascript">'
                . 'function PostAction(id, action, params){
$(\'<form method="POST"><input type="hidden" name="control" value="\'+id+\'"/><input type="hidden" name="action" value="\'+action+\'"/></form>\').appendTo(\'body\').submit();
}'
                . 'console.log(\'PageController\');'
                . '</script>';
    }
}
