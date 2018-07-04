<!DOCTYPE html>
<html>
<head>
    <title><php:Placeholder cmp:id="PageTitle"></php:Placeholder></title>
    <script type="text/javascript" src="//ajax.aspnetcdn.com/ajax/jQuery/jquery-3.1.0.min.js"></script>
    <php:StyleSheetLink html:type="text/css" html:rel="stylesheet" html:href="/styles/main.css" />
    <php:Placeholder cmp:id="PageHead"></php:Placeholder>
</head>
<body>
<!--php:Header cmp:id="MainHeader"></php:Header-->
    <header class="top-bottom fixed">
    <a class="homepage" href="/" title="Dragon City Wiki Homepage"></a>
    <php:Button cmp:id="btnEditPage" cmp:onClick="EditPage">Edit Page</php:Button>
    <php:Menu cmp:menuDataSource="MainMenuDataSource" cmp:class="menu">
        <php:Template cmp:role="head">
            <div class="menu-item">
        </php:Template>
        <php:Template cmp:role="main">
            <a href="<php:DataField cmp:name="link" />" class="menu-item-text"><php:DataField cmp:name="text" /></a>
        </php:Template>
        <php:Template cmp:role="foot">
            </div>
        </php:Template>
    </php:Menu>
    </header>
    <php:MenuDataSource cmp:id="MainMenuDataSource" cmp:menuName="TopNavigation" cmp:connection="debug" />

    <php:Placeholder cmp:id="MainArea"></php:Placeholder>
</body>
</html>

<?php

function EditPage(){
    global $page;
    $page->EditPage();
}