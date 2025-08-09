<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //

    protected $fillable = ['title', 'content', 'category_id', 'tanant_id', 'created_by', 'featured_image', 'updated_by'];

    public function category()
    {
        return $this->belongsTo('App\Models\Categories\Category', 'category_id');
    }
}
