<?php

// Use the Inspiring class from Laravel's Foundation package
use Illuminate\Foundation\Inspiring;
// Use the Artisan facade for running artisan commands
use Illuminate\Support\Facades\Artisan;

// Define a new artisan command named 'inspire'
Artisan::command('inspire', function () {
    // Display an inspiring quote using the comment method
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote'); // Set the purpose of the command
