<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $tenantId;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->tenantId = currentTenantId();
    }

    protected $fillable = ['name', 'tenant_id'];

    public function posts()
    {
        return $this->hasMany('App\Models\Posts\Post', 'category_id')
            ->where('tenant_id', $this->tenantId);
    }
}
