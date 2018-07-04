<?php

require_once 'classHtmlElement.php';

class Button extends HtmlElement{

    private $onClick;
    
    function __construct(){
        parent::__construct('button');
    }

    public static function fieldMap() {
        return parent::fieldMap() + [
            'cmp:onClick' => 'onClick'
        ];
    }
    
    public function click(){
        $func = $this->onClick;
        $func();
    }

    function init() {
        parent::init();
        if ($this->onClick){
            $this->attr['html']['onclick'] = 'PostAction(\''.$this->get_id().'\',\'click\')';
        }
    }
}

?>