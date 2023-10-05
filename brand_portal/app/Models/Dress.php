<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Orchid\Screen\AsSource;
use Orchid\Support\Facades\Toast;

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
        $input = str_replace("\r\n", "\n", $input);
        $input = str_replace("\r", "\n", $input);

        return $input
            ? array_filter(array_map('trim', explode($delimiter, $input)), 'strlen')
            : [];
    }

    public function scopeFilter($query, array $filters)
    {
        try {
            // Sort Order
            if (isset($filters['sort'])) {
                $sortColumn = $filters['sort']; // Get the sort column from the filters
                if ($sortColumn[0] == '-') {
                    $sortDirection = 'desc';
                    $sortColumn = substr($sortColumn, 1);
                } else {
                    $sortDirection = 'asc';
                }
                if (in_array($sortColumn, Schema::getColumnListing('dresses'))) {
                    $query->orderBy($sortColumn, $sortDirection);
                }
            }

            if (isset($filters['filter'])) {
                $possibleFields = ['model_number', 'model_name', 'url'];
                foreach ($possibleFields as $field) {
                    if (isset($filters['filter'][$field])) {
                        $query->where($field, 'LIKE', "%{$filters['filter'][$field]}%");
                    }
                }
            }

            $query->select('dresses.*');

        } catch (Exception $e) {
            Toast::error('There was an error processing the scope filter. Error Message: ' . $e->getMessage());
        }
    }

    /**
     * Get the user that owns the dress.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendors::class, 'user_id', 'user_id');
    }
}
