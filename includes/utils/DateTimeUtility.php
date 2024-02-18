<?php
class DateTimeUtility {
    public const YEAR_LABEL = 'year ago';
    public const YEARS_LABEL = 'years ago';
    public const MONTH_LABEL = 'month ago';
    public const MONTHS_LABEL = 'months ago';
    public const DAY_LABEL = 'day ago';
    public const YESTERDAY_LABEL = 'Yesterday';
    public const DAYS_LABEL = 'days ago';
    public const HOUR_LABEL = 'hour ago';
    public const HOURSS_LABEL = 'hours ago';
    public const MINUTE_LABEL = 'minute ago';
    public const MINUTES_LABEL = 'minutes ago';
    public const SECOND_LABEL = 'Just Now';
    public const SECONDS_LABEL = 'seconds ago';
    
    public static function getDateTimeMessage($dateTime) {
        $dateStart = new DateTime($dateTime);
        $currentDateTime = new DateTime(date('Y-m-d H:i:s'));
        $interval = $currentDateTime->diff($dateStart);

        if ($interval->y >= 1) {
            return self::createDateTimeMessage($interval->y, self::YEARS_LABEL, self::YEAR_LABEL);
        } else if ($interval->m >= 1) {
            return self::createDateTimeMessage($interval->m, self::MONTHS_LABEL, self::MONTH_LABEL) . ' ' . self::getDayMessage($interval, false);
        } else if ($interval->d >= 1) {
            return self::getDayMessage($interval);
        } else if ($interval->h >=1) {
            return self::createDateTimeMessage($interval->h, self::HOURS_LABEL, self::HOUR_LABEL);
        } else if ($interval->i >= 1) {
            return self::createDateTimeMessage($interval->i, self::MINUTES_LABEL, self::MINUTE_LABEL);
        } else {
            return self::getSecondsMessage($interval);
        }
    }
    
    public static function getDayMessage($interval, $justDays = true) {
        if ($interval->d >= 1) {
            if ($justDays) {
                return $interval->d === 1 ? self::YESTERDAY_LABEL : $interval->d . ' ' . self::DAYS_LABEL;
            } else {
                return self::createDateTimeMessage($interval->d, self::DAYS_LABEL, self::DAY_LABEL);
            }
        }
    }
    
    public static function getSecondsMessage($interval) {
        if ($interval->s <= 30) {
            return self::SECOND_LABEL;
        } else {
            return $interval->s . ' ' . self::SECONDS_LABEL;
        }
    }

    public static function createDateTimeMessage($dateTime, $pluralLabel, $singularLabel) {
        $label = $dateTime === 1 ? $pluralLabel : $singularLabel;

        return $dateTime . ' ' . $label;   
    }
}
?>