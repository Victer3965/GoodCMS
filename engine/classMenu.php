<?php

require_once 'classPanel.php';
require_once 'classControl.php';
require_once 'IDataBoundControl.php';

class Menu extends Control implements IDataBoundControl
{
    /*
     * @var string
     */
    protected $menuName;
    protected $menuDataSource;
    private $dataRow;

    public static function fieldMap() {
        return parent::fieldMap() + [
            'cmp:menuDataSource' => 'menuDataSource'
        ];
    }
    
    function load() {
        //echo '<!--Menu:load-->';
        parent::load();
        $this->data = $this->getPage()->getById($this->menuDataSource);
    }

    function findTemplate($role)
    {
        foreach ($this->children as $element) {
            if (($element instanceof Template) && $element->role == $role) {
                return $element;
            }
        }
        return null;
    }

    public function getDataRow(){
        return $this->dataRow;
    }

    function renderMenuItem(&$menuItem, &$stream)
    {
        $head = $this->findTemplate('head');
        $main = $this->findTemplate('main');
        $foot = $this->findTemplate('foot');
        $this->dataRow = $menuItem['item'];
        if ($head)
            $head->render($stream);
        if ($main)
            $main->render($stream);
        if($menuItem['children'] && count($menuItem['children']))
            $this->renderSubMenu($menuItem['children'], $stream, 'class="sub-menu"');
        if ($foot)
            $foot->render($stream);
    }

    function renderSubMenu($menu, &$stream, $attr = '')
    {
        $stream[] = '<div '.$attr.'>';
        foreach ($menu as $item)
            $this->renderMenuItem($item, $stream);
        $stream[] = '</div>';
    }

    function render(&$stream)
    {
        //echo '<!--Menu:render-->';
        if (!$this->data)
            return;

        $data = $this->data->getData();
        if (!$data)
            return;
        
        $items = [];
        foreach ($data as $item) {
            $items[$item['id']] = $item;
        }

        $tree = [];
        $treeIndex = [];
        while (count($items)) {
            $updated = 0;
            foreach ($items as $id => $item) {
                if (!$item['parent_id']) {
                    $tree[] = ['item' => $item, 'children' => []];
                    $treeIndex[$id] = &$tree[count($tree)-1];
                    $updated = 1;
                    unset($items[$id]);
                    break;
                } else if ($treeIndex[$item['parent_id']]) {
                    $treeIndex[$item['parent_id']]['children'][] = ['item' => $item, 'children' => []];
                    $treeIndex[$id] = &$treeIndex[$item['parent_id']]['children'][count($treeIndex[$item['parent_id']]['children'])-1];
                    $updated = 1;
                    unset($items[$id]);
                    break;
                }
            }
            if (!$updated){
                break;
            }
        }

        $attr = [];
        if (is_array($this->attr['html']))
            foreach ($this->attr['html'] as $name => $value)
                $attr[] = $name . '="' . htmlentities($value) . '"';

        $this->renderSubMenu($tree, $stream, 'class="menu" unselectable="on" onselectstart="return false;" '.implode(' ', $attr));
    }
}

?>