<?php
require_once 'classHtmlElement.php';

class BulletList extends HtmlElement
{
    function __construct()
    {
        parent::__construct('ul');
    }
}

?>