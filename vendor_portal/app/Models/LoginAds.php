<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Support\Facades\Alert;

class LoginAds extends Model
{
    use HasFactory;

    protected $fillable = [
        'portal',
        'campaign_id',
        'title',
        'subtitle',
        'button_title',
        'created_at',
        'updated_at'
    ];

    public function campaign() : BelongsTo {
        return $this->belongsTo(Campaign::class);
    }

    public function scopeFilter($query, array $filters){
        try {
            if(isset($filters['portal'])) {
                $query->where('portal', $filters['portal']);
            }
        } catch(\Exception $e) {
            Alert::error('There was an error trying to filter for login ads. Error message: ' . $e->getMessage());
        }
    }

    public function portalToName() : ?string {
        return match((int)$this->portal) {
            2 => 'Local Admin',
            3 => 'Student',
            4 => 'Vendor',
            default => null
        };
    }
}
