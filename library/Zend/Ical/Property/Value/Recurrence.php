<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Ical
 * @subpackage Zend_Ical_Property
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @namespace
 */
namespace Zend\Ical\Property\Value;

/**
 * Recurrence value.
 *
 * @category   Zend
 * @package    Zend_Ical
 * @subpackage Zend_Ical_Property
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Recurrence implements Value
{
    /**
     * Allowed frequencies.
     * 
     * @var array
     */
    protected static $frequencies = array('SECONDLY', 'MINUTELY', 'HOURLY', 'DAILY', 'WEEKLY', 'MONTHLY', 'YEARLY');
    
    /**
     * Allowed weekdays.
     * 
     * @var array
     */
    protected static $frequencies = array('SU', 'MO', 'TU', 'WE', 'TH', 'FR', 'SA');
    
    /**
     * Frequency.
     * 
     * @var string
     */
    protected $frequency;
    
    /**
     * Until.
     * 
     * @var mixed
     */
    protected $until;
    
    /**
     * Count.
     * 
     * @var integer
     */
    protected $count;
    
    /**
     * Interval.
     * 
     * @var integer 
     */
    protected $interval;
    
    /**
     * By second.
     * 
     * @var array
     */
    protected $bySecond = array();
    
    /**
     * By minute.
     * 
     * @var array
     */
    protected $byMinute = array();
    
    /**
     * By hour.
     * 
     * @var array
     */
    protected $byHour = array();
    
    /**
     * By day.
     * 
     * @var array
     */
    protected $byDay = array();
    
    /**
     * By month day.
     * 
     * @var array
     */
    protected $byMonthDay = array();
    
    /**
     * By year day.
     * 
     * @var array
     */
    protected $byYearDay = array();
    
    /**
     * By week no.
     * 
     * @var array
     */
    protected $byWeekNo = array();
    
    /**
     * By month.
     * 
     * @var array 
     */
    protected $byMonth = array();
    
    /**
     * By set pos.
     * 
     * @var array
     */
    protected $bySetPos = array();
    
    /**
     * Weekday.
     * 
     * @var string
     */
    protected $weekday;
    
    /**
     * Create a new recurrence value.
     * 
     * @param  string $frequency
     * @return void
     */
    public function __construct($frequency)
    {
        $this->setFrequency($frequency);
    }
    
    /**
     * Set frequency.
     * 
     * @param  string $frequency
     * @return self
     */
    public function setFrequency($frequency)
    {
        $frequency = strtoupper($frequency);
        
        if (!in_array($frequency, self::$frequencies)) {
            throw new Exception\InvalidArgumentException(sprintf('Frequency value "%s" is not valid', $frequency));
        }
        
        $this->frequency = $frequency;
        return $this;
    }
    
    /**
     * Get frequency.
     * 
     * @return string
     */
    public function getFrequency()
    {
        return $this->frequency;
    }

    /**
     * Set until.
     * 
     * @param  mixed $until
     * @return self
     */
    public function setUntil($until = null)
    {
        if ($until === null) {
            $this->until = null;
        } elseif ($this->count !== null) {
            throw new Exception\RuntimeException('Until cannot be set while Count is set');
        } else {
            if (!$until instanceof DateTime && !$until instanceof Date) {
                try {
                    $until = DateTime::fromString($until);
                } catch (Exception $e) {
                    try {
                        $until = Date::fromString($until);
                    } catch (Exception $e) {
                        throw new Exception\InvalidArgumentException('Until does neither match DateTime nor Date');
                    }
                }
            }

            $this->until = $until;
        }
        
        return $this;
    }
    
    /**
     * Get until.
     * 
     * @return integer
     */
    public function getUntil()
    {
        return $this->until;
    }
    
    /**
     * Set count.
     * 
     * @param  integer $count
     * @return self
     */
    public function setCount($count = null)
    {
        if ($count === null) {
            $this->count = null;
        } elseif ($this->until !== null) {
            throw new Exception\RuntimeException('Count cannot be set while Until is set');
        } else {
            if (!is_numeric($count)) {
                throw new Exception\InvalidArgumentException(sprintf('Count must be an integer, "%s" received', $count));
            }

            $this->count = (int) $count;
        }
        
        return $this;
    }
    
    /**
     * Get count.
     * 
     * @return integer
     */
    public function getCount()
    {
        return $this->count;
    }
    
    /**
     * Set weekday.
     * 
     * @param  string $weekday
     * @return self
     */
    public function setWeekday($weekday = null)
    {
        if ($weekday === null) {
            $this->weekday = null;
        } else {
            $weekday = strtoupper($weekday);

            if (!in_array($weekday, self::$weekdays)) {
                throw new Exception\InvalidArgumentException(sprintf('Weekday value "%s" is not valid', $weekday));
            }

            $this->weekday = $weekday;
        }
        
        return $this;
    }
    
    /**
     * Get weekday.
     * 
     * @return string
     */
    public function getWeekday()
    {
        return $this->weekday;
    }
    
    /**
     * Set by second.
     * 
     * @param  array $bySecond
     * @return self
     */
    public function setBySecond(array $bySecond = null)
    {
        if ($bySecond === null) {
            $this->bySecond = array();
        } else {
            $values = array();
            
            foreach ($bySecond as $value) {
                if (!is_numeric($value)) {
                    throw new Exception\InvalidArgumentException(sprintf('BySecond values must be integers, "%s" received', $value));
                } elseif ($value < 0) {
                    throw new Exception\InvalidArgumentException(sprintf('BySecond value "%s" is lower than 0', $value));
                } elseif ($value > 60) {
                    throw new Exception\InvalidArgumentException(sprintf('BySecond value "%s" is greater than 60', $value));
                }
            
                $values[] = (int) $value;
            }
            
            if (count($values) === 0) {
                throw new Exception\InvalidArgumentException('BySecond values must contain at least one element');
            }
            
            $this->bySecond = $values;
        }
        
        return $this;
    }
    
    /**
     * Get by second.
     * 
     * @return array
     */
    public function getBySecond()
    {
        return $this->bySecond;
    }
    
    /**
     * Set by minute.
     * 
     * @param  array $byMinute
     * @return self
     */
    public function setByMinute(array $byMinute = null)
    {
        if ($byMinute === null) {
            $this->byMinute = array();
        } else {
            $values = array();
            
            foreach ($byMinute as $value) {
                if (!is_numeric($value)) {
                    throw new Exception\InvalidArgumentException(sprintf('ByMinute values must be integers, "%s" received', $value));
                } elseif ($value < 0) {
                    throw new Exception\InvalidArgumentException(sprintf('ByMinute value "%s" is lower than 0', $value));
                } elseif ($value > 59) {
                    throw new Exception\InvalidArgumentException(sprintf('ByMinute value "%s" is greater than 59', $value));
                }
                            
                $values[] = (int) $value;
            }
            
            if (count($values) === 0) {
                throw new Exception\InvalidArgumentException('ByMinute values must contain at least one element');
            }
            
            $this->byMinute = $values;
        }
        
        return $this;
    }
    
    /**
     * Get by minute.
     * 
     * @return array
     */
    public function getByMinute()
    {
        return $this->byMinute;
    }
    
    /**
     * Set by hour.
     * 
     * @param  array $byHour
     * @return self
     */
    public function setByHour(array $byHour = null)
    {
        if ($byHour === null) {
            $this->byHour = array();
        } else {
            $values = array();
            
            foreach ($byHour as $value) {
                if (!is_numeric($value)) {
                    throw new Exception\InvalidArgumentException(sprintf('ByHour values must be integers, "%s" received', $value));
                } elseif ($value < 0) {
                    throw new Exception\InvalidArgumentException(sprintf('ByHour value "%s" is lower than 0', $value));
                } elseif ($value > 23) {
                    throw new Exception\InvalidArgumentException(sprintf('ByHour value "%s" is greater than 23', $value));
                }
                            
                $values[] = (int) $value;
            }
            
            if (count($values) === 0) {
                throw new Exception\InvalidArgumentException('ByHour values must contain at least one element');
            }
            
            $this->byHour = $values;
        }
        
        return $this;
    }
    
    /**
     * Get by hour.
     * 
     * @return array
     */
    public function getByHour()
    {
        return $this->byHour;
    }
    
    /**
     * Set by day.
     * 
     * @param  array $byDay
     * @return self
     */
    public function setByDay(array $byDay = null)
    {
        if ($byDay === null) {
            $this->byDay = array();
        } else {
            $values = array();
            
            foreach ($byDay as $value) {
                $value = strtoupper($value);
                
                if (!preg_match('(^((?:[+-]?)\d{1,2})?([A-Z]{2})?$)S', $value, $match)) {
                    throw new Exception\InvalidArgumentException(sprintf('ByDay value "%s" is not valid', $value));
                }
                
                $value = array((int) $match[1], $match[2]);

                if ($value[0] < -53) {
                    throw new Exception\InvalidArgumentException(sprintf('ByDay value "%s" is lower than -53', $value));
                } elseif ($value[0] > 53) {
                    throw new Exception\InvalidArgumentException(sprintf('ByDay value "%s" is greater than 53', $value));
                } elseif ($value[0] == 0) {
                    throw new Exception\InvalidArgumentException(sprintf('ByDay value "%s" is 0', $value));
                } elseif (!in_array($value[1], self::$weekdays)) {
                    throw new Exception\InvalidArgumentException(sprintf('ByDay value "%s" is not valid', $value));
                }
                            
                $values[] = $value;
            }
            
            if (count($values) === 0) {
                throw new Exception\InvalidArgumentException('ByDay values must contain at least one element');
            }
            
            $this->byDay = $values;
        }
        
        return $this;
    }
    
    /**
     * Get by day.
     * 
     * @return array
     */
    public function getByDay()
    {
        return $this->byDay;
    }
    
    /**
     * Set by month day.
     * 
     * @param  array $byMonthDay
     * @return self
     */
    public function setByMonthDay(array $byMonthDay = null)
    {
        if ($byMonthDay === null) {
            $this->byMonthDay = array();
        } else {
            $values = array();
            
            foreach ($byMonthDay as $value) {
                if (!is_numeric($value)) {
                    throw new Exception\InvalidArgumentException(sprintf('ByMonthDay values must be integers, "%s" received', $value));
                } elseif ($value < 1) {
                    throw new Exception\InvalidArgumentException(sprintf('ByMonthDay value "%s" is lower than 1', $value));
                } elseif ($value > 31) {
                    throw new Exception\InvalidArgumentException(sprintf('ByMonthDay value "%s" is greater than 31', $value));
                }
                            
                $values[] = (int) $value;
            }
            
            if (count($values) === 0) {
                throw new Exception\InvalidArgumentException('ByMonthDay values must contain at least one element');
            }
            
            $this->byMonthDay = $values;
        }
        
        return $this;
    }
    
    /**
     * Get by month day.
     * 
     * @return array
     */
    public function getByMonthDay()
    {
        return $this->byMonthDay;
    }
    
    /**
     * Set by year day.
     * 
     * @param  array $byYearDay
     * @return self
     */
    public function setByYearDay(array $byYearDay = null)
    {
        if ($byYearDay === null) {
            $this->byYearDay = array();
        } else {
            $values = array();
            
            foreach ($byYearDay as $value) {
                if (!is_numeric($value)) {
                    throw new Exception\InvalidArgumentException(sprintf('ByYearDay values must be integers, "%s" received', $value));
                } elseif ($value < -366) {
                    throw new Exception\InvalidArgumentException(sprintf('ByYearDay value "%s" is lower than -366', $value));
                } elseif ($value > 366) {
                    throw new Exception\InvalidArgumentException(sprintf('ByYearDay value "%s" is greater than 366', $value));
                } elseif ($value == 0) {
                    throw new Exception\InvalidArgumentException(sprintf('ByYearDay value "%s" is 0', $value));
                }
                            
                $values[] = (int) $value;
            }
            
            if (count($values) === 0) {
                throw new Exception\InvalidArgumentException('ByYearDay values must contain at least one element');
            }
            
            $this->byYearDay = $values;
        }
        
        return $this;
    }
    
    /**
     * Get by year day.
     * 
     * @return array
     */
    public function getByYearDay()
    {
        return $this->byYearDay;
    }

    /**
     * Set by week no.
     * 
     * @param  array $byWeekNo
     * @return self
     */
    public function setByWeekNo(array $byWeekNo = null)
    {
        if ($byWeekNo === null) {
            $this->byWeekNo = array();
        } else {
            $values = array();
            
            foreach ($byWeekNo as $value) {
                if (!is_numeric($value)) {
                    throw new Exception\InvalidArgumentException(sprintf('ByWeekNo values must be integers, "%s" received', $value));
                } elseif ($value < -53) {
                    throw new Exception\InvalidArgumentException(sprintf('ByWeekNo value "%s" is lower than -53', $value));
                } elseif ($value > 53) {
                    throw new Exception\InvalidArgumentException(sprintf('ByWeekNo value "%s" is greater than 53', $value));
                } elseif ($value == 0) {
                    throw new Exception\InvalidArgumentException(sprintf('ByWeekNo value "%s" is 0', $value));
                }

                $values[] = (int) $value;
            }
            
            if (count($values) === 0) {
                throw new Exception\InvalidArgumentException('ByWeekNo values must contain at least one element');
            }
            
            $this->byWeekNo = $values;
        }
        
        return $this;
    }
    
    /**
     * Get by week no.
     * 
     * @return array
     */
    public function getByWeekNo()
    {
        return $this->byWeekNo;
    }
    
    /**
     * Set by month.
     * 
     * @param  array $byMonth
     * @return self
     */
    public function setByMonth(array $byMonth = null)
    {
        if ($byMonth === null) {
            $this->byMonth = array();
        } else {
            $values = array();
            
            foreach ($byMonth as $value) {
                if (!is_numeric($value)) {
                    throw new Exception\InvalidArgumentException(sprintf('ByMonth values must be integers, "%s" received', $value));
                } elseif ($value < 1) {
                    throw new Exception\InvalidArgumentException(sprintf('ByMonth value "%s" is lower than 1', $value));
                } elseif ($value > 12) {
                    throw new Exception\InvalidArgumentException(sprintf('ByMonth value "%s" is greater than 12', $value));
                }
                            
                $values[] = (int) $value;
            }
            
            if (count($values) === 0) {
                throw new Exception\InvalidArgumentException('ByMonth values must contain at least one element');
            }
            
            $this->byYearDay = $values;
        }
        
        return $this;
    }
    
    /**
     * Get by month.
     * 
     * @return array
     */
    public function getByMonth()
    {
        return $this->byMonth;
    }
    
    /**
     * Set by set pos.
     * 
     * @param  array $byYearDay
     * @return self
     */
    public function setBySetPos(array $bySetPos = null)
    {
        if ($bySetPos === null) {
            $this->bySetPos = array();
        } else {
            $values = array();
            
            foreach ($bySetPos as $value) {
                if (!is_numeric($value)) {
                    throw new Exception\InvalidArgumentException(sprintf('BySetPos values must be integers, "%s" received', $value));
                } elseif ($value < -366) {
                    throw new Exception\InvalidArgumentException(sprintf('BySetPos value "%s" is lower than -366', $value));
                } elseif ($value > 366) {
                    throw new Exception\InvalidArgumentException(sprintf('BySetPos value "%s" is greater than 366', $value));
                } elseif ($value == 0) {
                    throw new Exception\InvalidArgumentException(sprintf('BySetPos value "%s" is 0', $value));
                }
                            
                $values[] = (int) $value;
            }
            
            if (count($values) === 0) {
                throw new Exception\InvalidArgumentException('BySetPos values must contain at least one element');
            }
            
            $this->bySetPos = $values;
        }
        
        return $this;
    }
    
    /**
     * Get by set pos.
     * 
     * @return array
     */
    public function getBySetPos()
    {
        return $this->bySetPos;
    }
    
    /**
     * Get a recurrence iterator.
     * 
     * @param  mixed $dateTimeStart
     * @return RecurrenceIterator
     */
    public function getIterator($dateTimeStart)
    {
        return new RecurrenceIterator($this, $dateTimeStart);
    }
    
    /**
     * fromString(): defined by Value interface.
     * 
     * @see    Value::fromString()
     * @param  string $string
     * @return Value
     */
    public static function fromString($string)
    {
        $values = array();
        $parts  = explode(';', $string);
        
        foreach ($parts as $part) {
            $data = explode('=', $part, 2);
            
            if (!isset($data[1])) {
                return null;
            }
            
            $name  = strtoupper($data[0]);
            $value = $data[1];
            
            switch ($name) {
                case 'FREQ':
                case 'UNTIL':
                case 'COUNT':
                case 'INTERVAL':
                case 'WKST':
                    $values[$name] = $value;
                    break;

                case 'BYSECOND':
                case 'BYMINUTE':
                case 'BYHOUR':
                case 'BYDAY':
                case 'BYMONTHDAY':
                case 'BYYEARDAY':
                case 'BYWEEKNO':
                case 'BYMONTH':
                case 'BYSETPOS':
                    $values[$name] = explode(',', $value);
                    break;
                
                default:
                    return null;
            }
        }
        
        if (!isset($values['FREQ'])) {
            return null;
        }
        
        try {   
            $self = new self($values['FREQ']);
            
            foreach ($values as $name => $value) {
                switch ($name) {
                    case 'UNTIL':
                        $self->setUntil($value);
                        break;
                    
                    case 'COUNT':
                        $self->setCount($value);
                        break;
                    
                    case 'INTERVAL':
                        $self->setInterval($value);
                        break;
                    
                    case 'WKST':
                        $self->setWeekday($value);
                        break;
                    
                    case 'BYSECOND':
                        $self->setBySecond($value);
                        break;
                    
                    case 'BYMINUTE':
                        $self->setByMinute($value);
                        break;
                    
                    case 'BYHOUR':
                        $self->setByHour($value);
                        break;
                    
                    case 'BYDAY':
                        $self->setByDay($value);
                        break;
                    
                    case 'BYMONTHDAY':
                        $self->setByMonthDay($value);
                        break;
                    
                    case 'BYYEARDAY':
                        $self->setByYearDay($value);
                        break;
                    
                    case 'BYWEEKNO':
                        $self->setByWeekNo($value);
                        break;
                    
                    case 'BYMONTH':
                        $self->setByMonth($value);
                        break;
                    
                    case 'BYSETPOS':
                        $self->setBySetPos($value);
                        break;
                }
            }
        } catch (Exception $e) {
            return null;
        }
        
        return $value;
    }
}
