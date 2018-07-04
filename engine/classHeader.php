<?php
require_once 'classHtmlElement.php';
require_once 'classAdminPanel.php';
require_once 'classServicePanel.php';
require_once 'classMenu.php';

class Header extends Control
{
    function __construct()
    {
        parent::__construct('header', ['class'=>'top-bottom fixed'], true);

        $this->add(new AdminPanel());
        $this->add(new ServicePanel());
        $this->add(new Menu('TopNavigation'));
    }

    /**
     * @param string[] $stream
     */
    public function render(&$stream)
    {
        // TODO: Implement render() method.
    }
}
?>