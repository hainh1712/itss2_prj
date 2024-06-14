<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Example extends Model
{
    use HasFactory;

    protected $table = 'examples';
    protected $guarded = [];

    public function vocabularies(): BelongsToMany
    {
        return $this->belongsToMany(Vocabulary::class);
    }

    public function cards(): BelongsToMany
    {
        return $this->belongsToMany(Card::class);
    }
}
