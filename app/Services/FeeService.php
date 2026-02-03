<?php

namespace App\Services;

use App\Models\Setting;

class FeeService
{
    public function monthlyFee(): int
    {
        return (int) Setting::getValue('monthly_fee_amount', 0);
    }
}
