<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'state',
        'creator',
        'deleted_at',
    ];

    protected $casts = [
        'name'      => 'string',
        'address'   => 'string',
        'state'     => 'integer',
        'creator'   => 'integer',
        'deleted_at'=> 'datetime',
    ];
    
    protected $dates = [
        'deleted_at',
    ];

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator', 'id');
    }

    public function projects()
    {
        return $this->all();
    }
}
