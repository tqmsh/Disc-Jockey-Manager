<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Orchid\Support\Facades\Alert;

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
        'status',
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

    public function toStatusString() : string {
        return match($this->status) {
            0 => 'New',
            1 => 'Under Review',
            2 => 'Fixed'
        };
    }

    public function toCleanModuleString() : string{
        $moduleString = '';
        $module = $this->module;

        $module = explode('.', $module);
        $moduleString = ucfirst($module[1]);
        
        if(str_contains($moduleString, '-')) {
            $seperatedModuleString = explode('-', $moduleString);
            $moduleString = $seperatedModuleString[0] . ' ' . ucfirst($seperatedModuleString[1]);
        }

        return $moduleString;
    }

    public function scopeFilter($query, array $filters) {
        try {
            if(isset($filters['severity'])) {
                $query->where('severity', $filters['severity']);
            }

            if(isset($filters['status'])) {
                $query->where('status', $filters['status']);
            }
        } catch(\Exception $e) {
            Alert::error('There was an error processing the filter. Error Message: ' . $e->getMessage());
        }
    }
}