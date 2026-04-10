<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $name
 * @property Carbon|null $email_verified_at
 * @property string $email
 * @property string $password
 * @property string $remember_token
 */
class User extends \Encore\BaseKit\Models\User
{
    use HasApiTokens;

    /** @use HasFactory<UserFactory> */
    use HasFactory;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}
