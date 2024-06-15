<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class LoginAs extends Model
{
    use HasFactory, AsSource;

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
        $correctURLs = $this->getCorrectURLs();

        return match((int)$this->portal) {
            2 => $correctURLs['localadmin'],
            3 => $correctURLs['student'],
            4 => $correctURLs['vendor']
        };
    }

    private function getCorrectURLs() : array {
        $suffix = str_contains(url()->current(), '.promplanner.app') ? 'app' : 'net';

        return [
            'localadmin' => "app.promplanner.{$suffix}",
            'student' => "student.promplanner.{$suffix}",
            'vendor' => "vendor.promplanner.{$suffix}"
        ];
    }
}
