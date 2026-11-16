<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory, Uuids;

    // rename the id column, not mandatory
    public $incrementing = false;

    const Movies = 0;

    const Series = 1;

    const Documentary = 2;

    const Live_show = 4;

    const Award_show = 5;

    const Talent_show = 6;

    const onWatchlist = 0;

    const Watching = 1;

    const Watched = 2;

    const Abandoned = 3;

    public $guarded = [];

    protected $casts = [
        'is_custom' => 'boolean',
    ];

    /** Types a user can pick when creating a custom entry. */
    public const TYPE_LABELS = [
        self::Movies => 'Movie',
        self::Series => 'Series',
        self::Documentary => 'Documentary',
        self::Live_show => 'Live show',
        self::Award_show => 'Award show',
        self::Talent_show => 'Talent show',
    ];

    /** TMDB-backed titles only (custom entries excluded). */
    public function scopeFromTmdb($query)
    {
        return $query->where('is_custom', false);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
