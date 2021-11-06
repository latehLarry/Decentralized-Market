<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Order;

class DeleteOldOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:order {days?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete orders completed more than "x" days';

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
        $days = is_null($this->argument('days')) ? config('general.days_delete_old_order') : $this->argument('days');

        $orders = Order::where('finished', true)
                        ->where('updated_at', '<=', Carbon::now()->subDays($days))
                        ->where('deleted', false)
                        ->get();

        foreach ($orders as $order) {
            $order->deleted = true;
            $order->save();
        }

        return $this->warn("Delete all orders completed more than $days days ago!");
    }
}
