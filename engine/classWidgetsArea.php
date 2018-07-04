<?php

require_once 'classControl.php';
require_once 'classScript.php';
require_once 'classWidget.php';
require_once 'classApplicationConfiguration.php';
require_once 'DataProviders.php';

class WidgetsArea extends Control {

    /**
     * @var \DataProviders\DataProvider $provider
     */
    private $provider;

    public function init(){
        $this->initProvider();
        $this->provider->connect();
        $res = $this->provider->query('SELECT * FROM gc_pages_widgets WHERE page_id=' . $this->provider->quote($this->getPage()->pageId) . 
                ' AND area=' . $this->provider->quote($this->get_id()) . ' ORDER BY `order`');
        if (!$res){
            return;
        }

        foreach ($res as $widgetInfo){
            $widgetClass = $widgetInfo['class'];
            require_once 'class' . $widgetClass . '.php';
            $widget = new $widgetClass();
            if ($widget instanceof Widget){
                $this->addChildren($widget);
                $widget->initWidget($widgetInfo['data']);
            }
        }
        parent::init();
    }

    function loadComplete() {
        global $page;
        parent::loadComplete();

        $this->editMode = $page->get_editMode();
        if ($this->editMode){
            //echo '<!--Widget:init, editMode-->';
            $script = $this->getPage()->getById('gcScriptEditWidget');
            if (!$script){
                //echo '<!--Widget:init, no script - creating-->';
                $script = new Script();
                $script->setAttr('html:src="/js/gcEditWidget.js" html:type="text/javascript" cmp:id="gcScriptEditWidget"');
                $this->addChildren($script);
            }
        }
    }

    private function initProvider(){
        $defaultConnection = ApplicationConfiguration::getProperty('settings', 'defaultConnection')['value'];
        $connectionProperties = ApplicationConfiguration::getProperty('connections', $defaultConnection);
        $providerClass = $connectionProperties['provider'];
        $this->provider = new $providerClass($connectionProperties);
    }

    public function render(&$stream) {
        $stream[] = '<div class="gc-widget-area'.($this->editMode ? ' edit' : '').'">';
        $spacer = '';
        foreach ($this->children as $child) {
            if ($child instanceof Widget){
                if ($child->isVisible()){
                    $stream[] = $spacer;
                    $spacer = '';
                }
                $child->render($stream);
                if ($child->isVisible()){
                    $spacer = '<hr class="gc-widget-spacer" />';
                }
            } else if ($child instanceof Control){
                $child->render($stream);
            }
        }
        $stream[] = '</div>';
    }

}
