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
class RecurrenceIterator
{
    /**
     * BY* rule mapping.
     * 
     * @var array
     */
    protected static $byRules = array(
        'NOCONTRACTION' => -1,
        'BYSECOND'      => 0,
        'BYMINUTE'      => 1,
        'BYHOUR'        => 2,
        'BYDAY'         => 3,
        'BYMONTHDAY'    => 4,
        'BYYEARDAY'     => 5,
        'BYWEEKNO'      => 6,
        'BYMONTH'       => 7,
        'BYSETPOS'      => 8,
    );
    
    /**
     * Expand table.
     * 
     * @var array
     */
    protected static $expandTable = array(
        'UNKNOWN'  => 0,
        'CONTRACT' => 1,
        'EXPAND'   => 2,
        'ILLEGAL'  => 3,
    );
    
    /**
     * Expand map.
     * 
     * @var array
     */
    protected static $expandMap = array(
        'SECONDLY' => array(1, 1, 1, 1, 1, 1, 1, 1),
        'MINUTELY' => array(2, 1, 1, 1, 1, 1, 1, 1),
        'HOURLY'   => array(2, 2, 1, 1, 1, 1, 1, 1),
        'DAILY'    => array(2, 2, 2, 1, 1, 1, 1, 1),
        'WEEKLY'   => array(2, 2, 2, 2, 3, 3, 1, 1),
        'MONTHLY'  => array(2, 2, 2, 2, 2, 3, 3, 1),
        'YEARLY'   => array(2, 2, 2, 2, 2, 2, 2, 2),
        'NO'       => array(0, 0, 0, 0, 0, 0, 0, 0),
    );
    
    /**
     * Recurrence instance.
     * 
     * @var Recurrence
     */
    protected $recurrence;
    
    /**
     * Last.
     * 
     * @var Date
     */
    protected $endDate;
    
    /**
     * Date time start.
     * 
     * @var Date
     */
    protected $startDate;
    
    /**
     * Days index.
     * 
     * @var integer
     */
    protected $daysIndex;
    
    /**
     * Occutrence number.
     * 
     * @var integer
     */
    protected $occurenceNo;
    
    /**
     * Recurrence frequency.
     * 
     * @var string
     */
    protected $frequency;
    
    /**
     * BY* rules.
     * 
     * @var array
     */
    protected $rules;
    
    /**
     * BY* rules which had data.
     * 
     * @var array
     */
    protected $rulesHadData;
    
    /**
     * Create a new recurrence iterator.
     * 
     * @param  Recurrence $recurrence
     * @param  Date       $dateTimeStart
     * @return void
     */
    public function __construct(Recurrence $recurrence, Date $starDate)
    {
        if (!$startDate instanceof Date) {
            throw new Exception\InvalidArgumentException('DateTime start must be an instance of Date');
        }
        
        $this->recurrence    = $recurrence;
        $this->startDate     = $startDate;
        $this->endDate       = clone $startDate;
        $this->daysIndex     = 0;
        $this->occurenceNo   = 0;
        $this->frequency     = $recurrence->getFrequency();
        $this->rules         = array(
            'BYSECOND'   => $recurrence->getBySecond(),
            'BYMINUTE'   => $recurrence->getByMinute(),
            'BYHOUR'     => $recurrence->getByHour(),
            'BYDAY'      => $recurrence->getByDay(),
            'BYMONTHDAY' => $recurrence->getByMonthDay(),
            'BYYEARDAY'  => $recurrence->getByYearDay(),
            'BYWEEKNO'   => $recurrence->getByWeekNo(),
            'BYMONTH'    => $recurrence->getByMonth(),
            'BYSETPOS'   => $recurrence->getBySetPos(),
        );
        
        // Store which rules had date in them when the iterator was created. We
        // can't use the actual arrays, because the empty ones will be filled
        // with default values later in this method.
        foreach ($this->rules as $key => $value) {
            $this->rulesHadData[$key] = (count($value) > 0);
        }
        
        // Rewrite some of the rules and set up defaults to make later
        // processing easier. Primarily, it involves copying an element from
        // the start time into the corresponding BY* array when the BY* array
        // is empty.
        $this->setupDefaults('BYSECOND', 'SECONDLY', $this->startDate->getSeconds());
        $this->setupDefaults('BYMINUTE', 'MINUTELY', $this->startDate->getMinute());
        $this->setupDefaults('BYHOUR', 'HOURLY', $this->startDate->getHour());
        $this->setupDefaults('BYMONTHDAY', 'DAILY', $this->startDate->getDay());
        $this->setupDefaults('BYMONTH', 'MONTHLY', $this->startDate->getMonth());
        
        if ($this->frequency === 'WEEKLY') {
            if (count($this->rules['BYDAY']) === 0) {
                // Weekly recurrences with no BYDAY data should occur on the
                // same day of the week as the start time.
                $this->rules['BYDAY'][] = $this->dateTimeStart->getWeekday();
            } else {
                // If there is BYDAY data, then we need to move the initial time
                // to the start of the BYDAY data. That is if the start time is
                // on a wednesday, and the rule has BYDAY=MO,WE,FR, move the
                // initial time back to monday. Otherwise, jumping to the next
                // week (jumping 7 days ahead) will skip over some occurences in
                // the second week.
                $dow = $this->rules['BYDAY'][0] - $this->dateTimeStart->getWeekday();
                
                if (($this->last->getWeekDay() < $byDay[0] && $dow >= 0) || $dow < 0) {
                    // Initial time is after first day of BYDAY data
                    $this->endDate->addDays($dow);
                }
            }
        } elseif ($this->frequency === 'YEARLY') {
            // For yearly reccurences, begin by setting up the year days array.
            // The YEARLY rules work by expanding one year at a time.
            while (true) {
                
            }
        } elseif ($this->frequency === 'MONTHLY' && $this->rulesHadData['BYDAY']) {
            
        } elseif ($this->rulesHadData['BYMONTHDAY']) {
            
        }
    }
    
