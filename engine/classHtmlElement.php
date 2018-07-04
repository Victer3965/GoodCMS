<?php

require_once 'classControl.php';

class HtmlElement extends Control
{
    private $nodeName;
    private $canHaveChildren = true;

    function __construct($nodeName, $canHaveChildren = true)
    {
        $this->nodeName = $nodeName;
        $this->canHaveChildren = $canHaveChildren;
    }

    function __toString()
    {
        return (string)$this->nodeName;
    }

    function addFirst($child)
    {
        if ($this->canHaveChildren) {
            $this->children = array_merge([$child], $this->children);
        } else {
            throw new Exception();
        }
        return $this;
    }

    function add($child)
    {
        if (!$child) {
            throw new Exception('Child is null!');
        }
        $this->children[] = $child;
        return $this;
    }

    function render(&$stream)
    {
        $attr = [];
        if (is_array($this->attr['html']))
            foreach ($this->attr['html'] as $name => $value)
                $attr[] = $name . '="' . htmlentities($value) . '"';

        if ($this->canHaveChildren) {
            $stream[] = '<' . $this->nodeName . (count($attr) ? ' ' . implode(' ', $attr) : '') . '>';

            foreach ($this->children as $element)
                if ($element instanceof Control)
                    $element->render($stream);

            $stream[] = '</' . $this->nodeName . '>';
        } else {
            $stream[] = '<' . $this->nodeName . (count($attr) ? ' ' . implode(' ', $attr) : '') . '/>';
        }
    }
}

?>