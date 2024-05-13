<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Screen\AsSource;

class DisplayAds extends Model
{
    use HasFactory, AsSource;

    protected $fillable = [
        'route_uri',
        'ad_index',
        'campaign_id',
        'portal',
        'region_id',
        'gender',
        'square'
    ];

    public function campaign() : BelongsTo {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }

    public function region() : BelongsTo {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function portalToName() : string {
        return match($this->portal) {
            0 => 'Local Admin',
            1 => 'Student',
            2 => 'Vendor'
        };
    }
}
