<?php
require_once 'classControl.php';
require_once 'classPlaceholder.php';

class Content extends Control
{
    private $placeholderId;
    
    protected static function fieldMap(){
        //echo "Content:fieldMap\n";
        return parent::fieldMap() + [
            'cmp:Placeholder' => 'placeholderId'
        ];
    }
    
    function init()
    {
        parent::init();
        $this->bind();
    }

    function bind()
    {
        $placeholder = $this->getPage()->getMaster()->getById($this->placeholderId);
        if ($placeholder instanceof Placeholder){
            //echo "Placeholder\n";
            $placeholder->content = $this;
        } else {
            throw new Exception('No placeholder found with ID: ' . $this->placeholderId);
        }
    }

    /**
     * @param string[] $stream
     */
    public function render(&$stream)
    {
        //echo "content render\n";
        foreach ($this->children as $element)
            if ($element instanceof Control)
                $element->render($stream);
    }
}