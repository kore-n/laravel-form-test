<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'content', 'published_at', 'user_id'];
    protected $casts = [
        'published_at' => 'datetime',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 公開済みの投稿だけを取得
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at');
    }

    // 最新の投稿を取得
    public function scopeRecent($query)
    {
        return $query->orderBy('published_at', 'desc');
    }
}
