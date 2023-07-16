<?php

namespace App\Models;

use Orchid\Screen\AsSource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vendors extends Model
{
    use HasFactory;
    use AsSource;
    
    protected $fillable = ['country', 'state_province', 'address', 'zip_postal', 'phonenumber', 'website', 'user_id', 'category_id', 'account_status', 'updated_at', 'created_at', 'email', 'city', 'company_name'];

    public function category(){
        return $this->belongsTo(Categories::class, 'category_id', 'id');
    }
}
