<?php

namespace App\Enums;

enum RegistrationStatus: string
{
    case CANCELLED = 'cancelled';
    case COMPLETED = 'completed';
    case PENDING = 'pending';

}
