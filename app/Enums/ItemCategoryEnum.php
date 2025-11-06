<?php

namespace App\Enums;

enum ItemCategoryEnum: string
{
    case Metal = 'App\\Models\\Metal';
    case Findings = 'App\\Models\\Stone';
    case Miscellaneous = 'App\\Models\\Miscellaneous';
    case Product = 'App\\Models\\Product';
}
