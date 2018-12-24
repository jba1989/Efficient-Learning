<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Mail;
use Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\UpdateNtu::class,
        Commands\UpdateNctu::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // 維護開始
        $schedule->command('down')->weekly()->mondays()->at('3:00')->after(function () {
            Log::info('維護開始');
            Mail::raw('維護開始', function($message) {
                $message->to('jba1989@gmail.com');
                $message->subject('維護開始');
            });
        });

        // 更新台大課程
        $schedule
            ->command('update:ntu')
            ->weekly()->mondays()->at('3:10')
            ->before(function () {
                Log::info('開始更新台大課程');
                Mail::raw('開始更新台大課程', function($message) {
                    $message->to('jba1989@gmail.com');
                    $message->subject('開始更新台大課程');
                });
            })
            ->after(function () {
                Log::info('完成更新台大課程');
                Mail::raw('完成更新台大課程', function($message) {
                    $message->to('jba1989@gmail.com');
                    $message->subject('完成更新台大課程');
                });
            })->evenInMaintenanceMode();

        // 更新交大課程
        $schedule
            ->command('update:nctu')
            ->weekly()->mondays()->at('3:10')
            ->before(function () {
                Log::info('開始更新台大課程');
                Mail::raw('開始更新交大課程', function($message) {
                    $message->to('jba1989@gmail.com');
                    $message->subject('開始更新交大課程');
                });
            })
            ->after(function () {
                Log::info('完成更新交大課程');
                Mail::raw('完成更新交大課程', function($message) {
                    $message->to('jba1989@gmail.com');
                    $message->subject('開始更新交大課程');
                });
            })->evenInMaintenanceMode();

        // 維護結束
        $schedule->command('up')->weekly()->mondays()->at('4:30')
            ->after(function () {
                Log::info('維護結束');
                Mail::raw('維護結束', function($message) {
                    $message->to('jba1989@gmail.com');
                    $message->subject('維護結束');
                });
            })->evenInMaintenanceMode();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
