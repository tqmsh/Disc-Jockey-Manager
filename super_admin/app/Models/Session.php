<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Session extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = [
        'user_id',
        'time',
        'role',
        'created_at',
        'updated_at'

    ];

    public static function averageDurationForSchools()
    {
        $schoolSessions = self::where('role', '2')->get();

        $averageDurationInSeconds = $schoolSessions->avg('time');

        $hours = floor($averageDurationInSeconds / 3600);
        $minutes = floor(($averageDurationInSeconds % 3600) / 60);
        $seconds = $averageDurationInSeconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

    }

    public static function averageDurationForStudents()
    {
        $studentSessions = self::where('role', '3')->get();

        $averageDurationInSeconds = $studentSessions->avg('time');

        $hours = floor($averageDurationInSeconds / 3600);
        $minutes = floor(($averageDurationInSeconds % 3600) / 60);
        $seconds = $averageDurationInSeconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

    }

    public static function averageDurationForVendors()
    {
        $vendorsSessions = self::where('role', '4')->get();

        $averageDurationInSeconds = $vendorsSessions->avg('time');

        $hours = floor($averageDurationInSeconds / 3600);
        $minutes = floor(($averageDurationInSeconds % 3600) / 60);
        $seconds = $averageDurationInSeconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

    }

    public static function averageDurationForBrands()
    {
        $brandsSessions = self::where('role', '4')->get();

        $averageDurationInSeconds = $brandsSessions->avg('time');

        $hours = floor($averageDurationInSeconds / 3600);
        $minutes = floor(($averageDurationInSeconds % 3600) / 60);
        $seconds = $averageDurationInSeconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

    }

}
