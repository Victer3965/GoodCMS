<?php

require_once 'classPage.php';
require_once 'classApplicationConfiguration.php';
require_once 'DataProviders.php';

class LayoutBasedPage extends Page{
    //put your code here

    /**
     * @var \DataProviders\DataProvider $provider
     */
    private $provider;
    public $properties;
    public $pageId;

    protected function parseDirective($directive, $properties=''){
        if ($directive=='PageID')
            $this->pageId = $properties;
        else
            parent::parseDirective($directive, $properties);
    }

    public function run(){
        $this->init();
        $this->initProvider();

        $this->provider->connect();
        $res = $this->provider->query('SELECT * FROM gc_pages WHERE id=' .  $this->provider->quote($this->pageId), PDO::FETCH_ASSOC);

        if ($res === false) {
            return $this->error('Error retrieving page info: '.$this->provider->getError());
        }

        foreach ($res as $page) {
            break;
        }
        if (!isset($page)) {
            return $this->error('Page not found');
        }
        $this->properties = [];
        $this->pageProps = &$this->properties;
        $this->pageProps = [
            'id' => $page['id']
        ];

        $res = $this->provider->query('SELECT * FROM gc_pages_props WHERE (locale="' . $this->user->locale . '" OR locale IS NULL ) AND page_id=' . $this->pageProps['id'], PDO::FETCH_ASSOC);
        if ($res === false) {
            return $this->error('Error retrieving page info: 2');
        }
        foreach ($res as $prop) {
            $this->pageProps[$prop['property']] = $prop['value'];
        }
        $this->debug(print_r($this->pageProps, true));

        if ($this->pageProps['layout']){
            return $this->renderLayout();
        } else {
            return $this->error('Page layout is not set.');
        }
    }
    
    private function renderLayout(){
        $layout = Page::loadPage('layouts/'.$this->pageProps['layout']);
        if (!$layout){
            return $this->error('Layout not found: layouts/'.$this->pageProps['layout']);
        }
        if ($layout instanceof LayoutPage){
            $layout->pageId = $this->pageId;
            $layout->properties = $this->properties;
            //echo '<!--'.print_r($this->properties, true).'-->';
            //echo '<!--'.print_r($this->user, true).'-->';
            $layout->run();
            $this->outStream = &$layout->outStream;
        }
    }

    private function initProvider(){
        $defaultConnection = ApplicationConfiguration::getProperty('settings', 'defaultConnection')['value'];
        $connectionProperties = ApplicationConfiguration::getProperty('connections', $defaultConnection);
        $providerClass = $connectionProperties['provider'];
        $this->provider = new $providerClass($connectionProperties);
    }

    private function loadMasterPage(){
        $masterSetting = ApplicationConfiguration::getProperty('settings', 'masterPage');
        if ($masterSetting && $masterSetting['value'])
            $this->masterPageFile = $masterSetting['value'];
    }

    private function debug($text) {
        $this->debugText[] = $text;
    }

    private function error($text) {
require_once 'classContent.php';

        $this->loadMasterPage();
        $content = new Content();
        $content->setAttr("cmp:Placeholder=\"MainArea\"");
        $content->addChildren(new TextNode($text));
        $content->addChildren(new TextNode('<!--'.implode("\n",$this->debugText).'-->'));
        $this->addChildren($content);
        $this->init();
        $this->load();
        $this->render($this->outStream);
    }
}

?>