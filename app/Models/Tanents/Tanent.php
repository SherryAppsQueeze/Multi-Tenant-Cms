<?php

namespace App\Models\Tanents;

use Illuminate\Database\Eloquent\Model;

class Tanent extends Model
{

    protected $fillable = [
        'name',
        'subdomain',
        'created_at',
        'updated_at',
    ];
}
