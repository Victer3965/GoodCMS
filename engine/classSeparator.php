<?php
require_once 'classHtmlElement.php';

class Separator extends HtmlElement
{
    function __construct()
    {
        parent::__construct('hr',false);
    }

}

?>