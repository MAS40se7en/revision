<?php

namespace App\Enums;

enum ApplicationStatus: string
{
    case Wishlish = 'wishlist';
    case Applied = 'applied';
    case Interviewing = 'interviewing';
    case Offer = 'offer';
    case Rejected = 'rejected';
}
