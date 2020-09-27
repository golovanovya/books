<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Book model
 *
 * @property string $title
 * @property string $image
 * @property string $isbn
 * @property string $description
 */
class Book extends Model
{
    use HasFactory;

    protected $appends = ['imageUrl'];

    protected $fillable = [
        'title',
        'image',
        'isbn',
        'description',
    ];

    public $timestamps = false;

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            'title' => 'string|max:190',
            'isbn' => 'string',
            'description' => 'string|nullable',
            'image' => 'string|nullable',
        ];
    }

    /**
     * Get cover ulr
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? Storage::url($this->image) : null;
    }
}
