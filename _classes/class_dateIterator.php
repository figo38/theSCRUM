<?php 

/** 
 * @package Date 
 */  

/** 
 * Implementation of simple day iterator 
 * 
 * @license http://www.gnu.org/copyleft/gpl.html GPL 
 * @author Michal 'Techi' Vrchota <michal.vrchota@seznam.cz> 
 * @package Date 
 * 
 */ 

class DayIterator implements Iterator 
{ 
    /** 
     * @var    integer $from unix timestamp 
     */ 
           
    private $from; 

    /** 
     * @var    integer $to unix timestamp 
     */ 

    private $to; 
     
    /** 
     * @var    integer $currentDate unix timestamp 
     */ 

    private $currentDate; 

    /** 
     * @var    integer $currentDay 
     */ 

    private $currentDay; 

    /** 
     * Constructor 
     *  
     * @param integer $from  unix timestamp - hour, min and sec should be equal to 0 
     * @param integer $to    unix timestamp - hour, min and sec should be equal to 0 
     * @throws InvalidArgumentException 
     */      

    public function __construct($from, $to) 
    { 
        $this->from = $from; 
        $this->to = $to; 

        if (Empty($from) || Empty($to) || $from > $to) 
        { 
            throw new InvalidArgumentException('Invalid date '.Date('j.n.Y', $from).' - '.Date('j.n.Y', $to)); 
        } 
    } 
     
    /** 
     * check if given $time is inside given range 
     * 
     * @param integer $time   unix timestamp - hour, min and sec should be equal to 0 
     * @return bool   true if $time is in range      
     */          

    public function isBetween($time) 
    { 
        return $time >= $this->from && $time <= $this->to; 
    } 

    /** 
     * Get from 
     *  
     * @return integer unix timestamp 
     */                    

    public function getFrom() 
    { 
        return $this->from; 
    } 

    /** 
     * Get To 
     *  
     * @return integer unix timestamp 
     */      

    public function getTo() 
    { 
        return $this->to; 
    } 

    /** 
     * Set From date 
     * 
     * @param integer $from 
     */ 
     
    public function setFrom($from) 
    { 
        $this->from = $from; 
    } 
     
    /** 
     * Set To date 
     * 
     * @param integer $to 
     */ 
     
    public function setTo($to) 
    { 
        $this->to = $to; 
    } 
     
    /** 
     * Computes total number of days 
     *       
     * @return integer number of days      
     */          

    public function getDiffDays() 
    { 
        return round(($this->to - $this->from) / 86400); 
    } 

    public function rewind() 
    { 
        $this->currentDate = $this->from; 
        $this->currentDay = 1; 
    } 

    /** 
     * @return integer 
     */          

    public function key() 
    { 
        return $this->currentDay; 
    } 
     
    /** 
     * @return mixed 
     */          

    public function current() 
    { 
        return $this->currentDate; 
    } 

    public function next() 
    { 
        $this->currentDate = StrToTime('+1 day', $this->currentDate); 
        $this->currentDay++; 
    } 

    /** 
     * @return bool 
     */          

    public function valid() 
    { 
        return $this->currentDate <= $this->to; 
    } 
} 
?>