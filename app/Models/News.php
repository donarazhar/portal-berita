<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'author_id',
        'news_category_id',
        'title',
        'slug',
        'thumbnail',
        'content',
    ];

    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

    public function newsCategory()
    {
        return $this->belongsTo(NewsCategory::class, 'news_category_id');
    }

    public function banner()
    {
        return $this->hasOne(Banner::class, 'news_id');
    }
}
