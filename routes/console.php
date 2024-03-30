<?php

use App\Console\Commands\ScrapeLatestArticle;
use Illuminate\Support\Facades\Schedule;

// Sunday to Thursday bween 18:30 and 19:30 every 5 minutes
Schedule::command(ScrapeLatestArticle::class)
    ->sundays()
    ->between('18:30', '19:30')
    ->everyFiveMinutes();

Schedule::command(ScrapeLatestArticle::class)
    ->mondays()
    ->between('18:30', '19:30')
    ->everyFiveMinutes();

Schedule::command(ScrapeLatestArticle::class)
    ->tuesdays()
    ->between('18:30', '19:30')
    ->everyFiveMinutes();

Schedule::command(ScrapeLatestArticle::class)
    ->wednesdays()
    ->between('18:30', '19:30')
    ->everyFiveMinutes();

Schedule::command(ScrapeLatestArticle::class)
    ->thursdays()
    ->between('18:30', '19:30')
    ->everyFiveMinutes();

