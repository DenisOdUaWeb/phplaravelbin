<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

//use Illuminate\Support\Facades\Schedule;
//Schedule::call(function () {
  //  logger('daily function only');
//})->everyMinute();

Schedule::call(new App\EveryFiveSecondsClass)->everyThirtySeconds();//everyThirtySeconds