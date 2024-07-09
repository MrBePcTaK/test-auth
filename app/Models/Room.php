<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'area',
        'height',
        'project_id',
        'deleted_at',
    ];

    protected $casts = [
        'name'      => 'string',
        'area'      => 'float',
        'height'    => 'float',
        'project_id'=> 'integer',
        'deleted_at'=> 'timestamp',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
