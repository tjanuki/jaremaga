<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades;

Facades\Schedule::command('app:scrape-latest-article')
    ->timezone('America/Toronto')
    ->between('18:30', '19:30')
    ->everyFiveMinutes()
    ->days([Schedule::SUNDAY, Schedule::MONDAY, Schedule::TUESDAY, Schedule::WEDNESDAY, Schedule::THURSDAY]);
