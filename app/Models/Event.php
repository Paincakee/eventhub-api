<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\EventFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $title
 * @property string|null $slug
 * @property string|null $description
 * @property string $location
 * @property Carbon $start_date
 * @property Carbon|null $end_date
 * @property int $max_attendees
 * @property string|null $image_url
 * @property bool $is_published
 */
class Event extends Model
{
    /** @use HasFactory<EventFactory> */
    use HasFactory;
    use SoftDeletes;

    /**
     * Mass assignable attributes.
     * @var string[]
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'location',
        'start_date',
        'end_date',
        'max_attendees',
        'image_url',
        'is_published',
    ];

    /**
     * Cast attributes to specific types.
     * @var string[]
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_published' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $event) {
            $base = Str::slug($event->title);
            $event->slug = $event->slug ?: self::generateSlug($base, 1);
        });
    }

    /**
     * Generate a unique slug for the event.
     *
     * @param string $baseSlug
     * @param int $counter
     * @return string
     */
    private static function generateSlug(string $baseSlug, int $counter): string
    {
        $slug = $counter > 1 ? $baseSlug . '-'. $counter : $baseSlug;
        $isUsed = self::query()->where('slug', $slug)->exists();

        if ($isUsed) {
            $counter++;
            return self::generateSlug($baseSlug, $counter);
        }

        return $slug;
    }

    /**
     * Returns only published events.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }
}
