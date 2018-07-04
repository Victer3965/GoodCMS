<?php

require_once 'classControl.php';
require_once 'classTemplate.php';
require_once 'IDataBoundControl.php';

class DataGrid extends Control implements IDataBoundControl
{
    protected $data;
    protected $tableName;
    private $dataRow;

    public static function fieldMap() {
        return parent::fieldMap() + [
            'cmp:dataTable' => 'tableName'
        ];
    }
            
    function load() {
        parent::load();
        $this->data = $this->getPage()->getById($this->tableName);
        //$this->children[] = new HtmlElement('table');
    }
    
    function render(&$stream)
    {
        $attr = [];
        if (is_array($this->attr['html']))
            foreach ($this->attr['html'] as $name => $value)
                $attr[] = $name . '="' . htmlentities($value) . '"';

        $stream[] = '<table' . (count($attr) ? ' ' . implode(' ', $attr) : '') . '>';

        $this->renderHeader($stream);
        $this->renderBody($stream);
        $this->renderFooter($stream);
        $stream[] = '</table>';
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

    public function renderHeader(&$stream)
    {
        $template = $this->findTemplate('header');
        if (!$template)
            return;
        $stream[] = '<thead>';
        $template->render($stream);
        $stream[] = '</thead>';
    }

    public function renderBody(&$stream)
    {
        $template = $this->findTemplate('body');
        if (!$template)
            return;

        $stream[] = '<tbody>';
        if ($this->data){
            $data = $this->data->getData();
            if ($data) {
                foreach ($data as $row) {
                    $this->dataRow = $row;
                    $template->render($stream);
                }
            }
        }
        $stream[] = '</tbody>';
    }

    public function renderFooter(&$stream)
    {
        $template = $this->findTemplate('footer');
        if (!$template)
            return;
        $stream[] = '<tfoot>';
        $template->render($stream);
        $stream[] = '</tfoot>';
    }

    public function getDataRow(){
        return $this->dataRow;
    }
}
