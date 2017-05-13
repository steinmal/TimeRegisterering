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
    public static function intervallTilTimer(DateInterval $tid){
        $timer = $tid->d * 24 + $tid->h + ($tid->i + $tid->s / 60.0) / 60.0;
        return number_format($timer, 2);
    }
    public static function intervallTilMinutt(DateInterval $tid){
        $minutt = $tid->d * 24*60 + $tid->h * 60 + $tid->i + $tid->s / 60.0;
        return $minutt;
    }
    
    public static function DtimeToDInterval(DateTime $dt){
        $formatted = $dt->format('H:i:s');
        list($hours, $minutes, $seconds) = sscanf($formatted, '%d:%d:%d');
        return new DateInterval(sprintf('PT%dH%dM%dS', $hours, $minutes, $seconds));
    }
    
    public static function isBeforeToday(DateTime $dt){
        return DateHelper::dateCompareToday($dt) < 0;
    }
    public static function isToday(DateTime $dt){
        return DateHelper::dateCompareToday($dt) == 0;
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
    
    public function getTimer(){
        return DateHelper::intervallTilTimer($this->getInterval());
    }
    
    public static function dateCompare($date1, $date2){
        $dt1 = (new DateTime($date1))->getTimestamp();
        $dt2 = (new DateTime($date2))->getTimestamp();
        return $dt1 < $dt2 ? 1 : ($dt1 > $dt2 ? -1 : 0);
    }
    public static function dateCompareToday(DateTime $dt){
        $dt1 = $dt->getTimestamp();
        $dt2 = strtotime('today midnight');
        $dt3 = strtotime('tomorrow midnight');
        return $dt1 < $dt2 ? -1 : ($dt1 >= $dt3 ? 1 : 0);
    }
    
    /*public static function dateInside(DateTime $startOuter, DateTime $stopOuter, DateTime $startInner, DateTime $stopInner){
        if()
    }*/
}

?>