    /**
     * Setup default values.
     * 
     * @param string  $rule
     * @param string  $frequency
     * @param integer $value 
     */
    protected function setupDefaults($rule, $frequency, $value)
    {
        if ($this->rules[$rule] === null && self::$expandMap[$frequency][self::$byRules[$rule]] !== self::$expandTable['CONTRACT']) {
            $this->rules[$rule] = array($value);
        }
        
        if ($this->frequency !== $frequency && self::$expandMap[$frequency][self::$byRules[$rule]] !== self::$expandTable['CONTRACT']) {
            switch ($rule) {
                case 'BYSECOND':
                    $this->endDate->setSecond($this->rules[$rule][0]);
                    break;

                case 'BYMINUTE':
                    $this->endDate->setMinute($this->rules[$rule][0]);
                    break;

                case 'BYHOUR':
                    $this->endDate->setHour($this->rules[$rule][0]);
                    break;

                case 'BYMONTHDAY':
                    $this->endDate->setDay($this->rules[$rule][0]);
                    break;

                case 'BYMONTH':
                    $this->endDate->setMonth($this->rules[$rule][0]);
                    break;
            }
        }
    }
    
    /**
     * Expand year days.
     * 
     * @param  integer $year
     * @return void
     */
    protected function expandYearDays($year)
    {
        $date = new DateTime(0, 0, 0);
        
        $flags = ($this->rulesHadData['BYDAY'] ? 1 << self::$byRules['BYDAY'] : 0)
               + ($this->rulesHadData['BYWEEKNO'] ? 1 << self::$byRules['BYWEEKNO'] : 0)
               + ($this->rulesHadData['BYMONTHDAY'] ? 1 << self::$byRules['BYMONTHDAY'] : 0)
               + ($this->rulesHadData['BYWBYMONTHEKNO'] ? 1 << self::$byRules['BYMONTH'] : 0)
               + ($this->rulesHadData['BYYEARDAY'] ? 1 << self::$byRules['BYYEARDAY'] : 0);
        
        // BYWEEKNO together with BYMONTH may conflict, in this case BYMONTH wins.
        if (($flags & 1 << self::$byRules['BYMONTH']) && ($flags & 1 << self::$byRules['BYWEEKNO'])) {
            $date->setYear($year);
            
            // Calculate valid week numbers.
            foreach ($this->rules['BYMONTH'] as $month) {
                $date->setMonth($month);
                $date->setDay(1);
                $firstWeek = $date->getWeekNo();
                
                $date->setDay($date->getDaysInMonth());
                $lastWeek  = $date->getWeekNo;
                
                for ($week = $firstWeek; $week < $lastWeek; $week++) {
                    $validWeeks[$week] = true;
                }
            }
            
            // Check valid weeks.
            $valid = true;
            
            foreach ($this->rules['BYWEEKNO'] as $weekNo) {
                if (!isset($validWeeks[$weekNo])) {
                    $valid = false;
                    break;
                }
            }
            
            if ($valid) {
                $flags -= 1 << self::$byRules['BYMONTH'];
            } else {
                $flags -= 1 << self::$byRules['BYWEEKNO'];
            }
        }
    }
}
