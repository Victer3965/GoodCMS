<?php

namespace DataProviders{


abstract class DataProvider
{
    /**
     * @var string[] $settings Array of values required to connect.
     */
    protected $settings;

    public function __construct($settings) {
        $this->settings = $settings;
    }

    abstract public function connect();

    /**
     * @param string $query A string containing a query to the data source.
     */
    abstract public function query($query);

    /**
     * @param string $str A string to escape with quote characters.
     */
    abstract public function quote($str);
}

}