<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\File;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Log temizleme komutu
Artisan::command('logs:clear', function () {
    $logPath = storage_path('logs');
    $files = File::glob($logPath . '/*.log');
    $deletedCount = 0;
    
    foreach ($files as $file) {
        // 7 günden eski log dosyalarını sil
        if (filemtime($file) < strtotime('-7 days')) {
            File::delete($file);
            $deletedCount++;
        }
    }
    
    // laravel.log dosyasını temizle (silme değil)
    $mainLog = $logPath . '/laravel.log';
    if (File::exists($mainLog) && File::size($mainLog) > 50 * 1024 * 1024) { // 50MB'dan büyükse
        File::put($mainLog, '');
        $this->info('Main log file cleared (was > 50MB)');
    }
    
    $this->info("Deleted {$deletedCount} old log files.");
})->purpose('Clear old log files');

// Cache temizleme komutu
Artisan::command('cache:cleanup', function () {
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    $this->info('Cache and views cleared.');
})->purpose('Clear cache and compiled views');

// Scheduler - Günlük işler
Schedule::command('logs:clear')->daily()->at('03:00');
Schedule::command('cache:clear')->weekly()->sundays()->at('04:00');
