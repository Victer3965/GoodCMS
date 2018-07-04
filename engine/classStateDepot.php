<?php

class StateDepot {
    private $depot = [];
    private $track = false;

    public function set($key, $value){
        $this->depot[$key] = [$value, $this->track?true:false];
    }
    
    public function get($key, $default=null){
        return isset($this->depot[$key]) ? $this->depot[$key][0] : $default;
    }

    public function has($key){
        return isset($this->depot[$key]);
    }

    public function track(){
        $this->track = true;
    }

    public function clean($key){
        if (isset($this->depot[$key]))
            $this->depot[$key][1] = false;
    }

    public function isDirty($key){
        return isset($this->depot[$key]) ? $this->depot[$key][1] : null;
    }

    public function getDirties(){
        $result = [];
        foreach ($this->depot as $key=>$value)
            if ($value[1])
                $result[$key] = $value[0];
        return $result;
    }
}
