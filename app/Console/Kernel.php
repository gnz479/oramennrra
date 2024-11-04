<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;
<<<<<<< HEAD
=======
use App\Http\Controllers\Email\EmailController;
>>>>>>> 4e16e7f861f7d876825b05331e2fb39cdc0a8ca2

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
<<<<<<< HEAD
                //$controlador->store();
=======
                $controlador = new EmailController();
                $controlador->sendEmail();
>>>>>>> 4e16e7f861f7d876825b05331e2fb39cdc0a8ca2
                \Log::info('Tarea programada ejecutada correctamente.');
            } catch (\Exception $e) {
                \Log::error('Error al ejecutar la tarea programada: ' . $e->getMessage());
            }
<<<<<<< HEAD
        //})->everyMinute(); 
        })->dailyAt('16:10'); 
=======
        })->everyMinute(); 
        // })->dailyAt('08:45'); 
>>>>>>> 4e16e7f861f7d876825b05331e2fb39cdc0a8ca2
        
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
