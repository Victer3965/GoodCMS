<?php

require_once 'classControl.php';
require_once 'IDataBoundControl.php';

class Template extends Control implements IDataBoundControl
{
    public $role;
    private $parentDataBoundControl;

    protected static function fieldMap()
    {
        return parent::fieldMap() + [
                'cmp:role' => 'role'
            ];
    }

    public function getDataRow(){
        return $this->dataRow;
    }

    /**
     * @param string[] $stream
     */
    public function render(&$stream)
    {
        if (!$this->parentDataBoundControl){
            $control = $this->getParent();
            do{
                if ($control instanceof IDataBoundControl)
                    break;
                $control = $this->getParent();
            } while($control);
            $this->parentDataBoundControl = $control;
        }
        $this->dataRow = $this->parentDataBoundControl->getDataRow();
        foreach ($this->children as $element)
            if ($element instanceof Control)
                $element->render($stream);
    }
}
