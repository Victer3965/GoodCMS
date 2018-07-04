<?php
require_once 'classHtmlElement.php';

class Inline extends HtmlElement
{
    function __construct()
    {
        parent::__construct('span');
    }
}
?>