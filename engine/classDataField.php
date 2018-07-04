<?php

require_once 'classControl.php';

class DataField extends Control {

    private $parentDataBoundControl;
    protected $fieldName;

    public static function fieldMap()
    {
        return parent::fieldMap() + [
                'cmp:name' => 'fieldName'
            ];
    }

    function render(&$stream){
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
        $stream[] = $this->dataRow[$this->fieldName];
    }

}
