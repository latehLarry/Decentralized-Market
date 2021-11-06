<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Conversation;

class DeleteConversations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'conversations:delete {days?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Destroy all old conversations';

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
        $days = is_null($this->argument('days')) ? config('general.days_delete_conversations') : $this->argument('days');

        #Get old conversations
        $conversations = Conversation::where('created_at', '<=', Carbon::now()->subDays($days))->get();

        foreach ($conversations as $conversation) {
            $conversation->delete();
        }

        return $this->warn("Conversations over $days days old have been destroyed!");
    }
}
