<?php

class DateHelper{
    public static function sumDateInterval($d1, $d2){
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
    
    public function __construct(DateTime $before = null, DateTime $after = null){
        $this->before = ($before != null ? $before : DateTime::createFromFormat('!', ""));
        $this->after = ($after != null ? $after : DateTime::createFromFormat('!', ""));
    }
    
    public function add(DateInterval $dt){
        $this->after->add($dt);
    }
    
    public function getInterval(){
        return $this->before->diff($this->after);
    }
}

?>