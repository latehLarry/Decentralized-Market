<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Traits\Payment;
use App\Models\Order;

class CancelOrder extends Command
{
    use Payment;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:cancel {days?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel all orders with status waiting';

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
        $days = is_null($this->argument('days')) ? config('general.days_cancel_orders') : $this->argument('days');

        #Get old orders
        $orders = Order::where('status', 'waiting')->where('created_at', '<=', Carbon::now()->subDays($days))->get();

        foreach ($orders as $order) {
            $this->cancelPayment($order);
            
            $order->status = 'canceled';
            $order->finished = true;
            $order->save();
        }

        return $this->warn("Orders with wait status over $days days canceled successfully!");
    }
}
