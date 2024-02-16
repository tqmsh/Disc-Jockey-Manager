<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class BugReport extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = [
        'id',
        'reporter_user_id',
        'reporter_role',
        'title',
        'description',
        'module',
        'severity',
        'created_at',
        'updated_at'
    ];

    public function toSeverityString() : string {
        return match($this->severity) {
            1 => "Critical",
            2 => "Moderate",
            3 => "Minor"
        };
    }
}
