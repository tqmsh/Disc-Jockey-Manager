<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Dress extends Model
{
    use HasFactory;
    use AsSource;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dresses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'model_number',
        'brand',
        'model_name',
        'description',
        'colours',
        'sizes',
        'images',
        'url',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'colours' => 'array',
        'sizes' => 'array',
        'images' => 'array',
    ];

    public static function splitAndTrimNonEmpty($input, $delimiter): array
    {
        return $input
            ? array_filter(array_map('trim', explode($delimiter, $input)), 'strlen')
            : [];
    }
}
