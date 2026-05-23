<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('grants:monitor')->weeklyOn(5, '9:00');
