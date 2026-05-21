<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('grants:monitor')->everyMinute();
