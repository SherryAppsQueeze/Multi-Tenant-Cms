<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //
    protected $tenantId;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->tenantId = currentTenantId();
    }
    protected $fillable = ['title', 'content', 'category_id', 'tenant_id', 'created_by', 'featured_image', 'updated_by'];



    public function category()
    {
        return $this->belongsTo('App\Models\Categories\Category', 'category_id')
            ->where('tenant_id', $this->tenantId);
    }
}
