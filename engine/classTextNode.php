<?php
require_once 'classControl.php';
class TextNode extends Control
{
    private $text = '';

    function __construct($text)
    {
        parent::__construct();
        $this->text = $text;
    }

    function __toString()
    {
        return $this->text;
    }

    function render(&$stream)
    {
        $stream[] = $this->text;
    }
}

?>