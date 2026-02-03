<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::setValue('school_name', 'Ecole Primaire', 'string');
        Setting::setValue('monthly_fee_amount', 10000, 'int');
        Setting::setValue('payment_methods', ['cash', 'mobile_money'], 'json');
    }
}
