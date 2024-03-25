<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Notice extends Model
{
    use HasFactory, AsSource;
    protected $fillable = [
        'dashboard',
        'title',
        'subtitle',
        'url',
    ];
    public static $dashboard_names = [
        2 => 'Local Admin',
        3 => 'Student Portal',
        4 => 'Vendor Portal',
    ];
    public function getDashboardName()
    {
        return self::$dashboard_names[$this->dashboard];
    }
}
