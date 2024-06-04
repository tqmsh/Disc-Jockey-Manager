<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class VideoTutorial extends Model
{
    use HasFactory, AsSource;

    protected $fillable = [
        'id',
        'route_name',
        'url',
        'portal',
        'created_at',
        'updated_at'
    ];

    public static function portalToPrefix(int $portal) : ?string {
        return match($portal) {
            0 => 'localadmin_',
            1 => 'student_',
            2 => 'vendor_',
            default => null
        };
    }
}
