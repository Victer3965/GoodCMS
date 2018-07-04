<?php

require_once 'classPage.php';

global $page;
$page = Page::loadPage($_GET['page']);
if (!$page){
    header('HTTP/1.0 404 Not Found');
    require 'error404.php';
    return;
}

$page->run();

echo implode('', $page->outStream);

//echo '<!--' . print_r($page, true) . "\n-->";