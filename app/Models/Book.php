<?php

namespace App\Models;

use Database\Factories\BookFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Book model
 *
 * @property string $isbn
 * @property string $title
 * @property string $description
 * @property string $cover
 * @property string $storage
 * @property \DateTime $created_at
 * @property \DateTime $updated_at
 */
class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'isbn',
        'title',
        'cover',
        'description',
        'storage',
    ];

    protected $primaryKey = 'isbn';

    protected $keyType = 'string';

    protected $appends = ['imageUrl'];

    protected $hidden = ['storage'];

    public $incrementing = false;

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            'isbn' => 'string|max:255',
            'title' => 'string|max:190',
            'description' => 'nullable|string',
            'cover' => 'nullable|string|max:255',
            'storage' => 'string|max:255',
        ];
    }

    /**
     * Get cover ulr
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        return $this->cover ? url(Storage::disk($this->storage)->url($this->cover)) : null;
    }
}
