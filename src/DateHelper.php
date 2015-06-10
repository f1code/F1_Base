<?php

namespace F1\Base;

class DateHelper
{
    /**
     * Convert a date from the ASP.NET JSON serialization format
     *
     * @param string $date Input in asp.net json format (e.g., \/Date(3493403409)\/)
     * @return number Converted date (timestamp)
     */
    public static function convertFromAspnetDate($date)
    {
        return intval(substr($date, 6, 10));
    }


    /**
     * Convert a time range description (8/2/2015 morning) into a pair of datetime values describing the range.
     *
     * @param \DateTime $date
     * @param string $time_period a named range from the $ranges array.  If it is blank or does not match,
     *  we'll return any time during the day.
     * @param array $ranges array of ranges indexed by name.  E.g. array('morning' => array(8,12)).
     *  we'll use some defaults if not provided.
     * @return number[] pair of timestamps for start-end
     */
    public static function getTimeRange($date, $time_period, $ranges = null)
    {
        if(!$ranges) {
            $ranges = array(
                'morning' => array(8, 11),
                'afternoon' => array(12, 16),
                'evening' => array(17, 23)
            );
        }
        $selected_range = ($time_period && isset($ranges[$time_period])) ? $ranges[$time_period] : array(0, 23);

        $start = clone $date;
        $end = clone $date;

        $start->setTime($selected_range[0], 0);
        $end->setTime($selected_range[1] - 1, 59);
        return array($start->getTimestamp(), $end->getTimestamp());
    }
}