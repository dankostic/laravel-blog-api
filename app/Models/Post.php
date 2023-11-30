<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'slug',
    ];

    /**
     * @var string
     */
    protected $sluggable = 'title';

    /**
     * @return void
     */
    public static function boot(): void
    {
        parent::boot();

        static::saving(function (self $post) {
            $post->slug = !empty($post->slug) ? $post->slug : Str::slug($post->{$post->sluggable});
        });
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
