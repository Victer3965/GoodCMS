<?php

require_once 'classApplicationConfiguration.php';

class User
{
    private $user;
    private static $provider;
    public $isAuthenticated;

    private function __construct($userProps, $authenticated = false)
    {
        $this->user = $userProps;
        $this->isAuthenticated = $authenticated;
    }

    public static function GetFromSession()
    {
        session_start();
        if (isset($_SESSION['ip']) && $_SESSION['ip'] != $_SERVER['REMOTE_ADDR'] && !$_SESSION['noIPcheck']) {
            header('Location: error.php?reason=session-security');
            return;
        }
        if (isset($_SESSION['user']))
            return new User($_SESSION['user'], true);

        return new User(['locale' => 'ru_RU', 'is_admin' => false, 'theme'=>'blue']);
    }

    public static function GetByID($id)
    {
        self::initProvider();
        $db = self::$provider;

        $res = $db->query('SELECT * FROM gc_users WHERE id=' . $db->quote($id));
        if ($res === false)
            return null;

        foreach ($res as $user) {
            break;
        }
        if (!isset($user))
            return null;

        $res = $db->query('SELECT * FROM gc_users_props WHERE user_id=' . $user['id']);
        if ($res === false)
            return null;

        $userProps = Array();
        foreach ($res as $prop) {
            $userProps[$prop['property']] = $prop['value'];
        }

        return new User($userProps);
    }

    private static function initProvider(){
        if (isset(self::$provider))
            return;
        $defaultConnection = ApplicationConfiguration::getProperty('settings', 'defaultConnection')['value'];
        $connectionProperties = ApplicationConfiguration::getProperty('connections', $defaultConnection);
        $providerClass = $connectionProperties['provider'];
        self::$provider = new $providerClass($connectionProperties);
    }


    public function __get($name)
    {
        return $this->user[$name];
    }

    public function __isset($name)
    {
        return isset($this->user[$name]);
    }
}
?>