<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class LoginAs extends Model
{
    use HasFactory, AsSource;

    // Change the URLs here
    const WEBSITE_URLS = [
        'localadmin' => '127.0.0.1:8010',
        'student' => '127.0.0.1:8020',
        'vendor' => 'vendor.promplanner.app'
    ];

    protected $fillable = [
        'id',
        'la_key',
        'user_id',
        'portal',
        'updated_at',
        'created_at'
    ];

    public static function boot() {
        parent::boot();

        // Auto-fill LoginAs key on creation.
        static::creating(function(self $model) {
            $model->la_key = bin2hex(random_bytes(32));
        });
    }

    public function portalToTarget() : string {
        return match((int)$this->portal) {
            2 => self::WEBSITE_URLS['localadmin'],
            3 => self::WEBSITE_URLS['student'],
            4 => self::WEBSITE_URLS['vendor']
        };
    }
}
