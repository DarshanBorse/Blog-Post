<?php

namespace App\Models;

use App\Scopes\DeletedAdminScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'user_id'
    ];

    public function comment(){
        return $this->hasMany(Comment::class)->latest();
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    
    public function scopeLatest(Builder $query){
        return $query->orderBy(Static::CREATED_AT, 'desc');
    }

    public function scopeMostCommented(Builder $query)
    {
        // comments_count
        return $query->withCount('comment')->orderBy('comment_count', 'desc');
    }

    

    public static function boot(){
        static::addGlobalScope(new DeletedAdminScope);

        parent::boot();

        static::deleting(function(Post $post){
            $post->comment()->delete();
        });

        static::restoring(function(Post $post){
            $post->comments->restored();
        });
    }
}