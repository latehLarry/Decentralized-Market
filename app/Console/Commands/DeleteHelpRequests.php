<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\HelpRequest;

class DeleteHelpRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'helps:delete {days?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all help requests marked closed';

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
        #Days
        $days = is_null($this->argument('days')) ? config('general.days_delete_helps') : $this->argument('days');

        #Helps
        $helps = HelpRequest::where('closed', true)->where('updated_at', '<', Carbon::now()->subDays($days))->get();

        foreach ($helps as $help) {
            $help->delete();
        }

        return $this->warn("All help requests closed more than $days days ago have been deleted!");
    }
}
