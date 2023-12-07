<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dose extends Model
{
    use HasFactory;

    public function schedule():BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }
}
