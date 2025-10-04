<?php


namespace App\Console\Commands;

use App\Services\AnalyticsService;
use Illuminate\Console\Command;

class GenerateAnalytics extends Command
{
    protected $signature = 'analytics:generate {--lga_id= : Specific LGA ID to generate analytics for}';
    protected $description = 'Generate daily analytics snapshots for farmer data';

    public function handle(AnalyticsService $service): int
    {
        $this->info('Starting analytics generation...');
        
        $lgaId = $this->option('lga_id');
        
        try {
            $service->generateDailySnapshot($lgaId ? (int)$lgaId : null);
            
            $scope = $lgaId ? "LGA ID: {$lgaId}" : 'all LGAs';
            $this->info("Analytics generated successfully for {$scope}");
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error("Analytics generation failed: {$e->getMessage()}");
            return Command::FAILURE;
        }
    }
}

