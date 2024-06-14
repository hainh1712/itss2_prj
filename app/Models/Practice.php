<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Practice extends Model
{
    use HasFactory;

    protected $table = 'practices';

    protected $fillable = ['day'];
    protected $dates = ['day'];
}
