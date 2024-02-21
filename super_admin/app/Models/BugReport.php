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

    public function toCleanModuleString() : string{
        $module = $this->module;

        $module = str_replace('_portal', ' Portal', $module);

        $module = explode('.', $module);

        $module[0] = ucfirst($module[0]);
        $module[1] = ucfirst($module[1]);
        
        return implode(" - ", $module);
    }

    public function scopeFilter($query, array $filters) {
        try {
            if(isset($filters['severity'])) {
                $query->where('severity', $filters['severity']);
            }
        } catch(\Exception $e) {
            Alert::error('There was an error processing the filter. Error Message: ' . $e->getMessage());
        }
    }
}
