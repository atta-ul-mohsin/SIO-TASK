<?php

namespace App\Services;

use App\Models\TimeLog;
use DateTime;

class TimeLogService
{
    public static $SECONDS_IN_A_DAY = 86400;
    public static $SEC_IN_AN_HOUR = 3600;
    public static $SEC_IN_A_MINUTE = 60;

    /**
     * @param int $userId
     * @param int $timeInSec
     * @param string $date
     * @param int|null $excludeId
     * @return array|bool
     */
    public static function isValidTime(int $userId, int $timeInSec, string $date, ?int $excludeId): array|bool
    {
        $loggedTime = TimeLog::getLoggedTime($userId, $date, $excludeId);
        if (($loggedTime + $timeInSec) > self::$SECONDS_IN_A_DAY) {
            $timeData = self::secondsToTime(max((self::$SECONDS_IN_A_DAY - $loggedTime), 0));
            return ['error' => 'true', 'available_time' => sprintf(" %s hours %s minutes %s seconds", $timeData['hours'], $timeData['minutes'], $timeData['seconds'])];
        }
        return true;
    }

    /**
     * @param int $seconds
     * @return array
     */
    public static function secondsToTime(int $seconds): array
    {
        $timeData = array();
        $baseDate = new \DateTime('@0');
        $secndDate = new \DateTime("@$seconds");
        $timeData['days'] = $baseDate->diff($secndDate)->format('%a');
        $timeData['hours'] = $baseDate->diff($secndDate)->format('%h');
        $timeData['minutes'] = $baseDate->diff($secndDate)->format('%i');
        $timeData['seconds'] = $baseDate->diff($secndDate)->format('%s');
        return $timeData;
    }

    /**
     * @param array $timeData
     * @return int
     */
    public static function timeToSeconds(array $timeData): int
    {
        $totalSeconds = 0;

        if (!empty($timeData['hours'])) {
            $totalSeconds += ($timeData['hours'] * self::$SEC_IN_AN_HOUR);
        }

        if (!empty($timeData['minutes'])) {
            $totalSeconds += ($timeData['minutes'] * self::$SEC_IN_A_MINUTE);
        }

        if (!empty($timeData['seconds'])) {
            $totalSeconds += $timeData['seconds'];
        }

        return (int)$totalSeconds;
    }

    /**
     * @param string|null $reportType
     * @return array
     */
    public static function getReportData(?string $reportType, $userId): array
    {
        $to = date('Y-m-d');
        $from = '';

        switch ($reportType) {
            case 'weekly':
                $from = (new DateTime('7 days ago'))->format('Y-m-d');
                break;
            case "monthly":
                $from = (new DateTime('30 days ago'))->format('Y-m-d');
                break;
            case "yearly":
                $from = (new DateTime('365 days ago'))->format('Y-m-d');
                break;
            default:
                break;
        }

        $datesRange = array('to' => $to, 'from' => $from);
        $timeLogs = TimeLog::getLogs($userId, $datesRange);
        $graphData = array();

        foreach ($timeLogs as $timeLog) {
            if (!isset($graphData[$timeLog->log_date])) {
                $graphData[$timeLog->log_date] = 0;
            }
            $graphData[$timeLog->log_date] += $timeLog->time_spent;
        }

        return $graphData;
    }
}
