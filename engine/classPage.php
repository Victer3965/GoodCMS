<?php

require_once 'classControl.php';
require_once 'classTextNode.php';
require_once 'classApplicationConfiguration.php';
require_once 'classUser.php';
require_once 'classPageController.php';

class Page extends Control
{
    /**
     * @var string[] $outStream
     * @var string $code
     */
    public $outStream = [];
    private $code = '';
    private $master;
    protected $masterPageFile;
    public $user;
    protected $pageController = null;

    public function get_editMode(){
        return $this->viewState->get('editMode', false);
    }

    public function set_editMode($editMode){
        return $this->viewState->set('editMode', $editMode);
    }

    public static function loadPage($path){
        if (!file_exists($path))
            return null;
        $code = file_get_contents($path);
        preg_match_all('/<!DIRECTIVE\s+([\s\S]*?)\s*>/', $code, $directives, PREG_SET_ORDER);
        foreach($directives as $directive){
            if (preg_match('/Page="(.*?)"/', $directive[1], $match)){
                require_once 'class' . $match[1] . '.php';
                return new $match[1]($path);
            }
        }
        return new Page($path);
    }

    public function __construct($path)
    {
        parent::__construct();
        //echo 'construct: ' . get_class($this) . ', path: ' . $path . "\n";
        //$this->setParent($this);
        $this->setPage($this);
        $this->code = file_get_contents($path);
        $this->parseDirectives();
        $this->evalPHP();
        $this->parse(0, $children);
        $this->addChildren(new PageController());
        $this->addChildren($children);
        //print_r($this);
    }

    public function run()
    {
        $this->init();
        $this->load();
        $this->pageController->executeAction();
        $this->loadComplete();
        $this->render($this->outStream);
    }

    protected function parseDirective($directive, $properties=''){
    }

    private function parseDirectives(){
        do {
            $i = preg_match('/<!DIRECTIVE\s+([\s\S]*?)\s*>/', $this->code, $directive, PREG_OFFSET_CAPTURE);

            if ($i) {
                if (preg_match('/(\S+)="(.*?)"/', $directive[1][0], $match)){
                    $this->parseDirective($match[1], $match[2]);
                }
                $this->code = substr($this->code, 0, $directive[0][1]) . substr($this->code, $directive[0][1] + strlen($directive[0][0]));
            }
        } while ($i);
    }

    private function evalPHP()
    {
        $allowCodeBlocks = ApplicationConfiguration::getProperty('settings', 'allowCodeBlocks');
        $allowed = ($allowCodeBlocks && $allowCodeBlocks['value']=='false') ? false : true;
        do {
            $i = preg_match('/<\?php([\s\S]*?)(?:\?>|$)/', $this->code, $match, PREG_OFFSET_CAPTURE);

            if ($i) {
                if (!$allowed){
                    throw new Exception('Code blocks are not allowed.');
                }
                eval($match[1][0]);
                $this->code = substr($this->code, 0, $match[0][1]) . substr($this->code, $match[0][1] + strlen($match[0][0]));
            }
        } while ($i);
    }

    function parse(int $start, &$nodes, &$end = null, $searchEnd = '')
    {
        $nodes = [];
        do {
            if ($searchEnd) {
                $i = preg_match('/<\/' . preg_quote($searchEnd) . '\s*>/', $this->code, $match, PREG_OFFSET_CAPTURE, $start);
                if (!$i) {
                    throw new Exception('DOM error. No closing tag for: "' . $searchEnd . '"');
                }
                $e = $match[0][1];
                $end = $match[0][1] + strlen($match[0][0]);
            } else {
                $end = $e = strlen($this->code);
            }

            $i = preg_match('/<(\w+?)\:([^\s>]+)([\s\S]*?)(\/?)>/', $this->code, $match, PREG_OFFSET_CAPTURE, $start);
            //print_r($match);
            if ($i && $match[0][1] >= $e || !$i) {
                if ($e > $start)
                    $nodes[] = new TextNode(substr($this->code, $start, $e - $start));
                return;
            }
            if ($match[0][1]>$start)
                $nodes[] = new TextNode(substr($this->code, $start, $match[0][1] - $start));
            require_once 'class' . $match[2][0] . '.php';
            if ($match[4][0] === '/') {
                $nodes[] = $new = new $match[2][0]();
                if ($new instanceof Control)
                    $new->setAttr($match[3][0]);
                $start = $match[0][1] + strlen($match[0][0]);
            } else {
                $nodes[] = $new = new $match[2][0]();
                $this->parse($match[0][1] + strlen($match[0][0]), $children, $end, $match[1][0] . ':' . $match[2][0]);
                if ($new instanceof Control) {
                    $new->setAttr($match[3][0]);
                    $new->addChildren($children);
                }
                $start = $end;
            }
        } while (true);
    }

    function getById($id)
    {
        foreach ($this->children as $child) {
            if ($child->id == $id) {
                return $child;
            }
            $grandchild = $child->getById($id);
            if ($grandchild) {
                return $grandchild;
            }
        }
        return null;
    }

    function init()
    {
        $this->user = User::GetFromSession();
        if ($this->masterPageFile){
            $this->master = new Page($this->masterPageFile);
            $this->master->init();
            $this->master->load();
        }
        parent::init();
    }

    function render(&$stream)
    {
        if ($this->master){
            $this->master->render($stream);
        } else {
            foreach ($this->children as $child) {
                if ($child instanceof Control)
                    $child->render($stream);
            }
        }
    }

    public function getPageController(){
        return $this->pageController;
    }
    
    public function setPageController(PageController $pageController){
        if ($this->pageController != null)
            throw new Exception("Page controller can be set only once.");
        $this->pageController = $pageController;
    }
    
    function EditPage(){
        $this->set_editMode(true);
    }
    
    /**
     * @return Page
     */
    public function getMaster()
    {
        return $this->master;
    }

    /**
     * @param Page $master
     */
    private function setMaster($master)
    {
        $this->master = $master;
    }
}
