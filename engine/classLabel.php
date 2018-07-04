<?php
require_once 'classHtmlElement.php';

class Label extends HtmlElement
{
    function __construct()
    {
        parent::__construct('label');
    }
}