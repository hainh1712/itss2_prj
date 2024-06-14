<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class HistoryVocabulary extends Model
{
    use HasFactory;

    protected $table = 'history_vocabs';
    protected $guarded = [];

    public function histories(): BelongsToMany
    {
        return $this->belongsToMany(History::class);
    }

    public function vocabularies(): BelongsToMany
    {
        return $this->belongsToMany(Vocabulary::class);
    }
}
