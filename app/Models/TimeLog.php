<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class TimeLog extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'time_spent',
        'log_date',
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'log_date' => 'datetime',
    ];


    public function getLogDateAttribute()
    {
        return Carbon::parse($this->attributes['log_date'])->format('d M Y');
    }

    /**
     * @param $userId
     * @param $logsDate
     * @param $excludeId
     * @return int
     */
    public static function getLoggedTime($userId = null, $logsDate = null, $excludeId = null):int
    {
        $time_logs = new self();

        if ($userId) {
            $time_logs = $time_logs->where(['user_id' => $userId]);
        }

        if (!empty($logsDate)) {
            $time_logs = $time_logs->where(['log_date' => $logsDate]);
        }

        if ($excludeId) {
            $time_logs = $time_logs->where('id', '<>', $excludeId);
        }

        return $time_logs->sum('time_spent');
    }

    /**
     * @param int|null $userId
     * @param array|null $dateRange
     * @param string $orderBy
     * @return Collection
     */
    public static function getLogs(?int $userId, ?array $dateRange, string $orderBy="log_date" ): Collection
    {
        $time_logs = new self();

        if ($userId) {
            $time_logs = $time_logs->where(['user_id' => $userId]);
        }

        if (!empty($dateRange['from']) && !empty($dateRange['to'])) {
            $time_logs = $time_logs->whereBetween('log_date', [$dateRange['from'], $dateRange['to']]);
        }
        $time_logs->orderBy($orderBy, 'asc');

        return $time_logs->get();
    }
}
