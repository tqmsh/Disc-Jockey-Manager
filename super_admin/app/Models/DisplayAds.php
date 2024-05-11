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
        'category_id',
        'gender',
        'square'
    ];

    public function campaign() : BelongsTo {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }

    public function region() : BelongsTo {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function category() : BelongsTo {
        return $this->belongsTo(Categories::class, 'category_id');
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
            if(isset($filters['route_uri'])){
                $query ->where('route_uri', $filters['route_uri']);
            }

            if(isset($filters['portal'])){
                $query ->where('portal', $filters['portal']);
            }
            
            if(isset($filters['region_id'])){
                $query ->where('region_id', $filters['region_id']);
            }

            $query->select('display_ads.*');


        }catch(Exception $e){

            Alert::error('There was an error processing the filter. Error Message: ' . $e->getMessage());
        }
    }
}
