<?php

namespace App\Services;

use App\Models\SchoolYear;

class CurrentSchoolYearService
{
    public function current(): SchoolYear
    {
        return SchoolYear::current();
    }
}
