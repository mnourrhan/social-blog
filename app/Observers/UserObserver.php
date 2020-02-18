<?php

namespace App\Observers;

use Carbon\Carbon;

class UserObserver {

    public function creating($model)
    {
        $birth_date = Carbon::parse($model->birth_date);
        $model->age = $birth_date->diffInYears(Carbon::now());
    }
}
