<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Schedule extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rule_id',
        'day',
        'time',
    ];

    public function rule(): BelongsTo
    {
        return $this->belongsTo(Rule::class);
    }

    public function doses(): HasMany
    {
        return $this->hasMany(Dose::class);
    }
}
