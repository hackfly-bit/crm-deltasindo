<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CleanSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'session:clean {--hours=24 : Clean sessions older than specified hours}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean old session files to prevent permission issues';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $hours = $this->option('hours');
        $sessionPath = storage_path('framework/sessions');
        
        if (!File::exists($sessionPath)) {
            $this->error('Session directory does not exist: ' . $sessionPath);
            return 1;
        }
        
        $cutoffTime = now()->subHours($hours);
        $files = File::files($sessionPath);
        $deletedCount = 0;
        
        foreach ($files as $file) {
            if (File::lastModified($file) < $cutoffTime->getTimestamp()) {
                File::delete($file);
                $deletedCount++;
            }
        }
        
        $this->info("Cleaned {$deletedCount} session files older than {$hours} hours.");
        \Log::info('Session cleanup completed', ['deleted_count' => $deletedCount, 'hours' => $hours]);
        
        return 0;
    }
}