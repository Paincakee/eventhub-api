<?php

namespace App\Models;

use App\Enums\RegistrationStatus;
use Database\Factories\RegistrationFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $uuid
 * @property int|null $user_id
 * @property int $event_id
 * @property string $status
 * @property string $name
 * @property string $email
 *
 * @property-read User $user
 * @property-read Event $event
 */
#[UseFactory(RegistrationFactory::class)]
class Registration extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'event_id',
        'status',
        'name',
        'email',
    ];

    /**
     * @array<string, mixed>
     */
    protected $attributes = [
        'status' => RegistrationStatus::PENDING
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => RegistrationStatus::class,
        ];
    }

    /**
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    protected static function booted(): void
    {
        static::creating(static function (self $registration) {
            $registration->uuid = self::getUuid(Str::uuid());
        });
    }

    /**
     * @param string $uuid
     * @return string
     */
    private static function getUuid(string $uuid): string
    {
        $isUsed = self::query()->where('uuid', $uuid)->exists();

        if ($isUsed) {
            return self::getUuid(Str::uuid());
        }

        return $uuid;
    }

    /**
     * @return BelongsTo<User, self>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Event, self>
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
