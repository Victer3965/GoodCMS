<?php

require_once 'classControl.php';

abstract class Widget extends Control {

//    abstract public function initWidget(&$data);
    protected $settings;
    protected $menuControl;
    
    abstract protected function renderInternal(&$stream);

    public function load(){
        parent::load();
        if (count($this->children)>0)
            $this->initWidget($this->children[0]);
    }

    public function getTitle(){
        return get_class($this);
    }

    public function getMenu(){
        return [
            'edit' => [
                'onclick' => 'GoodCMS.ensureScript("GoodCMS")'
            ],
            'remove' => [
                'onclick' => 'return confirm("Удалить виджет?")'
            ]
        ];
    }

    public function handleMenu($action){
        switch ($action){
            case 'edit':
                break;
            case 'remove':
                break;
        }
    }

    public function isVisible(){
        return true;
    }

    public function initWidget(&$data){
        $this->settings = simplexml_load_string($data);
    }
    
    function loadComplete() {
        parent::loadComplete();
        
        if ($this->getParent()->editMode){
            $this->addChildren($this->menuControl = new WidgetsContextMenu($this->getMenu()));
            $this->menuControl->init();
            $this->menuControl->load();
        }
    }

    public function render(&$stream){
        if ($this->getParent()->editMode){
            $stream[] = '<div class="gc-widget">';
            $stream[] = '<div class="gc-widget-title"><div class="gc-widget-menu-button"><div class="gc-widget-menu">';
            $this->renderMenu($stream);
            $stream[] = '</div></div>'.$this->getTitle().'</div>';
            $this->renderInternal($stream);
            $stream[] = '</div>';
        } else {
            $this->renderInternal($stream);
        }
    }    

    private function renderMenu(&$stream){
        $stream[] = '<form method="POST"><input type="hidden" name="EditMode" value="true" /><input type="hidden" name="control" value="'.$this->uniqueId.'" />';
        foreach ($this->getMenu() as $action=>$props){
            $stream[] = '<input type="submit" name="action" value="'.$action.'" class="gc-menu-action" '.
                (isset($props['onclick'])?'onclick="'.htmlentities($props['onclick']).'" ':'').'/>';
        }
        $stream[] = '</form>';
    }

    public abstract function edit();
    
}
