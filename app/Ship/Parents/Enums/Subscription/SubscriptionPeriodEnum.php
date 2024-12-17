<?php

declare(strict_types=1);

namespace App\Ship\Parents\Enums\Subscription;

use App\Ship\Parents\Enums\EnumExtention;

enum SubscriptionPeriodEnum: string
{
    use EnumExtention;

    case Annually = 'annually';
    case Monthly = 'monthly';
    case Weekly = 'weekly';
}
