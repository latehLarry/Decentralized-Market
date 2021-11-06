<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Traits\Payment;
use App\Models\Order;

class CompleteOrder extends Command
{
   use Payment;
       
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:complete {days?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Complete all orders marked as shipped';

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
        $days = is_null($this->argument('days')) ? config('general.days_complete_order') : $this->argument('days');

        #Orders
        $orders = Order::where('status', 'sent')->where('updated_at', '<', Carbon::now()->subDays($days))->get();

        foreach ($orders as $order) {
            $this->releasePayment($order);
            
            $order->status = 'delivered';
            $order->finished = true;
            $order->save();
        }

        return $this->warn("Orders marked as shipped more than $days days ago have been completed!");
    }
}
