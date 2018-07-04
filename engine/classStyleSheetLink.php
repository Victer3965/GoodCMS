<?php

require_once 'classLink.php';

class StyleSheetLink extends Link {

    public function init(){
        $this->attr['html']['href'] = $this->attr['html']['href'] . '?v=' . filemtime($_SERVER['SERVER_ROOT'] . $this->attr['html']['href']);
        parent::init();
    }
}
