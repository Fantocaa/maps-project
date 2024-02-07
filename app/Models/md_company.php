<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class md_company extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function companies()
    {
        return $this->belongsToMany(user_company::class, 'user_companies');
    }
}
