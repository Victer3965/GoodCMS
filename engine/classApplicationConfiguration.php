<?php

class ApplicationConfiguration {

    private static $configuration = null;
    private static $xPath = null;

    private static function ensureConfiguration(){
        if (self::$configuration)
            return;
        $pathInfo = pathinfo($_SERVER['DOCUMENT_ROOT'].$_SERVER['PHP_SELF']);
        $fileName = $pathInfo['dirname'].'/.configuration.xml';
        if (file_exists($fileName)){
            $configuration = self::$configuration = new DOMDocument();
            $configuration->load($fileName);
            self::$xPath = new DOMXPath($configuration);
        } else {
            throw new Exception('Application configuration file not found: '.$fileName);
        }
    }
    
    /**
     * 
     * @param string $section Separate sub-sections with /
     * @param string $propertyName
     * @return string[]
     */
    public static function getProperty($section, $propertyName){
        self::ensureConfiguration();
        $nodes = self::$xPath->query('//configuration/' . $section . '/*[@name="'.$propertyName.'"]/@*');
        $result = [];
        if ($nodes){
            foreach ($nodes as $node) {
                $result[$node->name] = $node->value;
            }
        }
        return $result;
    }
}
