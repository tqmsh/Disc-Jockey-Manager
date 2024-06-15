<?php

namespace App\Models;

use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;
use Orchid\Support\Facades\Alert;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vendors extends Model
{
    use HasFactory;
    use AsSource;

    use Filterable;


    protected $fillable = ['country', 'state_province', 'address', 'zip_postal', 'phonenumber', 'website', 'user_id', 'category_id', 'account_status', 'updated_at', 'created_at', 'email', 'city', 'company_name'];

    public function scopeFilter($query, array $filters){

        try{

            if(isset($filters['country'])){
                $query->where('country', 'like', '%' . request('country') . '%');
            }

            if(isset($filters['state_province'])){
                $query->where('state_province', 'like', '%' . request('state_province') . '%');
            }

            if(isset($filters['category_id'])){
                $query->where('category_id', request('category_id'));
            }

            if(isset($filters['name_filter']) && isset($filters['search_input_by'])){
                $query->where($filters['search_input_by'], 'like', '%' . request('name_filter') . '%');
            }

            $query->select('vendors.*');


        }catch(Exception $e){

            Alert::error('There was an error processing the filter. Error Message: ' . $e->getMessage());
        }
    }
}
