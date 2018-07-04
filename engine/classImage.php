<?php

require_once 'classHtmlElement.php';

class Image extends HtmlElement
{

    function __construct()
    {
        parent::__construct('img', false);
    }

}

?>