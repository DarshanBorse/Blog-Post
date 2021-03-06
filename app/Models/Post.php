<?php

namespace App\Models;

use App\Scopes\DeletedAdminScope;
use App\Trait\Taggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes, Taggable;

    protected $fillable = [
        'title',
        'content',
        'user_id',
    ];

    public function comment()
    {
        return $this->morphMany(Comment::class, 'commentable')->latest();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeLatest(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    public function scopeMostCommented(Builder $query)
    {
        return $query->withCount('comment')->orderBy('comment_count', 'desc');
    }

    public function scopeLatestWithRelation(Builder $query)
    {
        return $query->latest()->withCount('comment')->with('user')->with('tags');
    }

    

    public function images()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public static function boot()
    {
        static::addGlobalScope(new DeletedAdminScope);

        parent::boot();

        // static::deleting(function (Post $post) {
        //     $post->comment()->delete();
        //     Cache::forget("blog-posts-{$post->id}");
        // });

        // static::updating(function (Post $post) {
        //     Cache::forget("blog-posts-{$post->id}");
        // });

        // static::restoring(function (Post $post) {
        //     $post->comments->restored();
        // });
    }
}
