<?php

require_once 'classPage.php';
require_once 'classApplicationConfiguration.php';

class LayoutPage extends Page{
    //put your code here

    public $properties;
            
    function init() {
        $masterSetting = ApplicationConfiguration::getProperty('settings', 'masterPage');
        if ($masterSetting && $masterSetting['value'])
            $this->masterPageFile = $masterSetting['value'];
        parent::init();
    }
    
}
