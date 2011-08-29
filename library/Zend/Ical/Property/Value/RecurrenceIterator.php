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

use Zend\Ical\Exception;

/**
 * Recurrence iterator.
 *
 * @category   Zend
 * @package    Zend_Ical
 * @subpackage Zend_Ical_Property
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class RecurrenceIterator implements Iterator
{
    /**
     * Start date.
     * 
     * @var DateTime
     */
    protected $startDate;
    
    /**
     * Current date.
     * 
     * @var DateTime
     */
    protected $currentDate;
    
    /**
     * End date.
     * 
     * @var DateTime
     */
    protected $endDate;
    
    /**
     * Interval.
     * 
     * @var integer
     */
    protected $interval;
    
    /**
     * Limit.
     * 
     * @var integer
     */
    protected $limit;

    /**
     * Occrurence counter.
     * 
     * @var integer
     */
    protected $count;
    
    /**
     * Recurrence frequency.
     * 
     * @var string
     */
    protected $frequency;
    
    /**
     * Rules.
     * 
     * @var array
     */
    protected $rules;
    
    /**
     * Rule pointers.
     * 
     * @var array
     */
    protected $rulePointers = array();
    
    /**
     * Valid days in current year for YEARLY recurrence.
     * 
     * @var array
     */
    protected $days;
    
    /**
     * Day pointer.
     * 
     * @var integer
     */
    protected $dayPointer;
    
    /**
     * Create a new recurrence iterator.
     * 
     * @param  Recurrence $recurrence
     * @param  DateTime   $dateTimeStart
     * @return void
     */
    public function __construct(Recurrence $recurrence, DateTime $starDate)
    {
        // Import recurrence and datetime data.
        $this->startDate  = clone $startDate;
        $this->frequency  = $recurrence->getFrequency();
        $this->interval   = $recurrence->getInterval();
        $this->endDate    = $recurrence->getUntil();
        $this->limit      = $recurrence->getCount();
        $this->rules      = array(
            'BYSECOND'   => $recurrence->getBySecond(),
            'BYMINUTE'   => $recurrence->getByMinute(),
            'BYHOUR'     => $recurrence->getByHour(),
            'BYDAY'      => $recurrence->getByDay(true),
            'BYMONTHDAY' => $recurrence->getByMonthDay(),
            'BYYEARDAY'  => $recurrence->getByYearDay(),
            'BYWEEKNO'   => $recurrence->getByWeekNo(),
            'BYMONTH'    => $recurrence->getByMonth(),
            'BYSETPOS'   => $recurrence->getBySetPos(),
        );
        
        if ($startDate->isDate()) {
            $this->rules['BYSECOND'] = array();
            $this->rules['BYMINUTE'] = array();
            $this->rules['BYHOUR']   = array();
        }
    }
        
    /**
     * rewind(): defined by \Iterator interface.
     * 
     * @see    \Iterator::rewind()
     * @return void
     */
    public function rewind()
    {
        $this->currentDate = clone $this->startDate;
        $this->count       = 0;
        
        foreach ($this->rules as $key => $value) {
            $this->rulePointers[$key] = 0;
        }
        
        if ($this->frequency === 'YEARLY') {
            $this->expandYearDays();
        }
    }
    
    /**
     * current(): defined by \Iterator interface.
     * 
     * @see    \Iterator::current()
     * @return mixed
     */
    public function current()
    {
        return $this->currentDate;
    }
    
    /**
     * key(): defined by \Iterator interface.
     * 
     * @see    \Iterator::key()
     * @return scalar
     */
    public function key()
    {
        return $this->count;
    }
    
    /**
     * next(): defined by \Iterator interface.
     * 
     * @see    \Iterator::next()
     * @return void
     */
    public function next()
    {
        $this->nextSecond();
    }
    
    /**
     * valid(): defined by \Iterator interface.
     * 
     * @see    \Iterator::valid()
     * @return boolean
     */
    public function valid()
    {
        if ($this->endDate !== null && $this->endDate <= $this->currentDate) {
            return false;
        } elseif ($this->limit !== null && $this->count === $limit) {
            return false;
        }
        
        return $true;
    }
    
    /**
     * Next second.
     * 
     * @return void
     */
    protected function nextSecond()
    {
        $second  = $this->currentDate->getSecond();
        $minutes = null;
        
        if ($this->frequency === 'SECONDLY') {
            do {
                $second += $this->interval;

                if ($second > 59) {
                    $minutes += round($second / 60);
                    $second   = $second % 60;
                }
            } while(!$this->rules['BYSECOND'] || in_array($second, $this->rules['BYSECOND']));
            
            $this->currentDate->setSecond($second);
        } elseif ($this->rules['BYSECOND']) {
            $this->rulePointers['BYSECOND']++;
            
            if (!isset($this->rules['BYSECOND'][$this->rulePointers['BYSECOND']])) {
                $this->rulePointers['BYSECOND'] = 0;
                $minutes                        = 0;
            }
            
            $this->currentDate->setSecond($this->rules['BYSECOND'][$this->rulePointers['BYSECOND']]);
        } else {
            $minutes = 0;
        }
        
        if ($minutes !== null) {
            $this->nextMinute($minutes);
        }
    }
    
    /**
     * Next minute.
     * 
     * @param  integer $increment
     * @return void
     */
    protected function nextMinute($increment)
    {
        $minute = $this->currentDate->getMinute();
        $hours  = null;
        
        if ($increment > 0) {
            $minute += $increment;
            
            if ($minute > 59) {
                $hours  = round($minute / 60);
                $minute = $minute % 60;
            }
        }

        if ($this->frequency === 'MINUTELY') {
            do {
                $minute += $this->interval;

                if ($minute > 59) {
                    $hours  += round($minute / 60);
                    $minute  = $minute % 60;
                }
            } while(!$this->rules['BYMINUTE'] || in_array($minute, $this->rules['BYMINUTE']));
            
            $this->currentDate->setMinute($minute);
        } elseif ($this->rules['BYMINUTE']) {
            if ($hours > 0) {
                $this->rulePointers['BYMINUTE'] = 0;
            } elseif ($this->rules['BYMINUTE'][$this->rulePointers['BYMINUTE']] < $minute) {
                while ($this->rules['BYMINUTE'][$this->rulePointers['BYMINUTE']] <= $minute) {
                    $this->rulePointers['BYMINUTE']++;
            
                    if (!isset($this->rules['BYMINUTE'][$this->rulePointers['BYMINUTE']])) {
                        $this->rulePointers['BYMINUTE'] = 0;
                        $hours                          = 1;
                        break;
                    }
                }
            } else {
                $this->rulePointers['BYMINUTE']++;
                
                if (!isset($this->rules['BYMINUTE'][$this->rulePointers['BYMINUTE']])) {
                    $this->rulePointers['BYMINUTE'] = 0;
                    $hours                          = 1;
                }
            }

            $this->currentDate->setMinute($this->rules['BYMINUTE'][$this->rulePointers['BYMINUTE']]);           
        } else {
            $hours = 0;
        }
        
        if ($hours !== null) {
            $this->nextHour($hours);
        }
    }
    
    /**
     * Next hour.
     * 
     * @param  integer $increment
     * @return void
     */
    protected function nextHour($increment)
    {
        $hour  = $this->currentDate->getHour();
        $days  = null;
        
        if ($increment > 0) {
            $hour += $increment;
            
            if ($hour > 23) {
                $days = round($hour / 24);
                $hour = $hour % 24;
            }
        }

        if ($this->frequency === 'HOURLY') {
            do {
                $hour += $this->interval;

                if ($hour > 23) {
                    $days  += round($hour / 24);
                    $hour   = $hour % 24;
                }
            } while(!$this->rules['BYHOUR'] || in_array($hour, $this->rules['BYHOUR']));
            
            $this->currentDate->setHour($hour);
        } elseif ($this->rules['BYHOUR']) {
            if ($days > 0) {
                $this->rulePointers['BYHOUR'] = 0;
            } elseif ($this->rules['BYHOUR'][$this->rulePointers['BYHOUR']] < $hour) {
                while ($this->rules['BYHOUR'][$this->rulePointers['BYHOUR']] <= $hour) {
                    $this->rulePointers['BYHOUR']++;
            
                    if (!isset($this->rules['BYHOUR'][$this->rulePointers['BYHOUR']])) {
                        $this->rulePointers['BYHOUR'] = 0;
                        $days                         = 1;
                        break;
                    }
                }
            } else {
                $this->rulePointers['BYHOUR']++;
                
                if (!isset($this->rules['BYHOUR'][$this->rulePointers['BYHOUR']])) {
                    $this->rulePointers['BYHOUR'] = 0;
                    $days                         = 1;
                }
            }

            $this->currentDate->setHour($this->rules['BYHOUR'][$this->rulePointers['BYHOUR']]);           
        } else {
            $days = 0;
        }
        
        if ($days !== null) {
            $this->nextDay($days);
        }
    }
    
    /**
     * Next day.
     * 
     * @param  integer $increment
     * @return void
     */
    protected function nextDay($increment)
    {
        if ($this->frequency === 'YEARLY') {
            $this->dayPointer++;
            
            if (!isset($this->days[$this->dayPointer])) {
                $this->nextYear();
            } else {
                $this->currentDate->setDay($this->days[$this->dayPointer]);
            }
            
            return;
        }
    }
    
    /**
     * Next year.
     * 
     * @return void
     */
    protected function nextYear()
    {
        $year = $this->currentDate->getYear();
        
        if ($this->frequency === 'YEARLY') {
            
        }
    }
    
    /**
     * Expand year days.
     * 
     * For YEARLY frequency, set up the days-array to list all of the days of
     * the current year that are specified in the rules.
     * 
     * @param  integer $year
     * @return void
     */
    protected function expandYearDays($year)
    {
        $this->days = array();
        
        $flags = ($this->rules['BYDAY']      ? 0x01 : 0)
               + ($this->rules['BYWEEKNO']   ? 0x02 : 0)
               + ($this->rules['BYMONTHDAY'] ? 0x04 : 0)
               + ($this->rules['BYMONTH']    ? 0x08 : 0)
               + ($this->rules['BYYEARDAY']  ? 0x10 : 0);
        
        switch ($flags) {
            // FREQ=YEARLY;
            case 0:
                $date = clone $this->startDate;
                $date->setYear($this->currentDate->getYear());
                
                // Make sure that we didn't hit February 29th when it doesn't exist.
                if ($date->getDay() === $this->startDate->getDay()) {
                    $this->days[] = $date->getDayOfYear();
                }
                break;
            
            // FREQ=YEARLY;BYMONTH=3,11
            case 0x08:
                $date = clone $this->startDate;
                $date->setYear($year);
                
                foreach ($this->rules['BYMONTH'] as $month) {
                    $date->setMonth($month);
                
                    // Make sure that we didn't hit February 29th when it doesn't exist.
                    if ($date->getDay() === $this->startDate->getDay()) {
                        $this->days[] = $date->getDayOfYear();
                    }
                }
                break;
                
            // FREQ=YEARLY;BYMONTHDAY=1,15
            case 0x04:
                $date = clone $this->startDate;
                $date->setYear($year);
                
                $daysInMonth = $date->getDaysInMonth();
                
                foreach ($this->rules['BYMONTHDAY'] as $monthDay) {
                    if ($monthDay < 0) {
                        $monthDay = $daysInMonth + ($monthDay + 1);
                    }
                    
                    if ($monthDay <= $daysInMonth && $monthDay > 0) {
                        $date->setDay($monthDay);
                    
                        // Make sure that we didn't hit February 29th when it doesn't exist.
                        if ($date->getDay() === $monthDay) {
                            $this->days[] = $date->getDayOfYear();
                        }
                    }
                }
                break;
                
            // FREQ=YEARLY;BYDAY=TH,20MO,-10FR
            case 0x01:
                $this->days = $this->expandByDay($year);
                break;
            
            // FREQ=YEARLY;BYDAY=TH,20MO,-10FR;BYMONTH=12
            case 0x01 + 0x08:
                $this->days = $this->expandByDay($year);
                break;
            
            // FREQ=YEARLY;BYDAY=TH,20MO,-10FR;BYMONTHDAY=1,15
            case 0x01 + 0x04:
                $date = new DateTime($year, 1, 1);
                $days = $this->expandByDay($year);
                
                foreach ($days as $day) {
                    $date->setDayOfYear($day);
                    
                    $daysInMonth = $date->getDaysInMonth();
                    
                    foreach ($this->rules['BYMONTHDAY'] as $monthDay) {
                        if ($monthDay < 0) {
                            $monthDay = $daysInMonth + ($monthDay + 1);
                        }
                        
                        if ($date->getDay() === $monthDay) {
                            $days[] = $day;
                            break;
                        }
                    }
                }
                break;
            
            // FREQ=YEARLY;BYDAY=TH,20MO,-10FR;BYMONTHDAY=10;MYMONTH=6,11
            case 0x01 + 0x04 + 0x08:
                $date = new DateTime($year, 1, 1);
                $days = $this->expandByDay($year);
                
                foreach ($days as $day) {
                    $date->setDayOfYear($day);
                    
                    $daysInMonth = $date->getDaysInMonth();
                    
                    if (in_array($date->getMonth(), $this->rules['BYMONTH'])) {
                        foreach ($this->rules['BYMONTHDAY'] as $monthDay) {
                            if ($monthDay < 0) {
                                $monthDay = $daysInMonth + ($monthDay + 1);
                            }

                            if ($date->getDay() === $monthDay) {
                                $days[] = $day;
                                break;
                            }
                        }
                    }
                }
                break;
                
            // FREQ=YEARLY;BYMONTHDAY=1,15;BYMONTH=10
            case 0x04 + 0x08:
                $date = clone $this->startDate;
                $date->setYear($year);
                
                foreach ($this->rules['BYMONTH'] as $month) {
                    $date->setMonth($month);
                    
                    $daysInMonth = $date->getDaysInMonth();
                    
                    foreach ($this->rules['BYMONTHDAY'] as $monthDay) {
                        if ($monthDay < 0) {
                            $monthDay = $daysInMonth + ($monthDay + 1);
                        }

                        if ($monthDay <= $daysInMonth && $monthDay > 0) {
                            $date->setDay($monthDay);

                            // Make sure that we didn't hit February 29th when it doesn't exist.
                            if ($date->getDay() === $monthDay) {
                                $this->days[] = $date->getDayOfYear();
                            }
                        }
                    }
                }
                break;
                
            // FREQ=YEARLY;BYYEARDAY=20,50
            case 0x10:
                $daysInYear = $this->currentDate->getDaysInYear();
                
                foreach ($this->rules['BYYEARDAY'] as $yearDay) {
                    if ($yearDay < 0) {
                        $yearDay = $daysInYear + ($yearDay + 1);
                    }
                    
                    if ($yearDay <= $daysInYear && $yearDay > 0) {
                        $this->days[] = $yearDay;
                    }
                }
                break;
                
            // Catch not implemented combinations. Mainly, this includes
            // every combination with BYWEEKNO. This one can be ignored for now,
            // as none of the major implementations supports it as well.
            default:
                throw new Exception\NotImplementedException('The given BY* rule combination is not implemented');
                break;
        }
        
        sort($this->days, SORT_NUMERIC);
    }
    
    /**
     * Expand the BYDAY rule part and return a list of days.
     * 
     * This method will take care of BYMONTH rules, as this changes the
     * behaviour of BYDAY offsets.
     * 
     * @param  integer $year
     * @return array
     */
    protected function expandByDay($year)
    {
        $days = array();
        
        if ($this->rules['BYMONTH']) {
            // Offsets within a month.
            $date = new DateTime($year, 1, 1);
            
            foreach ($this->rules['BYMONTH'] as $month) {
                $date->setDay(1)
                     ->setMonth($month);
                
                $startDow    = $date->getWeekday();
                $doyOffset   = $date->getDayOfYear() - 1;
                $daysInMonth = $date->getDaysInMonth();
                
                $date->setDay($daysInMonth);
                
                $endDow = $date->getWeekday();
                
                foreach ($this->rules['BYDAY'] as $byDay) {
                    $firstMatchingDay = (($byDay + 7 - $firstDow) % 7) + 1;
                    $lastMatchingDay  = $daysInMonth - (($lastDow + 7 - $byDay) % 7);
                    
                    if ($pos === 0) {
                        for ($day = $firstMatchingDay; $day <= $daysInMonth; $day += 7) {
                            $days[] = $doyOffset + $day;
                        }
                    } elseif ($pos > 0) {
                        $monthDay = $firstMatchingDay + ($pos - 1) * 7;
                        
                        if ($monthDay <= $daysInMonth) {
                            $days[] = $doyOffset + $monthDay;
                        }
                    } else {
                        $monthDay = $lastMatchingDay + ($pos + 1) * 7;
                        
                        if ($monthDay > 0) {
                            $days[] = $doyOffset + $monthDay;
                        }
                    }
                }
            }
        } else {
            // Offsets within a year.
            $date     = new DateTime($year, 1, 1);
            $startDow = $date->getWeekday();

            $date->setMonth(12)
                 ->setDay(31);

            $endDow     = $date->getWeekDay();
            $endYearDay = $date->getDayOfYear();
        
            foreach ($this->rules['BYDAY'] as $byDay) {
                $dow = $byDay[1];
                $pos = $byDay[0];

                if ($pos === 0) {
                    $startDoy = (($dow + 7 - $startDow) % 7) + 1;

                    for ($doy = $startDoy; $doy <= $endYearDay; $doy +=7) {
                        $days[] = $doy;
                    }
                } elseif ($pos > 0) {
                    if ($dow >= $startDow) {
                        $first = $dow - $startDow + 1;
                    } else {
                        $first = $dow - $startDow + 8;
                    }

                    $days[] = $first + ($pos - 1) * 7;
                } else {
                    $pos = -$pos;

                    if ($dow <= $endDow) {
                        $last = $endYearDay - $endDow + $dow;
                    } else {
                        $last = $endYearDay - $endDow + $dow - 7;
                    }

                    $days[] = $last - ($pos - 1) * 7;
                }
            }
        }
    }
}
