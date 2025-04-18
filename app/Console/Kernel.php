<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Email\EmailController;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            try {
                $controlador = new EmailController();
                $controlador->getTasksToNotificacion();
                \Log::info('Tarea programada ejecutada correctamente. -');
            } catch (\Exception $e) {
                \Log::error('Error al ejecutar la tarea programada: ' . $e->getMessage());
            }

        // })->everyMinute(); 
        })->dailyAt('10:00'); 
        
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
