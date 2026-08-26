<?php

namespace App\Enums;

enum BookingType: string
{
    case Member = 'member';
    case Guest = 'guest';
    case Competition = 'competition';
    case Blocked = 'blocked';
}
