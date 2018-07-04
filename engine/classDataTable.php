<?php

require_once 'classControl.php';
require_once 'DataProviders.php';
require_once 'classApplicationConfiguration.php';

class DataTable extends Control
{
    /**
     * @var string $query
     * @var string $connection
     * @var \DataProviders\Provider $provider
     */
    public $query;
    protected $connection;
    protected $provider;

    //private $onInit;

    public static function fieldMap()
    {
        return parent::fieldMap() + [
                'cmp:connection' => 'connection',
                'cmp:query' => 'query'
            ];
    }

    public function init()
    {
        parent::init();
        $connectionProperties = ApplicationConfiguration::getProperty('connections', $this->connection);
        $providerClass = $connectionProperties['provider'];
        $this->provider = new $providerClass($connectionProperties);
    }

    public function getData()
    {
        $this->provider->connect();
        $result = $this->provider->query($this->query);
        return $result;
    }

    /**
     * @param string[] $stream
     */
    function render(&$stream)
    {
        // TODO: Implement render() method.
    }
}
