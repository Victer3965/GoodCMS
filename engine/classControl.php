<?php

require_once 'classStateDepot.php';

abstract class Control
{
    /**
     * @var StateDepot $viewState
     */
    protected $viewState;
    private static $controls = [];
    private $autoId = 0;
    protected $children = [];
    protected $attr = [];
    private $page;
    private $parent;
    private $id;
    protected $onInit = '';
    protected $onLoad = '';

    private function assignUniqueId()
    {
        return ++$this->autoId;
    }

    public function __construct()
    {
        $this->viewState = new StateDepot();
    }

    public function init()
    {
        foreach ($this->children as $children) {
            $children->init();
        }
        if ($this->onInit) {
            $onInit = $this->onInit;
            $onInit($this);
        }
    }

    public function load()
    {
        if ($this->onLoad) {
            $onLoad = $this->onLoad;
            $onLoad($this);
        }
        foreach ($this->children as $child) {
            $child->load();
        }
    }

    public function loadComplete()
    {
        if ($this->onLoadComplete) {
            $onLoadComplete = $this->onLoadComplete;
            $onLoadComplete($this);
        }
        foreach ($this->children as $child) {
            $child->loadComplete();
        }
    }

    /**
     * @return Control
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Control $parent
     */
    protected function setParent($parent)
    {
        $this->parent = $parent;
        $this->setPage($parent->getPage());
    }

    /**
     * @return Page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param Page $page
     */
    protected function setPage($page)
    {
/*
        if ($page && get_class($page)!='Page'){
            throw new Exception('Wrong page: '. get_class($page));
        }
        echo 'setPage: ' . get_class($this) . ', page: ' . get_class($page) . "\n";
 */
        if (isset($this->page)){
            throw new Exception('Page cannot be defined twice.');
        }
        $this->page = $page;
        foreach ($this->children as $child){
            $child->setPage($page);
        }
    }

    /**
     * @param Control[]|Control $children
     */
    function addChildren(&$children)
    {
        if (is_array($children)) {
            foreach ($children as $child) {
                $child->setParent($this);
            }
            $this->children = array_merge($this->children, $children);
        } else {
            $children->setParent($this);
            $this->children[] = $children;
        }
    }

    /**
     * @param string[] $stream
     */
    public abstract function render(&$stream);

    protected function set_id($id){
        if (isset(Control::$controls[$id])){
            if (Control::$controls[$id]==$this)
                return;
            throw new Exception("Duplicate control id: ".$id);
        }
        
        if (isset(Control::$controls[$this->id]))
            unset(Control::$controls[$this->id]);
        
        $this->id = $id;
        Control::$controls[$id] = $this;
    }
    
    public function get_id(){
        $id = $this->id;
        return $id;
    }

    public function getById($id)
    {
        if (isset(Control::$controls[$id])){
            return Control::$controls[$id];
        }
        
        return null;
    }

    /**
     * @param string $attr
     */
    public function setAttr($attr)
    {
        $this->attr = $this->parseAttr($attr);

    }

    /**
     * @return string[]
     */
    protected static function fieldMap()
    {
        //echo "Control:fieldMap\n";
        return [
            'cmp:id' => 'id',
            'cmp:onInit' => 'onInit',
            'cmp:onLoad' => 'onLoad'
        ];
    }

    private function parseAttr($attr)
    {
        preg_match_all('/\s*(\S+)=(?:"(.*?)"|(.*?)(?=\s|$))/', $attr, $matches, PREG_SET_ORDER);
        $result = [];
        $class = get_class($this);
        $selfMap = $class::fieldMap();

        foreach ($matches as $match) {
            if (isset($selfMap[$match[1]])) {
                $field = $selfMap[$match[1]];
                if (method_exists($this, 'set_'.$field)){
                    $method = 'set_'.$field;
                    $this->$method($match[2] . $match[3]);
                } else {
                    $r = new ReflectionProperty($class, $field);
                    $r->setAccessible(true);
                    $r->setValue($this, $match[2] . $match[3]);
                }
            }
            preg_match('/(?:(.*?)\:|)(.+)?/', $match[1], $pars);
            $result[$pars[1]][$pars[2]] = $match[2] . $match[3];
        }
        return $result;
    }
}
