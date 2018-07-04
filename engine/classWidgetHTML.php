<?php

require_once 'classWidget.php';

class WidgetHTML extends Widget {

    /**
     * @var array
     */
    private $data;
    private $editPanel;

    public function initWidget(&$data) {
        parent::initWidget($data);
        if ($this->data){
            
        }
    }

    protected function renderInternal(&$stream) {
        if ((string)$this->settings->source){
            if (file_exists($_SERVER['SERVER_ROOT'] . $this->settings->source))
                $stream[] = file_get_contents($_SERVER['SERVER_ROOT'] . $this->settings->source);
            else
                $stream[] = '<div class="error">Cannot display widget content. File not found: '.$this->settings->source.'</div>';
        } else {
            $stream[] = (string)$this->settings->html;
        }
    }

    public function edit() {
        $editPanel = 1;
    }

}
