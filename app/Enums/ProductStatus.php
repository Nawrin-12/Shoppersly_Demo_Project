<?php

namespace App\Enums;

enum ProductStatus: string
{
    case Available = 'available';
    case Unavailable = 'unavailable';
}
