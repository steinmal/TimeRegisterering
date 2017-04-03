<?php

class DateHelper{
    public static function sumDateInterval(DateInterval $d1, DateInterval $d2){
        $before = DateTime::createFromFormat('!', "");
        $after = clone $before;

        if($d1 != null){ $after->add($d1); }
        if($d2 != null){ $after->add($d2); }
        
        return $before->diff($after);
    }

    public static function sumDateIntervalList(array $intervals){
        $before = DateTime::createFromFormat('!', "");
        $after = clone $before;
        
        foreach($intervals as $interval){
            $after->add($interval);
        }
        
        return $before->diff($after);
    }
    
    private $before;
    private $after;
    
    public function __construct($before = null, $after = null){
        $this->before = $before ? $before : DateTime::createFromFormat('!', "");
        $this->after = $after ? $after : DateTime::createFromFormat('!', "");
    }
    
    public function add(DateInterval $dt){
        $after->add($dt);
    }
    
    public function getInterval(){
        return $before->diff($after);
    }
}

?>