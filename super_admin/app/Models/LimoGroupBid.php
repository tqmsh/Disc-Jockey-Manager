<?php

namespace App\Models;

use Orchid\Screen\AsSource;
use Orchid\Support\Facades\Alert;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LimoGroupBid extends Model
{
    use HasFactory;
    use AsSource;

    protected $fillable = [
        'id',
        'user_id',
        'limo_group_id',
        'region_id',
        'school_id',
        'package_id',
        'category_id',
        'school_name',
        'notes',
        'status',
        'company_name',
        'url',
        'contact_instructions',
        'created_at',
        'updated_at'
    ];

      public function scopeFilter($query, array $filters){

        try{

            if(isset($filters['category_id'])){
                $query ->where('category_id', $filters['category_id']);
            }

            $query->get();

        }catch(\Exception $e){

            Alert::error('There was an error processing the filter. Error Message: ' . $e->getMessage());
        }
    }

    public function limoGroup(){
        return $this->belongsTo(LimoGroup::class, 'limo_group_id', 'id');
    }
}
