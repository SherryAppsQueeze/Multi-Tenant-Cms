<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{


    protected $fillable = ['name', 'tenant_id'];

    public function posts()
    {
        return $this->hasMany('App\Models\Posts\Post', 'category_id');
    }
}
