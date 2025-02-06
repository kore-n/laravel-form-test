<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes; // ソフトデリートを適用
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

    public function getFormattedPublishedAtAttribute()
    {
        return $this->published_at ? $this->published_at->format('Y年m月d日 H:i') : '未公開';
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = strtoupper($value);
    }
}
