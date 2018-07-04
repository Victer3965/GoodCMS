<?php
require_once 'classHtmlElement.php';

class NumericList extends HtmlElement
{
    function __construct()
    {
        parent::__construct('ol');
    }
}
?>