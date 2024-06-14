<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Vocabulary extends Model
{
    use HasFactory;
    protected $table = 'vocabularies';
    protected $guarded = [];

    public function examples(): HasMany
    {
        return $this->hasMany(Example::class);
    }

    public function historyVocabs(): BelongsToMany
    {
        return $this->belongsToMany(HistoryVocabulary::class);
    }
}
