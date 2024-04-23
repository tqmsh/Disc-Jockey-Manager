<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Screen\AsSource;
use Orchid\Support\Facades\Alert;

class DisplayAds extends Model
{
    use HasFactory, AsSource;

    protected $fillable = [
        'route_uri',
        'ad_index',
        'campaign_id',
        'portal',
        'region_id',
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

    public function scopeFilter($query, array $filters){

        try{
            if(isset($filters['route'])){
                $query ->where('route_uri', $filters['route']);
            }

            if(isset($filters['portal'])){
                $query ->where('portal', $filters['portal']);
            }
            
            if(isset($filters['region'])){
                $query ->where('region_id', $filters['region']);
            }

            $query->select('display_ads.*');


        }catch(Exception $e){

            Alert::error('There was an error processing the filter. Error Message: ' . $e->getMessage());
        }
    }
}
