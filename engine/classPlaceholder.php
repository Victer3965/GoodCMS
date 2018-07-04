<?php
require_once 'classControl.php';
require_once 'classContent.php';

class Placeholder extends Control
{
    /***
     * @var Content $content
     */
    public $content;

    function render(&$stream)
    {
        //echo "placeholder render\n";
        if ($this->content instanceof Content)
            $this->content->render($stream);
    }
}